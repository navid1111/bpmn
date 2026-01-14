<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CamundaService
{
    protected string $zeebeRestUrl;
    protected string $operateUrl;
    protected string $tasklistUrl;

    public function __construct()
    {
        $this->zeebeRestUrl = config('services.camunda.zeebe_rest_url', 'http://localhost:8080');
        $this->operateUrl = config('services.camunda.operate_url', 'http://localhost:8081');
        $this->tasklistUrl = config('services.camunda.tasklist_url', 'http://localhost:8082');
    }

    /**
     * Deploy a BPMN process to Zeebe using REST API v2
     * The v2 API uses multipart/form-data for deployments
     */
    public function deployProcess(string $bpmnXml, string $resourceName): array
    {
        try {
            // Ensure the process is marked as executable (required by Camunda)
            $bpmnXml = $this->ensureExecutableProcess($bpmnXml);

            // Create a temporary file for the BPMN content
            $tempFile = tempnam(sys_get_temp_dir(), 'bpmn_');
            file_put_contents($tempFile, $bpmnXml);

            // Camunda 8 REST API v2 uses multipart/form-data
            $response = Http::timeout(30)
                ->attach('resources', file_get_contents($tempFile), $resourceName)
                ->post("{$this->zeebeRestUrl}/v2/deployments");

            // Clean up temp file
            unlink($tempFile);

            if ($response->successful()) {
                $data = $response->json();
                
                // Cache the deployment for dashboard display
                $this->cacheDeployment($data);
                
                return [
                    'success' => true,
                    'message' => 'Process deployed successfully',
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to deploy process: ' . $response->body(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Camunda deployment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Deployment error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Ensure the BPMN process has isExecutable="true"
     */
    protected function ensureExecutableProcess(string $bpmnXml): string
    {
        // Replace isExecutable="false" with isExecutable="true"
        $bpmnXml = preg_replace(
            '/isExecutable\s*=\s*["\']false["\']/i',
            'isExecutable="true"',
            $bpmnXml
        );

        // If no isExecutable attribute exists, add it to the process element
        if (!preg_match('/isExecutable\s*=/', $bpmnXml)) {
            $bpmnXml = preg_replace(
                '/<bpmn:process\s+id=/i',
                '<bpmn:process isExecutable="true" id=',
                $bpmnXml
            );
        }

        return $bpmnXml;
    }

    /**
     * Cache deployment info for dashboard display
     */
    protected function cacheDeployment(array $deploymentData): void
    {
        $cached = cache()->get('camunda_deployments', []);
        
        if (isset($deploymentData['deployments'])) {
            foreach ($deploymentData['deployments'] as $deployment) {
                if (isset($deployment['processDefinition'])) {
                    $def = $deployment['processDefinition'];
                    $key = $def['processDefinitionKey'] ?? $def['processDefinitionId'];
                    $cached[$key] = [
                        'processDefinitionKey' => $def['processDefinitionKey'] ?? null,
                        'processDefinitionId' => $def['processDefinitionId'] ?? null,
                        'name' => $def['resourceName'] ?? $def['processDefinitionId'],
                        'version' => $def['processDefinitionVersion'] ?? 1,
                        'deployedAt' => now()->toIso8601String(),
                    ];
                }
            }
        }
        
        cache()->put('camunda_deployments', $cached, now()->addDays(7));
    }

    /**
     * Deploy process using Zeebe REST API v2 (alias for deployProcess)
     */
    public function deployProcessViaGateway(string $bpmnXml, string $resourceName): array
    {
        return $this->deployProcess($bpmnXml, $resourceName);
    }

    /**
     * Create a new process instance using v2 API
     */
    public function createProcessInstance(string $bpmnProcessId, array $variables = []): array
    {
        try {
            // Format variables for Camunda 8 v2 API
            $formattedVariables = [];
            foreach ($variables as $key => $value) {
                $formattedVariables[$key] = $value;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post("{$this->zeebeRestUrl}/v2/process-instances", [
                    'processDefinitionId' => $bpmnProcessId,
                    'variables' => (object) $formattedVariables
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Process instance created successfully',
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create process instance: ' . $response->body()
            ];
        } catch (\Exception $e) {
            Log::error('Create process instance error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get process instances from Operate
     */
    public function getProcessInstances(string $bpmnProcessId = null): array
    {
        try {
            $query = [];
            if ($bpmnProcessId) {
                $query['filter'] = ['bpmnProcessId' => $bpmnProcessId];
            }

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->operateUrl}/v1/process-instances/search", $query);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch process instances'
            ];
        } catch (\Exception $e) {
            Log::error('Get process instances error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get process definitions from Zeebe (stored locally after deployment)
     * Note: Zeebe REST API doesn't have a search endpoint for definitions,
     * so we track deployments in the session/cache
     */
    public function getProcessDefinitions(): array
    {
        try {
            // Try to get from Operate with CSRF handling
            $session = Http::timeout(5)->get("{$this->operateUrl}/");
            $cookies = $session->cookies();
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->withCookies($cookies->toArray(), parse_url($this->operateUrl, PHP_URL_HOST))
                ->post("{$this->operateUrl}/v1/process-definitions/search", []);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            // If Operate API fails, return cached deployments
            $cached = cache()->get('camunda_deployments', []);
            return [
                'success' => true,
                'data' => ['items' => $cached],
                'source' => 'cache'
            ];
        } catch (\Exception $e) {
            Log::error('Get process definitions error: ' . $e->getMessage());
            
            // Return cached data on error
            $cached = cache()->get('camunda_deployments', []);
            return [
                'success' => count($cached) > 0,
                'data' => ['items' => $cached],
                'source' => 'cache'
            ];
        }
    }

    /**
     * Check if Camunda services are healthy
     */
    public function checkHealth(): array
    {
        $results = [
            'zeebe' => false,
            'operate' => false,
            'tasklist' => false,
            'elasticsearch' => false
        ];

        try {
            // Check Zeebe actuator health endpoint (on port 9600)
            $zeebeResponse = Http::timeout(5)->get("http://localhost:9600/actuator/health");
            $results['zeebe'] = $zeebeResponse->successful();
        } catch (\Exception $e) {
            $results['zeebe'] = false;
        }

        try {
            // Check Operate - just verify it responds (redirects to /operate)
            $operateResponse = Http::timeout(5)->get("{$this->operateUrl}/");
            // 302 redirect is a valid response indicating Operate is running
            $results['operate'] = $operateResponse->successful() || $operateResponse->status() === 302;
        } catch (\Exception $e) {
            $results['operate'] = false;
        }

        try {
            // Check Tasklist - just verify it responds
            $tasklistResponse = Http::timeout(5)->get("{$this->tasklistUrl}/");
            // 302 redirect is a valid response indicating Tasklist is running
            $results['tasklist'] = $tasklistResponse->successful() || $tasklistResponse->status() === 302;
        } catch (\Exception $e) {
            $results['tasklist'] = false;
        }

        try {
            // Check Elasticsearch
            $esResponse = Http::timeout(5)->get("http://localhost:9200/_cluster/health");
            $results['elasticsearch'] = $esResponse->successful();
        } catch (\Exception $e) {
            $results['elasticsearch'] = false;
        }

        return $results;
    }
}

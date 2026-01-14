<?php

namespace App\Http\Controllers;

use App\Models\BpmnDiagram;
use App\Services\CamundaService;
use Illuminate\Http\Request;

class CamundaController extends Controller
{
    protected CamundaService $camundaService;

    public function __construct(CamundaService $camundaService)
    {
        $this->camundaService = $camundaService;
    }

    /**
     * Deploy a BPMN diagram to Camunda
     */
    public function deploy(BpmnDiagram $diagram)
    {
        $resourceName = str_replace(' ', '_', $diagram->name) . '.bpmn';
        
        $result = $this->camundaService->deployProcess(
            $diagram->xml_content,
            $resourceName
        );

        return response()->json($result);
    }

    /**
     * Deploy BPMN XML directly (without saving to database first)
     */
    public function deployXml(Request $request)
    {
        $request->validate([
            'xml_content' => 'required|string',
            'name' => 'required|string|max:255'
        ]);

        $resourceName = str_replace(' ', '_', $request->name) . '.bpmn';
        
        $result = $this->camundaService->deployProcess(
            $request->xml_content,
            $resourceName
        );

        return response()->json($result);
    }

    /**
     * Create a new process instance
     */
    public function createInstance(Request $request)
    {
        $request->validate([
            'bpmn_process_id' => 'required|string',
            'variables' => 'nullable|array'
        ]);

        $result = $this->camundaService->createProcessInstance(
            $request->bpmn_process_id,
            $request->variables ?? []
        );

        return response()->json($result);
    }

    /**
     * Get all process instances
     */
    public function getInstances(Request $request)
    {
        $bpmnProcessId = $request->query('bpmn_process_id');
        $result = $this->camundaService->getProcessInstances($bpmnProcessId);

        return response()->json($result);
    }

    /**
     * Get all deployed process definitions
     */
    public function getDefinitions()
    {
        $result = $this->camundaService->getProcessDefinitions();
        return response()->json($result);
    }

    /**
     * Check health of Camunda services
     */
    public function health()
    {
        $results = $this->camundaService->checkHealth();
        
        $allHealthy = !in_array(false, $results);
        
        return response()->json([
            'success' => $allHealthy,
            'services' => $results
        ], $allHealthy ? 200 : 503);
    }

    /**
     * Show Camunda dashboard view
     */
    public function dashboard()
    {
        $health = $this->camundaService->checkHealth();
        return view('camunda.dashboard', compact('health'));
    }
}

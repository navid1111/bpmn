<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Camunda Dashboard - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .status-card {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 16px;
            border: 1px solid #e5e7eb;
        }
        .status-healthy {
            background: #d1fae5;
            border-color: #10b981;
        }
        .status-unhealthy {
            background: #fee2e2;
            border-color: #ef4444;
        }
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .link-card {
            display: block;
            padding: 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }
        .link-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1 style="font-size: 28px; font-weight: 700;">🚀 Camunda 8 Dashboard</h1>
            <div>
                <a href="{{ route('bpmn.index') }}" class="btn btn-primary">← Back to BPMN Editor</a>
            </div>
        </div>

        <!-- Service Health Status -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Service Health Status</h2>
            <div class="service-grid">
                <div class="status-card {{ $health['zeebe'] ? 'status-healthy' : 'status-unhealthy' }}">
                    <h3 style="font-weight: 600; margin-bottom: 8px;">⚙️ Zeebe</h3>
                    <p>Workflow Engine</p>
                    <span style="font-weight: 500;">{{ $health['zeebe'] ? '✅ Running' : '❌ Not Running' }}</span>
                </div>
                <div class="status-card {{ $health['operate'] ? 'status-healthy' : 'status-unhealthy' }}">
                    <h3 style="font-weight: 600; margin-bottom: 8px;">📊 Operate</h3>
                    <p>Process Monitoring</p>
                    <span style="font-weight: 500;">{{ $health['operate'] ? '✅ Running' : '❌ Not Running' }}</span>
                </div>
                <div class="status-card {{ $health['tasklist'] ? 'status-healthy' : 'status-unhealthy' }}">
                    <h3 style="font-weight: 600; margin-bottom: 8px;">📋 Tasklist</h3>
                    <p>User Tasks</p>
                    <span style="font-weight: 500;">{{ $health['tasklist'] ? '✅ Running' : '❌ Not Running' }}</span>
                </div>
                <div class="status-card {{ $health['elasticsearch'] ? 'status-healthy' : 'status-unhealthy' }}">
                    <h3 style="font-weight: 600; margin-bottom: 8px;">🔍 Elasticsearch</h3>
                    <p>Search Engine</p>
                    <span style="font-weight: 500;">{{ $health['elasticsearch'] ? '✅ Running' : '❌ Not Running' }}</span>
                </div>
            </div>
        </div>

        @if(!$health['zeebe'])
        <!-- Docker Instructions -->
        <div style="background: #fef3c7; border: 1px solid #f59e0b; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h3 style="font-weight: 600; color: #92400e; margin-bottom: 12px;">⚠️ Camunda Services Not Running</h3>
            <p style="margin-bottom: 12px;">To start Camunda 8 services, run the following command in your terminal:</p>
            <pre style="background: #1f2937; color: #f9fafb; padding: 16px; border-radius: 6px; overflow-x: auto;">docker compose up -d</pre>
            <p style="margin-top: 12px; color: #78350f;">This will start Zeebe, Operate, Tasklist, Connectors, and Elasticsearch.</p>
            <p style="margin-top: 8px; color: #78350f;"><strong>Note:</strong> First startup may take a few minutes to download images and initialize services.</p>
        </div>
        @endif

        <!-- Quick Links -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Quick Links</h2>
            <div class="service-grid">
                <a href="http://localhost:8081" target="_blank" class="link-card">
                    <h3 style="font-weight: 600; color: #3b82f6;">📊 Operate UI</h3>
                    <p style="color: #6b7280; margin-top: 8px;">Monitor process instances, incidents, and variables</p>
                    <span style="color: #9ca3af; font-size: 12px;">http://localhost:8081</span>
                </a>
                <a href="http://localhost:8082" target="_blank" class="link-card">
                    <h3 style="font-weight: 600; color: #10b981;">📋 Tasklist UI</h3>
                    <p style="color: #6b7280; margin-top: 8px;">Complete user tasks and manage task assignments</p>
                    <span style="color: #9ca3af; font-size: 12px;">http://localhost:8082</span>
                </a>
                <a href="{{ route('bpmn.create') }}" class="link-card">
                    <h3 style="font-weight: 600; color: #8b5cf6;">✏️ Create BPMN</h3>
                    <p style="color: #6b7280; margin-top: 8px;">Design a new BPMN process diagram</p>
                </a>
                <a href="{{ route('bpmn.index') }}" class="link-card">
                    <h3 style="font-weight: 600; color: #f59e0b;">📁 My Diagrams</h3>
                    <p style="color: #6b7280; margin-top: 8px;">View and manage saved BPMN diagrams</p>
                </a>
            </div>
        </div>

        <!-- Process Definitions -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Deployed Processes</h2>
            <div id="processDefinitions" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                <p style="color: #6b7280;">Loading process definitions...</p>
            </div>
        </div>

        <!-- Create Process Instance -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 600; margin-bottom: 16px;">Start Process Instance</h2>
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <input type="text" id="bpmnProcessId" placeholder="Enter BPMN Process ID" 
                           style="flex: 1; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <button onclick="createInstance()" class="btn btn-success">▶️ Start Instance</button>
                </div>
                <div id="instanceResult" style="margin-top: 12px;"></div>
            </div>
        </div>
    </div>

    <script>
        window.csrfToken = '{{ csrf_token() }}';

        // Load process definitions on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProcessDefinitions();
        });

        async function loadProcessDefinitions() {
            const container = document.getElementById('processDefinitions');
            try {
                const response = await fetch('{{ route('camunda.definitions') }}');
                const result = await response.json();
                
                if (result.success && result.data?.items && Object.keys(result.data.items).length > 0) {
                    let html = '<table style="width: 100%; border-collapse: collapse;">';
                    html += '<thead><tr style="border-bottom: 2px solid #e5e7eb;">';
                    html += '<th style="text-align: left; padding: 12px;">Process ID</th>';
                    html += '<th style="text-align: left; padding: 12px;">Name</th>';
                    html += '<th style="text-align: left; padding: 12px;">Version</th>';
                    html += '<th style="text-align: left; padding: 12px;">Actions</th>';
                    html += '</tr></thead><tbody>';
                    
                    // Handle both array and object formats
                    const items = Array.isArray(result.data.items) ? result.data.items : Object.values(result.data.items);
                    
                    items.forEach(item => {
                        const processId = item.processDefinitionId || item.bpmnProcessId || 'Unknown';
                        html += `<tr style="border-bottom: 1px solid #e5e7eb;">`;
                        html += `<td style="padding: 12px;"><code>${processId}</code></td>`;
                        html += `<td style="padding: 12px;">${item.name || item.resourceName || '-'}</td>`;
                        html += `<td style="padding: 12px;">${item.version || item.processDefinitionVersion || 1}</td>`;
                        html += `<td style="padding: 12px;">`;
                        html += `<button onclick="startProcess('${processId}')" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;">▶️ Start</button>`;
                        html += `</td>`;
                        html += `</tr>`;
                    });
                    
                    html += '</tbody></table>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p style="color: #6b7280;">No deployed processes found. Deploy a BPMN diagram to see it here.</p>';
                }
            } catch (err) {
                container.innerHTML = `<p style="color: #ef4444;">Unable to load process definitions. Make sure Camunda services are running.</p>`;
            }
        }

        async function startProcess(bpmnProcessId) {
            document.getElementById('bpmnProcessId').value = bpmnProcessId;
            await createInstance();
        }

        async function createInstance() {
            const bpmnProcessId = document.getElementById('bpmnProcessId').value.trim();
            const resultContainer = document.getElementById('instanceResult');
            
            if (!bpmnProcessId) {
                resultContainer.innerHTML = '<p style="color: #ef4444;">Please enter a BPMN Process ID</p>';
                return;
            }
            
            try {
                const response = await fetch('{{ route('camunda.instances.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({
                        bpmn_process_id: bpmnProcessId,
                        variables: {}
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultContainer.innerHTML = `<p style="color: #10b981;">✅ Process instance created successfully! Key: ${result.data?.processInstanceKey || 'N/A'}</p>`;
                } else {
                    resultContainer.innerHTML = `<p style="color: #ef4444;">❌ Failed: ${result.message}</p>`;
                }
            } catch (err) {
                resultContainer.innerHTML = `<p style="color: #ef4444;">❌ Error: ${err.message}</p>`;
            }
        }
    </script>
</body>
</html>

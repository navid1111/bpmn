<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BPMN Editor - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bpmn-editor.js'])
    
    <style>
        #canvas {
            height: calc(100vh - 140px);
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .bpmn-toolbar {
            background: #f5f5f5;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .btn {
            padding: 8px 16px;
            margin-right: 8px;
            border: 1px solid #ccc;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #e0e0e0;
        }
        .btn-primary {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
            border-color: #0056b3;
        }
        .btn-success {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        .btn-success:hover {
            background: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <div style="max-width: 1400px; margin: 0 auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="font-size: 24px; font-weight: 600;">BPMN Editor</h1>
            <div>
                <a href="{{ route('bpmn.index') }}" class="btn">← Back to List</a>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="bpmn-toolbar">
            <input type="text" id="diagramName" placeholder="Diagram Name" 
                   value="{{ isset($diagram) ? $diagram->name : '' }}"
                   style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 300px; margin-right: 8px;">
            
            <input type="text" id="diagramDescription" placeholder="Description (optional)" 
                   value="{{ isset($diagram) ? $diagram->description : '' }}"
                   style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 300px; margin-right: 8px;">
            
            <button id="saveBtn" class="btn btn-success">💾 Save</button>
            <button id="exportBtn" class="btn">📥 Export XML</button>
            <button id="newBtn" class="btn">📄 New Diagram</button>
            <button id="undoBtn" class="btn">↶ Undo</button>
            <button id="redoBtn" class="btn">↷ Redo</button>
            <button id="zoomInBtn" class="btn">🔍+</button>
            <button id="zoomOutBtn" class="btn">🔍-</button>
            <button id="fitViewBtn" class="btn">⛶ Fit</button>
        </div>

        <!-- BPMN Canvas -->
        <div id="canvas"></div>

        <!-- Hidden inputs -->
        <input type="hidden" id="diagramId" value="{{ isset($diagram) ? $diagram->id : '' }}">
        <input type="hidden" id="existingXml" value="{{ isset($diagram) ? htmlspecialchars($diagram->xml_content) : '' }}">
    </div>

    <script>
        // Pass CSRF token to JavaScript
        window.csrfToken = '{{ csrf_token() }}';
        window.diagramId = '{{ isset($diagram) ? $diagram->id : '' }}';
        window.routeStore = '{{ route('api.bpmn.store') }}';
        window.routeUpdate = '{{ isset($diagram) ? route('api.bpmn.update', $diagram->id) : '' }}';
        window.routeIndex = '{{ route('bpmn.index') }}';
    </script>
</body>
</html>

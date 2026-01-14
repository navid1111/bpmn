<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BPMN Diagrams - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .table tr:hover {
            background: #f8f9fa;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>My BPMN Diagrams</h1>
            <a href="{{ route('bpmn.create') }}" class="btn btn-primary">+ Create New Diagram</a>
        </div>

        @if($diagrams->isEmpty())
            <p style="text-align: center; padding: 40px; color: #666;">
                No diagrams yet. Create your first BPMN diagram!
            </p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($diagrams as $diagram)
                        <tr>
                            <td><strong>{{ $diagram->name }}</strong></td>
                            <td>{{ Str::limit($diagram->description, 50) }}</td>
                            <td>{{ $diagram->version }}</td>
                            <td>
                                @if($diagram->is_published)
                                    <span style="color: green;">✓ Published</span>
                                @else
                                    <span style="color: orange;">○ Draft</span>
                                @endif
                            </td>
                            <td>{{ $diagram->updated_at->diffForHumans() }}</td>
                            <td class="actions">
                                <a href="{{ route('bpmn.edit', $diagram) }}">Edit</a>
                                <a href="{{ route('bpmn.export', $diagram) }}">Export</a>
                                <a href="#" onclick="deleteDiagram({{ $diagram->id }}); return false;" style="color: #dc3545;">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $diagrams->links() }}
            </div>
        @endif
    </div>

    <script>
        function deleteDiagram(id) {
            if (!confirm('Are you sure you want to delete this diagram?')) {
                return;
            }

            fetch(`/api/bpmn/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Diagram deleted successfully');
                    location.reload();
                } else {
                    alert('Error deleting diagram: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error deleting diagram');
                console.error(error);
            });
        }
    </script>
</body>
</html>

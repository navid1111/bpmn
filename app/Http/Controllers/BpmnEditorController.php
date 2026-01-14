<?php

namespace App\Http\Controllers;

use App\Models\BpmnDiagram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BpmnEditorController extends Controller
{
    /**
     * Display a listing of the user's BPMN diagrams.
     */
    public function index()
    {
        $query = BpmnDiagram::query();
        
        if (Auth::check()) {
            $query->byUser(Auth::id());
        }
        
        $diagrams = $query->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('bpmn.index', compact('diagrams'));
    }

    /**
     * Show the form for creating a new BPMN diagram.
     */
    public function create()
    {
        return view('bpmn.editor');
    }

    /**
     * Store a newly created BPMN diagram.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'xml_content' => 'required|string',
            'version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $diagram = BpmnDiagram::create([
            'name' => $request->name,
            'description' => $request->description,
            'xml_content' => $request->xml_content,
            'version' => $request->version ?? '1.0',
            'user_id' => Auth::check() ? Auth::id() : null,
            'is_published' => $request->is_published ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'BPMN diagram saved successfully',
            'diagram' => $diagram
        ], 201);
    }

    /**
     * Display the specified BPMN diagram in the editor.
     */
    public function show(BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram or it's published (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id() && !$diagram->is_published) {
            abort(403, 'Unauthorized access to this diagram.');
        }

        return view('bpmn.editor', compact('diagram'));
    }

    /**
     * Show the form for editing the specified BPMN diagram.
     */
    public function edit(BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this diagram.');
        }

        return view('bpmn.editor', compact('diagram'));
    }

    /**
     * Update the specified BPMN diagram.
     */
    public function update(Request $request, BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this diagram.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'xml_content' => 'required|string',
            'version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $diagram->update([
            'name' => $request->name,
            'description' => $request->description,
            'xml_content' => $request->xml_content,
            'version' => $request->version,
            'is_published' => $request->is_published ?? $diagram->is_published,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'BPMN diagram updated successfully',
            'diagram' => $diagram
        ]);
    }

    /**
     * Remove the specified BPMN diagram.
     */
    public function destroy(BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this diagram.'
            ], 403);
        }

        $diagram->delete();

        return response()->json([
            'success' => true,
            'message' => 'BPMN diagram deleted successfully'
        ]);
    }

    /**
     * Get BPMN diagram data as JSON (API endpoint).
     */
    public function getDiagram(BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram or it's published (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id() && !$diagram->is_published) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this diagram.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'diagram' => $diagram
        ]);
    }

    /**
     * Export BPMN diagram as XML file.
     */
    public function export(BpmnDiagram $diagram)
    {
        // Ensure user owns this diagram or it's published (skip check if no auth)
        if (Auth::check() && $diagram->user_id !== Auth::id() && !$diagram->is_published) {
            abort(403, 'Unauthorized access to this diagram.');
        }

        $filename = str_replace(' ', '_', $diagram->name) . '.bpmn';

        return response($diagram->xml_content)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}

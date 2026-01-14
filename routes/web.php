<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\ProposalTable;
use App\Http\Controllers\BpmnEditorController;

Route::get('/', ProposalTable::class);

// BPMN Editor Routes (no authentication required)
Route::get('/bpmn', [BpmnEditorController::class, 'index'])->name('bpmn.index');
Route::get('/bpmn/create', [BpmnEditorController::class, 'create'])->name('bpmn.create');
Route::get('/bpmn/{diagram}', [BpmnEditorController::class, 'show'])->name('bpmn.show');
Route::get('/bpmn/{diagram}/edit', [BpmnEditorController::class, 'edit'])->name('bpmn.edit');
Route::get('/bpmn/{diagram}/export', [BpmnEditorController::class, 'export'])->name('bpmn.export');

// API Routes for AJAX operations
Route::post('/api/bpmn', [BpmnEditorController::class, 'store'])->name('api.bpmn.store');
Route::get('/api/bpmn/{diagram}', [BpmnEditorController::class, 'getDiagram'])->name('api.bpmn.get');
Route::put('/api/bpmn/{diagram}', [BpmnEditorController::class, 'update'])->name('api.bpmn.update');
Route::delete('/api/bpmn/{diagram}', [BpmnEditorController::class, 'destroy'])->name('api.bpmn.destroy');

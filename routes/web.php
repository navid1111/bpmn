<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BpmnEditorController;
use App\Http\Controllers\CamundaController;
use App\Livewire\ProposalTable;
use Illuminate\Support\Facades\Route;

// Redirect to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});

// BPMN Editor Routes (no authentication required)
Route::get('/bpmn', [BpmnEditorController::class, 'index'])->name('bpmn.index');
Route::get('/bpmn/create', [BpmnEditorController::class, 'create'])->name('bpmn.create');
Route::get('/bpmn/{diagram}', [BpmnEditorController::class, 'show'])->name('bpmn.show');
Route::get('/bpmn/{diagram}/edit', [BpmnEditorController::class, 'edit'])->name('bpmn.edit');
Route::get('/bpmn/{diagram}/export', [BpmnEditorController::class, 'export'])->name('bpmn.export');

// API Routes for AJAX operations (no authentication)
Route::post('/api/bpmn', [BpmnEditorController::class, 'store'])->name('api.bpmn.store');
Route::get('/api/bpmn/{diagram}', [BpmnEditorController::class, 'getDiagram'])->name('api.bpmn.get');
Route::put('/api/bpmn/{diagram}', [BpmnEditorController::class, 'update'])->name('api.bpmn.update');
Route::delete('/api/bpmn/{diagram}', [BpmnEditorController::class, 'destroy'])->name('api.bpmn.destroy');

// Camunda Integration Routes (no authentication)
Route::prefix('camunda')->name('camunda.')->group(function () {
    Route::get('/', [CamundaController::class, 'dashboard'])->name('dashboard');
    Route::get('/health', [CamundaController::class, 'health'])->name('health');
    Route::post('/deploy/{diagram}', [CamundaController::class, 'deploy'])->name('deploy');
    Route::post('/deploy-xml', [CamundaController::class, 'deployXml'])->name('deploy.xml');
    Route::post('/instances', [CamundaController::class, 'createInstance'])->name('instances.create');
    Route::get('/instances', [CamundaController::class, 'getInstances'])->name('instances.index');
    Route::get('/definitions', [CamundaController::class, 'getDefinitions'])->name('definitions');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Proposal Table
    Route::get('/dashboard', ProposalTable::class)->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

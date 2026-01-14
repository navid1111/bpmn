<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proposal;

class ProposalTable extends Component
{
    use WithPagination;

    public $category = '';
    public $businessType = '';
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedBusinessType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Proposal::distinct()->pluck('category')->filter();
        $businessTypes = Proposal::distinct()->pluck('business_type')->filter();

        $proposals = Proposal::query()
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->businessType, fn($q) => $q->where('business_type', $this->businessType))
            ->when($this->search, fn($q) => $q->where('proposal', 'like', "%{$this->search}%")
                                              ->orWhere('partner_id', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.proposal-table', [
            'proposals' => $proposals,
            'categories' => $categories,
            'businessTypes' => $businessTypes,
        ])->layout('layouts.app', ['header' => 'Proposal List']);
    }
}

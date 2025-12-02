<?php

namespace App\Livewire\User;

use App\Models\Assessment;
use Livewire\Component;
use Livewire\WithPagination;

class AssessmentHistory extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $assessments = Assessment::where('user_id', auth()->id())
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.assessment-history', [
            'assessments' => $assessments
        ])->layout('layouts.app');
    }
}

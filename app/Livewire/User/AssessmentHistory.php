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

    public function mount()
    {
        // Log for debugging
        \Log::info('AssessmentHistory mounted', [
            'user_id' => auth()->id(),
            'total_assessments' => Assessment::where('user_id', auth()->id())->count()
        ]);
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

        // Log assessment counts by status for debugging
        \Log::info('Assessment query results', [
            'user_id' => auth()->id(),
            'total' => $assessments->total(),
            'status_filter' => $this->statusFilter,
            'search' => $this->search
        ]);

        return view('livewire.user.assessment-history', [
            'assessments' => $assessments
        ])->layout('layouts.app');
    }
}

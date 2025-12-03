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
        $userId = auth()->id();

        // Debug: Check if user is authenticated
        if (!$userId) {
            \Log::error('AssessmentHistory: User not authenticated!');
        }

        // Debug: Count all assessments for this user
        $totalCount = Assessment::where('user_id', $userId)->count();
        \Log::info('AssessmentHistory: Total assessments for user', [
            'user_id' => $userId,
            'total_count' => $totalCount
        ]);

        // Debug: Show all assessments without filters
        $allAssessments = Assessment::where('user_id', $userId)->get();
        \Log::info('AssessmentHistory: All assessments', [
            'user_id' => $userId,
            'assessments' => $allAssessments->map(function($a) {
                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'status' => $a->status,
                    'created_at' => $a->created_at->toDateTimeString()
                ];
            })->toArray()
        ]);

        $assessments = Assessment::where('user_id', $userId)
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
            'user_id' => $userId,
            'total' => $assessments->total(),
            'status_filter' => $this->statusFilter,
            'search' => $this->search,
            'current_page' => $assessments->currentPage(),
            'per_page' => $assessments->perPage()
        ]);

        return view('livewire.user.assessment-history', [
            'assessments' => $assessments
        ])->layout('layouts.app');
    }
}

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

        // Check if user is admin/editor (more robust check)
        $isAdmin = false;
        try {
            $user = auth()->user();
            if ($user) {
                // Check via database directly to avoid potential method issues
                $isAdmin = \DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', $user->id)
                    ->where('model_has_roles.model_type', 'App\Models\User')
                    ->whereIn('roles.name', ['admin', 'editor'])
                    ->exists();
            }
        } catch (\Exception $e) {
            \Log::error('Error checking admin role: ' . $e->getMessage());
            $isAdmin = false;
        }

        // Admin bisa lihat SEMUA assessments, user biasa hanya miliknya
        $query = Assessment::with('user');

        // Jika bukan admin, filter hanya assessment milik user tersebut
        if (!$isAdmin) {
            $query->where('user_id', $userId);
        }

        // Apply search dan status filter
        $assessments = $query
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        // Log for debugging
        \Log::info('Assessment query results', [
            'user_id' => $userId,
            'is_admin' => $isAdmin,
            'total' => $assessments->total(),
            'count' => $assessments->count(),
            'status_filter' => $this->statusFilter,
            'search' => $this->search
        ]);

        return view('livewire.user.assessment-history', [
            'assessments' => $assessments,
            'isAdmin' => $isAdmin
        ])->layout('layouts.app');
    }
}

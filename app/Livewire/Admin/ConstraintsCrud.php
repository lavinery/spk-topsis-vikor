<?php

namespace App\Livewire\Admin;

use App\Models\Constraint;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class ConstraintsCrud extends Component
{
    use WithPagination;

    public $name, $expr_json = '{}', $action = 'exclude_alternative', $active = true;
    public ?int $editingId = null;
    public string $q = '';
    public bool $constraintsEnabled;
    public $importFile;

    protected $rules = [
        'name' => 'required|string|max:200',
        'expr_json' => 'required|json',
        'action' => 'required|in:exclude_alternative,warn_only',
        'active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-form-modal');
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $constraint = Constraint::findOrFail($id);
        $this->fill($constraint->toArray());
        $this->expr_json = json_encode($constraint->expr_json, JSON_PRETTY_PRINT);
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->expr_json = '{}';
        $this->action = 'exclude_alternative';
        $this->active = true;
    }

    public function save()
    {
        $data = $this->validate();

        // Parse JSON
        $data['expr_json'] = json_decode($data['expr_json'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError('expr_json', 'Invalid JSON format');
            return;
        }

        if ($this->editingId) {
            Constraint::findOrFail($this->editingId)->update($data);
        } else {
            Constraint::create($data);
        }

        $this->resetForm();
        $this->dispatch('close-form-modal');
        session()->flash('ok', 'Data constraint berhasil disimpan!');
    }

    public function delete($id)
    {
        Constraint::findOrFail($id)->delete();
        session()->flash('ok', 'Data constraint berhasil dihapus!');
    }

    public function mount()
    {
        // Load global constraints enabled status
        $this->constraintsEnabled = Cache::get('constraints_system_enabled', true);
    }

    public function toggleConstraintsSystem()
    {
        $this->constraintsEnabled = !$this->constraintsEnabled;
        Cache::forever('constraints_system_enabled', $this->constraintsEnabled);

        session()->flash('ok', $this->constraintsEnabled
            ? 'Sistem Constraints DIAKTIFKAN'
            : 'Sistem Constraints DINONAKTIFKAN');
    }

    public function render()
    {
        $constraints = Constraint::when($this->q, fn($q) => $q->where('name', 'like', '%' . $this->q . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.constraints-crud', compact('constraints'));
    }
}
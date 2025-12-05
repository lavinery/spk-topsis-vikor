<?php

namespace App\Livewire\Admin;

use App\Models\Mountain;
use Livewire\Component;
use Livewire\WithPagination;

class MountainsCrud extends Component
{
    use WithPagination;

    public $name, $elevation_m, $province, $lat, $lng, $status = 'active';
    public ?int $editingId = null;
    public string $q = '';
    public $importFile;

    protected $rules = [
        'name' => 'required|string|max:150',
        'elevation_m' => 'required|integer|min:1',
        'province' => 'required|string|max:100',
        'lat' => 'nullable|numeric|between:-90,90',
        'lng' => 'nullable|numeric|between:-180,180',
        'status' => 'required|in:active,inactive',
    ];

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-form-modal');
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $this->fill(Mountain::findOrFail($id)->toArray());
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->elevation_m = null;
        $this->province = '';
        $this->lat = null;
        $this->lng = null;
        $this->status = 'active';
    }

    public function save()
    {
        $data = $this->validate();
        $this->editingId
            ? Mountain::findOrFail($this->editingId)->update($data)
            : Mountain::create($data);
        $this->resetForm();
        $this->dispatch('close-form-modal');
        session()->flash('ok', 'Data gunung berhasil disimpan!');
    }

    public function delete($id)
    {
        Mountain::findOrFail($id)->delete();
        session()->flash('ok', 'Data gunung berhasil dihapus!');
    }

    public function render()
    {
        $mountains = Mountain::when($this->q, fn($q) => $q->where('name', 'like', '%' . $this->q . '%'))
            ->latest()
            ->paginate(10);
        return view('livewire.admin.mountains-crud', compact('mountains'));
    }
}
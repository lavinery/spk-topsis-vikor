<?php

namespace App\Livewire\Admin;

use App\Models\CategoryMap;
use App\Models\Criterion;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryMapsCrud extends Component
{
    use WithPagination;
    
    public $criterion_id = null;
    public $key;
    public $score;
    public $label;
    public $q = '';
    public $editingId = null;
    public $importFile;

    protected $rules = [
        'criterion_id' => 'required|exists:criteria,id',
        'key' => 'required|string|max:80',
        'score' => 'required|numeric|min:0|max:1',
        'label' => 'nullable|string|max:120',
    ];

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-form-modal');
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $categoryMap = CategoryMap::findOrFail($id);
        $this->fill($categoryMap->toArray());
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->criterion_id = null;
        $this->key = $this->label = null;
        $this->score = null;
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->editingId) {
            CategoryMap::findOrFail($this->editingId)->update($data);
        } else {
            CategoryMap::create($data);
        }

        $this->resetForm();
        $this->dispatch('close-form-modal');
        session()->flash('ok', 'Data pemetaan kategori berhasil disimpan!');
    }

    public function delete($id)
    {
        CategoryMap::findOrFail($id)->delete();
        session()->flash('ok', 'Data pemetaan kategori berhasil dihapus!');
    }

    public function render()
    {
        $criteria = Criterion::where('scale', 'categorical')
            ->orderBy('sort_order')
            ->get();
            
        $list = CategoryMap::with('criterion')
            ->when($this->criterion_id, fn($q) => $q->where('criterion_id', $this->criterion_id))
            ->when($this->q, fn($q) => $q->where('key', 'like', '%' . $this->q . '%'))
            ->orderBy('key')
            ->paginate(15);

        return view('livewire.admin.category-maps-crud', compact('criteria', 'list'));
    }
}
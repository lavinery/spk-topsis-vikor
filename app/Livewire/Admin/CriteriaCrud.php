<?php

namespace App\Livewire\Admin;

use App\Models\Criterion;
use Livewire\Component;
use Livewire\WithPagination;

class CriteriaCrud extends Component
{
    use WithPagination;

    public $q = '';
    public $editingId = null;
    public $code, $name, $source = 'ROUTE', $type = 'benefit', $active = true, $sort_order = 0, $unit, $scale = 'numeric', $min_hint, $max_hint;

    protected $rules = [
        'code' => 'required|regex:/^C\d+$/|max:10',
        'name' => 'nullable|string|max:120',
        'source' => 'required|in:USER,MOUNTAIN,ROUTE',
        'type' => 'required|in:benefit,cost',
        'active' => 'boolean',
        'sort_order' => 'integer',
        'unit' => 'nullable|string|max:24',
        'scale' => 'required|in:numeric,categorical',
        'min_hint' => 'nullable|numeric',
        'max_hint' => 'nullable|numeric',
    ];

    public function edit($id)
    {
        $this->editingId = $id;
        $criterion = Criterion::findOrFail($id);
        $this->fill($criterion->toArray());
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->code = $this->name = $this->unit = null;
        $this->source = 'ROUTE';
        $this->type = 'benefit';
        $this->active = true;
        $this->sort_order = 0;
        $this->scale = 'numeric';
        $this->min_hint = $this->max_hint = null;
    }

    public function save()
    {
        $data = $this->validate();
        
        if ($this->editingId) {
            Criterion::findOrFail($this->editingId)->update($data);
        } else {
            Criterion::create($data);
        }
        
        $this->resetForm();
        session()->flash('ok', 'Saved');
    }

    public function delete($id)
    {
        Criterion::findOrFail($id)->delete();
        session()->flash('ok', 'Deleted');
    }

    public function up($id)
    {
        $c = Criterion::findOrFail($id);
        $c->decrement('sort_order');
    }

    public function down($id)
    {
        $c = Criterion::findOrFail($id);
        $c->increment('sort_order');
    }

    public function render()
    {
        $list = Criterion::when($this->q, function ($query) {
                $query->where(function ($x) {
                    $x->where('code', 'like', '%' . $this->q . '%')
                      ->orWhere('name', 'like', '%' . $this->q . '%');
                });
            })
            ->orderBy('sort_order')
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
            ->paginate(15);

        return view('livewire.admin.criteria-crud', compact('list'));
    }
}
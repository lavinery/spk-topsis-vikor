<?php

namespace App\Livewire\Admin;

use App\Models\Criterion;
use App\Models\FuzzyTerm;
use Livewire\Component;
use Livewire\WithPagination;

class FuzzyTermsCrud extends Component
{
    use WithPagination;

    public $criterion_id;
    public $code, $label, $shape = 'triangular', $params_json;
    public $editingId = null;
    public $q = '';

    protected $rules = [
        'criterion_id' => 'required|exists:criteria,id',
        'code' => 'required|string|max:50',
        'label' => 'nullable|string|max:100',
        'shape' => 'required|in:triangular,trapezoidal',
        'params_json' => 'required|string',
    ];

    public function edit($id)
    {
        $this->editingId = $id;
        $r = FuzzyTerm::findOrFail($id);
        $this->criterion_id = $r->criterion_id;
        $this->code = $r->code;
        $this->label = $r->label;
        $this->shape = $r->shape;
        $this->params_json = is_array($r->params_json) ? json_encode($r->params_json) : $r->params_json;
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->criterion_id = null;
        $this->code = $this->label = $this->params_json = null;
        $this->shape = 'triangular';
    }

    public function save()
    {
        $data = $this->validate();
        // normalize json
        $decoded = json_decode($data['params_json'], true);
        if (!is_array($decoded)) {
            $this->addError('params_json', 'Params harus berupa JSON array, contoh: [1,2,3]');
            return;
        }
        $data['params_json'] = json_encode($decoded);

        if ($this->editingId) {
            FuzzyTerm::findOrFail($this->editingId)->update($data);
        } else {
            FuzzyTerm::create($data);
        }
        $this->resetForm();
        session()->flash('ok', 'Saved');
    }

    public function delete($id)
    {
        FuzzyTerm::findOrFail($id)->delete();
        session()->flash('ok', 'Deleted');
    }

    public function render()
    {
        $criteria = Criterion::where('source','USER')->orderBy('code')->get();
        $list = FuzzyTerm::when($this->criterion_id, fn($q)=>$q->where('criterion_id',$this->criterion_id))
            ->when($this->q, fn($q)=>$q->where('code','like','%'.$this->q.'%'))
            ->orderBy('criterion_id')->orderBy('code')
            ->paginate(15);
        return view('livewire.admin.fuzzy-terms-crud', compact('criteria','list'));
    }
}



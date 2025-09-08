<?php

namespace App\Livewire\Admin;

use App\Models\Criterion;
use App\Models\CriterionWeight;
use Livewire\Component;
use Livewire\WithPagination;

class CriterionWeightsCrud extends Component
{
    use WithPagination;

    public $q = '';
    public $editingId = null;
    public $criterion_id;
    public $weight;
    public $version = 'v1';

    protected $rules = [
        'criterion_id' => 'required|exists:criteria,id',
        'weight' => 'required|numeric|min:0|max:1',
        'version' => 'required|string|max:10',
    ];

    public function edit($id)
    {
        $this->editingId = $id;
        $criterionWeight = CriterionWeight::findOrFail($id);
        $this->fill($criterionWeight->toArray());
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->criterion_id = null;
        $this->weight = null;
        $this->version = 'v1';
    }

    public function save()
    {
        $data = $this->validate();
        
        if ($this->editingId) {
            CriterionWeight::findOrFail($this->editingId)->update($data);
        } else {
            CriterionWeight::create($data);
        }
        
        $this->resetForm();
        session()->flash('ok', 'Bobot kriteria berhasil disimpan');
    }

    public function delete($id)
    {
        CriterionWeight::findOrFail($id)->delete();
        session()->flash('ok', 'Bobot kriteria berhasil dihapus');
    }

    public function normalizeWeights()
    {
        $weights = CriterionWeight::all();
        $totalWeight = $weights->sum('weight');
        
        if ($totalWeight > 0) {
            foreach ($weights as $weight) {
                $weight->update(['weight' => $weight->weight / $totalWeight]);
            }
            session()->flash('ok', 'Bobot berhasil dinormalisasi (total = 1.0)');
        } else {
            session()->flash('err', 'Tidak ada bobot untuk dinormalisasi');
        }
    }

    public function render()
    {
        $query = CriterionWeight::with('criterion')
            ->join('criteria', 'criterion_weights.criterion_id', '=', 'criteria.id')
            ->where('criteria.active', 1);

        if ($this->q) {
            $query->where(function($q) {
                $q->where('criteria.code', 'like', '%' . $this->q . '%')
                  ->orWhere('criteria.name', 'like', '%' . $this->q . '%');
            });
        }

        $weights = $query->select('criterion_weights.*')
            ->orderBy('criteria.sort_order')
            ->orderBy('criteria.code')
            ->paginate(20);

        $criteria = Criterion::where('active', 1)
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get();

        $totalWeight = CriterionWeight::sum('weight');

        return view('livewire.admin.criterion-weights-crud', compact('weights', 'criteria', 'totalWeight'));
    }
}

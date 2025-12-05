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
    public $remainingWeight = 1.0;
    public $importFile;

    protected $rules = [
        'criterion_id' => 'required|exists:criteria,id',
        'weight' => 'required|numeric|min:0|max:1',
        'version' => 'required|string|max:10',
    ];

    protected $messages = [
        'weight.max' => 'Bobot tidak boleh lebih dari 1.0',
        'weight.min' => 'Bobot tidak boleh kurang dari 0',
        'weight.required' => 'Bobot wajib diisi',
        'weight.numeric' => 'Bobot harus berupa angka',
        'criterion_id.required' => 'Kriteria wajib dipilih',
        'criterion_id.exists' => 'Kriteria tidak valid',
    ];

    public function updatedWeight()
    {
        $this->calculateRemainingWeight();
    }

    public function calculateRemainingWeight()
    {
        $currentTotal = CriterionWeight::when($this->editingId, function($q) {
            return $q->where('id', '!=', $this->editingId);
        })->sum('weight');

        $this->remainingWeight = 1.0 - $currentTotal;

        // Round to avoid floating point precision issues
        $this->remainingWeight = round($this->remainingWeight, 4);
    }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-form-modal');
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $criterionWeight = CriterionWeight::findOrFail($id);
        $this->fill($criterionWeight->toArray());
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->criterion_id = null;
        $this->weight = null;
        $this->version = 'v1';
        $this->calculateRemainingWeight();
    }

    public function save()
    {
        // Validate bobot tidak melebihi sisa yang tersedia
        $this->calculateRemainingWeight();

        if ($this->weight > $this->remainingWeight && !$this->editingId) {
            $this->addError('weight', "Bobot melebihi sisa yang tersedia ({$this->remainingWeight}). Total bobot tidak boleh lebih dari 1.0");
            return;
        }

        $data = $this->validate();

        if ($this->editingId) {
            CriterionWeight::findOrFail($this->editingId)->update($data);
        } else {
            CriterionWeight::create($data);
        }

        $this->resetForm();
        $this->dispatch('close-form-modal');
        session()->flash('ok', 'Data bobot kriteria berhasil disimpan!');
    }

    public function delete($id)
    {
        CriterionWeight::findOrFail($id)->delete();
        session()->flash('ok', 'Data bobot kriteria berhasil dihapus!');
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
        $this->calculateRemainingWeight();

        return view('livewire.admin.criterion-weights-crud', compact('weights', 'criteria', 'totalWeight'));
    }
}

<?php

namespace App\Livewire\Admin;

use App\Models\Route as RouteModel;
use App\Models\Mountain;
use Livewire\Component;
use Livewire\WithPagination;

class RoutesCrud extends Component
{
    use WithPagination;

    public $mountain_id, $name, $distance_km, $elevation_gain_m, $slope_class, $land_cover_key, $water_sources_score, $support_facility_score, $permit_required=false;
    public ?int $editingId = null;
    public string $q = '';

    protected $rules = [
      'mountain_id'=>'required|exists:mountains,id',
      'name'=>'required|string|max:150',
      'distance_km'=>'nullable|numeric|min:0|max:500',
      'elevation_gain_m'=>'nullable|integer|min:0|max:9000',
      'slope_class'=>'nullable|integer|min:1|max:6',
      'land_cover_key'=>'nullable|string|max:50',
      'water_sources_score'=>'nullable|integer|min:0|max:10',
      'support_facility_score'=>'nullable|integer|min:0|max:10',
      'permit_required'=>'boolean',
    ];

    protected $messages = [
      'mountain_id.required' => 'Gunung harus dipilih',
      'mountain_id.exists' => 'Gunung tidak valid',
      'name.required' => 'Nama jalur harus diisi',
      'name.max' => 'Nama jalur maksimal 150 karakter',
      'distance_km.numeric' => 'Panjang jalur harus berupa angka',
      'distance_km.min' => 'Panjang jalur tidak boleh negatif',
      'distance_km.max' => 'Panjang jalur maksimal 500 km',
      'elevation_gain_m.integer' => 'Elevasi gain harus berupa angka bulat',
      'elevation_gain_m.min' => 'Elevasi gain tidak boleh negatif',
      'elevation_gain_m.max' => 'Elevasi gain maksimal 9000 meter',
      'slope_class.integer' => 'Kelas kecuraman harus berupa angka',
      'slope_class.min' => 'Kelas kecuraman minimal 1',
      'slope_class.max' => 'Kelas kecuraman maksimal 6',
      'water_sources_score.min' => 'Skor sumber air minimal 0',
      'water_sources_score.max' => 'Skor sumber air maksimal 10',
      'support_facility_score.min' => 'Skor sarana pendukung minimal 0',
      'support_facility_score.max' => 'Skor sarana pendukung maksimal 10',
    ];

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-form-modal');
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $this->fill(RouteModel::findOrFail($id)->toArray());
        $this->dispatch('open-form-modal');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->mountain_id = null;
        $this->name = '';
        $this->distance_km = null;
        $this->elevation_gain_m = null;
        $this->slope_class = null;
        $this->land_cover_key = null;
        $this->water_sources_score = null;
        $this->support_facility_score = null;
        $this->permit_required = false;
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        $this->editingId
            ? RouteModel::findOrFail($this->editingId)->update($data)
            : RouteModel::create($data);
        $this->resetForm();
        $this->dispatch('close-form-modal');
        session()->flash('ok', $this->editingId ? 'Jalur berhasil diupdate' : 'Jalur berhasil ditambahkan');
    }

    public function delete($id)
    {
        RouteModel::findOrFail($id)->delete();
        session()->flash('ok', 'Jalur berhasil dihapus');
    }

    public function render()
    {
      $routes = RouteModel::with('mountain')
        ->when($this->q, fn($q)=>$q->where('name','like','%'.$this->q.'%'))
        ->latest()->paginate(10);
      $mountains = Mountain::orderBy('name')->get();
      return view('livewire.admin.routes-crud', compact('routes','mountains'));
    }
}
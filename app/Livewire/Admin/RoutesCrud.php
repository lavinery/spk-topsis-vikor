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
      'distance_km'=>'nullable|numeric',
      'elevation_gain_m'=>'nullable|integer',
      'slope_class'=>'nullable|integer|min:1|max:5',
      'land_cover_key'=>'nullable|string|max:50',
      'water_sources_score'=>'nullable|integer|min:0|max:10',
      'support_facility_score'=>'nullable|integer|min:0|max:10',
      'permit_required'=>'boolean',
    ];

    public function edit($id){ $this->editingId=$id; $this->fill(RouteModel::findOrFail($id)->toArray()); }
    public function resetForm(){ $this->editingId=null; $this->mountain_id=null; $this->name=''; $this->distance_km=null; $this->elevation_gain_m=null; $this->slope_class=null; $this->land_cover_key=null; $this->water_sources_score=null; $this->support_facility_score=null; $this->permit_required=false; }
    public function save(){
      $data = $this->validate();
      $this->editingId
        ? RouteModel::findOrFail($this->editingId)->update($data)
        : RouteModel::create($data);
      $this->resetForm();
      session()->flash('ok','Saved');
    }
    public function delete($id){ RouteModel::findOrFail($id)->delete(); }

    public function render()
    {
      $routes = RouteModel::with('mountain')
        ->when($this->q, fn($q)=>$q->where('name','like','%'.$this->q.'%'))
        ->latest()->paginate(10);
      $mountains = Mountain::orderBy('name')->get();
      return view('livewire.admin.routes-crud', compact('routes','mountains'));
    }
}
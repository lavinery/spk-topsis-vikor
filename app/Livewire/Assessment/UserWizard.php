<?php

namespace App\Livewire\Assessment;

use Livewire\Component;
use App\Models\{Assessment, Criterion};
use Illuminate\Support\Facades\DB;

class UserWizard extends Component
{
    public int $assessmentId;
    public array $steps = [];         // [{id, code, name, scale, type, options?}]
    public int $i = 0;                // index current step
    public array $answers = [];       // [criterion_id => value]
    public bool $open = true;         // modal visible
    public bool $saved = false;       // autosave indicator

    public function mount($id)
    {
        $this->assessmentId = (int)$id;
        
        // Check if user has access to this assessment
        $assessment = Assessment::findOrFail($this->assessmentId);
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }
        
        // ambil criteria USER yang aktif & berurutan
        $criteria = Criterion::where('active', 1)
            ->where('source', 'USER')
            ->orderBy('sort_order')
            ->orderByRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED)")
            ->get();

        // siapkan steps (+ opsi untuk categorical/boolean)
        foreach ($criteria as $c) {
            $step = [
                'id' => $c->id, 
                'code' => $c->code, 
                'name' => $c->name,
                'scale' => $c->scale, 
                'type' => $c->type, 
                'unit' => $c->unit,
                'min_hint' => $c->min_hint,
                'max_hint' => $c->max_hint,
                'notes' => $c->notes
            ];
            
            if ($c->data_type === 'boolean') {
                // Boolean as categorical with Yes/No
                $step['scale'] = 'categorical';
                $step['options'] = [
                    ['key' => '1', 'label' => 'Ya', 'score' => 1.0],
                    ['key' => '0', 'label' => 'Tidak', 'score' => 0.0],
                ];
            } elseif ($c->scale === 'categorical') {
                $step['options'] = DB::table('category_maps')
                    ->where('criterion_id', $c->id)
                    ->orderBy('key')
                    ->get(['key', 'label', 'score'])
                    ->map(function($r) {
                        return [
                            'key' => $r->key, 
                            'label' => $r->label ?? $r->key, 
                            'score' => $r->score
                        ];
                    })->toArray();
            }
            $this->steps[] = $step;
        }

        // prefill jawaban existing (jika ada)
        $rows = DB::table('assessment_answers')
            ->where('assessment_id', $this->assessmentId)
            ->get();
        foreach ($rows as $r) {
            $this->answers[$r->criterion_id] = $r->value_raw ?? $r->value_numeric;
        }
    }

    public function close()
    {
        $this->open = false;
        return redirect()->route('assess.result', $this->assessmentId);
    }

    public function next()
    {
        if (!$this->validateCurrent()) {
            $this->dispatch('validation-error');
            return;
        }
        
        $this->saveCurrent();
        if ($this->i < count($this->steps) - 1) {
            $this->i++;
        }
    }

    public function prev()
    {
        if ($this->i > 0) {
            $this->i--;
        }
    }

    public function goToStep($index)
    {
        if ($index >= 0 && $index < count($this->steps)) {
            $this->i = $index;
        }
    }

    public function validateCurrent(): bool
    {
        if (!isset($this->steps[$this->i])) {
            return false;
        }
        
        $c = $this->steps[$this->i];
        $val = $this->answers[$c['id']] ?? null;

        // Clear previous errors
        $this->resetErrorBag('answers.' . $c['id']);

        // validasi ringan
        if ($c['scale'] === 'numeric') {
            if ($val === null || $val === '') {
                $this->addError('answers.' . $c['id'], 'Nilai tidak boleh kosong');
                return false;
            }
            if (!is_numeric($val)) {
                $this->addError('answers.' . $c['id'], 'Nilai harus berupa angka');
                return false;
            }
            // Check min/max hints if available
            if ($c['min_hint'] && (float)$val < (float)$c['min_hint']) {
                $this->addError('answers.' . $c['id'], "Nilai minimal: {$c['min_hint']}");
                return false;
            }
            if ($c['max_hint'] && (float)$val > (float)$c['max_hint']) {
                $this->addError('answers.' . $c['id'], "Nilai maksimal: {$c['max_hint']}");
                return false;
            }
        }
        
        if ($c['scale'] === 'categorical' && empty($val)) {
            $this->addError('answers.' . $c['id'], 'Pilih salah satu opsi');
            return false;
        }

        return true;
    }

    public function saveCurrent(): void
    {
        if (!isset($this->steps[$this->i])) {
            return;
        }
        
        $c = $this->steps[$this->i];  // step meta
        $val = $this->answers[$c['id']] ?? null;

        // validasi ringan
        if ($c['scale'] === 'numeric' && !is_numeric($val) && $val !== null && $val !== '') {
            return;
        }
        if ($c['scale'] === 'categorical' && empty($val)) {
            return;
        }

        // simpan/inject ke assessment_answers
        $payload = [
            'assessment_id' => $this->assessmentId,
            'criterion_id' => $c['id'],
            'updated_at' => now(),
            'created_at' => now()
        ];
        
        if ($c['scale'] === 'numeric') {
            $payload['value_numeric'] = is_numeric($val) ? (float)$val : null;
            $payload['value_raw'] = (string)$val;
            // Store raw input for fuzzy processing
            $payload['raw_input'] = is_numeric($val) ? (float)$val : null;
        } else {
            $payload['value_raw'] = (string)$val;         // simpan key
            $payload['value_numeric'] = null;              // transformasi nanti saat normalisasi (kalau dipakai)
            $payload['raw_input'] = null;
        }
        
        DB::table('assessment_answers')->updateOrInsert(
            ['assessment_id' => $this->assessmentId, 'criterion_id' => $c['id']], 
            $payload
        );
        
        $this->saved = true;
        $this->dispatch('saved');
    }

    public function finishAndRun()
    {
        if (!$this->validateCurrent()) {
            return;
        }
        
        $this->saveCurrent();
        
        // Use JavaScript to submit POST form to run TOPSIS
        \Log::info('UserWizard dispatching run-topsis-calculation', [
            'assessmentId' => $this->assessmentId
        ]);
        
        $this->dispatch('run-topsis-calculation', [
            'assessmentId' => $this->assessmentId
        ]);
    }

    public function getProgressProperty(): float
    {
        $n = max(1, count($this->steps));
        return (($this->i + 1) / $n) * 100;
    }

    public function getCurrentStepProperty()
    {
        return $this->steps[$this->i] ?? null;
    }

    public function getIsFirstStepProperty(): bool
    {
        return $this->i === 0;
    }

    public function getIsLastStepProperty(): bool
    {
        return $this->i === count($this->steps) - 1;
    }

    public function render()
    {
        return view('livewire.assessment.user-wizard');
    }
}
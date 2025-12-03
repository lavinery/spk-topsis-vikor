<?php

namespace App\Services;

use App\Models\Assessment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ConstraintService
{
    public function apply(Assessment $a): array
    {
        // Cek apakah sistem constraints diaktifkan
        $constraintsEnabled = Cache::get('constraints_system_enabled', true);

        if (!$constraintsEnabled) {
            // Sistem constraints dinonaktifkan, skip semua proses
            \Log::info('Constraints system is DISABLED - skipping all constraint checks');
            return [];
        }

        $rules = DB::table('constraints')->where('active', true)->get();
        // build quick lookup: route attrs + mountain status
        $alt = $a->alternatives()->with('route.mountain')->get();
        $excluded = [];

        foreach ($alt as $row) {
            $route = $row->route;
            $ctx = [
                // raw user answers by code (for rules referencing raw level)
                'C3_raw' => optional($a->answers()->whereHas('criterion', fn($q) => $q->where('code', 'C3')))->first()?->value_raw,
                // route attrs:
                'slope_class' => $route->slope_class,
                'mountain_status' => $route->mountain->status ?? null,
            ];

            $violate = false;
            foreach ($rules as $r) {
                if ($this->match($ctx, json_decode($r->expr_json, true))) {
                    if ($r->action === 'exclude_alternative') {
                        $violate = true;
                        break;
                    }
                }
            }
            if ($violate) {
                $row->is_excluded = true;
                $row->save();
                $excluded[] = $row->route_id;
            }
        }

        return $excluded; // list route_id yang diexclude
    }

    private function match(array $ctx, array $expr): bool
    {
        // Minimal evaluator: supports {"all":[cond...]}, {"any":[cond...]}
        if (isset($expr['all'])) {
            foreach ($expr['all'] as $c) {
                if (!$this->check($ctx, $c)) {
                    return false;
                }
            }
            return true;
        }
        if (isset($expr['any'])) {
            foreach ($expr['any'] as $c) {
                if ($this->check($ctx, $c)) {
                    return true;
                }
            }
            return false;
        }
        return $this->check($ctx, $expr);
    }

    private function check(array $ctx, array $cond): bool
    {
        // cond: {criterion|route_attr, op, value}
        $left = null;
        if (isset($cond['criterion']) && $cond['criterion'] === 'C3') {
            $left = $ctx['C3_raw'] ?? null;
        } elseif (isset($cond['route_attr'])) {
            $left = match($cond['route_attr']) {
                'mountain.status' => $ctx['mountain_status'] ?? null,
                default => $ctx[$cond['route_attr']] ?? null,
            };
        }
        $op = $cond['op'] ?? 'eq';
        $right = $cond['value'] ?? null;

        return match($op) {
            'eq' => $left == $right,
            'gt' => (float)$left > (float)$right,
            'gte' => (float)$left >= (float)$right,
            'lt' => (float)$left < (float)$right,
            'lte' => (float)$left <= (float)$right,
            default => false
        };
    }
}

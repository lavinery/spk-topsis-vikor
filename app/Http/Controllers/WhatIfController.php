<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Criterion;
use Illuminate\Http\Request;

class WhatIfController extends Controller
{
    public function run(Request $r, $id)
    {
        $a = Assessment::findOrFail($id);
        
        // Check if user has access to this assessment
        if ($a->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        $weights = $r->input('weights', []);
        
        if (empty($weights)) {
            return response()->json([
                'success' => false,
                'error' => 'No weights provided'
            ], 400);
        }

        // For now, just return a message that what-if analysis is not available
        return response()->json([
            'success' => false,
            'error' => 'What-if analysis feature is temporarily disabled. Please use the admin panel to manage criterion weights.'
        ], 400);
    }

    public function criteria($id)
    {
        $a = Assessment::findOrFail($id);
        
        // Check if user has access to this assessment
        if ($a->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        $criteria = Criterion::where('active', 1)
            ->orderBy('sort_order')
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'code' => $c->code,
                    'name' => $c->name,
                    'source' => $c->source,
                    'type' => $c->type
                ];
            });

        return response()->json([
            'success' => true,
            'criteria' => $criteria
        ]);
    }

    public function history($id)
    {
        $a = Assessment::findOrFail($id);
        
        // Check if user has access to this assessment
        if ($a->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        // Return empty history for now
        return response()->json([
            'success' => true,
            'history' => []
        ]);
    }

    public function saveAsPreset(Request $r, $id)
    {
        return response()->json([
            'success' => false,
            'error' => 'Weight preset feature has been removed. Please use the admin panel to manage criterion weights.'
        ], 400);
    }
}

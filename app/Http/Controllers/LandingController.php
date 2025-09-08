<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Criterion;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAlternative;
use App\Models\Route as RouteModel;
use App\Services\AnswerNormalizer;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // filter ringan (opsional): province
        $provinces = \App\Models\Mountain::select('province')->distinct()->pluck('province')->filter()->values();
        $userCriteria = Criterion::where('source','USER')->orderByRaw("CAST(SUBSTRING(code,2) AS UNSIGNED)")->get();
        
        // Get statistics for homepage
        $mountainsCount = \App\Models\Mountain::count();
        $routesCount = \App\Models\Route::count();
        $assessmentsCount = \App\Models\Assessment::count();
        
        // Get popular routes (most used in assessments)
        $popularRoutes = \App\Models\AssessmentAlternative::selectRaw('route_id, COUNT(*) as usage_count')
            ->groupBy('route_id')
            ->orderByDesc('usage_count')
            ->limit(6)
            ->get()
            ->map(function($item) {
                $route = \App\Models\Route::with('mountain')->find($item->route_id);
                if ($route) {
                    return [
                        'name' => $route->mountain->name . ' — ' . $route->name,
                        'province' => $route->mountain->province,
                        'difficulty' => $this->getDifficultyLevel($route->slope_class),
                        'distance' => $route->distance_km,
                        'duration' => $this->estimateDuration($route->distance_km, $route->elevation_gain_m),
                        'elevation' => $route->elevation_gain_m,
                        'rating' => $this->calculateRating($route),
                        'status' => 'DIBUKA',
                        'updated' => $route->updated_at->diffForHumans()
                    ];
                }
                return null;
            })
            ->filter();
        
        return view('landing', compact('provinces','userCriteria','mountainsCount','routesCount','assessmentsCount','popularRoutes'));
    }
    
    private function getDifficultyLevel($slopeClass)
    {
        return match($slopeClass) {
            1, 2 => 'Pemula',
            3, 4 => 'Menengah',
            5, 6 => 'Ahli',
            default => 'Unknown'
        };
    }
    
    private function estimateDuration($distance, $elevation)
    {
        // Rough estimation: 3-4 km/h on flat, slower with elevation
        $baseHours = $distance / 3.5;
        $elevationFactor = $elevation / 1000 * 0.5; // +0.5 hour per 1000m elevation
        return round($baseHours + $elevationFactor, 1);
    }
    
    private function calculateRating($route)
    {
        // Simple rating based on water sources and support facilities
        $waterScore = $route->water_sources_score ?? 5;
        $supportScore = $route->support_facility_score ?? 5;
        $rating = ($waterScore + $supportScore) / 2;
        return round($rating, 1);
    }

    public function start(Request $r)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }
        
        // 1) buat assessment dengan weight preset dan konfigurasi
        $a = Assessment::create([
          'user_id'=>auth()->id(),
          'title'=>$r->input('title','Assessment '.now()->format('Y-m-d H:i')),
          'status'=>'draft',
          'top_k'=> (int)($r->input('top_k',5)),
          'pure_formula'=> $r->boolean('pure_formula', false),
        ]);

        // 2) simpan jawaban C1..C14 (value_raw)
        $crit = Criterion::where('source','USER')->pluck('id','code');
        foreach ($crit as $code=>$cid) {
          $val = $r->input($code);
          if ($code==='C13') $val = $r->boolean('C13') ? '1' : '0';
          AssessmentAnswer::create(['assessment_id'=>$a->id,'criterion_id'=>$cid,'value_raw'=> (string)($val ?? '')]);
        }
        // 3) normalize
        $normalizer = app('App\Services\AnswerNormalizer');
        $normalizer->normalize($a);

        // 4) pilih alternatif (contoh: semua rute gunung status open / province filter)
        $q = RouteModel::with('mountain')->whereHas('mountain', fn($x)=>$x->where('status','!=','closed'));
        if ($prov = $r->input('province')) $q->whereHas('mountain', fn($x)=>$x->where('province',$prov));
        $routeIds = $q->pluck('id')->toArray();
        $bulk = [];
        foreach ($routeIds as $rid) $bulk[] = ['assessment_id'=>$a->id,'route_id'=>$rid,'is_excluded'=>false];
        AssessmentAlternative::insert($bulk);

        // 5) redirect ke wizard untuk mengisi kuesioner
        return redirect()->route('assess.wizard', $a->id)->with('next','Silakan isi kuesioner untuk mendapatkan rekomendasi terbaik.');
    }
}
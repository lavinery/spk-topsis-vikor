<?php

namespace App\Services;

use App\Models\Criterion;
use Illuminate\Support\Facades\DB;

final class FuzzyService
{
    /**
     * Calculate triangular membership function
     * 
     * @param float $x Input value
     * @param array $params [a, b, c] where a < b < c
     * @return float Membership degree (0-1)
     */
    public function membershipTriangular(float $x, array $params): float
    {
        [$a, $b, $c] = $params;

        if ($x < $a || $x > $c) {
            return 0.0;
        }

        if ($x <= $b) {
            $denominator = $b - $a;
            if ($denominator == 0) {
                return 1.0; // If a == b, x is at the peak
            }
            return ($x - $a) / $denominator;
        } else {
            $denominator = $c - $b;
            if ($denominator == 0) {
                return 1.0; // If b == c, x is at the peak
            }
            return ($c - $x) / $denominator;
        }
    }
    
    /**
     * Calculate trapezoidal membership function
     * 
     * @param float $x Input value
     * @param array $params [a, b, c, d] where a <= b <= c <= d
     * @return float Membership degree (0-1)
     */
    public function membershipTrapezoidal(float $x, array $params): float
    {
        [$a, $b, $c, $d] = $params;

        if ($x < $a || $x > $d) {
            return 0.0;
        }

        if ($x >= $b && $x <= $c) {
            return 1.0;
        }

        if ($x < $b) {
            $denominator = $b - $a;
            if ($denominator == 0) {
                return 1.0; // If a == b, x is at the plateau
            }
            return ($x - $a) / $denominator;
        } else {
            $denominator = $d - $c;
            if ($denominator == 0) {
                return 1.0; // If c == d, x is at the plateau
            }
            return ($d - $x) / $denominator;
        }
    }
    
    /**
     * Fuzzify a scalar value using given terms
     * 
     * @param float $x Input value
     * @param array $terms Array of terms with shape and params_json
     * @return array Membership degrees for each term [term_code => membership_degree]
     */
    public function fuzzifyScalar(float $x, array $terms): array
    {
        $memberships = [];
        
        foreach ($terms as $term) {
            $termArray = is_object($term) ? (array) $term : $term;
            $raw = $termArray['params_json'] ?? null;
            $params = is_array($raw) ? $raw : json_decode((string) $raw, true);
            if (!is_array($params)) {
                continue; // skip invalid term
            }
            $shape = $termArray['shape'];
            $code = $termArray['code'];
            
            if ($shape === 'triangular') {
                $memberships[$code] = $this->membershipTriangular($x, $params);
            } elseif ($shape === 'trapezoidal') {
                $memberships[$code] = $this->membershipTrapezoidal($x, $params);
            }
        }
        
        return $memberships;
    }
    
    /**
     * Aggregate membership degrees using max (Mamdani style)
     * For single input, this just returns the membership degrees as-is
     * 
     * @param array $membershipDict Dictionary of [term_code => membership_degree]
     * @return array Aggregated membership degrees
     */
    public function aggregate(array $membershipDict): array
    {
        // For single input, no aggregation needed
        // In multi-rule scenarios, this would use max operation
        return $membershipDict;
    }
    
    /**
     * Defuzzify using centroid method
     * 
     * @param array $terms Array of terms with params_json
     * @param array $membershipDict Dictionary of [term_code => membership_degree]
     * @param int $resolution Number of points for centroid calculation (default 200)
     * @return float Defuzzified crisp value
     */
    public function defuzzifyCentroid(array $terms, array $membershipDict, int $resolution = 200): float
    {
        if (empty($membershipDict) || empty($terms)) {
            return 0.0;
        }
        
        // Find the overall range by examining all terms
        $minX = PHP_FLOAT_MAX;
        $maxX = PHP_FLOAT_MIN;
        
        foreach ($terms as $term) {
            $termArray = is_object($term) ? (array) $term : $term;
            $params = json_decode($termArray['params_json'], true);
            $minX = min($minX, min($params));
            $maxX = max($maxX, max($params));
        }
        
        if ($minX >= $maxX) {
            return $minX;
        }
        
        // Calculate centroid
        $numerator = 0.0;
        $denominator = 0.0;
        $dx = ($maxX - $minX) / $resolution;
        
        for ($i = 0; $i <= $resolution; $i++) {
            $x = $minX + ($i * $dx);
            $maxMembership = 0.0;
            
            // Find maximum membership degree at point x
            foreach ($membershipDict as $termCode => $membershipDegree) {
                $term = collect($terms)->firstWhere('code', $termCode);
                if (!$term) continue;
                
                $termArray = is_object($term) ? (array) $term : $term;
                $raw = $termArray['params_json'] ?? null;
                $params = is_array($raw) ? $raw : json_decode((string) $raw, true);
                if (!is_array($params)) {
                    continue;
                }
                $shape = $termArray['shape'];
                
                $membershipAtX = 0.0;
                if ($shape === 'triangular') {
                    $membershipAtX = $this->membershipTriangular($x, $params);
                } elseif ($shape === 'trapezoidal') {
                    $membershipAtX = $this->membershipTrapezoidal($x, $params);
                }
                
                $maxMembership = max($maxMembership, min($membershipDegree, $membershipAtX));
            }
            
            $numerator += $x * $maxMembership;
            $denominator += $maxMembership;
        }
        
        return $denominator > 0 ? $numerator / $denominator : $minX;
    }
    
    /**
     * Complete fuzzification and defuzzification process for a criterion
     * 
     * @param float $inputValue Raw input value (1-5 scale)
     * @param int $criterionId Criterion ID
     * @return array Result with defuzzified value and metadata
     */
    public function fuzzifyAndDefuzzify(float $inputValue, int $criterionId): array
    {
        // Get fuzzy mapping for input range validation first (so we can short-circuit on out-of-range)
        $mapping = DB::table('fuzzy_mappings')
            ->where('criterion_id', $criterionId)
            ->first();
        
        if ($mapping) {
            // Validate input range
            if ($inputValue < $mapping->input_min || $inputValue > $mapping->input_max) {
                // Use default term if input is outside range
                if ($mapping->default_term_code) {
                    return [
                        'defuzzified_value' => $inputValue,
                        'memberships' => [$mapping->default_term_code => 1.0],
                        'terms' => [],
                        'warning' => 'Input outside range, using default term'
                    ];
                }
            }
        }
        
        // Get fuzzy terms for this criterion
        $terms = DB::table('fuzzy_terms')
            ->where('criterion_id', $criterionId)
            ->get()
            ->toArray();
        
        if (empty($terms)) {
            return [
                'defuzzified_value' => $inputValue,
                'memberships' => [],
                'terms' => [],
                'error' => 'No fuzzy terms found for criterion'
            ];
        }
        
        // Fuzzify the input
        $memberships = $this->fuzzifyScalar($inputValue, $terms);
        
        // Aggregate (for single input, this is a no-op)
        $aggregated = $this->aggregate($memberships);
        
        // Defuzzify using centroid
        $defuzzifiedValue = $this->defuzzifyCentroid($terms, $aggregated);
        
        return [
            'defuzzified_value' => $defuzzifiedValue,
            'memberships' => $memberships,
            'terms' => $terms,
            'input_value' => $inputValue
        ];
    }
    
    /**
     * Get fuzzy terms for a criterion
     * 
     * @param int $criterionId
     * @return \Illuminate\Support\Collection
     */
    public function getFuzzyTerms(int $criterionId)
    {
        return DB::table('fuzzy_terms')
            ->where('criterion_id', $criterionId)
            ->orderBy('code')
            ->get();
    }
    
    /**
     * Get fuzzy mapping for a criterion
     * 
     * @param int $criterionId
     * @return object|null
     */
    public function getFuzzyMapping(int $criterionId)
    {
        return DB::table('fuzzy_mappings')
            ->where('criterion_id', $criterionId)
            ->first();
    }
    
    /**
     * Check if a criterion has fuzzy processing enabled
     * 
     * @param int $criterionId
     * @return bool
     */
    public function isCriterionFuzzy(int $criterionId): bool
    {
        $criterion = Criterion::find($criterionId);
        return $criterion && $criterion->is_fuzzy;
    }
}

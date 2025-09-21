<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SPK Pure Formula Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for pure TOPSIS implementation
    | without compatibility mapping or special normalizations
    |
    */

    'pure_formula' => true,                 // Enable pure formula mode
    'strict_missing' => true,               // Missing data → exclude alternative
    'pure_sources' => ['MOUNTAIN', 'ROUTE'], // Only per-alternative criteria
    
    
    /*
    |--------------------------------------------------------------------------
    | Pure Weights Configuration
    |--------------------------------------------------------------------------
    |
    | Pure weights for C15-C21 (external criteria only)
    | Renormalized to sum = 1
    |
    */
    
    'pure_weights' => [
        'C15' => 0.210526,  // Ketinggian (4% / 0.19)
        'C16' => 0.210526,  // Elevation gain (4% / 0.19)
        'C17' => 0.157895,  // Tutupan Lahan (3% / 0.19)
        'C18' => 0.157895,  // Panjang Jalur (3% / 0.19)
        'C19' => 0.157895,  // Kecuraman (3% / 0.19)
        'C20' => 0.052632,  // Sumber Air (1% / 0.19)
        'C21' => 0.052632,  // Sarana Pendukung (1% / 0.19)
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Fuzzy Processing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for fuzzy MADM processing layer
    |
    */
    
    'fuzzy' => [
        'enabled' => true,
        'defuzzification' => 'centroid',
        'default_shape' => 'triangular',
        'resolution' => 200,
        'input_range' => [1.0, 5.0], // Default input range for user criteria
        'default_term' => 'SEDANG', // Default term for out-of-range inputs
    ],
];

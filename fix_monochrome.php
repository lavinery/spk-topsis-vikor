<?php

// Script untuk fix monochrome theme
$replacements = [
    // Text colors
    'text-gray-900' => 'text-neutral-text',
    'text-gray-600' => 'text-neutral-sub',
    'text-gray-500' => 'text-neutral-sub',
    'text-gray-400' => 'text-neutral-sub',
    'text-gray-300' => 'text-neutral-sub',
    
    // Background colors
    'bg-gray-50' => 'bg-white',
    'bg-gray-100' => 'bg-white',
    'bg-gray-200' => 'bg-neutral-line',
    'bg-gray-300' => 'bg-neutral-line',
    
    // Border colors
    'border-gray-200' => 'border-neutral-line',
    'border-gray-300' => 'border-neutral-line',
    'border-2 border-gray-200' => 'border border-neutral-line',
    'border-2 border-gray-300' => 'border border-neutral-line',
    
    // Brand colors
    'text-brand-600' => 'text-brand',
    'text-brand-700' => 'text-brand',
    'bg-brand-600' => 'bg-brand',
    'bg-brand-700' => 'bg-brand',
    'hover:bg-brand-700' => 'hover:opacity-90',
    'focus:border-brand-500' => 'focus:border-brand',
    'focus:ring-brand-200' => 'focus:ring-brand/20',
    
    // Shadow
    'shadow-sm' => 'shadow-soft',
    'shadow-md' => 'shadow-soft',
    
    // Spacing
    'px-4 sm:px-6 lg:px-8' => 'px-4',
    'py-12' => 'py-10',
    'py-16' => 'py-12',
    
    // Typography
    'font-display' => 'font-semibold',
    'font-body' => '',
    'text-4xl' => 'text-3xl',
    'text-lg' => 'text-sm',
    
    // Remove problematic classes
    'bg-slate-900' => 'bg-neutral-bg',
    'bg-slate-800' => 'bg-white',
    'text-white' => 'text-neutral-text',
    'bg-emerald-50' => 'bg-white',
    'text-emerald-700' => 'text-neutral-text',
    'bg-amber-50' => 'bg-white',
    'text-amber-700' => 'text-neutral-text',
    'bg-rose-50' => 'bg-white',
    'text-rose-700' => 'text-neutral-text',
];

$files = [
    'resources/views/landing.blade.php',
    'resources/views/livewire/assessment/result-top.blade.php',
    'resources/views/livewire/assessment/user-wizard.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "Processing: $file\n";
        $content = file_get_contents($file);
        
        foreach ($replacements as $old => $new) {
            $content = str_replace($old, $new, $content);
        }
        
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}

echo "Monochrome fix completed!\n";

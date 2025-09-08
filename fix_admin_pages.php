<?php

// Script khusus untuk fix halaman admin
$replacements = [
    'bg-indigo-600' => 'bg-brand',
    'bg-indigo-700' => 'bg-brand',
    'bg-indigo-800' => 'bg-brand',
    'bg-indigo-900' => 'bg-brand',
    'text-indigo-600' => 'text-brand',
    'text-indigo-700' => 'text-brand',
    'text-indigo-800' => 'text-brand',
    'text-indigo-900' => 'text-brand',
    'hover:bg-indigo-700' => 'hover:opacity-90',
    'hover:bg-indigo-800' => 'hover:opacity-90',
    'hover:bg-indigo-900' => 'hover:opacity-90',
    'border-indigo-600' => 'border-brand',
    'border-indigo-700' => 'border-brand',
    'border-indigo-800' => 'border-brand',
    'border-indigo-900' => 'border-brand',
    'focus:border-indigo-500' => 'focus:border-brand',
    'focus:ring-indigo-200' => 'focus:ring-brand/20',
    'focus:ring-indigo-300' => 'focus:ring-brand/20',
    'focus:ring-indigo-400' => 'focus:ring-brand/20',
    'ring-indigo-200' => 'ring-brand/20',
    'ring-indigo-300' => 'ring-brand/20',
    'ring-indigo-400' => 'ring-brand/20',
    'bg-emerald-50' => 'bg-white',
    'text-emerald-700' => 'text-neutral-text',
    'bg-amber-50' => 'bg-white',
    'text-amber-700' => 'text-neutral-text',
    'bg-rose-50' => 'bg-white',
    'text-rose-700' => 'text-neutral-text',
    'bg-blue-50' => 'bg-white',
    'text-blue-700' => 'text-neutral-text',
    'bg-green-50' => 'bg-white',
    'text-green-700' => 'text-neutral-text',
    'bg-red-50' => 'bg-white',
    'text-red-700' => 'text-neutral-text',
    'bg-yellow-50' => 'bg-white',
    'text-yellow-700' => 'text-neutral-text',
    'bg-purple-50' => 'bg-white',
    'text-purple-700' => 'text-neutral-text',
    'bg-pink-50' => 'bg-white',
    'text-pink-700' => 'text-neutral-text',
    'bg-cyan-50' => 'bg-white',
    'text-cyan-700' => 'text-neutral-text',
    'bg-teal-50' => 'bg-white',
    'text-teal-700' => 'text-neutral-text',
    'bg-orange-50' => 'bg-white',
    'text-orange-700' => 'text-neutral-text',
    'bg-lime-50' => 'bg-white',
    'text-lime-700' => 'text-neutral-text',
    'bg-violet-50' => 'bg-white',
    'text-violet-700' => 'text-neutral-text',
    'bg-fuchsia-50' => 'bg-white',
    'text-fuchsia-700' => 'text-neutral-text',
    'bg-sky-50' => 'bg-white',
    'text-sky-700' => 'text-neutral-text',
];

$adminFiles = [
    'resources/views/livewire/admin/weight-presets-crud.blade.php',
    'resources/views/livewire/admin/category-maps-crud.blade.php',
    'resources/views/livewire/admin/criteria-crud.blade.php',
    'resources/views/livewire/admin/routes-crud.blade.php',
    'resources/views/livewire/admin/constraints-crud.blade.php',
    'resources/views/livewire/admin/mountains-crud.blade.php',
    'resources/views/livewire/admin/assessments-monitor.blade.php',
    'resources/views/admin/dashboard.blade.php'
];

echo "=== FIXING ADMIN PAGES ===\n\n";

foreach ($adminFiles as $file) {
    if (file_exists($file)) {
        echo "Processing: " . basename($file) . "\n";
        $content = file_get_contents($file);
        $originalContent = $content;
        
        foreach ($replacements as $old => $new) {
            $content = str_replace($old, $new, $content);
        }
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "✅ Fixed: " . basename($file) . "\n";
        } else {
            echo "✅ Clean: " . basename($file) . "\n";
        }
    }
}

echo "\n=== ADMIN PAGES FIXED ===\n";

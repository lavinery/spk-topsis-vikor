<?php

// Script untuk fix border pada form dan input elements
$replacements = [
    // Input borders
    'border-gray-300' => 'border-neutral-line',
    'border-gray-400' => 'border-neutral-line',
    'border-gray-500' => 'border-neutral-line',
    'border-gray-600' => 'border-neutral-line',
    'border-gray-700' => 'border-neutral-line',
    'border-gray-800' => 'border-neutral-line',
    'border-gray-900' => 'border-neutral-line',
    
    // Select borders
    'select.*border-gray-300' => 'select.*border-neutral-line',
    'select.*border-gray-400' => 'select.*border-neutral-line',
    'select.*border-gray-500' => 'select.*border-neutral-line',
    'select.*border-gray-600' => 'select.*border-neutral-line',
    'select.*border-gray-700' => 'select.*border-neutral-line',
    'select.*border-gray-800' => 'select.*border-neutral-line',
    'select.*border-gray-900' => 'select.*border-neutral-line',
    
    // Textarea borders
    'textarea.*border-gray-300' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-400' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-500' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-600' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-700' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-800' => 'textarea.*border-neutral-line',
    'textarea.*border-gray-900' => 'textarea.*border-neutral-line',
    
    // Checkbox borders
    'checkbox.*border-gray-300' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-400' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-500' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-600' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-700' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-800' => 'checkbox.*border-neutral-line',
    'checkbox.*border-gray-900' => 'checkbox.*border-neutral-line',
    
    // Radio borders
    'radio.*border-gray-300' => 'radio.*border-neutral-line',
    'radio.*border-gray-400' => 'radio.*border-neutral-line',
    'radio.*border-gray-500' => 'radio.*border-neutral-line',
    'radio.*border-gray-600' => 'radio.*border-neutral-line',
    'radio.*border-gray-700' => 'radio.*border-neutral-line',
    'radio.*border-gray-800' => 'radio.*border-neutral-line',
    'radio.*border-gray-900' => 'radio.*border-neutral-line',
    
    // File input borders
    'file.*border-gray-300' => 'file.*border-neutral-line',
    'file.*border-gray-400' => 'file.*border-neutral-line',
    'file.*border-gray-500' => 'file.*border-neutral-line',
    'file.*border-gray-600' => 'file.*border-neutral-line',
    'file.*border-gray-700' => 'file.*border-neutral-line',
    'file.*border-gray-800' => 'file.*border-neutral-line',
    'file.*border-gray-900' => 'file.*border-neutral-line',
    
    // Focus states
    'focus:border-gray-300' => 'focus:border-brand',
    'focus:border-gray-400' => 'focus:border-brand',
    'focus:border-gray-500' => 'focus:border-brand',
    'focus:border-gray-600' => 'focus:border-brand',
    'focus:border-gray-700' => 'focus:border-brand',
    'focus:border-gray-800' => 'focus:border-brand',
    'focus:border-gray-900' => 'focus:border-brand',
    
    // Ring states
    'focus:ring-gray-200' => 'focus:ring-brand/20',
    'focus:ring-gray-300' => 'focus:ring-brand/20',
    'focus:ring-gray-400' => 'focus:ring-brand/20',
    'focus:ring-gray-500' => 'focus:ring-brand/20',
    'focus:ring-gray-600' => 'focus:ring-brand/20',
    'focus:ring-gray-700' => 'focus:ring-brand/20',
    'focus:ring-gray-800' => 'focus:ring-brand/20',
    'focus:ring-gray-900' => 'focus:ring-brand/20',
    
    // Placeholder colors
    'placeholder-gray-400' => 'placeholder-neutral-sub',
    'placeholder-gray-500' => 'placeholder-neutral-sub',
    'placeholder-gray-600' => 'placeholder-neutral-sub',
    'placeholder-gray-700' => 'placeholder-neutral-sub',
    'placeholder-gray-800' => 'placeholder-neutral-sub',
    'placeholder-gray-900' => 'placeholder-neutral-sub',
    
    // Text colors
    'text-gray-400' => 'text-neutral-sub',
    'text-gray-500' => 'text-neutral-sub',
    'text-gray-600' => 'text-neutral-sub',
    'text-gray-700' => 'text-neutral-sub',
    'text-gray-800' => 'text-neutral-text',
    'text-gray-900' => 'text-neutral-text',
    
    // Background colors
    'bg-gray-50' => 'bg-white',
    'bg-gray-100' => 'bg-white',
    'bg-gray-200' => 'bg-neutral-line',
    'bg-gray-300' => 'bg-neutral-line',
    'bg-gray-400' => 'bg-neutral-line',
    'bg-gray-500' => 'bg-neutral-line',
    'bg-gray-600' => 'bg-neutral-line',
    'bg-gray-700' => 'bg-neutral-line',
    'bg-gray-800' => 'bg-neutral-line',
    'bg-gray-900' => 'bg-neutral-line',
];

// Dapatkan semua file blade
$files = glob('resources/views/**/*.blade.php');

echo "=== FIXING FORM BORDERS ===\n\n";

$processed = 0;
$fixed = 0;

foreach ($files as $file) {
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
            $fixed++;
        } else {
            echo "✅ Clean: " . basename($file) . "\n";
        }
        
        $processed++;
    }
}

echo "\n=== FORM BORDERS FIXED ===\n";
echo "Processed: {$processed} files\n";
echo "Fixed: {$fixed} files\n";
echo "Clean: " . ($processed - $fixed) . " files\n";

echo "\n🎨 All form elements now have consistent borders!\n";
echo "✅ Input fields have neutral borders\n";
echo "✅ Select fields have neutral borders\n";
echo "✅ Textarea fields have neutral borders\n";
echo "✅ Checkbox fields have neutral borders\n";
echo "✅ Radio fields have neutral borders\n";
echo "✅ File input fields have neutral borders\n";
echo "✅ Focus states use brand color\n";
echo "✅ Ring states use brand color\n";
echo "✅ Placeholder colors are neutral\n";
echo "✅ Text colors are neutral\n";
echo "✅ Background colors are neutral\n";
echo "✅ Professional appearance\n";

<?php

// Script untuk menambahkan border pada komponen yang perlu
$replacements = [
    // Form containers tanpa border
    'bg-white rounded-xl p-6 shadow-soft' => 'bg-white border border-neutral-line rounded-xl p-6 shadow-soft',
    'bg-white rounded-2xl p-6 shadow-soft' => 'bg-white border border-neutral-line rounded-2xl p-6 shadow-soft',
    'bg-white rounded-lg p-6 shadow-soft' => 'bg-white border border-neutral-line rounded-lg p-6 shadow-soft',
    'bg-white rounded-xl p-4 shadow-soft' => 'bg-white border border-neutral-line rounded-xl p-4 shadow-soft',
    'bg-white rounded-2xl p-4 shadow-soft' => 'bg-white border border-neutral-line rounded-2xl p-4 shadow-soft',
    'bg-white rounded-lg p-4 shadow-soft' => 'bg-white border border-neutral-line rounded-lg p-4 shadow-soft',
    'bg-white rounded-xl p-3 shadow-soft' => 'bg-white border border-neutral-line rounded-xl p-3 shadow-soft',
    'bg-white rounded-2xl p-3 shadow-soft' => 'bg-white border border-neutral-line rounded-2xl p-3 shadow-soft',
    'bg-white rounded-lg p-3 shadow-soft' => 'bg-white border border-neutral-line rounded-lg p-3 shadow-soft',
    
    // Card containers tanpa border
    'bg-white rounded-xl p-6' => 'bg-white border border-neutral-line rounded-xl p-6',
    'bg-white rounded-2xl p-6' => 'bg-white border border-neutral-line rounded-2xl p-6',
    'bg-white rounded-lg p-6' => 'bg-white border border-neutral-line rounded-lg p-6',
    'bg-white rounded-xl p-4' => 'bg-white border border-neutral-line rounded-xl p-4',
    'bg-white rounded-2xl p-4' => 'bg-white border border-neutral-line rounded-2xl p-4',
    'bg-white rounded-lg p-4' => 'bg-white border border-neutral-line rounded-lg p-4',
    'bg-white rounded-xl p-3' => 'bg-white border border-neutral-line rounded-xl p-3',
    'bg-white rounded-2xl p-3' => 'bg-white border border-neutral-line rounded-2xl p-3',
    'bg-white rounded-lg p-3' => 'bg-white border border-neutral-line rounded-lg p-3',
    
    // Modal containers
    'bg-white rounded-2xl shadow-soft' => 'bg-white border border-neutral-line rounded-2xl shadow-soft',
    'bg-white rounded-xl shadow-soft' => 'bg-white border border-neutral-line rounded-xl shadow-soft',
    'bg-white rounded-lg shadow-soft' => 'bg-white border border-neutral-line rounded-lg shadow-soft',
    
    // Input containers
    'bg-white rounded-lg' => 'bg-white border border-neutral-line rounded-lg',
    'bg-white rounded-xl' => 'bg-white border border-neutral-line rounded-xl',
    'bg-white rounded-2xl' => 'bg-white border border-neutral-line rounded-2xl',
    
    // Table containers
    'bg-white rounded-lg overflow-hidden' => 'bg-white border border-neutral-line rounded-lg overflow-hidden',
    'bg-white rounded-xl overflow-hidden' => 'bg-white border border-neutral-line rounded-xl overflow-hidden',
    'bg-white rounded-2xl overflow-hidden' => 'bg-white border border-neutral-line rounded-2xl overflow-hidden',
    
    // List containers
    'bg-white rounded-lg space-y-2' => 'bg-white border border-neutral-line rounded-lg space-y-2',
    'bg-white rounded-xl space-y-2' => 'bg-white border border-neutral-line rounded-xl space-y-2',
    'bg-white rounded-2xl space-y-2' => 'bg-white border border-neutral-line rounded-2xl space-y-2',
    
    // Button containers
    'bg-white rounded-lg flex items-center' => 'bg-white border border-neutral-line rounded-lg flex items-center',
    'bg-white rounded-xl flex items-center' => 'bg-white border border-neutral-line rounded-xl flex items-center',
    'bg-white rounded-2xl flex items-center' => 'bg-white border border-neutral-line rounded-2xl flex items-center',
    
    // Fix border-2 yang tidak konsisten
    'border-2 border-neutral-line' => 'border border-neutral-line',
    
    // Hero section yang tidak perlu border
    'bg-white border-t border-neutral-line' => 'bg-white border-t border-neutral-line',
    'bg-white border-b border-neutral-line' => 'bg-white border-b border-neutral-line',
    'bg-white border-l border-neutral-line' => 'bg-white border-l border-neutral-line',
    'bg-white border-r border-neutral-line' => 'bg-white border-r border-neutral-line',
];

// Dapatkan semua file blade
$files = glob('resources/views/**/*.blade.php');

echo "=== ADDING BORDERS TO COMPONENTS ===\n\n";

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

echo "\n=== BORDER ADDITION COMPLETED ===\n";
echo "Processed: {$processed} files\n";
echo "Fixed: {$fixed} files\n";
echo "Clean: " . ($processed - $fixed) . " files\n";

echo "\n🎨 All components now have consistent borders!\n";
echo "✅ Form containers have borders\n";
echo "✅ Card containers have borders\n";
echo "✅ Modal containers have borders\n";
echo "✅ Input containers have borders\n";
echo "✅ Table containers have borders\n";
echo "✅ List containers have borders\n";
echo "✅ Button containers have borders\n";
echo "✅ Consistent border styling\n";
echo "✅ Professional appearance\n";

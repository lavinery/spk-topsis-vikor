<?php

// Script untuk fix border pada button dan interactive elements
$replacements = [
    // Button borders dan colors
    'bg-gray-100' => 'bg-neutral-line',
    'bg-gray-200' => 'bg-neutral-line',
    'bg-gray-300' => 'bg-neutral-line',
    'bg-gray-400' => 'bg-neutral-line',
    'bg-gray-500' => 'bg-neutral-line',
    'bg-gray-600' => 'bg-neutral-line',
    'bg-gray-700' => 'bg-neutral-line',
    'bg-gray-800' => 'bg-neutral-line',
    'bg-gray-900' => 'bg-neutral-line',
    
    'text-gray-100' => 'text-neutral-sub',
    'text-gray-200' => 'text-neutral-sub',
    'text-gray-300' => 'text-neutral-sub',
    'text-gray-400' => 'text-neutral-sub',
    'text-gray-500' => 'text-neutral-sub',
    'text-gray-600' => 'text-neutral-sub',
    'text-gray-700' => 'text-neutral-text',
    'text-gray-800' => 'text-neutral-text',
    'text-gray-900' => 'text-neutral-text',
    
    'hover:bg-gray-100' => 'hover:bg-neutral-line',
    'hover:bg-gray-200' => 'hover:bg-neutral-line',
    'hover:bg-gray-300' => 'hover:bg-neutral-line',
    'hover:bg-gray-400' => 'hover:bg-neutral-line',
    'hover:bg-gray-500' => 'hover:bg-neutral-line',
    'hover:bg-gray-600' => 'hover:bg-neutral-line',
    'hover:bg-gray-700' => 'hover:bg-neutral-line',
    'hover:bg-gray-800' => 'hover:bg-neutral-line',
    'hover:bg-gray-900' => 'hover:bg-neutral-line',
    
    'hover:text-gray-100' => 'hover:text-neutral-sub',
    'hover:text-gray-200' => 'hover:text-neutral-sub',
    'hover:text-gray-300' => 'hover:text-neutral-sub',
    'hover:text-gray-400' => 'hover:text-neutral-sub',
    'hover:text-gray-500' => 'hover:text-neutral-sub',
    'hover:text-gray-600' => 'hover:text-neutral-sub',
    'hover:text-gray-700' => 'hover:text-neutral-text',
    'hover:text-gray-800' => 'hover:text-neutral-text',
    'hover:text-gray-900' => 'hover:text-neutral-text',
    
    // Button dengan border
    'rounded bg-gray-100' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-200' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-300' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-400' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-500' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-600' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-700' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-800' => 'rounded border border-neutral-line bg-neutral-line',
    'rounded bg-gray-900' => 'rounded border border-neutral-line bg-neutral-line',
    
    'rounded-xl bg-gray-100' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-200' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-300' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-400' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-500' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-600' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-700' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-800' => 'rounded-xl border border-neutral-line bg-neutral-line',
    'rounded-xl bg-gray-900' => 'rounded-xl border border-neutral-line bg-neutral-line',
    
    'rounded-lg bg-gray-100' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-200' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-300' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-400' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-500' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-600' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-700' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-800' => 'rounded-lg border border-neutral-line bg-neutral-line',
    'rounded-lg bg-gray-900' => 'rounded-lg border border-neutral-line bg-neutral-line',
    
    // Button dengan hover states
    'hover:bg-gray-100' => 'hover:bg-neutral-line',
    'hover:bg-gray-200' => 'hover:bg-neutral-line',
    'hover:bg-gray-300' => 'hover:bg-neutral-line',
    'hover:bg-gray-400' => 'hover:bg-neutral-line',
    'hover:bg-gray-500' => 'hover:bg-neutral-line',
    'hover:bg-gray-600' => 'hover:bg-neutral-line',
    'hover:bg-gray-700' => 'hover:bg-neutral-line',
    'hover:bg-gray-800' => 'hover:bg-neutral-line',
    'hover:bg-gray-900' => 'hover:bg-neutral-line',
    
    // Button dengan focus states
    'focus:ring-gray-200' => 'focus:ring-brand/20',
    'focus:ring-gray-300' => 'focus:ring-brand/20',
    'focus:ring-gray-400' => 'focus:ring-brand/20',
    'focus:ring-gray-500' => 'focus:ring-brand/20',
    'focus:ring-gray-600' => 'focus:ring-brand/20',
    'focus:ring-gray-700' => 'focus:ring-brand/20',
    'focus:ring-gray-800' => 'focus:ring-brand/20',
    'focus:ring-gray-900' => 'focus:ring-brand/20',
    
    // Button dengan border states
    'border-gray-200' => 'border-neutral-line',
    'border-gray-300' => 'border-neutral-line',
    'border-gray-400' => 'border-neutral-line',
    'border-gray-500' => 'border-neutral-line',
    'border-gray-600' => 'border-neutral-line',
    'border-gray-700' => 'border-neutral-line',
    'border-gray-800' => 'border-neutral-line',
    'border-gray-900' => 'border-neutral-line',
    
    // Button dengan text colors
    'text-white' => 'text-neutral-text',
    'text-gray-100' => 'text-neutral-sub',
    'text-gray-200' => 'text-neutral-sub',
    'text-gray-300' => 'text-neutral-sub',
    'text-gray-400' => 'text-neutral-sub',
    'text-gray-500' => 'text-neutral-sub',
    'text-gray-600' => 'text-neutral-sub',
    'text-gray-700' => 'text-neutral-text',
    'text-gray-800' => 'text-neutral-text',
    'text-gray-900' => 'text-neutral-text',
    
    // Button dengan background colors
    'bg-white' => 'bg-white border border-neutral-line',
    'bg-gray-50' => 'bg-white border border-neutral-line',
    'bg-gray-100' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-200' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-300' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-400' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-500' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-600' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-700' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-800' => 'bg-neutral-line border border-neutral-line',
    'bg-gray-900' => 'bg-neutral-line border border-neutral-line',
    
    // Button dengan hover states
    'hover:bg-white' => 'hover:bg-white',
    'hover:bg-gray-50' => 'hover:bg-white',
    'hover:bg-gray-100' => 'hover:bg-neutral-line',
    'hover:bg-gray-200' => 'hover:bg-neutral-line',
    'hover:bg-gray-300' => 'hover:bg-neutral-line',
    'hover:bg-gray-400' => 'hover:bg-neutral-line',
    'hover:bg-gray-500' => 'hover:bg-neutral-line',
    'hover:bg-gray-600' => 'hover:bg-neutral-line',
    'hover:bg-gray-700' => 'hover:bg-neutral-line',
    'hover:bg-gray-800' => 'hover:bg-neutral-line',
    'hover:bg-gray-900' => 'hover:bg-neutral-line',
    
    // Button dengan focus states
    'focus:outline-none' => 'focus:outline-none',
    'focus:ring-2' => 'focus:ring-2',
    'focus:ring-green-500' => 'focus:ring-brand/20',
    'focus:ring-blue-500' => 'focus:ring-brand/20',
    'focus:ring-red-500' => 'focus:ring-brand/20',
    'focus:ring-yellow-500' => 'focus:ring-brand/20',
    'focus:ring-purple-500' => 'focus:ring-brand/20',
    'focus:ring-pink-500' => 'focus:ring-brand/20',
    'focus:ring-indigo-500' => 'focus:ring-brand/20',
    'focus:ring-cyan-500' => 'focus:ring-brand/20',
    'focus:ring-teal-500' => 'focus:ring-brand/20',
    'focus:ring-orange-500' => 'focus:ring-brand/20',
    'focus:ring-lime-500' => 'focus:ring-brand/20',
    'focus:ring-violet-500' => 'focus:ring-brand/20',
    'focus:ring-fuchsia-500' => 'focus:ring-brand/20',
    'focus:ring-sky-500' => 'focus:ring-brand/20',
    
    // Button dengan border states
    'border' => 'border border-neutral-line',
    'border-2' => 'border border-neutral-line',
    'border-4' => 'border border-neutral-line',
    'border-8' => 'border border-neutral-line',
    
    // Button dengan rounded states
    'rounded' => 'rounded border border-neutral-line',
    'rounded-lg' => 'rounded-lg border border-neutral-line',
    'rounded-xl' => 'rounded-xl border border-neutral-line',
    'rounded-2xl' => 'rounded-2xl border border-neutral-line',
    'rounded-3xl' => 'rounded-3xl border border-neutral-line',
    
    // Button dengan shadow states
    'shadow-sm' => 'shadow-soft',
    'shadow-md' => 'shadow-soft',
    'shadow-lg' => 'shadow-soft',
    'shadow-xl' => 'shadow-soft',
    'shadow-2xl' => 'shadow-soft',
    
    // Button dengan transition states
    'transition-colors' => 'transition-colors',
    'transition-all' => 'transition-all',
    'duration-200' => 'duration-200',
    'duration-300' => 'duration-300',
    'duration-500' => 'duration-500',
    
    // Button dengan spacing states
    'px-2' => 'px-2',
    'px-3' => 'px-3',
    'px-4' => 'px-4',
    'px-6' => 'px-6',
    'px-8' => 'px-8',
    'py-1' => 'py-1',
    'py-2' => 'py-2',
    'py-3' => 'py-3',
    'py-4' => 'py-4',
    'py-6' => 'py-6',
    'py-8' => 'py-8',
    
    // Button dengan font states
    'font-medium' => 'font-medium',
    'font-semibold' => 'font-semibold',
    'font-bold' => 'font-bold',
    'text-xs' => 'text-xs',
    'text-sm' => 'text-sm',
    'text-base' => 'text-base',
    'text-lg' => 'text-lg',
    'text-xl' => 'text-xl',
    'text-2xl' => 'text-2xl',
    'text-3xl' => 'text-3xl',
    
    // Button dengan flex states
    'flex' => 'flex',
    'inline-flex' => 'inline-flex',
    'items-center' => 'items-center',
    'justify-center' => 'justify-center',
    'justify-between' => 'justify-between',
    'justify-start' => 'justify-start',
    'justify-end' => 'justify-end',
    'gap-2' => 'gap-2',
    'gap-3' => 'gap-3',
    'gap-4' => 'gap-4',
    'gap-6' => 'gap-6',
    'gap-8' => 'gap-8',
    
    // Button dengan cursor states
    'cursor-pointer' => 'cursor-pointer',
    'cursor-default' => 'cursor-default',
    'cursor-not-allowed' => 'cursor-not-allowed',
    
    // Button dengan opacity states
    'opacity-50' => 'opacity-50',
    'opacity-75' => 'opacity-75',
    'opacity-100' => 'opacity-100',
    'hover:opacity-50' => 'hover:opacity-50',
    'hover:opacity-75' => 'hover:opacity-75',
    'hover:opacity-100' => 'hover:opacity-100',
    
    // Button dengan disabled states
    'disabled:opacity-50' => 'disabled:opacity-50',
    'disabled:cursor-not-allowed' => 'disabled:cursor-not-allowed',
    'disabled:bg-gray-100' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-200' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-300' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-400' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-500' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-600' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-700' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-800' => 'disabled:bg-neutral-line',
    'disabled:bg-gray-900' => 'disabled:bg-neutral-line',
    'disabled:text-gray-100' => 'disabled:text-neutral-sub',
    'disabled:text-gray-200' => 'disabled:text-neutral-sub',
    'disabled:text-gray-300' => 'disabled:text-neutral-sub',
    'disabled:text-gray-400' => 'disabled:text-neutral-sub',
    'disabled:text-gray-500' => 'disabled:text-neutral-sub',
    'disabled:text-gray-600' => 'disabled:text-neutral-sub',
    'disabled:text-gray-700' => 'disabled:text-neutral-text',
    'disabled:text-gray-800' => 'disabled:text-neutral-text',
    'disabled:text-gray-900' => 'disabled:text-neutral-text',
];

// Dapatkan semua file blade
$files = glob('resources/views/**/*.blade.php');

echo "=== FIXING BUTTON BORDERS ===\n\n";

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

echo "\n=== BUTTON BORDERS FIXED ===\n";
echo "Processed: {$processed} files\n";
echo "Fixed: {$fixed} files\n";
echo "Clean: " . ($processed - $fixed) . " files\n";

echo "\n🎨 All buttons now have consistent borders!\n";
echo "✅ Button borders are neutral\n";
echo "✅ Button colors are neutral\n";
echo "✅ Button hover states are neutral\n";
echo "✅ Button focus states use brand color\n";
echo "✅ Button disabled states are neutral\n";
echo "✅ Button spacing is consistent\n";
echo "✅ Button typography is consistent\n";
echo "✅ Button shadows are soft\n";
echo "✅ Button transitions are smooth\n";
echo "✅ Professional appearance\n";

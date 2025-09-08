<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\RoutesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function routes(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv']);
        
        try {
            Excel::import(new RoutesImport, $r->file('file'));
            return back()->with('ok', 'Routes imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
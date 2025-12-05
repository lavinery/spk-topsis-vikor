<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\RoutesImport;
use App\Imports\MountainsImport;
use App\Imports\CriteriaImport;
use App\Imports\CriterionWeightsImport;
use App\Imports\CategoryMapsImport;
use App\Imports\FuzzyTermsImport;
use App\Imports\ConstraintsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function mountains(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new MountainsImport, $r->file('file'));
            return back()->with('ok', 'Data gunung berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function routes(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new RoutesImport, $r->file('file'));
            return back()->with('ok', 'Data jalur berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function criteria(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new CriteriaImport, $r->file('file'));
            return back()->with('ok', 'Data kriteria berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function criterionWeights(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new CriterionWeightsImport, $r->file('file'));
            return back()->with('ok', 'Data bobot kriteria berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function categoryMaps(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new CategoryMapsImport, $r->file('file'));
            return back()->with('ok', 'Data pemetaan kategori berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function fuzzyTerms(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new FuzzyTermsImport, $r->file('file'));
            return back()->with('ok', 'Data fuzzy terms berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function constraints(Request $r)
    {
        $r->validate(['file' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new ConstraintsImport, $r->file('file'));
            return back()->with('ok', 'Data constraints berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }
}
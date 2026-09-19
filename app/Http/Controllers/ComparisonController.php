<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index(Request $request)
    {
        $providers = Provider::where('active', true)->get();
        $sectionHeaders = Setting::get('sectionHeaders', []);

        return view('pages.balanza', compact('providers', 'sectionHeaders'));
    }
}

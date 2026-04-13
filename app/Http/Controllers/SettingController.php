<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'gst_enabled' => Setting::get('gst_enabled', '0'),
            'gst_type' => Setting::get('gst_type', 'exclusive'),
            'gst_percentage' => Setting::get('gst_percentage', '0'),
            'cgst_percentage' => Setting::get('cgst_percentage', '0'),
            'sgst_percentage' => Setting::get('sgst_percentage', '0'),
        ];
        
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cgst_percentage' => 'nullable|numeric|min:0|max:100',
            'sgst_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $totalGst = (float)$request->cgst_percentage + (float)$request->sgst_percentage;

        Setting::set('gst_enabled', $request->gst_enabled == '1' ? '1' : '0');
        Setting::set('gst_type', $request->gst_type);
        Setting::set('gst_percentage', $totalGst);
        Setting::set('cgst_percentage', $request->cgst_percentage);
        Setting::set('sgst_percentage', $request->sgst_percentage);

        return redirect()->back()->with('success', 'GST સેટિંગ્સ સફળતાપૂર્વક અપડેટ કરવામાં આવ્યા.');
    }
}

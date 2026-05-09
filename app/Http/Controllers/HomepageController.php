<?php

namespace App\Http\Controllers;

use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageController extends Controller
{
    // Show the homepage management form
    public function index()
    {
        $settings = HomepageSetting::first();
        if (!$settings) {
            $settings = new HomepageSetting();
        }
        return view('homepage_management', compact('settings'));
    }
    
    // Update homepage settings
public function update(Request $request)
{
    $settings = HomepageSetting::first();
    
    if (!$settings) {
        $settings = new HomepageSetting();
    }
    
    // --- Update text fields ---
    $settings->hero_headline = $request->hero_headline;
    $settings->hero_highlight = $request->hero_highlight;
    $settings->about_main = $request->about_main;
    $settings->about_more = $request->about_more;
    $settings->dean_name = $request->dean_name;
    $settings->dean_title = $request->dean_title;
    $settings->news_rss = $request->news_rss;
    
    // How to Apply Text Fields
    $settings->apply_address = $request->apply_address;
    $settings->apply_link = $request->apply_link;
    $settings->apply_on_site = $request->apply_on_site; 
    $settings->apply_online = $request->apply_online;   
    
    $settings->contact_email = $request->contact_email;
    $settings->contact_fb = $request->contact_fb;
    $settings->contact_address = $request->contact_address;
    $settings->contact_map = $request->contact_map;
    
    // --- Handle programs array ---
    if ($request->has('programs')) {
        $settings->programs = array_filter($request->programs); 
    }
    
    // --- Handle FAQs array ---
    if ($request->has('faq_questions') && $request->has('faq_answers')) {
        $faqs = [];
        for ($i = 0; $i < count($request->faq_questions); $i++) {
            if (!empty($request->faq_questions[$i])) {
                $faqs[] = [
                    'question' => $request->faq_questions[$i],
                    'answer' => $request->faq_answers[$i] ?? ''
                ];
            }
        }
        $settings->faqs = $faqs;
    }
    
    // --- Handle file uploads ---

    // 1. Hero Image
    if ($request->hasFile('hero_image')) {
        if ($settings->hero_image && Storage::disk('public')->exists($settings->hero_image)) {
            Storage::disk('public')->delete($settings->hero_image);
        }
        $settings->hero_image = $request->file('hero_image')->store('homepage', 'public');
    }
    
    // 2. Dean Image
    if ($request->hasFile('dean_image')) {
        if ($settings->dean_image && Storage::disk('public')->exists($settings->dean_image)) {
            Storage::disk('public')->delete($settings->dean_image);
        }
        $settings->dean_image = $request->file('dean_image')->store('homepage', 'public');
    }
    
    // 3. QR Code
    if ($request->hasFile('apply_qr')) {
        if ($settings->apply_qr && Storage::disk('public')->exists($settings->apply_qr)) {
            Storage::disk('public')->delete($settings->apply_qr);
        }
        $settings->apply_qr = $request->file('apply_qr')->store('homepage', 'public');
    }

    
    

    // --- HANDLE ONSITE EXAMPLES (TOC & FOLDER) ---

    // A. Handle Individual Deletion (from 'X' button)
    if ($request->delete_toc == "1") {
        if ($settings->apply_example_toc && Storage::disk('public')->exists($settings->apply_example_toc)) {
            Storage::disk('public')->delete($settings->apply_example_toc);
        }
        $settings->apply_example_toc = null;
    }

    if ($request->delete_folder == "1") {
        if ($settings->apply_example_folder && Storage::disk('public')->exists($settings->apply_example_folder)) {
            Storage::disk('public')->delete($settings->apply_example_folder);
        }
        $settings->apply_example_folder = null;
    }

    // B. Handle New Multiple Uploads
    if ($request->hasFile('onsite_examples')) {
        $files = $request->file('onsite_examples');

        // Image 1: Table of Contents (TOC)
        if (isset($files[0])) {
            if ($settings->apply_example_toc && Storage::disk('public')->exists($settings->apply_example_toc)) {
                Storage::disk('public')->delete($settings->apply_example_toc);
            }
            $settings->apply_example_toc = $files[0]->store('homepage', 'public');
        }

        // Image 2: Tabbed Folder
        if (isset($files[1])) {
            if ($settings->apply_example_folder && Storage::disk('public')->exists($settings->apply_example_folder)) {
                Storage::disk('public')->delete($settings->apply_example_folder);
            }
            $settings->apply_example_folder = $files[1]->store('homepage', 'public');
        }
    }
    
    $settings->save();
    
    return redirect()->route('homepage_management')->with('success', 'Homepage settings saved successfully!');
}
    
    public function getSettings()
    {
        $settings = HomepageSetting::first();
        if (!$settings) {
            $settings = new HomepageSetting();
        }
        return response()->json($settings);
    }

    public function uploadProgramPdf(Request $request)
{
    try {

        $request->validate([
            'pdf' => 'required|mimes:pdf|max:204800'
        ]);

        $path = $request->file('pdf')->store('program_pdfs', 'public');

        return response()->json([
            'success' => true,
            'path' => $path
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);

    }
}
}
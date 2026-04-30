<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $requirements = Requirement::latest()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('note', 'like', "%{$search}%");
            })
            ->get();

        if ($request->ajax()) {
            return view('partials.requirement_rows', compact('requirements'))->render();
        }

        return view('requirements_list', compact('requirements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'submission_type' => 'required|in:gdrive_link,file_upload'
        ]);

        $requirement = Requirement::create([
            'name' => $request->name,
            'note' => $request->note,
            'submission_type' => $request->submission_type
        ]);

        return response()->json(['success' => true, 'data' => $requirement]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'submission_type' => 'required|in:gdrive_link,file_upload'
        ]);

        $requirement = Requirement::findOrFail($id);
        
        $requirement->update([
            'name' => $request->name,
            'note' => $request->note,
            'submission_type' => $request->submission_type
        ]);

        return response()->json(['success' => true, 'data' => $requirement]);
    }

    public function destroy($id)
    {
        Requirement::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
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

    // Kapag AJAX ang request, table rows lang ang ibabalik natin
    if ($request->ajax()) {
        return view('partials.requirement_rows', compact('requirements'))->render();
    }

    return view('requirements_list', compact('requirements'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'note' => 'nullable'
        ]);

        Requirement::create($request->only('name', 'note'));

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $req = Requirement::findOrFail($id);

        $req->update([
            'name' => $request->name,
            'note' => $request->note
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Requirement::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    
}

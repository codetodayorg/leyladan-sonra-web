<?php

namespace App\Http\Controllers\Admin\Child;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\Diagnosis;

class DiagnosisController extends AdminController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $diagnosises = Diagnosis::orderBy('name');
        if ($request->filled('search')) {
            $diagnosises->search($request->search);
        }
        if ($request->filled('download')) {
            Diagnosis::download($diagnosises);
        }

        $diagnosises = $diagnosises->paginate($request->per_page ?: 25);
        return view('admin.diagnosis.index', compact('diagnosises'));
    }

    public function create()
    {
        return view('admin.diagnosis.create');
    }

    public function store(Request $request)
    {
        $diagnosis = Diagnosis::create([
          'name' => $request->name,
          'desc' => $request->desc
        ]);
        session_success(__('messages.diagnosis.create', ['name' =>  $diagnosis->name]));
        return redirect()->route('admin.diagnosis.index');
    }

    public function edit(Diagnosis $diagnosi)
    {
        $diagnosis = $diagnosi;
        return view('admin.diagnosis.edit', compact(['diagnosis']));
    }

    public function update(Request $request, Diagnosis $diagnosi)
    {
        $diagnosi->update($request->all());
        session_success(__('messages.diagnosis.update', ['name' =>  $diagnosi->name]));
        return redirect()->route('admin.diagnosis.index');
    }

    public function destroy(Diagnosis $diagnosi)
    {
        $diagnosi->delete();
        return api_success($diagnosi);
    }
}

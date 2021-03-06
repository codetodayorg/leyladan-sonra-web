<?php

namespace App\Http\Controllers\Admin\Child;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemporaryMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $media = $request->file('image');
        $id = str_random(8);
        $fileName = $id . "." . $media->getClientOriginalExtension();
        $path = asset("storage/tmp/{$fileName}");
        $media->move(storage_path('app/public/tmp'), $fileName);

        return api_success(['id' => $id, 'name' => $fileName, 'path' => $path, 'ratio' => $request->ratio]);
    }
}
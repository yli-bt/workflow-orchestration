<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activities;

class ActivityController extends Controller
{
    public function index()
    {
        return response()->json(Activities::all());
    }

    public function show($uuid)
    {
        return response()->json(Activities::findOrFail($uuid));
    }
}

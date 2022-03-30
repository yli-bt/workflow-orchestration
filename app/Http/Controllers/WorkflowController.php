<?php

namespace App\Http\Controllers;

use App\Models\Workflows;

class WorkflowController extends Controller
{
    public function index()
    {
        return response()->json(Workflows::all());
    }

    public function show($uuid)
    {
        return response()->json(Workflows::findOrFail($uuid));
    }
}

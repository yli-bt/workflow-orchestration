<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkflowRuns;

class WorkflowRunController extends Controller
{
    public function index()
    {
        return response()->json(WorkflowRuns::all());
    }

    public function show($uuid)
    {
        return response()->json(WorkflowRuns::findOrFail($uuid));
    }
}

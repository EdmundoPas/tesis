<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BullyingReport;
use Illuminate\Http\Request;

class BullyingController extends Controller
{
    public function index()
    {
        return response()->json(BullyingReport::with(['reporter', 'student'])->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['nullable', 'exists:students,id'],
            'tipo' => ['required', 'in:fisico,verbal,ciber,exclusion'],
            'descripcion' => ['required', 'string', 'min:10'],
            'anonimo' => ['boolean'],
        ]);

        $report = BullyingReport::create([
            'reporter_id' => $request->user()->id,
            'student_id' => $validated['student_id'] ?? null,
            'tipo' => $validated['tipo'],
            'descripcion' => $validated['descripcion'],
            'anonimo' => $validated['anonimo'] ?? false,
            'estado' => 'pendiente',
        ]);

        return response()->json($report, 201);
    }
}

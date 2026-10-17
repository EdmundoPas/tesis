<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::query();

        if ($request->filled('student_id')) {
            $query->whereHas('enrollment', fn ($q) => $q->where('student_id', $request->student_id));
        }

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            $query->whereHas('enrollment.course', fn ($courses) => $courses->where('teacher_id', $teacherId ?? 0));
        }

        return response()->json($query->with('enrollment.student')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'period' => ['required', 'string'],
            'score' => ['required', 'numeric', 'min:0', 'max:20'],
            'competency' => ['nullable', 'string'],
        ]);

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            abort_unless(
                $teacherId !== null && Course::query()
                    ->whereKey($validated['course_id'])
                    ->where('teacher_id', $teacherId)
                    ->exists(),
                403
            );
        }

        $enrollment = Enrollment::firstOrCreate(
            [
                'student_id' => $validated['student_id'],
                'course_id' => $validated['course_id'],
                'period' => $validated['period'],
            ],
            [
                'period' => $validated['period'],
            ]
        );

        $grade = Grade::create([
            'enrollment_id' => $enrollment->id,
            'period' => $validated['period'],
            'score' => $validated['score'],
            'competency' => $validated['competency'] ?? 'general',
        ]);

        return response()->json($grade->load('enrollment.student'), 201);
    }
}

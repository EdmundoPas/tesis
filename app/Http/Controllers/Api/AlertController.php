<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $alerts = Alert::query();

        if ($request->filled('student_id')) {
            $alerts->where('student_id', $request->student_id);
        }

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            $alerts->whereHas('student.enrollments.course', fn ($courses) => $courses->where('teacher_id', $teacherId ?? 0));
        }

        return response()->json($alerts->with('student')->latest()->get());
    }

    public function evaluate(Request $request, Student $student, AlertService $alertService)
    {
        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            abort_unless(
                $teacherId !== null && $student->enrollments()->whereHas('course', fn ($courses) => $courses->where('teacher_id', $teacherId))->exists(),
                404
            );
        }

        $payload = $alertService->evaluateStudent($student);

        return response()->json($payload);
    }
}

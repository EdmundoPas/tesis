<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('attendances');

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            $query->whereHas('enrollments.course', fn ($courses) => $courses->where('teacher_id', $teacherId ?? 0));
            $query->with(['grades' => fn ($grades) => $grades->whereHas(
                'enrollment.course',
                fn ($courses) => $courses->where('teacher_id', $teacherId ?? 0)
            )]);
        } else {
            $query->with('grades');
        }

        return response()->json($query->get());
    }

    public function show(Request $request, Student $student)
    {
        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id');
            abort_unless(
                $teacherId !== null && $student->enrollments()->whereHas('course', fn ($courses) => $courses->where('teacher_id', $teacherId))->exists(),
                404
            );

            $student->load([
                'attendances',
                'grades' => fn ($grades) => $grades->whereHas(
                    'enrollment.course',
                    fn ($courses) => $courses->where('teacher_id', $teacherId)
                ),
            ]);
        } else {
            $student->load(['grades', 'attendances']);
        }

        return response()->json($student->load('alerts'));
    }
}

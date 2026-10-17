<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $studentsQuery = Student::with(['grades', 'attendances']);
        $alertsQuery = Alert::with('student')->latest()->take(10);

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id') ?? 0;
            $studentsQuery->whereHas('enrollments.course', fn ($courses) => $courses->where('teacher_id', $teacherId));
            $studentsQuery->with(['grades' => fn ($grades) => $grades->whereHas(
                'enrollment.course',
                fn ($courses) => $courses->where('teacher_id', $teacherId)
            )]);
            $alertsQuery->whereHas('student.enrollments.course', fn ($courses) => $courses->where('teacher_id', $teacherId));
        }

        $students = $studentsQuery->get();
        $alerts = $alertsQuery->get();
        $activeAlertsQuery = Alert::query()->where('estado', 'activa');

        if ($request->user()->role === 'teacher') {
            $teacherId = Teacher::query()->where('user_id', $request->user()->id)->value('id') ?? 0;
            $activeAlertsQuery->whereHas('student.enrollments.course', fn ($courses) => $courses->where('teacher_id', $teacherId));
        }

        return response()->json([
            'summary' => [
                'students' => $students->count(),
                'average' => round($students->avg(fn ($student) => $student->grades->avg('score') ?? 0), 2),
                'active_alerts' => $activeAlertsQuery->count(),
                'attendance' => round($students->avg(fn ($student) => $student->attendances()->where('status', 'P')->count() / max($student->attendances()->count(), 1) * 100) ?? 0, 2),
            ],
            'students' => $students,
            'alerts' => $alerts,
        ]);
    }
}

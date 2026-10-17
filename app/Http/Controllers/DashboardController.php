<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'alerts' => [
                ['student' => 'Mateo Quispe Rojas', 'detail' => 'Promedio descendió en Matemática', 'grade' => '2.° B', 'level' => 'Urgente', 'tone' => 'red', 'initials' => 'MQ'],
                ['student' => 'Valeria Huamán Díaz', 'detail' => '3 inasistencias esta semana', 'grade' => '4.° A', 'level' => 'Atención', 'tone' => 'amber', 'initials' => 'VH'],
                ['student' => 'Diego Flores León', 'detail' => 'Rendimiento bajo en 2 cursos', 'grade' => '1.° C', 'level' => 'Atención', 'tone' => 'amber', 'initials' => 'DF'],
            ],
            'students' => [
                ['name' => 'Camila Torres Salas', 'grade' => '3.° A', 'average' => '17.8', 'attendance' => '98%', 'status' => 'Buen rendimiento', 'tone' => 'green', 'initials' => 'CT'],
                ['name' => 'Mateo Quispe Rojas', 'grade' => '2.° B', 'average' => '10.5', 'attendance' => '82%', 'status' => 'Requiere apoyo', 'tone' => 'red', 'initials' => 'MQ'],
                ['name' => 'Valeria Huamán Díaz', 'grade' => '4.° A', 'average' => '13.2', 'attendance' => '89%', 'status' => 'En observación', 'tone' => 'amber', 'initials' => 'VH'],
                ['name' => 'Diego Flores León', 'grade' => '1.° C', 'average' => '12.8', 'attendance' => '91%', 'status' => 'En observación', 'tone' => 'amber', 'initials' => 'DF'],
            ],
        ]);
    }

    public function panel(Request $request): View
    {
        $user = $request->user();
        abort_unless(in_array($user->role, ['admin', 'teacher'], true), 403);

        $teacherId = $user->role === 'teacher'
            ? Teacher::query()->where('user_id', $user->id)->value('id')
            : null;

        $studentsQuery = Student::query()
            ->with(['user:id,name', 'attendances', 'alerts' => fn ($query) => $query->where('estado', 'activa')])
            ->with(['enrollments' => function ($query) use ($teacherId) {
                if ($teacherId !== null) {
                    $query->whereHas('course', fn ($courses) => $courses->where('teacher_id', $teacherId));
                }

                $query->with(['course', 'grades']);
            }]);

        if ($user->role === 'teacher') {
            if ($teacherId === null) {
                $studentsQuery->whereRaw('1 = 0');
            } else {
                $studentsQuery->whereHas('enrollments.course', fn ($query) => $query->where('teacher_id', $teacherId));
            }
        }

        $students = $studentsQuery->get()->map(function (Student $student) {
            $grades = $student->enrollments->flatMap->grades;
            $attendanceCount = $student->attendances->count();
            $attendance = $attendanceCount === 0
                ? null
                : round($student->attendances->where('status', 'P')->count() / $attendanceCount * 100);

            return [
                'name' => $student->user?->name ?? 'Estudiante '.$student->code,
                'grade' => $student->grade.'° '.$student->section,
                'average' => $grades->isEmpty() ? '—' : number_format((float) $grades->avg('score'), 1),
                'attendance' => $attendance === null ? '—' : $attendance.'%',
                'alerts' => $student->alerts->count(),
                'courses' => $student->enrollments->pluck('course.name')->filter()->unique()->values(),
            ];
        });

        $alertsQuery = Alert::query()->with('student.user')->where('estado', 'activa')->latest();
        if ($user->role === 'teacher') {
            if ($teacherId === null) {
                $alertsQuery->whereRaw('1 = 0');
            } else {
                $alertsQuery->whereHas('student.enrollments.course', fn ($query) => $query->where('teacher_id', $teacherId));
            }
        }

        $activeAlerts = (clone $alertsQuery)->count();
        $messages = $user->role === 'admin'
            ? ChatMessage::query()->with(['user:id,name,role', 'room:id,name,tipo,grado_seccion'])->latest()->paginate(10, ['*'], 'messages_page')->withQueryString()
            : collect();

        return view('staff-dashboard', [
            'user' => $user,
            'isAdmin' => $user->role === 'admin',
            'students' => $students,
            'alerts' => $alertsQuery->paginate(10, ['*'], 'alerts_page')->withQueryString(),
            'messages' => $messages,
            'activeAlerts' => $activeAlerts,
            'average' => $students->where('average', '!=', '—')->avg(fn ($student) => (float) $student['average']),
        ]);
    }
}

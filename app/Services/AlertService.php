<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Student;
use Illuminate\Support\Collection;

class AlertService
{
    public function evaluateStudent(Student $student): array
    {
        $grades = $student->grades()->latest('period')->get();
        $average = $grades->avg('score');
        $failedCourses = $grades->filter(fn ($grade) => (float) $grade->score < 11.0)->count();
        $absences = $student->attendances()->where('status', 'F')->count();
        $trend = $this->calculateTrend($grades);

        $reasons = [];
        $riskLevel = 'verde';

        if ($average !== null && $average < 11) {
            $riskLevel = 'rojo';
            $reasons[] = 'Promedio bajo: '.number_format((float) $average, 1, '.', '');
        } elseif ($average !== null && $average < 13) {
            $riskLevel = $riskLevel === 'rojo' ? 'rojo' : 'amarillo';
            $reasons[] = 'Promedio bajo: '.number_format((float) $average, 1, '.', '');
        }

        if ($failedCourses >= 2) {
            $riskLevel = 'rojo';
            $reasons[] = $failedCourses.' cursos desaprobados';
        }

        if ($trend === 'descendente') {
            $riskLevel = $riskLevel === 'rojo' ? 'rojo' : 'amarillo';
            $reasons[] = 'Descenso progresivo de notas';
        }

        if ($absences >= 3) {
            $riskLevel = $riskLevel === 'rojo' ? 'rojo' : 'amarillo';
            $reasons[] = 'Inasistencias altas: '.$absences;
        }

        $payload = [
            'student_id' => $student->id,
            'tipo' => 'academico',
            'nivel' => $riskLevel,
            'descripcion' => implode(' | ', $reasons),
            'estado' => $riskLevel === 'verde' ? 'resuelta' : 'activa',
        ];

        if ($riskLevel === 'verde') {
            return $payload;
        }

        Alert::updateOrCreate(
            ['student_id' => $student->id, 'tipo' => 'academico', 'estado' => 'activa'],
            $payload
        );

        return $payload;
    }

    private function calculateTrend(Collection $grades): string
    {
        if ($grades->count() < 2) {
            return 'estable';
        }

        $values = $grades->take(3)->pluck('score')->map(fn ($score) => (float) $score)->values();

        if ($values->count() < 2) {
            return 'estable';
        }

        return $values->last() < $values->first() ? 'descendente' : 'estable';
    }
}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7fb">
    <title>Panel {{ $isAdmin ? 'administrativo' : 'docente' }} | San Luis Gonzaga</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="staff-shell">
    <header class="staff-topbar">
        <a class="brand" href="{{ route('staff.dashboard') }}">
            <span class="brand-mark">SL</span>
            <span class="brand-copy"><strong>San Luis</strong><small>SEGUIMIENTO ACADÉMICO</small></span>
        </a>
        <div class="staff-user">
            <span class="avatar avatar-indigo">{{ collect(explode(' ', $user->name))->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}</span>
            <span><strong>{{ $user->name }}</strong><small>{{ $isAdmin ? 'Administración' : 'Docente' }}</small></span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-button" type="submit">Cerrar sesión</button></form>
        </div>
    </header>

    <main class="staff-content">
        <div class="staff-heading">
            <div>
                <div class="eyebrow">AÑO ESCOLAR {{ now()->year }} · PANEL {{ $isAdmin ? 'ADMINISTRATIVO' : 'DOCENTE' }}</div>
                <h1>Hola, {{ $user->name }}</h1>
                <p class="page-subtitle">{{ $isAdmin ? 'Vista institucional de estudiantes, alertas y comunicación con las familias.' : 'Seguimiento de los estudiantes de tus cursos asignados.' }}</p>
            </div>
            <a class="text-link" href="{{ route('dashboard') }}">Ver resumen público →</a>
        </div>

        <section class="stats-grid" aria-label="Indicadores del panel">
            <article class="stat-card"><p class="stat-label">Estudiantes a cargo</p><div class="stat-value">{{ $students->count() }}</div><div class="stat-foot">{{ $isAdmin ? 'Total institucional' : 'En tus cursos' }}</div></article>
            <article class="stat-card"><p class="stat-label">Alertas activas</p><div class="stat-value">{{ $activeAlerts }}</div><div class="stat-foot">Requieren seguimiento</div></article>
            <article class="stat-card"><p class="stat-label">Promedio académico</p><div class="stat-value">{{ $average === null ? '—' : number_format((float) $average, 1) }} <span>/ 20</span></div><div class="stat-foot">Según notas registradas</div></article>
            @if ($isAdmin)
                <article class="stat-card"><p class="stat-label">Mensajes de familias</p><div class="stat-value">{{ $messages->total() }}</div><div class="stat-foot">Mensajes registrados en el sistema</div></article>
            @endif
        </section>

        <section class="staff-grid">
            <article class="panel staff-panel">
                <div class="panel-heading"><div><h2>Estudiantes</h2><p>{{ $isAdmin ? 'Vista de toda la institución' : 'Estudiantes vinculados a tus cursos' }}</p></div></div>
                @if ($students->isEmpty())
                    <p class="empty-state">Todavía no hay estudiantes asignados a esta cuenta.</p>
                @else
                    <div class="table-scroll">
                        <table>
                            <thead><tr><th>ESTUDIANTE</th><th>GRADO</th><th>PROMEDIO</th><th>ASISTENCIA</th><th>ALERTAS</th></tr></thead>
                            <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td><strong>{{ $student['name'] }}</strong>@if ($student['courses']->isNotEmpty())<small class="table-subtext">{{ $student['courses']->join(', ') }}</small>@endif</td>
                                    <td>{{ $student['grade'] }}</td>
                                    <td>{{ $student['average'] }}</td>
                                    <td>{{ $student['attendance'] }}</td>
                                    <td>{{ $student['alerts'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </article>

            <article class="panel staff-panel">
                <div class="panel-heading"><div><h2>Actividad reciente</h2><p>Alertas académicas que requieren atención</p></div></div>
                @forelse ($alerts as $alert)
                    <div class="staff-feed-row">
                        <span class="feed-dot"></span>
                        <div><strong>{{ $alert->student?->user?->name ?? 'Estudiante '.$alert->student_id }}</strong><p>{{ $alert->descripcion }}</p><small>{{ $alert->created_at?->diffForHumans() }}</small></div>
                        <span class="level-pill {{ $alert->nivel === 'rojo' ? 'red' : 'amber' }}">{{ ucfirst($alert->nivel) }}</span>
                    </div>
                @empty
                    <p class="empty-state">No hay alertas activas.</p>
                @endforelse
                @if ($alerts->hasPages())
                    <div class="pagination"><span>Página {{ $alerts->currentPage() }} de {{ $alerts->lastPage() }}</span><span>@if ($alerts->previousPageUrl())<a href="{{ $alerts->previousPageUrl() }}">← Anterior</a>@endif @if ($alerts->nextPageUrl())<a href="{{ $alerts->nextPageUrl() }}">Siguiente →</a>@endif</span></div>
                @endif
            </article>
        </section>

        @if ($isAdmin)
            <section class="panel staff-panel chat-panel">
                <div class="panel-heading"><div><h2>Mensajes de las familias</h2><p>Conversaciones recientes registradas en el sistema</p></div></div>
                @forelse ($messages as $message)
                    <div class="staff-feed-row message-row">
                        <span class="avatar avatar-green">{{ collect(explode(' ', $message->user?->name ?? '?'))->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('') }}</span>
                        <div><strong>{{ $message->user?->name ?? 'Usuario' }} <span class="muted">· {{ $message->room?->name ?? 'Conversación' }}</span></strong><p>{{ $message->message }}</p><small>{{ $message->created_at?->format('d/m/Y H:i') }}</small></div>
                    </div>
                @empty
                    <p class="empty-state">Todavía no hay mensajes registrados.</p>
                @endforelse
                @if ($messages->hasPages())
                    <div class="pagination"><span>Página {{ $messages->currentPage() }} de {{ $messages->lastPage() }}</span><span>@if ($messages->previousPageUrl())<a href="{{ $messages->previousPageUrl() }}">← Anterior</a>@endif @if ($messages->nextPageUrl())<a href="{{ $messages->nextPageUrl() }}">Siguiente →</a>@endif</span></div>
                @endif
            </section>
        @endif

        <footer class="page-footer">I.E. Seminario San Luis Gonzaga <span>·</span> Acceso institucional</footer>
    </main>
</div>
</body>
</html>

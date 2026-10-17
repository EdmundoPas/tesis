<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f7fb">
    <title>Panel académico | San Luis Gonzaga</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Inicio">
            <span class="brand-mark">SL</span>
            <span class="brand-copy"><strong>San Luis</strong><small>SEGUIMIENTO ACADÉMICO</small></span>
        </a>
        <div class="school-label">GESTIÓN ESCOLAR</div>
        <nav class="side-nav" aria-label="Navegación principal">
            <a class="nav-link active" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span>Resumen</a>
            <a class="nav-link" href="#estudiantes"><span class="nav-icon">♙</span>Estudiantes</a>
            <a class="nav-link" href="#rendimiento"><span class="nav-icon">▤</span>Rendimiento</a>
            <a class="nav-link" href="#alertas"><span class="nav-icon">♧</span>Alertas <span class="nav-count">8</span></a>
            <a class="nav-link" href="#convivencia"><span class="nav-icon">♡</span>Convivencia escolar</a>
            <a class="nav-link" href="#familias"><span class="nav-icon">☏</span>Escuela de padres</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="help-card">
                <span class="help-icon">?</span>
                <strong>¿Necesitas ayuda?</strong>
                <p>Consulta la guía de uso de la plataforma.</p>
                <a href="#ayuda">Ver guía <span aria-hidden="true">↗</span></a>
            </div>
            <button class="profile-button" type="button">
                <span class="avatar avatar-indigo">MR</span>
                <span class="profile-copy"><strong>Vista pública</strong><small>Datos de demostración</small></span>
                <span class="profile-more">···</span>
            </button>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="breadcrumbs">Panel <span>/</span> <strong>Resumen</strong></div>
            <div class="topbar-actions">
                <span class="term-chip"><span class="term-dot"></span> Año escolar 2026</span>
                <button class="icon-button notification-button" type="button" aria-label="Notificaciones">
                    ♧<span class="notification-dot"></span>
                </button>
                <a class="text-link" href="{{ route('login') }}">Ingresar <span aria-hidden="true">→</span></a>
            </div>
        </header>

        <div class="page-content">
            <section class="welcome-row">
                <div>
                    <div class="eyebrow">AÑO ESCOLAR 2026 · BIMESTRE III</div>
                    <h1>Buenos días, María <span aria-hidden="true">✦</span></h1>
                    <p class="page-subtitle">Aquí tienes el resumen del progreso de tu comunidad educativa.</p>
                </div>
                <div class="welcome-actions"><span class="demo-label">Datos de demostración</span><a class="button button-primary" href="{{ route('login') }}">Acceso de docentes y administración <span aria-hidden="true">→</span></a></div>
            </section>

            <section class="stats-grid" aria-label="Indicadores generales">
                <article class="stat-card">
                    <div class="stat-top"><span class="stat-icon icon-blue">♙</span><span class="stat-change positive">↗ 4.8%</span></div>
                    <p class="stat-label">Estudiantes matriculados</p>
                    <div class="stat-value">1,248 <span>estudiantes</span></div>
                    <div class="stat-foot">vs. periodo anterior</div>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span class="stat-icon icon-green">▤</span><span class="stat-change positive">↗ 2.3%</span></div>
                    <p class="stat-label">Promedio institucional</p>
                    <div class="stat-value">15.6 <span>/ 20</span></div>
                    <div class="stat-foot">vs. periodo anterior</div>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span class="stat-icon icon-amber">♧</span><span class="stat-change neutral">requieren seguimiento</span></div>
                    <p class="stat-label">Alertas activas</p>
                    <div class="stat-value">08 <span>estudiantes</span></div>
                    <div class="stat-foot"><a href="#alertas">Revisar alertas <span aria-hidden="true">→</span></a></div>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span class="stat-icon icon-purple">◷</span><span class="stat-change positive">↗ 1.2%</span></div>
                    <p class="stat-label">Asistencia promedio</p>
                    <div class="stat-value">94.2<span>%</span></div>
                    <div class="stat-foot">Esta semana</div>
                </article>
            </section>

            <section class="content-grid">
                <article class="panel performance-panel" id="rendimiento">
                    <div class="panel-heading">
                        <div><h2>Rendimiento académico</h2><p>Promedio general por periodo</p></div>
                        <button class="select-button" type="button">Todos los grados <span aria-hidden="true">⌄</span></button>
                    </div>
                    <div class="chart-summary"><strong>15.6</strong><span>/ 20</span><span class="summary-change">↗ 2.3%</span><small>respecto al periodo anterior</small></div>
                    <div class="chart-wrap" role="img" aria-label="Gráfico de rendimiento: tendencia al alza desde el primer bimestre">
                        <div class="y-labels"><span>20</span><span>15</span><span>10</span><span>5</span></div>
                        <div class="chart-area">
                            <div class="chart-grid"><i></i><i></i><i></i><i></i></div>
                            <svg class="chart-svg" viewBox="0 0 620 180" preserveAspectRatio="none" aria-hidden="true">
                                <defs><linearGradient id="fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#4778ed" stop-opacity=".18"/><stop offset="100%" stop-color="#4778ed" stop-opacity="0"/></linearGradient></defs>
                                <path d="M0,132 C50,126 65,107 124,113 S205,119 248,92 S334,102 372,69 S450,88 496,54 S565,47 620,24 L620,180 L0,180 Z" fill="url(#fill)"/>
                                <path d="M0,132 C50,126 65,107 124,113 S205,119 248,92 S334,102 372,69 S450,88 496,54 S565,47 620,24" fill="none" stroke="#4778ed" stroke-width="3" vector-effect="non-scaling-stroke"/>
                                <circle cx="620" cy="24" r="5" fill="#fff" stroke="#4778ed" stroke-width="3" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <div class="x-labels"><span>Bimestre 1</span><span>Bimestre 2</span><span>Bimestre 3</span><span>Bimestre 4</span></div>
                        </div>
                    </div>
                    <div class="chart-legend"><span><i></i>Promedio de notas</span><span class="legend-note">Escala vigesimal (0–20)</span></div>
                </article>

                <article class="panel alerts-panel" id="alertas">
                    <div class="panel-heading">
                        <div><h2>Alertas tempranas</h2><p>Estudiantes que necesitan atención</p></div>
                        <a class="text-link" href="#alertas">Ver todas <span aria-hidden="true">→</span></a>
                    </div>
                    <div class="alert-list">
                        @foreach ($alerts as $alert)
                            <div class="alert-row">
                                <span class="avatar avatar-{{ $alert['tone'] }}">{{ $alert['initials'] }}</span>
                                <div class="alert-copy"><strong>{{ $alert['student'] }}</strong><span>{{ $alert['detail'] }}</span></div>
                                <span class="level-pill {{ $alert['tone'] }}">{{ $alert['level'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <a class="all-alerts-link" href="#alertas">Ver las 8 alertas activas <span aria-hidden="true">→</span></a>
                </article>
            </section>

            <section class="panel students-panel" id="estudiantes">
                <div class="panel-heading students-heading">
                    <div><h2>Seguimiento de estudiantes</h2><p>Vista rápida del progreso y asistencia</p></div>
                    <div class="table-actions">
                        <label class="search-box"><span aria-hidden="true">⌕</span><input type="search" placeholder="Buscar estudiante" aria-label="Buscar estudiante"></label>
                        <button class="select-button" type="button">Todos los grados <span aria-hidden="true">⌄</span></button>
                    </div>
                </div>
                <div class="table-scroll">
                    <table>
                        <thead><tr><th>ESTUDIANTE</th><th>GRADO Y SECCIÓN</th><th>PROMEDIO</th><th>ASISTENCIA</th><th>ESTADO</th><th></th></tr></thead>
                        <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td><span class="student-cell"><span class="avatar avatar-{{ $student['tone'] }}">{{ $student['initials'] }}</span><strong>{{ $student['name'] }}</strong></span></td>
                                <td>{{ $student['grade'] }}</td>
                                <td><strong class="grade-number {{ $student['tone'] }}">{{ $student['average'] }}</strong><span class="muted"> / 20</span></td>
                                <td><span class="attendance-value">{{ $student['attendance'] }}</span></td>
                                <td><span class="status-pill {{ $student['tone'] }}"><i></i>{{ $student['status'] }}</span></td>
                                <td><button class="row-more" type="button" aria-label="Más opciones para {{ $student['name'] }}">···</button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-footer"><span>Mostrando <strong>4</strong> de <strong>1,248</strong> estudiantes</span><div><button type="button" disabled>←</button><button type="button">→</button></div></div>
            </section>

            <section class="module-grid">
                <article class="module-card" id="convivencia">
                    <span class="module-icon module-icon-purple">♡</span>
                    <div><h2>Convivencia escolar</h2><p>Canal de atención confidencial para comunicar situaciones que afecten el bienestar estudiantil.</p></div>
                    <span class="module-tag">Módulo en diseño</span>
                </article>
                <article class="module-card" id="familias">
                    <span class="module-icon module-icon-green">☏</span>
                    <div><h2>Escuela de padres</h2><p>Comunicados y espacios de coordinación entre tutores y familias por grado y sección.</p></div>
                    <span class="module-tag">Módulo en diseño</span>
                </article>
            </section>

            <footer class="page-footer">I.E. Seminario San Luis Gonzaga <span>·</span> Prototipo de seguimiento académico</footer>
        </div>
    </main>
</div>
<nav class="mobile-nav" aria-label="Navegación móvil">
    <a class="mobile-nav-active" href="{{ route('dashboard') }}"><span>⌂</span>Inicio</a>
    <a href="#estudiantes"><span>♙</span>Estudiantes</a>
    <a href="#alertas"><span>♧</span>Alertas</a>
    <a href="#familias"><span>☏</span>Familias</a>
</nav>
</body>
</html>

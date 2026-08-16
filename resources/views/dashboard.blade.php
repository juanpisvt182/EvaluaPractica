@extends('layouts.figma')

@section('page', 'dashboard')
@section('title', 'Dashboard')
@section('subtitle', 'Resumen general de EvaluaPractica')


{{-- Botón superior según el rol --}}
@section('top_action')

    @if($usuario->esAdministrador())

        <a
            class="btn btn--accent"
            href="{{ route('admin.usuarios.index') }}"
        >
            Gestionar usuarios
        </a>

    @elseif($usuario->esInstructor())

        <a
            class="btn btn--accent"
            href="{{ route('evaluacion.index') }}"
        >
            Ver evaluaciones
        </a>

    @else

        <a
            class="btn btn--accent"
            href="{{ route('intentos.index') }}"
        >
            Ver evaluaciones
        </a>

    @endif

@endsection


@section('content')

    {{-- Bienvenida --}}
    <section
        class="card section-card"
        style="margin-bottom:18px;"
    >

        <h3 style="margin:0 0 8px 0;">
            Hola, {{ $usuario->name }} 👋
        </h3>

        <p style="margin:0; color:var(--muted);">

            @if($usuario->esAdministrador())

                Aquí puedes consultar el estado general de la plataforma.

            @elseif($usuario->esInstructor())

                Aquí puedes consultar un resumen de tus grupos y evaluaciones.

            @else

                Aquí puedes consultar tus grupos, evaluaciones y resultados.

            @endif

        </p>

    </section>


    {{-- ========================================================= --}}
    {{-- ADMINISTRADOR --}}
    {{-- ========================================================= --}}

    @if($usuario->esAdministrador())

        <section
            style="
                display:grid;
                grid-template-columns:
                    repeat(auto-fit, minmax(190px, 1fr));
                gap:14px;
            "
        >

            {{-- Usuarios --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Usuarios
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalUsuarios'] }}
                </h2>

            </div>


            {{-- Estudiantes --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Estudiantes
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalEstudiantes'] }}
                </h2>

            </div>


            {{-- Docentes --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Docentes
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalDocentes'] }}
                </h2>

            </div>


            {{-- Grupos --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Grupos
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalGrupos'] }}
                </h2>

            </div>


            {{-- Evaluaciones --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalEvaluaciones'] }}
                </h2>

            </div>


            {{-- Intentos --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones realizadas
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalIntentos'] }}
                </h2>

            </div>

        </section>


        {{-- Accesos rápidos --}}
        <section
            class="card section-card"
            style="margin-top:18px;"
        >

            <h3 style="margin:0 0 14px;">
                Accesos rápidos
            </h3>

            <div
                style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:10px;
                "
            >

                <a
                    href="{{ route('admin.usuarios.index') }}"
                    class="btn btn--accent"
                >
                    Usuarios
                </a>

                <a
                    href="{{ route('grupos.index') }}"
                    class="btn btn--ghost"
                >
                    Grupos
                </a>

                <a
                    href="{{ route('evaluacion.index') }}"
                    class="btn btn--ghost"
                >
                    Evaluaciones
                </a>

            </div>

        </section>


    {{-- ========================================================= --}}
    {{-- DOCENTE --}}
    {{-- ========================================================= --}}

    @elseif($usuario->esInstructor())

        <section
            style="
                display:grid;
                grid-template-columns:
                    repeat(auto-fit, minmax(190px, 1fr));
                gap:14px;
            "
        >

            {{-- Mis grupos --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Mis grupos
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalGrupos'] }}
                </h2>

            </div>


            {{-- Estudiantes --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Estudiantes
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalEstudiantes'] }}
                </h2>

            </div>


            {{-- Evaluaciones --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Mis evaluaciones
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalEvaluaciones'] }}
                </h2>

            </div>


            {{-- Evaluaciones activas --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones activas
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['evaluacionesActivas'] }}
                </h2>

            </div>


            {{-- Intentos --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones realizadas
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalIntentos'] }}
                </h2>

            </div>

        </section>


        {{-- Accesos rápidos --}}
        <section
            class="card section-card"
            style="margin-top:18px;"
        >

            <h3 style="margin:0 0 14px;">
                Accesos rápidos
            </h3>

            <div
                style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:10px;
                "
            >

                <a
                    href="{{ route('grupos.index') }}"
                    class="btn btn--accent"
                >
                    Mis grupos
                </a>

                <a
                    href="{{ route('evaluacion.index') }}"
                    class="btn btn--ghost"
                >
                    Mis evaluaciones
                </a>

            </div>

        </section>


    {{-- ========================================================= --}}
    {{-- ESTUDIANTE --}}
    {{-- ========================================================= --}}

    @else

        <section
            style="
                display:grid;
                grid-template-columns:
                    repeat(auto-fit, minmax(190px, 1fr));
                gap:14px;
            "
        >

            {{-- Grupos --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Mis grupos
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalGrupos'] }}
                </h2>

            </div>


            {{-- Evaluaciones disponibles --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones disponibles
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['evaluacionesDisponibles'] }}
                </h2>

            </div>


            {{-- Completadas --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Evaluaciones completadas
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['evaluacionesCompletadas'] }}
                </h2>

            </div>


            {{-- Promedio --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Promedio
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['promedio'] }}%
                </h2>

            </div>


            {{-- Bitácoras --}}
            <div class="card section-card">

                <p
                    style="
                        margin:0;
                        color:var(--muted);
                        font-weight:700;
                    "
                >
                    Mis bitácoras
                </p>

                <h2 style="margin:8px 0 0;">
                    {{ $datos['totalBitacoras'] }}
                </h2>

            </div>

        </section>


        {{-- Accesos rápidos --}}
        <section
            class="card section-card"
            style="margin-top:18px;"
        >

            <h3 style="margin:0 0 14px;">
                Accesos rápidos
            </h3>

            <div
                style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:10px;
                "
            >

                <a
                    href="{{ route('intentos.index') }}"
                    class="btn btn--accent"
                >
                    Evaluaciones
                </a>

                <a
                    href="{{ route('intentos.historial') }}"
                    class="btn btn--ghost"
                >
                    Mis resultados
                </a>

                <a
                    href="{{ route('grupos.index') }}"
                    class="btn btn--ghost"
                >
                    Mis grupos
                </a>

                <a
                    href="{{ route('bitacoras.index') }}"
                    class="btn btn--ghost"
                >
                    Bitácoras
                </a>

            </div>

        </section>

    @endif

@endsection
@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Módulo de Evaluaciones')
@section('subtitle', 'Crea y administra las evaluaciones del proyecto')

@section('top_action')
    <a class="btn btn--accent" href="{{ route('evaluacion.create') }}">
        <svg aria-hidden="true" style="width:18px;height:18px">
            <use href="#icon-plus"></use>
        </svg>
        Nueva Evaluación
    </a>
@endsection

@section('content')

    <section class="card table-card" aria-label="Tabla de evaluaciones">

        <table class="table">

            <thead>
                <tr>
                    <th>Título</th>
                    <th>Instructor</th>
                    <th>Tiempo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($evaluaciones as $evaluacion)

                    @php
                        $pillClass = $evaluacion->estado === 'Activa'
                            ? 'pill--ok'
                            : 'pill--draft';
                    @endphp

                    <tr>

                        <td>
                            {{ $evaluacion->titulo }}
                        </td>

                        <td>
                            {{ $evaluacion->instructor->name ?? 'Sin instructor' }}
                        </td>

                        <td>
                            {{ $evaluacion->tiempo_limite }} min
                        </td>

                        <td>
                            <span class="pill {{ $pillClass }}">
                                {{ $evaluacion->estado }}
                            </span>
                        </td>

                        <td>

                            <div class="actions">

                                {{-- Ver --}}
                                <a
                                    class="icon-btn"
                                    title="Ver"
                                    href="{{ route('evaluacion.show', $evaluacion) }}"
                                >
                                    <svg aria-hidden="true">
                                        <use href="#icon-eye"></use>
                                    </svg>
                                </a>

                                {{-- Editar --}}
                                <a
                                    class="icon-btn"
                                    title="Editar"
                                    href="{{ route('evaluacion.edit', $evaluacion) }}"
                                >
                                    <svg
                                        aria-hidden="true"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                    </svg>
                                </a>

                                {{-- Eliminar --}}
                                <form
                                    method="POST"
                                    action="{{ route('evaluacion.destroy', $evaluacion) }}"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar esta evaluación?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="icon-btn"
                                        type="submit"
                                        title="Eliminar"
                                    >
                                        <svg aria-hidden="true">
                                            <use href="#icon-trash"></use>
                                        </svg>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="padding:18px 22px;">
                            No hay evaluaciones creadas todavía.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

@endsection
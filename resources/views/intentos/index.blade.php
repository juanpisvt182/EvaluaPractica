@extends('layouts.figma')

@section('page', 'evaluaciones-aprendiz')
@section('title', 'Evaluaciones Disponibles')
@section('subtitle', 'Consulta y presenta las evaluaciones disponibles')

@section('content')

    {{-- Mensaje de error --}}
    @if(session('error'))
        <div
            class="card"
            style="
                padding:14px 16px;
                margin-bottom:18px;
                border-left:4px solid #DC2626;
            "
        >
            <strong style="color:#B91C1C;">
                {{ session('error') }}
            </strong>
        </div>
    @endif


    <section class="card table-card">

        <table class="table">

            <thead>
                <tr>
                    <th>Evaluación</th>
                    <th>Instructor</th>
                    <th>Preguntas</th>
                    <th>Tiempo</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>

                @forelse($evaluaciones as $evaluacion)

                    <tr>

                        <td>
                            <strong>
                                {{ $evaluacion->titulo }}
                            </strong>

                            @if($evaluacion->descripcion)
                                <div
                                    style="
                                        margin-top:5px;
                                        color:#667085;
                                        font-size:13px;
                                    "
                                >
                                    {{ $evaluacion->descripcion }}
                                </div>
                            @endif
                        </td>


                        <td>
                            {{ $evaluacion->instructor->name ?? 'Instructor' }}
                        </td>


                        <td>
                            {{ $evaluacion->preguntas_count }}
                        </td>


                        <td>
                            {{ $evaluacion->tiempo_limite }} min
                        </td>


                        <td>
                            <span class="pill pill--ok">
                                Activa
                            </span>
                        </td>


                        <td>

                           @php
    $intento = $evaluacion->intentos->first();
@endphp


@if($evaluacion->preguntas_count === 0)

    <button
        type="button"
        class="btn btn--ghost"
        disabled
        style="opacity:.5; cursor:not-allowed;"
    >
        Sin preguntas
    </button>


@elseif($intento && $intento->estado === 'Finalizado')

    <a
        href="{{ route('intentos.resultado', $intento) }}"
        class="btn btn--ghost"
    >
        Ver resultado
    </a>


@elseif($intento && $intento->estado === 'En progreso')

    <a
        href="{{ route('intentos.presentar', $intento) }}"
        class="btn btn--accent"
    >
        Continuar
    </a>


@else

    <form
        method="POST"
        action="{{ route('intentos.iniciar', $evaluacion) }}"
    >
        @csrf

        <button
            type="submit"
            class="btn btn--accent"
        >
            Presentar
        </button>

    </form>

@endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="6"
                            style="padding:24px;"
                        >
                            No hay evaluaciones disponibles en este momento.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

@endsection
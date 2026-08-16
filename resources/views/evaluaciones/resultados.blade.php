@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Resultados de la Evaluación')
@section('subtitle', $evaluacion->titulo)

@section('content')

<div style="max-width:1000px;">

    <section
        class="card"
        style="
            padding:18px 22px;
            margin-bottom:20px;
        "
    >
        <strong>
            {{ $evaluacion->titulo }}
        </strong>

        <div style="color:#667085; margin-top:5px;">
            Resultados de los aprendices que finalizaron esta evaluación.
        </div>
    </section>


    <section class="card table-card">

        <table class="table">

            <thead>
                <tr>
                    <th>Aprendiz</th>
                    <th>Correo</th>
                    <th>Puntaje</th>
                    <th>Correctas</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>

                @forelse($intentos as $intento)

                    @php
                        $pillClass = $intento->puntaje >= 70
                            ? 'pill--ok'
                            : 'pill--draft';
                    @endphp

                    <tr>

                        <td>
                            <strong>
                                {{ $intento->usuario->name ?? 'Usuario eliminado' }}
                            </strong>
                        </td>

                        <td>
                            {{ $intento->usuario->email ?? '-' }}
                        </td>

                        <td>
                            <span class="pill {{ $pillClass }}">
                                {{ number_format($intento->puntaje, 0) }}%
                            </span>
                        </td>

                        <td>
                            {{ $intento->respuestas_correctas }}
                            /
                            {{ $intento->total_preguntas }}
                        </td>

                        <td>
                            @if($intento->finalizado_at)
                                {{ $intento->finalizado_at->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="padding:24px;">
                            Ningún aprendiz ha finalizado esta evaluación todavía.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>


    <div style="margin-top:20px;">

        <a
            href="{{ route('evaluacion.show', $evaluacion) }}"
            class="btn btn--ghost"
        >
            Volver a la Evaluación
        </a>

    </div>

</div>

@endsection
@extends('layouts.figma')

@section('page', 'evaluaciones-aprendiz')
@section('title', 'Mis Resultados')
@section('subtitle', 'Consulta los resultados de las evaluaciones que has presentado')

@section('content')

    <section class="card table-card">

        <table class="table">

            <thead>
                <tr>
                    <th>Evaluación</th>
                    <th>Puntaje</th>
                    <th>Correctas</th>
                    <th>Fecha</th>
                    <th>Acción</th>
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
                                {{ $intento->evaluacion->titulo ?? 'Evaluación eliminada' }}
                            </strong>
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


                        <td>

                            <a
                                href="{{ route('intentos.resultado', $intento) }}"
                                class="btn btn--ghost"
                            >
                                Ver resultado
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="padding:24px;">
                            Todavía no has finalizado ninguna evaluación.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

@endsection

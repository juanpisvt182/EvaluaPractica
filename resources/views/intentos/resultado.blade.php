@extends('layouts.figma')

@section('page', 'evaluaciones-aprendiz')
@section('title', 'Resultado de la Evaluación')
@section('subtitle', $intento->evaluacion->titulo)

@section('content')

    <div style="max-width:900px;">

        {{-- Resultado general --}}
        <section
            class="card"
            style="
                padding:28px;
                margin-bottom:24px;
                text-align:center;
            "
        >

            <div
                style="
                    font-size:42px;
                    font-weight:800;
                    margin-bottom:8px;
                "
            >
                {{ number_format($intento->puntaje, 0) }}%
            </div>

            <div
                style="
                    font-size:18px;
                    margin-bottom:10px;
                "
            >
                {{ $intento->respuestas_correctas }}
                de
                {{ $intento->total_preguntas }}
                respuestas correctas
            </div>

            <span class="pill pill--ok">
                Finalizado
            </span>

        </section>


        {{-- Respuestas --}}
        <section
            class="card"
            style="padding:24px;"
        >

            <h2 style="margin-top:0;">
                Respuestas
            </h2>

            @foreach($intento->respuestas as $respuesta)

                <div
                    style="
                        border:1px solid #EAECF0;
                        border-radius:10px;
                        padding:16px;
                        margin-bottom:14px;
                    "
                >

                    <strong>
                        {{ $loop->iteration }}.
                        {{ $respuesta->pregunta->enunciado }}
                    </strong>

                    <div style="margin-top:10px;">

                        Tu respuesta:

                        <strong>
                            {{ $respuesta->opcion->texto }}
                        </strong>

                    </div>


                    <div style="margin-top:8px;">

                        @if($respuesta->es_correcta)

                            <span
                                style="
                                    color:#067647;
                                    font-weight:700;
                                "
                            >
                                ✓ Correcta
                            </span>

                        @else

                            <span
                                style="
                                    color:#B42318;
                                    font-weight:700;
                                "
                            >
                                ✕ Incorrecta
                            </span>

                        @endif

                    </div>

                </div>

            @endforeach

        </section>


        <div style="margin-top:20px;">

            <a
                href="{{ route('intentos.index') }}"
                class="btn btn--ghost"
            >
                Volver a Evaluaciones
            </a>

        </div>

    </div>

@endsection
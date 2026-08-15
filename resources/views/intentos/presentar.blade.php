@extends('layouts.figma')

@section('page', 'evaluaciones-aprendiz')
@section('title', $intento->evaluacion->titulo)
@section('subtitle', 'Responde todas las preguntas antes de finalizar la evaluación')

@section('content')

    <div style="max-width:900px;">

        {{-- Información --}}
        <section
            class="card"
            style="
                padding:18px 22px;
                margin-bottom:20px;
                display:flex;
                justify-content:space-between;
                gap:20px;
            "
        >

            <div>
                <strong>Preguntas</strong>
                <div style="margin-top:4px;">
                    {{ $intento->evaluacion->preguntas->count() }}
                </div>
            </div>

            <div>
                <strong>Tiempo límite</strong>
                <div style="margin-top:4px;">
                    {{ $intento->evaluacion->tiempo_limite }} minutos
                </div>
            </div>

            <div>
                <strong>Estado</strong>
                <div style="margin-top:4px;">
                    <span class="pill pill--sent">
                        En progreso
                    </span>
                </div>
            </div>

        </section>


        @if ($errors->any())

            <div
                class="card"
                style="
                    padding:14px 16px;
                    margin-bottom:20px;
                    border-left:4px solid #DC2626;
                    color:#B91C1C;
                "
            >
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>

        @endif


        <form
            method="POST"
            action="{{ route('intentos.finalizar', $intento) }}"
            onsubmit="return confirm('¿Seguro que deseas finalizar la evaluación? Después no podrás cambiar estas respuestas.')"
        >

            @csrf


            @foreach($intento->evaluacion->preguntas as $pregunta)

                <section
                    class="card"
                    style="
                        padding:22px;
                        margin-bottom:18px;
                    "
                >

                    <div
                        style="
                            font-weight:700;
                            margin-bottom:16px;
                            font-size:16px;
                        "
                    >
                        {{ $loop->iteration }}.
                        {{ $pregunta->enunciado }}
                    </div>


                    @foreach($pregunta->opciones as $opcion)

                        <label
                            style="
                                display:flex;
                                align-items:center;
                                gap:12px;
                                padding:12px 14px;
                                border:1px solid #EAECF0;
                                border-radius:10px;
                                margin-bottom:10px;
                                cursor:pointer;
                            "
                        >

                            <input
                                type="radio"
                                name="respuestas[{{ $pregunta->id }}]"
                                value="{{ $opcion->id }}"
                                required
                                {{
                                    (string) old(
                                        'respuestas.' . $pregunta->id
                                    ) === (string) $opcion->id
                                        ? 'checked'
                                        : ''
                                }}
                            >

                            <span>
                                {{ $opcion->texto }}
                            </span>

                        </label>

                    @endforeach

                </section>

            @endforeach


            <div
                style="
                    display:flex;
                    justify-content:space-between;
                    align-items:center;
                    margin-top:24px;
                "
            >

                <a
                    href="{{ route('intentos.index') }}"
                    class="btn btn--ghost"
                >
                    Volver
                </a>

                <button
                    type="submit"
                    class="btn btn--accent"
                >
                    Finalizar Evaluación
                </button>

            </div>

        </form>

    </div>

@endsection
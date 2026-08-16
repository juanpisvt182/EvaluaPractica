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
    <strong>Tiempo restante</strong>

    <div
        id="temporizador"
        style="
            margin-top:4px;
            font-size:20px;
            font-weight:800;
        "
    >
        --:--
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
            id="form-evaluacion"
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
   <script>
    let segundosRestantes = {{ $segundosRestantes }};
    let evaluacionEnviada = false;

    const temporizador = document.getElementById('temporizador');
    const formulario = document.getElementById('form-evaluacion');

    function actualizarTemporizador() {

        const minutos = Math.floor(segundosRestantes / 60);
        const segundos = segundosRestantes % 60;

        temporizador.textContent =
            String(minutos).padStart(2, '0') +
            ':' +
            String(segundos).padStart(2, '0');

        if (segundosRestantes <= 60) {
            temporizador.style.color = '#B42318';
        }

        if (segundosRestantes <= 0 && !evaluacionEnviada) {

            evaluacionEnviada = true;

            temporizador.textContent = '00:00';

            clearInterval(intervalo);

            alert('El tiempo de la evaluación ha terminado.');

            formulario.submit();

            return;
        }

        segundosRestantes--;
    }

    actualizarTemporizador();

    const intervalo = setInterval(actualizarTemporizador, 1000);
</script>
@endsection
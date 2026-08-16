@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Detalle de la Evaluación')
@section('subtitle', 'Consulta la información y administra las preguntas')

@section('top_action')

    <a
        class="btn btn--ghost"
        href="{{ route('evaluacion.resultados', $evaluacion) }}"
    >
        Ver Resultados
    </a>

    <a
        class="btn btn--accent"
        href="{{ route('evaluacion.edit', $evaluacion) }}"
    >
        Editar Evaluación
    </a>

@endsection


@section('content')

    {{-- ========================================= --}}
    {{-- INFORMACIÓN DE LA EVALUACIÓN --}}
    {{-- ========================================= --}}

    <section
        class="card"
        style="
            padding:24px;
            max-width:900px;
            margin-bottom:24px;
        "
    >

        <div style="margin-bottom:22px;">
            <strong style="display:block; margin-bottom:6px;">
                Título
            </strong>

            <div>
                {{ $evaluacion->titulo }}
            </div>
        </div>


        <div style="margin-bottom:22px;">
            <strong style="display:block; margin-bottom:6px;">
                Descripción
            </strong>

            <div style="color:#667085;">
                {{ $evaluacion->descripcion ?: 'Sin descripción' }}
            </div>
        </div>


        <div style="margin-bottom:22px;">
            <strong style="display:block; margin-bottom:6px;">
                Instructor
            </strong>

            <div>
                {{ $evaluacion->instructor->name ?? 'Sin instructor' }}
            </div>
        </div>


        <div style="margin-bottom:22px;">
            <strong style="display:block; margin-bottom:6px;">
                Tiempo límite
            </strong>

            <div>
                {{ $evaluacion->tiempo_limite }} minutos
            </div>
        </div>


        <div style="margin-bottom:22px;">

            <strong style="display:block; margin-bottom:8px;">
                Estado
            </strong>

            @php
                $pillClass = $evaluacion->estado === 'Activa'
                    ? 'pill--ok'
                    : 'pill--draft';
            @endphp

            <span class="pill {{ $pillClass }}">
                {{ $evaluacion->estado }}
            </span>

        </div>


        <div>

            <strong style="display:block; margin-bottom:6px;">
                Fecha de creación
            </strong>

            <div>
                {{ $evaluacion->created_at->format('d/m/Y H:i') }}
            </div>

        </div>

    </section>



    {{-- ========================================= --}}
    {{-- PREGUNTAS EXISTENTES --}}
    {{-- ========================================= --}}

    <section
        class="card"
        style="
            padding:24px;
            max-width:900px;
            margin-bottom:24px;
        "
    >

        <div
            style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                margin-bottom:22px;
            "
        >

            <div>

                <h2 style="margin:0; font-size:20px;">
                    Preguntas de la evaluación
                </h2>

                <p
                    style="
                        margin:5px 0 0;
                        color:#667085;
                    "
                >
                    Total: {{ $evaluacion->preguntas->count() }}
                </p>

            </div>

        </div>


        @forelse($evaluacion->preguntas as $pregunta)

            <div
                style="
                    border:1px solid #E4E7EC;
                    border-radius:12px;
                    padding:20px;
                    margin-bottom:16px;
                "
            >

                <div
                    style="
                        display:flex;
                        justify-content:space-between;
                        gap:20px;
                        margin-bottom:16px;
                    "
                >

                    <div>

                        <strong>
                            Pregunta {{ $loop->iteration }}
                        </strong>

                        <div
                            style="
                                margin-top:7px;
                                font-size:16px;
                            "
                        >
                            {{ $pregunta->enunciado }}
                        </div>

                    </div>


                    <div
    style="
        display:flex;
        align-items:center;
        gap:8px;
    "
>

    {{-- Editar pregunta --}}
    <a
        href="{{ route('preguntas.edit', $pregunta) }}"
        class="icon-btn"
        title="Editar pregunta"
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


    {{-- Eliminar pregunta --}}
    <form
        method="POST"
        action="{{ route('preguntas.destroy', $pregunta) }}"
        onsubmit="return confirm('¿Seguro que deseas eliminar esta pregunta?')"
    >

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="icon-btn"
            title="Eliminar pregunta"
        >
            <svg aria-hidden="true">
                <use href="#icon-trash"></use>
            </svg>
        </button>

    </form>

</div>

        @empty

            <div
                style="
                    padding:18px;
                    background:#F9FAFB;
                    border-radius:10px;
                    color:#667085;
                "
            >
                Esta evaluación todavía no tiene preguntas.
            </div>

        @endforelse

    </section>



    {{-- ========================================= --}}
    {{-- FORMULARIO NUEVA PREGUNTA --}}
    {{-- ========================================= --}}

    <section
        class="card"
        style="
            padding:24px;
            max-width:900px;
            margin-bottom:24px;
        "
    >

        <h2
            style="
                margin-top:0;
                margin-bottom:6px;
                font-size:20px;
            "
        >
            Agregar nueva pregunta
        </h2>

        <p
            style="
                color:#667085;
                margin-top:0;
                margin-bottom:24px;
            "
        >
            Escribe cuatro opciones y selecciona cuál es la respuesta correcta.
        </p>


        {{-- Errores de validación --}}
        @if ($errors->any())

            <div
                style="
                    background:#FEF2F2;
                    border:1px solid #FECACA;
                    padding:14px 16px;
                    border-radius:10px;
                    margin-bottom:20px;
                    color:#B91C1C;
                "
            >

                <strong>
                    Revisa los siguientes campos:
                </strong>

                <ul style="margin-top:8px; padding-left:20px;">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('preguntas.store', $evaluacion) }}"
        >

            @csrf


            {{-- Enunciado --}}
            <div style="margin-bottom:24px;">

                <label
                    for="enunciado"
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
                >
                    Pregunta
                </label>

                <textarea
                    id="enunciado"
                    name="enunciado"
                    rows="3"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                        resize:vertical;
                    "
                    placeholder="Ejemplo: ¿Qué comando inicia el servidor de Laravel?"
                >{{ old('enunciado') }}</textarea>

            </div>



            {{-- OPCIONES --}}

            @for($i = 0; $i < 4; $i++)

                <div
                    style="
                        display:flex;
                        align-items:center;
                        gap:12px;
                        margin-bottom:14px;
                    "
                >

                    <input
                        type="radio"
                        name="correcta"
                        value="{{ $i }}"
                        required
                        {{ old('correcta') !== null &&
                           (int) old('correcta') === $i
                            ? 'checked'
                            : '' }}
                    >


                    <div style="flex:1;">

                        <label
                            style="
                                display:block;
                                font-weight:600;
                                margin-bottom:6px;
                            "
                        >
                            Opción {{ $i + 1 }}
                        </label>

                        <input
                            type="text"
                            name="opciones[]"
                            value="{{ old('opciones.' . $i) }}"
                            required
                            style="
                                width:100%;
                                padding:11px 13px;
                                border:1px solid #D7DCE5;
                                border-radius:10px;
                            "
                            placeholder="Escribe la opción {{ $i + 1 }}"
                        >

                    </div>

                </div>

            @endfor


            <p
                style="
                    color:#667085;
                    font-size:13px;
                    margin:8px 0 22px;
                "
            >
                Marca el círculo ubicado a la izquierda de la respuesta correcta.
            </p>


            <div
                style="
                    display:flex;
                    justify-content:flex-end;
                "
            >

                <button
                    type="submit"
                    class="btn btn--accent"
                >
                    Agregar Pregunta
                </button>

            </div>

        </form>

    </section>



    {{-- VOLVER --}}

    <div style="max-width:900px;">

        <a
            href="{{ route('evaluacion.index') }}"
            class="btn btn--ghost"
        >
            Volver a Evaluaciones
        </a>

    </div>

@endsection
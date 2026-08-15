@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Editar Pregunta')
@section('subtitle', 'Modifica la pregunta, sus opciones y la respuesta correcta')

@section('content')

<section class="card" style="padding:24px; max-width:900px;">

    @if ($errors->any())
        <div style="
            background:#FEF2F2;
            border:1px solid #FECACA;
            padding:14px 16px;
            border-radius:10px;
            margin-bottom:20px;
            color:#B91C1C;
        ">
            <strong>Revisa los siguientes campos:</strong>

            <ul style="margin-top:8px; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form
        method="POST"
        action="{{ route('preguntas.update', $pregunta) }}"
    >
        @csrf
        @method('PUT')


        {{-- Pregunta --}}
        <div style="margin-bottom:24px;">

            <label
                for="enunciado"
                style="display:block; font-weight:700; margin-bottom:8px;"
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
            >{{ old('enunciado', $pregunta->enunciado) }}</textarea>

        </div>


        {{-- Opciones --}}
        @foreach ($pregunta->opciones as $indice => $opcion)

            <div style="
                display:flex;
                align-items:center;
                gap:12px;
                margin-bottom:16px;
            ">

                <input
                    type="radio"
                    name="correcta"
                    value="{{ $indice }}"
                    required

                    @if(old('correcta') !== null)
                        {{ (int) old('correcta') === $indice ? 'checked' : '' }}
                    @else
                        {{ $opcion->es_correcta ? 'checked' : '' }}
                    @endif
                >


                <div style="flex:1;">

                    <label style="
                        display:block;
                        font-weight:600;
                        margin-bottom:6px;
                    ">
                        Opción {{ $indice + 1 }}
                    </label>

                    <input
                        type="text"
                        name="opciones[]"
                        value="{{ old('opciones.' . $indice, $opcion->texto) }}"
                        required
                        style="
                            width:100%;
                            padding:11px 13px;
                            border:1px solid #D7DCE5;
                            border-radius:10px;
                        "
                    >

                </div>

            </div>

        @endforeach


        <p style="
            color:#667085;
            font-size:13px;
            margin:8px 0 24px;
        ">
            Selecciona el círculo correspondiente a la respuesta correcta.
        </p>


        <div style="
            display:flex;
            justify-content:flex-end;
            gap:12px;
        ">

            <a
                href="{{ route('evaluacion.show', $evaluacion) }}"
                class="btn btn--ghost"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="btn btn--accent"
            >
                Guardar Cambios
            </button>

        </div>

    </form>

</section>

@endsection
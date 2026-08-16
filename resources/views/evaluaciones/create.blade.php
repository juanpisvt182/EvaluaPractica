@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Nueva Evaluación')
@section('subtitle', 'Crea una nueva evaluación para los aprendices')

@section('content')

    <section class="card" style="padding:24px; max-width:800px;">

        {{-- Mostrar errores de validación --}}
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
            action="{{ route('evaluacion.store') }}"
        >
            @csrf
{{-- Grupo / Materia --}}
<div style="margin-bottom:20px;">

    <label
        for="grupo_id"
        style="
            display:block;
            font-weight:700;
            margin-bottom:8px;
        "
    >
        Grupo / Materia
    </label>

    <select
        id="grupo_id"
        name="grupo_id"
        required
        style="
            width:100%;
            padding:12px 14px;
            border:1px solid #D7DCE5;
            border-radius:10px;
            background:white;
        "
    >

        <option value="">
            Selecciona un grupo
        </option>

        @foreach($grupos as $grupo)

            <option
                value="{{ $grupo->id }}"
                @selected(old('grupo_id') == $grupo->id)
            >
                {{ $grupo->nombre }} - {{ $grupo->materia }}
            </option>

        @endforeach

    </select>


    @if($grupos->isEmpty())

        <div
            style="
                margin-top:8px;
                color:#667085;
                font-size:14px;
            "
        >
            No tienes grupos activos disponibles.
        </div>

    @endif

</div>

            {{-- Título --}}
            <div style="margin-bottom:20px;">

                <label
                    for="titulo"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Título de la evaluación
                </label>

                <input
                    id="titulo"
                    type="text"
                    name="titulo"
                    value="{{ old('titulo') }}"
                    required
                    maxlength="255"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                    "
                    placeholder="Ejemplo: Evaluación de fundamentos de PHP"
                >

            </div>


            {{-- Descripción --}}
            <div style="margin-bottom:20px;">

                <label
                    for="descripcion"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="5"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                        resize:vertical;
                    "
                    placeholder="Describe brevemente el contenido de la evaluación"
                >{{ old('descripcion') }}</textarea>

            </div>


            {{-- Tiempo límite --}}
            <div style="margin-bottom:26px;">

                <label
                    for="tiempo_limite"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Tiempo límite
                </label>

                <input
                    id="tiempo_limite"
                    type="number"
                    name="tiempo_limite"
                    value="{{ old('tiempo_limite', 30) }}"
                    min="1"
                    required
                    style="
                        width:200px;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                    "
                >

                <span style="margin-left:8px; color:#667085;">
                    minutos
                </span>

            </div>


            {{-- Botones --}}
            <div
                style="
                    display:flex;
                    gap:12px;
                    justify-content:flex-end;
                "
            >

                <a
                    href="{{ route('evaluacion.index') }}"
                    class="btn btn--ghost"
                >
                    Cancelar
                </a>

                <button
    type="submit"
    class="btn btn--accent"
    @disabled($grupos->isEmpty())
>
                >
                    Crear Evaluación
                </button>

            </div>

        </form>

    </section>

@endsection
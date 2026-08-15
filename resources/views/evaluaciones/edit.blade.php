@extends('layouts.figma')

@section('page', 'evaluaciones')
@section('title', 'Editar Evaluación')
@section('subtitle', 'Modifica la información de la evaluación')

@section('content')

    <section class="card" style="padding:24px; max-width:800px;">

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
            action="{{ route('evaluacion.update', $evaluacion) }}"
        >

            @csrf
            @method('PUT')


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
                    value="{{ old('titulo', $evaluacion->titulo) }}"
                    required
                    maxlength="255"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                    "
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
                >{{ old('descripcion', $evaluacion->descripcion) }}</textarea>

            </div>


            {{-- Tiempo límite --}}
            <div style="margin-bottom:20px;">

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
                    value="{{ old('tiempo_limite', $evaluacion->tiempo_limite) }}"
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


            {{-- Estado --}}
            <div style="margin-bottom:26px;">

                <label
                    for="estado"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    style="
                        width:220px;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                        background:white;
                    "
                >

                    <option
                        value="Activa"
                        {{ old('estado', $evaluacion->estado) === 'Activa' ? 'selected' : '' }}
                    >
                        Activa
                    </option>

                    <option
                        value="Inactiva"
                        {{ old('estado', $evaluacion->estado) === 'Inactiva' ? 'selected' : '' }}
                    >
                        Inactiva
                    </option>

                </select>

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
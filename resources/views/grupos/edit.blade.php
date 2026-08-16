@extends('layouts.figma')

@section('page', 'grupos')
@section('title', 'Editar Grupo')
@section('subtitle', 'Modifica la información del grupo')

@section('content')

    <section
        class="card"
        style="padding:24px; max-width:800px;"
    >

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

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('grupos.update', $grupo) }}"
        >

            @csrf
            @method('PUT')


            {{-- Nombre --}}
            <div style="margin-bottom:20px;">

                <label
                    for="nombre"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Nombre del grupo
                </label>

                <input
                    id="nombre"
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $grupo->nombre) }}"
                    required
                    maxlength="100"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                    "
                >

            </div>


            {{-- Materia --}}
            <div style="margin-bottom:20px;">

                <label
                    for="materia"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Materia
                </label>

                <input
                    id="materia"
                    type="text"
                    name="materia"
                    value="{{ old('materia', $grupo->materia) }}"
                    required
                    maxlength="150"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                    "
                >

            </div>


            {{-- Docente --}}
            <div style="margin-bottom:20px;">

                <label
                    for="instructor_id"
                    style="display:block; font-weight:700; margin-bottom:8px;"
                >
                    Docente
                </label>

                <select
                    id="instructor_id"
                    name="instructor_id"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                        background:white;
                    "
                >

                    <option value="">
                        Sin docente asignado
                    </option>

                    @foreach($instructores as $instructor)

                        <option
                            value="{{ $instructor->id }}"
                            @selected(
                                old('instructor_id', $grupo->instructor_id)
                                == $instructor->id
                            )
                        >
                            {{ $instructor->name }}
                        </option>

                    @endforeach

                </select>

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
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #D7DCE5;
                        border-radius:10px;
                        background:white;
                    "
                >

                    <option
                        value="Activo"
                        @selected(old('estado', $grupo->estado) === 'Activo')
                    >
                        Activo
                    </option>

                    <option
                        value="Inactivo"
                        @selected(old('estado', $grupo->estado) === 'Inactivo')
                    >
                        Inactivo
                    </option>

                </select>

            </div>


            <div
                style="
                    display:flex;
                    gap:12px;
                    justify-content:flex-end;
                "
            >

                <a
                    href="{{ route('grupos.show', $grupo) }}"
                    class="btn btn--ghost"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn--accent"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </section>

@endsection
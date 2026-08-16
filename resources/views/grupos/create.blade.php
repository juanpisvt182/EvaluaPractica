@extends('layouts.figma')

@section('page', 'grupos')
@section('title', 'Nuevo Grupo')
@section('subtitle', 'Crea un grupo y asígnalo a una materia y docente')

@section('content')

    <section
        class="card"
        style="padding:24px; max-width:800px;"
    >

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

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('grupos.store') }}"
        >

            @csrf


            {{-- Nombre del grupo --}}
            <div style="margin-bottom:20px;">

                <label
                    for="nombre"
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
                >
                    Nombre del grupo
                </label>

                <input
                    id="nombre"
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    required
                    maxlength="100"
                    placeholder="Ejemplo: 9°A"
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
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
                >
                    Materia
                </label>

                <input
                    id="materia"
                    type="text"
                    name="materia"
                    value="{{ old('materia') }}"
                    required
                    maxlength="150"
                    placeholder="Ejemplo: Matemáticas"
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
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
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
                            @selected(old('instructor_id') == $instructor->id)
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
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
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
                        @selected(old('estado', 'Activo') === 'Activo')
                    >
                        Activo
                    </option>

                    <option
                        value="Inactivo"
                        @selected(old('estado') === 'Inactivo')
                    >
                        Inactivo
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
                    href="{{ route('grupos.index') }}"
                    class="btn btn--ghost"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn--accent"
                >
                    Crear Grupo
                </button>

            </div>

        </form>

    </section>

@endsection
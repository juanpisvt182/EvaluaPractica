@extends('layouts.figma')

@section('page', 'grupos')
@section('title', $grupo->nombre . ' - ' . $grupo->materia)
@section('subtitle', 'Información y estudiantes del grupo')

@section('content')

    <div style="max-width:1000px;">

        {{-- Información principal --}}
        <section
            class="card"
            style="
                padding:22px;
                margin-bottom:20px;
            "
        >

            <div
                style="
                    display:grid;
                    grid-template-columns:repeat(2, minmax(0, 1fr));
                    gap:24px;
                "
            >

                <div>
                    <strong>Grupo</strong>

                    <div style="margin-top:6px;">
                        {{ $grupo->nombre }}
                    </div>
                </div>

                <div>
                    <strong>Materia</strong>

                    <div style="margin-top:6px;">
                        {{ $grupo->materia }}
                    </div>
                </div>

                <div>
                    <strong>Docente</strong>

                    <div style="margin-top:6px;">
                        {{ $grupo->instructor->name ?? 'Sin docente asignado' }}
                    </div>
                </div>

                <div>
                    <strong>Estado</strong>

                    <div style="margin-top:6px;">

                        @php
                            $pillClass = $grupo->estado === 'Activo'
                                ? 'pill--ok'
                                : 'pill--draft';
                        @endphp

                        <span class="pill {{ $pillClass }}">
                            {{ $grupo->estado }}
                        </span>

                    </div>
                </div>

            </div>

        </section>


        {{-- Estudiantes --}}
        <section class="card table-card">

            <div style="padding:18px 22px;">

                <strong>
                    Estudiantes
                </strong>

                <div style="margin-top:4px; color:#667085;">
                    {{ $grupo->estudiantes->count() }}
                    estudiante(s) inscritos
                </div>


                {{-- Agregar estudiante: solo administrador --}}
                @if(auth()->user()->esAdministrador())

                    <form
                        method="POST"
                        action="{{ route('grupos.estudiantes.agregar', $grupo) }}"
                        style="
                            display:flex;
                            gap:10px;
                            margin-top:18px;
                            max-width:650px;
                        "
                    >

                        @csrf

                        <select
                            name="estudiante_id"
                            required
                            style="
                                flex:1;
                                padding:10px 12px;
                                border:1px solid #D7DCE5;
                                border-radius:10px;
                                background:white;
                            "
                        >

                            <option value="">
                                Selecciona un estudiante
                            </option>

                            @foreach($estudiantesDisponibles as $estudiante)

                                <option value="{{ $estudiante->id }}">
                                    {{ $estudiante->name }} - {{ $estudiante->email }}
                                </option>

                            @endforeach

                        </select>

                        <button
                            type="submit"
                            class="btn btn--accent"
                            @disabled($estudiantesDisponibles->isEmpty())
                        >
                            Agregar
                        </button>

                    </form>


                    @if($estudiantesDisponibles->isEmpty())

                        <div
                            style="
                                margin-top:10px;
                                color:#667085;
                                font-size:14px;
                            "
                        >
                            No hay más estudiantes disponibles para agregar.
                        </div>

                    @endif

                @endif

            </div>


            <table class="table">

                <thead>

                    <tr>

                        <th>Nombre</th>
                        <th>Correo electrónico</th>

                        @if(auth()->user()->esAdministrador())
                            <th>Acciones</th>
                        @endif

                    </tr>

                </thead>

                <tbody>

                    @forelse($grupo->estudiantes as $estudiante)

                        <tr>

                            <td>
                                {{ $estudiante->name }}
                            </td>

                            <td>
                                {{ $estudiante->email }}
                            </td>


                            @if(auth()->user()->esAdministrador())

                                <td>

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'grupos.estudiantes.quitar',
                                            [$grupo, $estudiante]
                                        ) }}"
                                        onsubmit="return confirm('¿Deseas retirar este estudiante del grupo?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="icon-btn"
                                            title="Retirar estudiante"
                                        >

                                            <svg aria-hidden="true">
                                                <use href="#icon-trash"></use>
                                            </svg>

                                        </button>

                                    </form>

                                </td>

                            @endif

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="{{ auth()->user()->esAdministrador() ? 3 : 2 }}"
                                style="padding:18px 22px;"
                            >
                                Este grupo todavía no tiene estudiantes.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </section>


        <div style="margin-top:20px;">

            <a
                href="{{ route('grupos.index') }}"
                class="btn btn--ghost"
            >
                Volver a Grupos
            </a>

        </div>

    </div>

@endsection
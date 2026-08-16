@extends('layouts.figma')

@section('page', 'revision-bitacoras')
@section('title', 'Revisión de Bitácoras')
@section('subtitle', 'Consulta y revisa las bitácoras enviadas por los estudiantes')

@section('content')

    <section
        class="card table-card"
        aria-label="Bitácoras para revisión"
    >

        <table class="table">

            <thead>
                <tr>
                    <th>Bitácora</th>
                    <th>Estudiante</th>
                    <th>Grupo</th>
                    <th>Materia</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($bitacoras as $bitacora)

                    @php

                        $pillClass = match($bitacora->estado) {
                            'Aprobado' => 'pill--ok',
                            'Enviado' => 'pill--sent',
                            default => 'pill--draft',
                        };

                    @endphp


                    <tr>

                        {{-- Número --}}
                        <td>
                            {{ $bitacora->numero }}
                        </td>


                        {{-- Estudiante --}}
                        <td>
                            {{ $bitacora->estudiante->name ?? 'Sin estudiante' }}
                        </td>


                        {{-- Grupo --}}
                        <td>
                            {{ $bitacora->grupo->nombre ?? 'Sin grupo' }}
                        </td>


                        {{-- Materia --}}
                        <td>
                            {{ $bitacora->grupo->materia ?? 'Sin materia' }}
                        </td>


                        {{-- Fecha --}}
                        <td>
                            {{ $bitacora->fecha?->format('d/m/Y') }}
                        </td>


                        {{-- Estado --}}
                        <td>

                            <span
                                class="pill {{ $pillClass }}"

                                @if($bitacora->estado === 'Rechazado')
                                    style="
                                        background:#FEE2E2;
                                        color:#B91C1C;
                                    "
                                @endif
                            >
                                {{ $bitacora->estado }}
                            </span>

                        </td>


                        {{-- Acciones --}}
                        <td>

                            <div class="actions">

                                {{-- Ver --}}
                                <a
                                    href="{{ route('bitacoras.revision.show', $bitacora) }}"
                                    class="icon-btn"
                                    title="Revisar bitácora"
                                >
                                    <svg aria-hidden="true">
                                        <use href="#icon-eye"></use>
                                    </svg>
                                </a>


                                {{-- Descargar --}}
                                @if($bitacora->archivo_path)

                                    <a
                                        href="{{ route('bitacoras.revision.download', $bitacora) }}"
                                        class="icon-btn"
                                        title="Descargar archivo"
                                    >
                                        <svg aria-hidden="true">
                                            <use href="#icon-download"></use>
                                        </svg>
                                    </a>

                                @else

                                    <button
                                        type="button"
                                        class="icon-btn"
                                        disabled
                                        title="Sin archivo"
                                        style="
                                            opacity:.35;
                                            cursor:not-allowed;
                                        "
                                    >
                                        <svg aria-hidden="true">
                                            <use href="#icon-download"></use>
                                        </svg>
                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="7"
                            style="padding:20px 22px;"
                        >
                            No hay bitácoras para revisar.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

@endsection
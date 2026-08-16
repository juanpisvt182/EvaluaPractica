@extends('layouts.figma')

@section('page', 'revision-bitacoras')
@section('title', 'Revisar Bitácora')
@section('subtitle', 'Consulta el trabajo enviado y registra tu revisión')

@section('content')

    {{-- Errores --}}
    @if($errors->any())

        <div
            class="card"
            style="
                padding:14px 16px;
                margin-bottom:16px;
                border:1px solid #FECACA;
                color:#B91C1C;
            "
        >

            <strong>
                Revisa los siguientes datos:
            </strong>

            <ul style="margin:8px 0 0; padding-left:20px;">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Información de la bitácora --}}
    <section
        class="card section-card"
        style="margin-bottom:18px;"
    >

        <div
            style="
                display:grid;
                grid-template-columns:
                    repeat(auto-fit, minmax(200px, 1fr));
                gap:18px;
            "
        >

            <div>
                <strong>Bitácora</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->numero }}
                </div>
            </div>


            <div>
                <strong>Estudiante</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->estudiante->name ?? 'Sin estudiante' }}
                </div>
            </div>


            <div>
                <strong>Grupo</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->grupo->nombre ?? 'Sin grupo' }}
                </div>
            </div>


            <div>
                <strong>Materia</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->grupo->materia ?? 'Sin materia' }}
                </div>
            </div>


            <div>
                <strong>Fecha</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->fecha?->format('d/m/Y') }}
                </div>
            </div>


            <div>
                <strong>Estado</strong>

                <div style="margin-top:6px;">
                    {{ $bitacora->estado }}
                </div>
            </div>

        </div>

    </section>


    {{-- Contenido --}}
    <section
        class="card section-card"
        style="margin-bottom:18px;"
    >

        <h3 style="margin:0 0 12px;">
            Contenido
        </h3>

        <div
            style="
                color:#344054;
                white-space:pre-wrap;
                line-height:1.6;
            "
        >
            {{ $bitacora->contenido ?? 'Sin contenido.' }}
        </div>

    </section>


    {{-- Archivo --}}
    <section
        class="card section-card"
        style="margin-bottom:18px;"
    >

        <h3 style="margin:0 0 12px;">
            Archivo adjunto
        </h3>

        @if($bitacora->archivo_path)

            <a
                href="{{ route('bitacoras.revision.download', $bitacora) }}"
                class="btn btn--accent"
            >
                Descargar
                {{ $bitacora->archivo_nombre ?? 'archivo' }}
            </a>

        @else

            <p style="margin:0; color:var(--muted);">
                El estudiante no adjuntó ningún archivo.
            </p>

        @endif

    </section>


    {{-- Revisión --}}
    <section class="card section-card">

        <h3 style="margin:0 0 8px;">
            Revisión del docente
        </h3>

        <p
            style="
                margin:0 0 18px;
                color:var(--muted);
            "
        >
            Puedes aprobar la bitácora o rechazarla indicando
            el motivo.
        </p>


        <form
            method="POST"
            action="{{ route('bitacoras.revision.revisar', $bitacora) }}"
        >

            @csrf
            @method('PATCH')


            <div style="margin-bottom:18px;">

                <label
                    for="retroalimentacion"
                    style="
                        display:block;
                        font-weight:700;
                        margin-bottom:8px;
                    "
                >
                    Retroalimentación
                </label>

                <textarea
                    id="retroalimentacion"
                    name="retroalimentacion"
                    class="textarea"
                    placeholder="Escribe una observación para el estudiante..."
                >{{ old('retroalimentacion', $bitacora->retroalimentacion) }}</textarea>

                <small
                    style="
                        display:block;
                        margin-top:6px;
                        color:var(--muted);
                    "
                >
                    La retroalimentación es obligatoria si rechazas
                    la bitácora.
                </small>

            </div>


            <div
                style="
                    display:flex;
                    flex-wrap:wrap;
                    gap:10px;
                "
            >

                <button
                    type="submit"
                    name="estado"
                    value="Aprobado"
                    class="btn btn--accent"
                >
                    Aprobar
                </button>


                <button
                    type="submit"
                    name="estado"
                    value="Rechazado"
                    class="btn btn--ghost"
                    style="
                        border-color:#FCA5A5;
                        color:#B91C1C;
                    "
                >
                    Rechazar
                </button>


                <a
                    href="{{ route('bitacoras.revision.index') }}"
                    class="btn btn--ghost"
                >
                    Volver
                </a>

            </div>

        </form>

    </section>

@endsection
@extends('layouts.figma')

@section('page', 'grupos')
@section('title', 'Grupos')
@section('subtitle', 'Consulta y administra los grupos y materias')

@section('top_action')

    @if(auth()->user()->esAdministrador())

        <a class="btn btn--accent" href="{{ route('grupos.create') }}">

            <svg aria-hidden="true" style="width:18px;height:18px">
                <use href="#icon-plus"></use>
            </svg>

            Nuevo Grupo
        </a>

    @endif

@endsection


@section('content')

    <section
        class="card table-card"
        aria-label="Tabla de grupos"
    >

        <table class="table">

            <thead>

               <tr>
    <th>Grupo</th>
    <th>Materia</th>
    <th>Docente</th>
    <th>Estudiantes</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>

            </thead>

            <tbody>

                @forelse($grupos as $grupo)

                    @php
                        $pillClass = $grupo->estado === 'Activo'
                            ? 'pill--ok'
                            : 'pill--draft';
                    @endphp

                    <tr>

                        <td>
                            <strong>
                                {{ $grupo->nombre }}
                            </strong>
                        </td>

                        <td>
                            {{ $grupo->materia }}
                        </td>

                        <td>
                            {{ $grupo->instructor->name ?? 'Sin docente asignado' }}
                        </td>

                        <td>
                            {{ $grupo->estudiantes_count }}
                        </td>

                        <td>

                            <span class="pill {{ $pillClass }}">
                                {{ $grupo->estado }}
                            </span>

                        </td>
                        <td>

   <td>

    <div class="actions">

        {{-- Ver --}}
        <a
            href="{{ route('grupos.show', $grupo) }}"
            class="icon-btn"
            title="Ver grupo"
        >
            <svg aria-hidden="true">
                <use href="#icon-eye"></use>
            </svg>
        </a>


        @if(auth()->user()->esAdministrador())

            {{-- Editar --}}
            <a
                href="{{ route('grupos.edit', $grupo) }}"
                class="icon-btn"
                title="Editar grupo"
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


            {{-- Eliminar --}}
            <form
                method="POST"
                action="{{ route('grupos.destroy', $grupo) }}"
                onsubmit="return confirm('¿Seguro que deseas eliminar este grupo?')"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="icon-btn"
                    title="Eliminar grupo"
                >
                    <svg aria-hidden="true">
                        <use href="#icon-trash"></use>
                    </svg>
                </button>
            </form>

        @endif

    </div>

</td>


                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            style="padding:18px 22px;"
                        >
                            No hay grupos disponibles todavía.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </section>

@endsection
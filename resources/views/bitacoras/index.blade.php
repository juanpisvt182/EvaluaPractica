@extends('layouts.figma')

@section('page', 'bitacoras')
@section('title', 'Módulo de Bitácoras')
@section('subtitle', 'Gestiona y revisa todas tus bitácoras')

@section('top_action')
    <a class="btn btn--accent" href="{{ route('bitacoras.create') }}">
        <svg aria-hidden="true" style="width:18px;height:18px"><use href="#icon-plus"></use></svg>
        Nueva Bitácora
    </a>
@endsection

@section('content')
    <section class="card table-card" aria-label="Tabla de bitácoras">
        <table class="table">
            <thead>
                <tr>
                    <th>Número de Bitácora</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bitacoras as $b)
                    @php
                        $pillClass = match($b->estado) {
                            'Aprobado' => 'pill--ok',
                            'Enviado' => 'pill--sent',
                            default => 'pill--draft',
                        };
                    @endphp

                    <tr>
                        <td>{{ $b->numero }}</td>
                        <td>{{ $b->fecha }}</td>
                        <td><span class="pill {{ $pillClass }}">{{ $b->estado }}</span></td>
                        <td>
                            <div class="actions">
                                {{-- Ver (demo por ahora) --}}
                                <button class="icon-btn" type="button" title="Ver"
                                    onclick="alert('Próximamente: ver detalle');">
                                    <svg aria-hidden="true"><use href="#icon-eye"></use></svg>
                                </button>

                                {{-- Descargar (demo por ahora) --}}
                                <button class="icon-btn" type="button" title="Descargar"
                                    onclick="alert('Próximamente: descargar archivo');">
                                    <svg aria-hidden="true"><use href="#icon-download"></use></svg>
                                </button>

                                {{-- Eliminar (real) --}}
                                <form method="POST" action="{{ route('bitacoras.destroy', $b) }}">
                                    @csrf @method('DELETE')
                                    <button class="icon-btn" type="submit" title="Eliminar">
                                        <svg aria-hidden="true"><use href="#icon-trash"></use></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:18px 22px;">No hay bitácoras todavía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
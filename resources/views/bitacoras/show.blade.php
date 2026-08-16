@extends('layouts.figma')

@section('page', 'bitacoras')
@section('title', 'Detalle de Bitácora')
@section('subtitle', 'Visualización del registro')

@section('content')
<section class="card section-card">
  <div style="display:grid; gap:12px;">
    <div><b>Número:</b> {{ $bitacora->numero }}</div>
    <div><b>Fecha:</b> {{ $bitacora->fecha }}</div>
    <div><b>Estado:</b> {{ $bitacora->estado }}</div>

    <div>
      <b>Contenido:</b>
      <div style="margin-top:8px; color:#344054; white-space:pre-wrap;">
        {{ $bitacora->contenido ?? 'Sin contenido.' }}
      </div>
    </div>

    <div>
      <b>Archivo:</b>
      @if($bitacora->archivo_path)
        <div style="margin-top:10px;">
          <a class="btn btn--accent" href="{{ route('bitacoras.download', $bitacora) }}">
            Descargar: {{ $bitacora->archivo_nombre ?? 'archivo' }}
          </a>
        </div>
      @else
        <div style="color:#667085; margin-top:8px;">No hay archivo adjunto.</div>
      @endif
    </div>
{{-- Revisión del docente --}}
@if(in_array($bitacora->estado, ['Aprobado', 'Rechazado']))

    <div
        style="
            margin-top:18px;
            padding:16px;
            border:1px solid #E5E7EB;
            border-radius:12px;
            background:#F9FAFB;
        "
    >

        <h3 style="margin:0 0 14px;">
            Revisión del docente
        </h3>


        <div style="margin-bottom:10px;">

            <strong>Resultado:</strong>

            <span
                class="pill"
                style="
                    margin-left:6px;

                    @if($bitacora->estado === 'Aprobado')
                        background:#DCFCE7;
                        color:#15803D;
                    @else
                        background:#FEE2E2;
                        color:#B91C1C;
                    @endif
                "
            >
                {{ $bitacora->estado }}
            </span>

        </div>


        <div style="margin-bottom:10px;">

            <strong>Revisado por:</strong>

            {{ $bitacora->revisor->name ?? 'Docente' }}

        </div>


        @if($bitacora->revisado_at)

            <div style="margin-bottom:10px;">

                <strong>Fecha de revisión:</strong>

                {{ $bitacora->revisado_at->format('d/m/Y H:i') }}

            </div>

        @endif


        <div>

            <strong>Retroalimentación:</strong>

            <div
                style="
                    margin-top:8px;
                    color:#344054;
                    white-space:pre-wrap;
                "
            >{{ $bitacora->retroalimentacion ?: 'Sin observaciones adicionales.' }}</div>

        </div>

    </div>

@endif
    <div class="footer-actions" style="justify-content:flex-start;">
      <a class="btn btn--ghost" href="{{ route('bitacoras.index') }}">Volver</a>
    </div>
  </div>
</section>
@endsection
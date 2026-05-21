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

    <div class="footer-actions" style="justify-content:flex-start;">
      <a class="btn btn--ghost" href="{{ route('bitacoras.index') }}">Volver</a>
    </div>
  </div>
</section>
@endsection
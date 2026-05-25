@extends('layouts.figma')

@section('page', 'dashboard')
@section('title', 'Dashboard')
@section('subtitle', 'Resumen general del sistema')

@section('top_action')
  <a class="btn btn--accent" href="{{ route('bitacoras.index') }}">
    Ir a Bitácoras
  </a>
@endsection

@section('content')
  <section class="card section-card">
    <h3 style="margin:0 0 10px 0;">Hola, {{ auth()->user()->name }} 👋</h3>
    <p style="margin:0; color: var(--muted);">
      Aquí puedes ver un resumen rápido de tu progreso.
    </p>
  </section>

  <section class="grid" style="margin-top:16px; display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:14px;">
    <div class="card section-card">
      <p style="margin:0; color: var(--muted); font-weight:700;">Total bitácoras</p>
      <h2 style="margin:8px 0 0 0;">{{ $total ?? 0 }}</h2>
    </div>

    <div class="card section-card">
      <p style="margin:0; color: var(--muted); font-weight:700;">Aprobadas</p>
      <h2 style="margin:8px 0 0 0;">{{ $aprobadas ?? 0 }}</h2>
    </div>

    <div class="card section-card">
      <p style="margin:0; color: var(--muted); font-weight:700;">Pendientes</p>
      <h2 style="margin:8px 0 0 0;">{{ $pendientes ?? 0 }}</h2>
    </div>
  </section>

  <section class="card section-card" style="margin-top:16px;">
    <p style="margin:0; color: var(--muted); font-weight:700;">Progreso</p>
    <div style="margin-top:10px; width:100%; background:#eef2ff; border-radius:999px; height:14px; overflow:hidden;">
      <div style="height:100%; width: {{ $progreso ?? 0 }}%; background: var(--accent);"></div>
    </div>
    <p style="margin:10px 0 0 0; color: var(--muted);">
      {{ $progreso ?? 0 }}% completado (Aprobadas / Total)
    </p>
  </section>
@endsection
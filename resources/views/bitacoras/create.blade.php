@extends('layouts.figma')

@section('page', 'bitacoras')
@section('title', 'Nueva Bitácora')
@section('subtitle', 'Crea un nuevo registro de bitácora')

@section('content')
    <section class="card section-card">
       <form method="POST" action="{{ route('bitacoras.store') }}" enctype="multipart/form-data">
  @csrf

  <div style="display:grid; gap:14px;">
    <div>
      <label style="font-weight:900; color: var(--navy);">Fecha</label><br>
      <input type="date" name="fecha" required class="textarea" style="min-height:auto; height:44px;">
    </div>

    <div>
      <label style="font-weight:900; color: var(--navy);">Estado</label><br>
      <select name="estado" required class="textarea" style="min-height:auto; height:44px;">
        <option value="Borrador">Borrador</option>
        <option value="Enviado">Enviado</option>
        <option value="Aprobado">Aprobado</option>
      </select>
    </div>

    <div>
      <label style="font-weight:900; color: var(--navy);">Contenido</label><br>
      <textarea name="contenido" class="textarea" placeholder="Describe lo realizado..."></textarea>
    </div>

    <div>
      <label style="font-weight:900; color: var(--navy);">Adjuntar archivo (PDF/DOC/DOCX)</label><br>
      <input type="file" name="archivo" class="textarea" style="min-height:auto; height:44px; padding-top:10px;" accept=".pdf,.doc,.docx">
    </div>

    <div class="footer-actions">
      <button class="btn btn--accent" type="submit">Guardar</button>
      <a class="btn btn--ghost" href="{{ route('bitacoras.index') }}">Volver</a>
    </div>
  </div>
</form>
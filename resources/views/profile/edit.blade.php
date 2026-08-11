@extends('layouts.figma')

@section('title', 'Mi perfil')

@section('subtitle', 'Consulta y actualiza la información de tu cuenta.')

@section('page', 'perfil')

@section('content')

    @php
        $nombreRol = ucfirst($user->rol ?? 'aprendiz');

        $claseRol = match ($user->rol) {
            'administrador' => 'pill--ok',
            'instructor' => 'pill--sent',
            default => 'pill--draft',
        };
    @endphp

    <div class="card" style="padding: 28px; max-width: 760px;">

        <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            padding-bottom:22px;
            margin-bottom:24px;
            border-bottom:1px solid var(--border);
        ">
            <div>
                <h2 style="
                    margin:0 0 7px;
                    color:var(--navy);
                    font-size:21px;
                ">
                    Información personal
                </h2>

                <p style="
                    margin:0;
                    color:var(--muted);
                    font-size:14px;
                ">
                    Estos son los datos asociados a tu cuenta.
                </p>
            </div>

            <span class="pill {{ $claseRol }}">
                {{ $nombreRol }}
            </span>
        </div>

        @if (session('status') === 'profile-updated')
            <div style="
                padding:14px 16px;
                margin-bottom:20px;
                border-radius:14px;
                background:rgba(34, 197, 94, .12);
                border:1px solid rgba(34, 197, 94, .22);
                color:#15803D;
                font-weight:800;
            ">
                Perfil actualizado correctamente.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name" class="label">
                    Nombre completo
                </label>

                <div class="input">
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                    >
                </div>

                @error('name')
                    <p style="margin:8px 0 0; color:#DC2626; font-size:13px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="label">
                    Correo electrónico
                </label>

                <div class="input">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                    >
                </div>

                @error('email')
                    <p style="margin:8px 0 0; color:#DC2626; font-size:13px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <span class="label">
                    Rol asignado
                </span>

                <div style="
                    padding:15px 16px;
                    border:1px solid var(--border);
                    border-radius:16px;
                    background:#F8FAFC;
                    color:#475467;
                    font-weight:700;
                ">
                    {{ $nombreRol }}
                </div>

                <p style="
                    margin:8px 0 0;
                    color:var(--muted);
                    font-size:12px;
                ">
                    El rol solamente puede ser modificado por un administrador.
                </p>
            </div>

            <div style="
                display:flex;
                justify-content:flex-end;
                margin-top:26px;
            ">
                <button type="submit" class="btn btn--accent">
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>

@endsection
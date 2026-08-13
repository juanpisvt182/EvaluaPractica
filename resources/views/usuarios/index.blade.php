@extends('layouts.figma')

@section('title', 'Usuarios')

@section('subtitle', 'Administra los usuarios y sus roles dentro de EvaluaPractica.')

@section('page', 'usuarios')

@section('content')

    <div class="card table-card">

        <table class="table">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Rol actual</th>
                    <th>Cambiar rol</th>
                </tr>
            </thead>

            <tbody>

                @forelse($usuarios as $usuario)

                    @php
                        $claseRol = match ($usuario->rol) {
                            'administrador' => 'pill--ok',
                            'instructor' => 'pill--sent',
                            default => 'pill--draft',
                        };
                    @endphp

                    <tr>

                        <td>
                            <strong>{{ $usuario->name }}</strong>

                            @if($usuario->id === auth()->id())
                                <div style="
                                    margin-top:5px;
                                    color:var(--muted);
                                    font-size:12px;
                                ">
                                    Tu cuenta
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $usuario->email }}
                        </td>

                        <td>
                            <span class="pill {{ $claseRol }}">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>

                        <td>

                            <form
                                method="POST"
                                action="{{ route('admin.usuarios.rol', $usuario) }}"
                                style="
                                    display:flex;
                                    align-items:center;
                                    gap:10px;
                                "
                            >
                                @csrf
                                @method('PATCH')

                                <select
                                    name="rol"
                                    style="
                                        padding:11px 12px;
                                        border:1px solid var(--border);
                                        border-radius:12px;
                                        background:#fff;
                                        color:var(--text);
                                        font-weight:700;
                                    "
                                >

                                    <option
                                        value="aprendiz"
                                        {{ $usuario->rol === 'aprendiz' ? 'selected' : '' }}
                                    >
                                        Aprendiz
                                    </option>

                                    <option
                                        value="instructor"
                                        {{ $usuario->rol === 'instructor' ? 'selected' : '' }}
                                    >
                                        Instructor
                                    </option>

                                    <option
                                        value="administrador"
                                        {{ $usuario->rol === 'administrador' ? 'selected' : '' }}
                                    >
                                        Administrador
                                    </option>

                                </select>

                                <button
                                    type="submit"
                                    class="btn btn--accent"
                                    style="padding:11px 14px;"
                                >
                                    Guardar
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4">
                            No hay usuarios registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @if($errors->any())

        <div
            class="card"
            style="
                padding:14px 16px;
                margin-top:16px;
                color:#B42318;
            "
        >

            @foreach($errors->all() as $error)
                <div>
                    {{ $error }}
                </div>
            @endforeach

        </div>

    @endif

@endsection
@extends('layouts.app', ['active' => 'newaccount', 'title' => 'Registro de cuenta'])

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <!-- Page Title -->
    <div class="page-title light-background">
        <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Crear usuario</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="current">Registro</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Crea tu cuenta</h2>
            <p>Trabaje a través de su tarea de manera eficiente e intuitiva.</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <form method="POST" action="{{ route('orgs.validacionCreacionUsuario', ['id' => $org->id]) }}">
                @csrf

                <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="rut" class="col-md-4 col-form-label text-md-end">RUT</label>
                    <div class="col-md-6">
                        <input id="rut" type="text" pattern="^\d{7,8}-[\dkK]$"
                            class="form-control @error('rut') is-invalid @enderror" name="rut" value="{{ old('rut') }}"
                            required placeholder="Ej: 12959718-6">
                        @error('rut')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                    <div class="col-md-6 position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password">
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password-confirm"
                        class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                    <div class="col-md-6 position-relative">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2"
                            onclick="togglePassword('password-confirm', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>



                <div class="row mb-3">
                    <label for="perfil_id" class="form-label col-md-4 col-form-label text-md-end">Seleccione Perfil</label>
                    <div class="col-md-6">

                        <select class="form-select form-select" id="perfil_id" name="perfil_id"
                            onchange="handlePerfilIdChange()" required>
                            <option selected>Seleccione un perfil</option>
                            <option value="1">Administrador</option>
                            <option value="3">CRC</option>
                            <option value="5">Operador</option>
                        </select>
                        @error('perfil_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3" id="plan-container"
                    style="{{ old('perfil_id') == 1 ? 'display:flex;' : 'display:none;' }}">
                    <label for="plan_id" class="form-label col-md-4 col-form-label text-md-end">Seleccione un Plan</label>
                    <div class="col-md-6">
                        <select id="plan_id" name="plan_id" class="form-select form-select">
                            <option value="">Seleccione un plan</option>
                            <option value="0" {{ old('plan_id') == '0' ? 'selected' : '' }}>Esencial</option>
                            <option value="1" {{ old('plan_id') == '1' ? 'selected' : '' }}>Intermedio</option>
                            <option value="2" {{ old('plan_id') == '2' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                        @error('plan_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                @if (Auth::user()->perfil_id == 0)
                    <div class="row mb-3">
                        <label for="org_id" class="form-label col-md-4 col-form-label text-md-end">Organización</label>
                        <div class="col-md-6">
                            <select id="org_id" name="org_id" class="form-select" required>
                                <option value="">Seleccione una organización</option>
                                @foreach($orgs as $orgItem)
                                    <option value="{{ $orgItem->id }}">{{ $orgItem->name }}</option>
                                @endforeach
                            </select>
                            @error('org_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                @else
                    <div class="row mb-3">
                        <label for="org_id" class="form-label col-md-4 col-form-label text-md-end">Organización</label>
                        <div class="col-md-6">
                            <input class="form-control" readonly value="{{  $org->name }}">
                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                        </div>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </section>
@endsection
@section('js')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />

    <script>
        function handlePerfilIdChange() {
            const perfil = document.getElementById('perfil_id').value;
            const planContainer = document.getElementById('plan-container');
            const planSelect = document.getElementById('plan_id');


 if (perfil === '1') {
        planContainer.style.display = 'flex';
        planSelect.required = true;
    } else {
        planContainer.style.display = 'none';
        planSelect.required = false;
        planSelect.value = ''; // vacía si no se usa
    }


        }
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
        $(document).ready(function () {
            $('#org_id').select2({
                placeholder: 'Seleccione una organización',
                width: '100%',
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection

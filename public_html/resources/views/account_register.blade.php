@extends('layouts.app', ['active'=>'newaccount','title'=>'Registro de cuenta'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Registro de cuenta</h1>
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
            <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row mb-3">
                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="account_name" class="col-md-4 col-form-label text-md-end">Nombre de cuenta</label>

                <div class="col-md-4">
                    <input id="account_name" type="text" class="form-control" onclick="slug(this.value)" onchange="slug(this.value)" name="account_name" value="" required>
                    @error('account_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input id="sub-domain" type="text" class="form-control" name="sub-domain" required readonly value=".rederp.cl">
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="plan_id" class="col-md-4 col-form-label text-md-end">Planes disponibles</label>

                <div class="col-md-6">
                    <select class="form-select form-select mb-3" id="plan_id" name="plan_id" required>
                      <option selected>Abrir este menú de selección</option>
                      <option value="1">Plan Gratis (Libre)</option>
                      <option value="2">Plan Emprende ($ 20.000 / Mes)</option>
                      <option value="3">Plan Emprende ($ 200.000 / Anual)</option>
                      <option value="4">Plan Empresa ($ 50.000 / Mes)</option>
                      <option value="5">Plan Empresa ($ 500.000 / Anual)</option>
                    </select>
                    @error('plan_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
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
    <script>
        function slug(d) {
          var Text = d;
          Text = Text.toLowerCase();
          Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
          document.getElementById('account_name').value=Text;
        };
    </script>    
@endsection
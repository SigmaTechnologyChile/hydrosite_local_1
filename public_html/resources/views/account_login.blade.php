@extends('layouts.app', ['active'=>'loginaccount','title'=>'Acceso de cuenta'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Acceso de cuenta</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Acceso de cuenta</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
        <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Inicie sesión en su cuenta</h2>
        <p>Ingrese el nombre de dominio de su cuenta y lo conectaremos</p>
      </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <form method="POST" action="{{ route('account.access') }}">
                @csrf

                <div class="row mb-3 text-center">
                    <div class="col-md-8">
                        <input id="account_name" type="text" onclick="slug(this.value)" onchange="slug(this.value)" name="account_name"  class="form-control form-control-lg" placeholder="Nombre de cuenta" aria-label=".form-control-lg account" required>
                    </div>
                    <div class="col-md-4">
                        <input id="sub-domain" type="text" class="form-control form-control-lg" name="sub-domain" required readonly disabled value=".rederp.cl">
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" style="background: var(--swiper-theme-color);">
                            Continuar
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

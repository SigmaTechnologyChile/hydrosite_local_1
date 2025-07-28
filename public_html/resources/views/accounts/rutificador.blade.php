@extends('layouts.app', ['active'=>'accounts.rutificador','title'=>'Pago de cuenta en Linea'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Pago de cuenta en Linea</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Pago de cuenta en Linea</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Paga tus cuentas</h2>
        <p>Ingresa el RUT del titular para pagar tus servicios de Agua:</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4 justify-content-md-center">
          <div class="col-lg-6">
            <div class="php-email-form">
              <div class="row gy-4">

                <div class="col-md-12">
                  <input type="text" class="form-control" id="rut" name="rut" placeholder="RUT" required="">
                  <small>Ejemplo: 22222222-K</small>
                </div>

                <div class="col-md-12 text-center">
                  <button id="validateRut" type="submit">Ingresar</button>
                </div>

              </div>
            </div>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

@endsection
@section('js')
<script>
    jQuery('#rut').Rut({
		  on_error: function(){
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Rut incorrecto!"
            });
			//alert('Rut incorrecto');
			document.getElementById('rut').value='';
		  },
		  on_success: function(){
			var rut = document.getElementById('rut').value;
		  }
	});

    jQuery('#validateRut').on( "click", function() {
        console.log("pepe")
       var rut = document.getElementById('rut').value;
       window.location = "{{route('welcome')}}/deudas/"+rut;
    });

</script>

@endsection

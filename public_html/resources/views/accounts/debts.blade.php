@extends('layouts.app', ['active'=>'accounts.orders','title'=>'Contacto'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Deudas</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Cuenta</li>
            <li class="current">Deudas</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Selecciona el (los) documento (s) que quieres pagar:</h2>
        <p>Servicio de Agua potable</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <form id="form-entry" action="{{ route('checkout-save') }}" method="post">
        @csrf
        <input type="hidden" name="dni" value="{{$rut}}">
        <div class="row gy-4">
          <div class="col-lg-12">
                <div class="table-responsive">
                   <table class="table">
  <thead>
    <tr>
      <th scope="col">No. de servicio</th>
      <th scope="col">Sector</th>
      <th scope="col">Vencimiento</th>
      <th scope="col">Monto</th>
    </tr>
  </thead>
  <tbody>
      @if($readings->isNotEmpty())
    @foreach($readings as $reading)

          <tr>
            <th scope="row">{{ Illuminate\Support\Str::padLeft($reading->service->nro , 5, 0) }}</th>
            <td>{{ $reading->service->sector }}</td>
            <td>
              {{ $reading->period }}
              <span class="badge bg-warning text-dark">Pendiente</span>
            </td>
            <td>
              @money($reading->total)
              <input
                type="checkbox"
                class="service-checkbox"
                data-total="{{ $reading->total }}"
                value="{{ $reading->service_id }}_{{ $reading->id }}"
                name="readings[]"
              >
            </td>
          </tr>
  @endforeach
      @else
        <tr>
          <th scope="row">-</th>
          <td colspan="1">Sin deuda pendiente </td>
          <td>@money(0)  </td>
        </tr>
      @endif

  </tbody>
</table>

                  </div>
          </div>
          <div class="col-lg-12">
              <p>Atención: Los medios de pago disponibles varían dependiendo de si pagas tus servicios de forma online o presencial</p>
          </div>
          <div class="col-lg-12">
              <div class="row justify-content-end">
                <div class="col-4">
                  Total a pagar:
                </div>
                <div class="col-3">
                  <h3 id="totalAmount">$ 0</h3>
                </div>
              </div>
          </div>

          <div class="col-6">
              <div class="d-grid gap-2">
                <a href="{{ route('accounts.rutificador') }}" class="btn btn-outline-secondary btn-lg">Volver</a>
              </div>
          </div>
          <div class="col-6">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg" id="payButton" disabled>Continuar</button>
              </div>
          </div>
        </div>
        </form>
      </div>

    </section><!-- /Contact Section -->

@endsection
@section('js')
    <script>
      // Función para actualizar el total cuando se seleccionan o deseleccionan los servicios
      document.querySelectorAll('.service-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          let totalAmount = 0;
          document.querySelectorAll('.service-checkbox:checked').forEach(function(checkedBox) {
            totalAmount += parseFloat(checkedBox.getAttribute('data-total'));
          });

          // Mostrar el total
          document.getElementById('totalAmount').textContent = totalAmount.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });


          // Activar o desactivar el botón de pagar
          document.getElementById('payButton').disabled = totalAmount === 0;
        });
      });


    </script>
@endsection

@extends('layouts.nice', ['active' => 'orgs.orders.show', 'title' => 'Detalles de la Orden'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Orden de Pago</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Order Summary Section -->
    <section id="inscription" class="py-4">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-4">

                <!-- Order Summary Card -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h5 class="card-title fs-4 mb-0 text-dark">Orden de Pago</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6 position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">{{ $order->order_code }}</span>
                            </div>
                            <ul class="list-group list-group-flush mb-4">
                                @foreach($items as $item)
                                    <li class="list-group-item px-0 d-flex justify-content-between py-3 border-bottom">
                                        <div>
                                            <h6 class="mb-1 fw-semibold">{!! $item->description !!}</h6>
                                            <p class="text-muted mb-0 small">

                                            @money($item->price) x {{ $item->qty }}

                                            </p>
                                        </div>
                                        <span class="fw-semibold">

                                            @money($item->total, 'CLP')

                                        </span>
                                    </li>
                                @endforeach

                                <li class="list-group-item px-0 d-flex justify-content-between py-3 bg-light bg-opacity-50 rounded my-2">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Costo Servicio</h6>
                                        <p class="text-muted mb-0 small">@money($order->commission_rate) x {{ $order->qty }}</p>
                                    </div>
                                    <span class="text-warning fw-bold">@money($order->commission)</span>
                                </li>

                                <li class="list-group-item px-0 d-flex justify-content-between py-3 border-top">
                                    <h5 class="fw-bold mb-0">Total</h5>
                                    <h5 class="fw-bold mb-0 text-primary">@money($order->total)</h5>

                                </li>
                            </ul>

                           @if($order->payment_method_id ==1)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold" target="_blank">
                                    <i class="bi bi-credit-card me-2"></i>Generar Voucher POS
                                </a>
                            @elseif($order->payment_method_id ==2)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold" target="_blank">
                                    <i class="bi bi-receipt me-2"></i>Generar Voucher Efectivo
                                </a>
                            @elseif($order->payment_method_id ==3)
                                <a href="{{ route('orgs.voucher.create', [$org->id, $order->order_code]) }}" class="btn btn-primary btn-lg w-100 fw-semibold" target="_blank">
                                    <i class="bi bi-receipt me-2"></i>Generar Voucher Transferencia
                                </a>
                            @else
                                <div class="text-muted">Método de pago no válido</div>
                            @endif


                        </div>
                    </div>
                </div>

                <!-- Payer Details Card -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 h-100">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h5 class="card-title fs-4 mb-0 text-dark">Detalle del Pagador</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="dni" class="form-label small text-muted">RUT/DNI</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" class="form-control bg-light" id="dni" value="{{ $order->dni }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-sm-8">
                                    <label for="firstName" class="form-label small text-muted">Nombre</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control bg-light" id="firstName" value="{{ $order->name }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label small text-muted">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control bg-light" id="email" value="{{ $order->email }}" disabled readonly>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                        <div>
                                            Los datos se utilizaran para generar la orden de pago.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function copy_date() {
            document.getElementById('ticket_firstName[]').value = document.getElementById('firstName').value;
            document.getElementById('ticket_lastName[]').value = document.getElementById('lastName').value;
            document.getElementById('ticket_email[]').value = document.getElementById('email').value;
        }

        // Opcional: Animaci��n para destacar el total
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const totalElement = document.querySelector('.list-group-item:last-child .text-primary');
                totalElement.classList.add('animate__animated', 'animate__pulse');
            }, 500);
        });
    </script>
@endsection


@extends('layouts.app',['active'=>'orders.summary','title'=>'Resumen de la pago'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Resumen de la pago</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Cuenta</li>
            <li class="current">Resumen de la pago</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    <section id="inscription" class="contact section transparent-background">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="card">
                <div class="card-header">
                    <h4>Detalle de Pago de servicio <span class="badge bg-secondary float-end">Orden de Pago: {{ $order->order_code }} </span></h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-4">
                          <label for="firstName" class="form-label">RUT/DNI</label>
                          <div class="form-control">{{$order->dni}}</div>
                        </div>

                        <div class="col-sm-8">
                          <label for="firstName" class="form-label">Nombres</label>
                          <div class="form-control">{{$order->name}}</div>
                        </div>

                        <div class="col-8">
                          <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                          <div class="form-control">{{$order->email}}</div>
                        </div>

                        <div class="col-4">
                          <label for="email" class="form-label">Teléfono <span class="text-muted"></span></label>
                          <div class="form-control">{{$order->phone}}</div>
                        </div>
                    </div>
                    <div class="col-12 m-2">
                        <h5>Servicios pagados</h5>
                        @foreach($orderitems as $item)
                        <p>{!!$item->description!!}</p>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <p>
                        Estado: {!!($order->payment_status==1? '<span class="badge bg-success">Pagado</span>' : ($order->payment_status==2? '<span class="badge bg-danger">Rechazado</span>' : '<span class="badge bg-warning text-dark">Pendiente de Pago</span>'))!!}
                    </p>
                    <p class="float-start">Comisión: @money($order->commission)</p>
                    <h5 class="float-end">Total: @money($order->total)</h5>
                </div>
            </div>
    </div>
    </section>
@endsection

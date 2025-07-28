@extends('layouts.app',['active'=>'accounts.checkout', 'title'=>'CheckOut'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Detalle de pago</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Cuenta</li>
            <li class="current">Detalle de pago</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    
    <section id="inscription" class="contact section transparent-background">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-5">
        <div class="col-md-5 col-lg-6 order-md-last" >
            <h5 class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-secondary">Orden de Pago: </span>
              <span class="badge bg-secondary rounded-pill">{{$order->order_code}}</span>
            </h5>
            <ul class="list-group mb-3" style="color: black;">
              @foreach($items as $item)  
              <li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                  <h6 class="my-0" style="color: black;">{!!$item->description!!}</h6>
                  <small class="text-muted">
                    @money($item->price) x {{$item->qty}}
                  </small>
                </div>
                <span class="text-muted">@money($item->total)</span>                
              </li>
              @endforeach

              <li class="list-group-item d-flex justify-content-between bg-light">
                <div class="text-warning">
                  <h6 class="my-0" style="color: black;">Costo Servicio</h6>
                  <small>@money($order->commission_rate) x {{$order->qty}}</small>
                </div>
                <span class="text-warning">@money($order->commission) </span>
              </li>

              <li class="list-group-item d-flex justify-content-between">
                <span>Total</span>
                <strong>@money($order->total)</strong>
              </li>
            </ul>
            <hr class="my-4">
            <div class="col-12">
                <a href="{{url('/payment/'.$order->order_code)}}" class="w-100 btn btn-success btn-lg">Ir al Pago</a>
            </div>
          </div>
        <div class="col-md-7 col-lg-6">
            <h4 class="mb-3">Detalle del Pagador</h4>

              <div class="row g-3">
                  
                <div class="col-sm-2">
                  <label for="firstName" class="form-label">RUT/DNI</label>
                  <input type="text" class="form-control" id="dni" value="{{$order->dni}}" disabled>
                  <div class="invalid-feedback">
                    Valid first name is required.
                  </div>
                </div>
                
                <div class="col-sm-10">
                  <label for="firstName" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="firstName" value="{{$order->name}}" disabled>
                  <div class="invalid-feedback">
                    Valid first name is required.
                  </div>
                </div>

                <div class="col-12">
                  <label for="email" class="form-label">Email <span class="text-muted"></span></label>
                  <input type="email" class="form-control" id="email" value="{{$order->email}}" disabled>
                  <div class="invalid-feedback">
                    Please enter a valid email address for shipping updates.
                  </div>
                </div>
          </div>
        </div>
    </div>
        </div>
    </section>
@endsection
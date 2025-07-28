@extends('layouts.nice', ['active'=>'subscriptions','title'=>'Suscripciones'])

@section('content')
    <div class="pagetitle">
      <h1>Suscripciones</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Suscripciones</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Suscripciones</h5>
              <p>Lista de Suscripciones</p>
              <!-- Small tables -->
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Paquete</th>
                    <th scope="col">Organización</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha de termino</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $subscription)
                  <tr>
                    <th scope="row">{{$subscription->id}}</th>
                    <td>{{$subscription->package->name}}</td>
                    <td>{{$subscription->org->razon_social}}</td>
                    <td>@if($subscription->active) <span class="badge bg-success">Activado</span> @else <span class="badge bg-danger">Activado</span> @endif</td>
                    <td> {{$subscription->updated_at}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End small tables -->
            
            </div>
            <div class="card-footer">
                {!! $subscriptions->render('pagination::bootstrap-4') !!}
            </div>
          </div>
    </section>
@endsection
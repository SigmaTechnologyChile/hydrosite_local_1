@extends('layouts.nice', ['active'=>'packages','title'=>'Suscripciones'])

@section('content')
    <div class="pagetitle">
      <h1>Planes</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Planes</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Planes</h5>
              <p>Lista de Planes</p>
              <!-- Small tables -->
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Precio</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                  <tr>
                    <th scope="row">{{$package->id}}</th>
                    <td>{{$package->name}}</td>
                    <td><i class="bx bxs-usd"></i> {{$package->price}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End small tables -->
            
            </div>
            <div class="card-footer">
                {!! $packages->render('pagination::bootstrap-4') !!}
            </div>
          </div>
    </section>
@endsection
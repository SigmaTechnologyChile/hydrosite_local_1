@extends('layouts.app', ['active'=>'plans'])

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
              <h5 class="card-title">Planes flexibles</h5>
              <p>Lista Planes</p>
              <!-- Small tables -->
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Planes</th>
                    <th scope="col" class="text-end">Precios</th>
                    <th scope="col" class="text-end">Horas</th>
                    <th scope="col" class="text-end">Estados</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                  <tr>
                    <th scope="row">{{$plan->id}}</th>
                    <td><a href="{{route('plans.edit',[$plan->id])}}" class="text-primary">{{$plan->name}}</a></td>
                    <td class="text-end">@money($plan->price)</td>
                    <td class="fw-bold text-end">{{$plan->hours}}</td>
                    <td class="green text-end">@if($plan->active)<span class="badge bg-success">Activado</span> @else <span class="badge bg-danger">Desactivado</span>@endif</td>
                    
                  </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- End small tables -->

            </div>
          </div>
    </section>


@endsection

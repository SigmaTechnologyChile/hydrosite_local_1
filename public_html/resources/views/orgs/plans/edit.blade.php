@extends('layouts.app', ['active'=>'plans'])

@section('content')
    <div class="pagetitle">
      <h1>Editar Plan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item">Planes</li>
          <li class="breadcrumb-item active">Editar Plan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Editar plan: # {{$plan->id}}</h5>
                <form class="row g-3">
                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" placeholder="Nombre Plan" value="{{$plan->name}}">
                    <label for="floatingName">Nombre Plan</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="floatingPrice" placeholder="Precio" value="{{$plan->price}}">
                    <label for="floatingPrice">Precio</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="floatingHours" placeholder="Horas" value="{{$plan->hours}}">
                    <label for="floatingHours">Horas</label>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" @if($plan->active)checked=""@endif>
                          <label class="form-check-label" for="flexSwitchCheckChecked">Mostrar registro</label>
                    </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
              </form>

            </div>
          </div>
    </section>


@endsection

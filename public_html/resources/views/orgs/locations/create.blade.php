@extends('layouts.nice', ['active'=>'orgs.locations.create','title'=>'Crear Sector'])

@section('content')
<div class="pagetitle">
  <h1>{{$org->name}}</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
      <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
      <li class="breadcrumb-item"><a href="{{route('orgs.locations.index',$org->id)}}">Sectores</a></li>
      <li class="breadcrumb-item active">Crear Sector</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title"><i class="bi bi-plus-circle-fill me-2"></i>Crear nuevo Sector</h5>

      <!-- Formulario -->
      <form class="row g-3" action="{{ route('orgs.locations.store', $org->id) }}" method="POST">
        @csrf

        <!-- Nombre del sector -->
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Sector" required>
            <label for="name"><i class="bi bi-house-door me-2"></i>Nombre del Sector</label>
          </div>
        </div>

        <!-- Ciudad (Comuna) -->
        <div class="col-md-6">
          <div class="form-floating">
            <select name="city_id" id="city_id" class="form-select" required>
              <option value="" disabled selected>Seleccione una comuna</option>
              @foreach ($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name_city }}</option>
              @endforeach
            </select>
            <label for="city_id"><i class="bi bi-geo-fill me-2"></i>Comuna</label>
          </div>
        </div>

        <!-- Botones -->
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill me-2"></i>Crear</button>
          <a href="{{ route('orgs.locations.index', $org->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cancelar</a>
        </div>
      </form><!-- End Formulario -->

    </div>
  </div>
</section>
@endsection



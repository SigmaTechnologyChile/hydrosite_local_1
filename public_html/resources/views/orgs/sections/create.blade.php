@extends('layouts.nice', ['active'=>'orgs.sections.create','title'=>'Crear Tramo'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.sections.index',$org->id)}}">Tramos</a></li>
          <li class="breadcrumb-item active">Crear Tramo</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Crear nuevo Tramo</h5>

              <!-- Floating Labels Form -->
          <form method="POST" action="{{ route('orgs.sections.storeTier', $org->id) }}">
    @csrf
    <div class="mb-3">
        <label for="tier_name" class="form-label">Nombre del tramo</label>
        <input type="text" class="form-control" name="tier_name" id="tier_name" required>
    </div>
    <div class="mb-3">
        <label for="range_from" class="form-label">Desde</label>
        <input type="number" class="form-control" name="range_from" id="range_from" required>
    </div>
    <div class="mb-3">
        <label for="range_to" class="form-label">Hasta</label>
        <input type="number" class="form-control" name="range_to" id="range_to" required>
    </div>
    <div class="mb-3">
        <label for="value" class="form-label">Valor</label>
        <input type="number" class="form-control" name="value" id="value" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear Tramo</button>
</form>
<!-- End floating Labels Form -->

            </div>
          </div>
    </section>

@endsection

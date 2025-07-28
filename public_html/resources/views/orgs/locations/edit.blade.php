@extends('layouts.nice', ['active'=>'orgs.locations.index', 'title'=>'Editar Sector'])

@section('content')
<div class="pagetitle">
    <h1>{{$org->name}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.locations.index', $org->id)}}">Sectores</a></li>
            <li class="breadcrumb-item active">Editar Sector</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-pencil-fill me-2"></i>Editar Sector: {{$location->name}}</h5>

            <form class="row g-3" action="{{ route('orgs.locations.update', [$org->id, $location->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre del Sector -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre del Sector" value="{{ old('name', $location->name) }}" required>
                        <label for="name"><i class="bi bi-house-door me-2"></i>Nombre del Sector</label>
                    </div>
                </div>

                <!-- Región (preestablecida) -->
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" value="Maule" readonly>
                        <label><i class="bi bi-geo-alt-fill me-2"></i>Región</label>
                        <input type="hidden" name="state_id" value="{{ $location->state_id }}">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="city_id" id="city_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione una comuna</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ $location->city_id == $city->id ? 'selected' : '' }}>
                                    {{ $city->name_city }}
                                </option>
                            @endforeach
                        </select>
                        <label for="city_id"><i class="bi bi-geo-fill me-2"></i>Comuna</label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill me-2"></i>Actualizar</button>
                    <a href="{{ route('orgs.locations.index', $org->id) }}" class="btn btn-secondary"><i class="bi bi-arrow-left-circle-fill me-2"></i>Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</section>
@endsection



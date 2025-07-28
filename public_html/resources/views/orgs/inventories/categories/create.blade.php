@extends('layouts.nice', ['active' => 'orgs.categories.create', 'title' => 'Categorías'])

@section('content')
    <div class="pagetitle">
        <h1>{{ $org->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
                <li class="breadcrumb-item active">Categorías</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

   
    <div class="card">
        <form action="{{ route('orgs.categories.store', $org->id) }}" method="POST">
        <div class="card-body">
            <h2 class="card-title d-flex justify-content-between align-items-center">
                Crear Nueva Categoría
            </h2>
    
          
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
           
            
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label"><i class="fas fa-tag"></i> Nombre de la Categoría</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
    
                <div class="mb-3">
                    <label for="active" class="form-label"><i class="fas fa-check-circle"></i> Estado de la Categoría</label>
                    <select class="form-select" id="active" name="active" required>
                        <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>
                            <i class="fas fa-check-circle"></i> Activo
                        </option>
                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>
                            <i class="fas fa-times-circle"></i> Inactivo
                        </option>
                    </select>
                </div>
            
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Crear Categoría</button>
                <a href="{{ route('orgs.categories.index', $org->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver al Listado de Categorías</a>
            </div>
        </div>
        </form>
    </div><!-- End Card -->

@endsection

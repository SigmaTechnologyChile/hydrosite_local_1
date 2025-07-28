@extends('layouts.nice', ['active'=>'orgs.inventories.create','title'=>'Crear Inventario'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.inventories.index',$org->id)}}">Inventarios</a></li>
          <li class="breadcrumb-item active">Crear Inventario</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Editar inventario: #{{ $inventary->id }}</h5>

                <form class="row g-3" method="POST" action="{{ route('orgs.inventories.update', [$org->id, $inventary->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="description" placeholder="Descripción"
                                value="{{ old('description', $inventary->description) }}" required>
                            <label for="description">Descripción</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="qxt" placeholder="Cantidad"
                                value="{{ old('qxt', $inventary->qxt) }}" required>
                            <label for="qxt">Cantidad</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="order_date" placeholder="Fecha de orden"
                                value="{{ old('order_date', $inventary->order_date) }}" required>
                            <label for="order_date">Fecha de orden</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" name="amount" placeholder="Monto"
                                value="{{ old('amount', $inventary->amount) }}" required>
                            <label for="amount">Monto</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="status" placeholder="Estado"
                                value="{{ old('status', $inventary->status) }}" required>
                            <label for="status">Estado</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="location" placeholder="Ubicación"
                                value="{{ old('location', $inventary->location) }}">
                            <label for="location">Ubicación</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="responsible" placeholder="Responsable"
                                value="{{ old('responsible', $inventary->responsible) }}">
                            <label for="responsible">Responsable</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="low_date" placeholder="Fecha de baja"
                                value="{{ old('low_date', $inventary->low_date) }}">
                            <label for="low_date">Fecha de baja</label>
                        </div>
                    </div>
                    <!-- Agregamos el campo de selección de categoría -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $inventary->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="category_id">Categoría</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-floating">
                            <textarea class="form-control" name="observations" placeholder="Observaciones"
                                style="height: 100px">{{ old('observations', $inventary->observations) }}</textarea>
                            <label for="observations">Observaciones</label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('orgs.inventories.index', $org->id) }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection

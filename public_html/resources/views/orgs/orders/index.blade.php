@extends('layouts.nice', ['active'=>'orgs.inventories.index','title'=>'Inventarios'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Inventarios</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET">
                    <div class="row align-items-end">
                        <!-- Año -->
                        <div class="col-md-2">
                            <label class="form-label">Año:</label>
                            <select name="year" class="form-select">
                                @for ($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
    
                        <!-- Mes -->
                        <div class="col-md-2">
                            <label class="form-label">Mes:</label>
                            <select name="month" class="form-select">
                                @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ request('month', date('m')) == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buscador -->
                        <div class="col-md-3">
                            <label class="form-label">Buscar:</label>
                            <input type="text" name="search" class="form-control" placeholder="Buscar por descripción, responsable">
                        </div>
    
                        <!-- Botón Filtrar -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                        <div class="col-md-3 d-flex justify-content-md-end align-items-center mt-3 mt-md-0">
                            <a href="{{route('orgs.inventories.create',$org->id)}}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn me-2">
                                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo
                            </a>
                            <a href="" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm pulse-btn"><!-- route -->
                                <i class="bi bi-box-arrow-right me-2"></i>Exportar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <section class="section dashboard">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">N° Registro</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Fecha Último Pedido</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Ubicación</th>
                                <th scope="col">Responsable</th>
                                <th scope="col">Fecha Traslado/Baja</th>
                                <th scope="col">Observaciones</th>
                                <th scope="col" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventories as $inventory)
                            <tr data-id="{{ $inventory->id }}">
                                <td>{{ $inventory->id }}</td>
                                <td>{{ $inventory->qxt }}</td>
                                <td><span class="text-warning fw-bold">{{ $inventory->order_date }}</span></td>
                                <td>{{ $inventory->description }}</td>
                                <td><span class="text-success fw-bold">@money($inventory->amount)</span></td>
                                <td><span class="text-primary fw-bold">{{ $inventory->status }}</span></td>
                                <td>{{ $inventory->location }}</td>
                                <td>{{ $inventory->responsible }}</td>
                                <td>{{ $inventory->low_date }}</td>
                                <td>{{ $inventory->observations }}</td>
                                <td class="text-center">
                                    <a href="{{ route('orgs.inventories.edit', [$org->id, $inventory->id]) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-pencil-square me-1"></i> Editar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    {!! $inventories->render('pagination::bootstrap-4') !!}
@endsection
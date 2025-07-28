@extends('layouts.nice', ['active'=>'orgs.inventories.index','title'=>'Inventarios'])

@section('content')
    <div class="pagetitle">
        <h1>{{ $org->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.index') }}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orgs.dashboard', $org->id) }}">{{ $org->name }}</a></li>
                <li class="breadcrumb-item active">Inventarios</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <!-- Filtros y acciones -->
        <div class="card top-selling overflow-auto mb-4">
            <div class="card-body pt-2">
                <form method="GET">
                    <div class="row align-items-end">
                        <!-- Fecha Desde -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Fecha Desde</label>
                            <input type="date" name="start_date" class="form-control rounded-3" value="{{ request('start_date', date('Y-m-01')) }}">
                        </div>
                        
                        <!-- Fecha Hasta -->
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Fecha Hasta</label>
                            <input type="date" name="end_date" class="form-control rounded-3" value="{{ request('end_date', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Buscar:</label>
                            <input type="text" name="search" class="form-control" placeholder="Descripción, responsable" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-auto d-flex align-items-center">
                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
                            <i class="bi bi-funnel-fill me-2"></i>Filtrar</button>
                        </div>
                        <div class="col-md-3 d-flex justify-content-md-end mt-3 mt-md-0 gap-2">
    <a href="{{ route('orgs.inventories.create', $org->id) }}" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
        <i class="bi bi-plus-circle-fill"></i> Nuevo
    </a>
    <a href="{{ route('orgs.inventories.export', $org->id) }}" class="btn btn-primary pulse-btn p-1 px-2 rounded-2">
        <i class="bi bi-box-arrow-up-right"></i> Exportar
    </a>
</div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de Inventarios -->
        <div class="card top-selling overflow-auto">
            <div class="card-body">
                <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                                <th>N° Registro</th>
                                <th>Cantidad</th>
                                <th>Último Pedido</th>
                                <th>Descripción</th>
                                <th>Valor</th>
                                <th>Estado</th>
                                <th>Ubicación</th>
                                <th>Responsable</th>
                                <th>Traslado/Baja</th>
                                <th>Observaciones</th>
                                <th>Categoría</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQuantity = 0;
                                $totalAmount = 0;
                            @endphp
                            @foreach($inventories as $inv)
                                <tr>
                                    <td>{{ $inv->id }}</td>
                                    <td>{{ $inv->qxt }}</td>
                                    <td><span class="text-warning fw-bold">{{ $inv->order_date }}</span></td>
                                    <td>{{ $inv->description }}</td>
                                    <td><span class="text-success fw-bold">@money($inv->amount)</span></td>
                                    <td><span class="text-primary fw-bold">{{ $inv->status }}</span></td>
                                    <td>{{ $inv->location }}</td>
                                    <td>{{ $inv->responsible }}</td>
                                    <td>{{ $inv->low_date }}</td>
                                    <td>{{ $inv->observations }}</td>
                                    <td>{{ $inv->category->name ?? 'Sin Categoría' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orgs.inventories.edit', [$org->id, $inv->id]) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $totalQuantity += $inv->qxt;
                                    $totalAmount   += $inv->amount;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php
            // Construir resumen por categoría
            $categorySummary = collect();
            foreach($inventories as $inv) {
                $cat = $inv->category->name ?? 'Sin Categoría';
                if (! $categorySummary->has($cat)) {
                    $categorySummary->put($cat, ['quantity'=>0,'amount'=>0]);
                }
                $s = $categorySummary->get($cat);
                $s['quantity'] += $inv->qxt;
                $s['amount']   += $inv->amount;
                $categorySummary->put($cat, $s);
            }
        @endphp

        <div class="card mt-4 shadow">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0">📦 Resumen Totalizador del Inventario</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <!-- Totales Generales -->
                    <div class="col-md-4">
                        <div class="border rounded p-4 text-center bg-light h-100">
                            <h6 class="text-secondary mb-3">Totales Generales</h6>
                            <div class="mb-2">
                                <span class="fw-bold text-dark">Total Cantidad</span>
                                <div class="display-6 text-info">{{ $totalQuantity }}</div>
                            </div>
                            <div>
                                <span class="fw-bold text-dark">Total Valor</span>
                                <div class="display-6 text-success">@money($totalAmount)</div>
                            </div>
                        </div>
                    </div>
        
                    <!-- Resumen por Categoría -->
                    <div class="col-md-4">
                        <div class="border rounded p-4 bg-light h-100">
                            <h6 class="text-secondary mb-3">Resumen por Categoría</h6>
                            @foreach($categorySummary as $catName => $sum)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <span class="fw-semibold text-dark">{{ $catName }}</span><br>
                                        <small class="text-muted">Cantidad: {{ $sum['quantity'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary fs-6 px-3 py-2 mb-1">
                                            {{ $totalAmount > 0 
                                                ? number_format(($sum['amount']/$totalAmount)*100,1).'%'
                                                : '0%' }}
                                        </span><br>
                                        <span class="fw-bold text-success">@money($sum['amount'])</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
        
                    <!-- Estadísticas Generales -->
                    <div class="col-md-4">
                        <div class="border rounded p-4 bg-light h-100">
                            <h6 class="text-secondary mb-3">Estadísticas Generales</h6>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-dark">🗃 Artículos registrados:</span>
                                <span class="badge bg-dark fs-6">{{ $inventories->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-dark">📦 Unidades totales:</span>
                                <span class="badge bg-info fs-6 text-dark">{{ $totalQuantity }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark">📁 Categorías distintas:</span>
                                <span class="badge bg-secondary fs-6">{{ $categorySummary->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! $inventories->render('pagination::bootstrap-4') !!}
    </section>
@endsection

@push('styles')
<style>
    .list-group-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .badge.fs-5 {
        font-size: 1.25rem !important;
    }
    .card-header.bg-primary {
        background-color: #007bff !important;
    }
</style>
@endpush
@extends('layouts.nice', ['active' => 'orgs.payments.index', 'title' => 'Ingresar un Pago'])
@push('styles')
<style>
    .enhanced-btn {
        position: relative;
        overflow: hidden;
        border: none;
        transition: all 0.4s ease;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 30%, #0a58ca 70%, #084298 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        color: white;
        font-weight: 500;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    }

    .enhanced-btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.15) 1px, transparent 1px),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.7;
    }

    .enhanced-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15), 0 6px 6px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 20%, #0a58ca 60%, #084298 100%);
    }

    .enhanced-btn:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }

    /* Estilos adicionales para la tabla */
    .table-responsive {
        overflow-x: auto;
    }
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>{{$org->name}}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
            <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
            <li class="breadcrumb-item active">Pagos</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="card top-selling overflow-auto">
        <div class="card-body pt-2">
            <form method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- Sectores -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold d-block text-center">Sectores</label>
                        <select name="sector" class="form-select rounded-3 py-2">
                            @if($locations->isNotEmpty())
                                <option value="">Todos</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No hay sectores disponibles</option>
                            @endif
                        </select>
                    </div>

                    <!-- Buscador -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold d-block text-center">Buscar</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-white ps-3">
                                <i class="bi bi-search fs-5 text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control rounded-end-3 py-2"
                                placeholder="Buscar por nombre, apellido, Rut"
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <!-- Botón Filtrar -->
                    <div class="col-md-auto d-flex align-items-center">
                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn">
                            <i class="bi bi-funnel-fill me-2"></i>Filtrar
                        </button>
                    </div>
@php
    $disabled = true; // o alguna condición dinámica
@endphp
                    <!-- Botón Exportar -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <a href="{{ $disabled ? '#' : route('orgs.readings.export', array_merge(['id' => $org->id], request()->except('page'))) }}"
   class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn {{ $disabled ? 'disabled' : '' }}"
   aria-disabled="{{ $disabled ? 'true' : 'false' }}">
    <i class="bi bi-box-arrow-right me-2"></i>Exportar
</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">N° Cliente</th>
                            <th scope="col" class="text-center">RUT/RUN</th>
                            <th scope="col" class="text-center">Nombres</th>
                            <th scope="col" class="text-center">Apellidos</th>
                            <th scope="col" class="text-center">Servicios</th>
                            <th scope="col" class="text-center">Estado</th>
                            <th scope="col" class="text-center">TOTAL ($)</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr data-id="{{ $member->id }}">
                            <td class="text-center">{{ $member->id }}</td>
                            <td class="text-center">
                                <a href="{{route('orgs.members.edit', [$org->id, $member->id])}}">{{ $member->rut }}</a>
                            </td>
                            <td class="text-center">{{ $member->first_name }}</td>
                            <td class="text-center">{{ $member->last_name }}</td>
                            <td class="text-center">{{ $member->qrx_serv }}</td>
                            <td class="text-center">
                                @if($member->total_amount == 0)
                                    <span class="badge bg-success">Sin deudas</span>
                                @else
                                    <span class="badge bg-warning">Pendiente de pago</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-success fw-bold">@money($member->total_amount)</span>
                            </td>
                            <td class="text-center">
                                <a href="{{route('orgs.payments.services', [$org->id, $member->rut])}}"
                                    class="btn btn-sm btn-success">Ver servicios</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {!! $members->render('pagination::bootstrap-4') !!}
            </div>
        </div>
    </div>
</section>

<!-- Modal para Nuevo Miembro -->
<div class="modal fade" id="newMemberModal" tabindex="-1" aria-labelledby="newMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newMemberModalLabel">Nuevo Inventario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newMemberForm" action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción del Artículo</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="qxt" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="qxt" name="qxt" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="order_date" class="form-label">Fecha último Pedido</label>
                                <input type="date" class="form-control" id="order_date" name="order_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Valor</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Disponible">Disponible</option>
                                    <option value="En uso">En uso</option>
                                    <option value="En mantenimiento">En mantenimiento</option>
                                    <option value="Dado de baja">Dado de baja</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="location" class="form-label">Ubicación</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="responsible" class="form-label">Nombre del responsable</label>
                                <input type="text" class="form-control" id="responsible" name="responsible">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="low_date" class="form-label">Fecha de Traslado o Baja</label>
                                <input type="date" class="form-control" id="low_date" name="low_date">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciones (Opcional)</label>
                                <textarea class="form-control" id="observations" name="observations" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Añadir Inventario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición de Lectura -->
<div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReadingForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_reading" class="form-label">Lectura Actual</label>
                        <input type="number" class="form-control" id="current_reading" name="current_reading" required>
                    </div>
                    <input type="hidden" id="reading_id" name="reading_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="editReadingForm" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

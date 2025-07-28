@extends('layouts.nice', ['active'=>'orgs.readings.index','title'=>'Ingreso de Lecturas'])

@push('css')
<style>
    left: 0;
    width: 100%;
    height: 100%;
    background-image:
        radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.15) 1px, transparent 1px),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 20px 20px;
    opacity: 0.7;

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

    /* Estilos para los inputs y selects */
    .form-select, .form-control {
        height: 38px;
    }

    .input-group-text {
        height: 38px;
    }

    /* Estilos para la tabla */
    .table-responsive {
        overflow-x: auto;
    }

    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    /* Estilos para el modal de carga masiva */
    .file-upload {
        border: 2px dashed #4a6491;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        background-color: rgba(74, 100, 145, 0.05);
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-upload:hover {
        background-color: rgba(74, 100, 145, 0.1);
        transform: scale(1.01);
    }

    .file-upload i {
        font-size: 3rem;
        color: #4a6491;
        margin-bottom: 15px;
    }

    .preview-table {
        max-height: 300px;
        overflow-y: auto;
    }

    .preview-table th {
        background-color: #2c3e50;
        color: white;
        position: sticky;
        top: 0;
    }

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(90deg, #2c3e50, #4a6491);
        color: white;
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer {
        background-color: #f8f9fa;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }

    .step-indicator::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #dee2e6;
        z-index: 0;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .step.active .step-number {
        background-color: #4a6491;
        color: white;
    }

    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
    }

    .step.active .step-label {
        color: #2c3e50;
        font-weight: 600;
    }

    .success-icon {
        font-size: 5rem;
        color: #28a745;
        margin: 20px 0;
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

          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>

          <li class="breadcrumb-item active">Lecturas</li>

        </ol>

      </nav>

    </div><!-- End Page Title -->



    <section class="section dashboard">

    <div class="card top-selling overflow-auto">

        <div class="card-body pt-2">

            <form method="GET" id="filterForm" action="{{ route('orgs.readings.index', $org->id) }}">
                <div class="row g-3 align-items-end">
                    <!-- Periodo Actual al margen izquierdo -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold d-block">Periodo Actual</label>
                        <div class="form-control text-center bg-light fw-bold" style="height:38px;">
                            {{ str_pad(date('m'), 2, '0', STR_PAD_LEFT) }}-{{ date('Y') }}
                        </div>
                    </div>
                    <!-- Selector de Sector -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Sector</label>
                        <select class="form-select" id="sectorSelect" name="sector">
                            <option value="">Todos</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{ request('sector') == $sector->id ? 'selected' : '' }}>{{ $sector->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Buscador por nombre, apellido o RUT -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Buscar</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Nombre, Apellido o RUT">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                    </div>
                    <!-- Botón Exportar -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <a href="#" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn disabled" tabindex="-1" aria-disabled="true">
                            <i class="bi bi-box-arrow-right me-2"></i>Exportar
                        </a>
                    </div>
                    <!-- Botón Ingreso Masivo -->
                    <div class="col-md-auto d-flex align-items-center ms-2">
                        <button type="button" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-file-upload me-2"></i>Ingreso Masivo
                        </button>
                    </div>
                </div>

            </form>



            <div class="card-body">

                <div class="table-responsive mt-3">

                <table class="table table-bordered table-hover">

                    <thead class="table-light">

                        <tr>

                                <th scope="col" class="text-center">ID Lec.</th>

                                <th scope="col" class="text-center">Sector</th>

                                <th scope="col" class="text-center">Nro Serv.</th>

                                <th scope="col" class="text-center">RUT/RUN</th>

                                <th scope="col" class="text-center">Nombre/Apellido</th>

                                <th scope="col" class="text-center">Fecha</th>

                                <th scope="col" class="text-center">Lect. Anterior</th>

                                <th scope="col" class="text-center">Lect. Actual</th>

                                <th scope="col" class="text-center">Consumo <br/><small>(M³)</small></th>

                                <th scope="col" class="text-center">Total<br/><small>$</small></th>

                        </thead>
                        <tbody>
                            @foreach($readings as $reading)
                            <tr data-id="{{ $reading->id }}">
                                <td class="text-center">{{ $reading->id }}</td>
                                <td class="text-center">{{ $reading->location_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ Illuminate\Support\Str::padLeft($reading->nro,5,0) }}</td>
                                <td class="text-center"><a href="{{route('orgs.members.edit',[$org->id,$reading->member_id])}}">{{ $reading->rut }}</a></td>
                                <td class="text-center">{{ $reading->full_name ?? 'N/A' }}</td>
                                <td class="text-center" style="white-space: nowrap;">{{ $reading->period }}</td>
                                <td class="text-center"><span class="text-warning fw-bold">{{ $reading->previous_reading }}</span></td>
                                <td class="text-center">
                                    @if($reading->cm3 == 0)
                                    <form method="POST" action="{{ route('orgs.readings.current_reading_update', $org->id) }}"
                                        class="current-reading-form" data-reading-id="{{ $reading->id }}">
                                        @csrf
                                        <input type="hidden" name="reading_id" value="{{ $reading->id }}">
                                        <input type="number"
                                            name="current_reading"
                                            class="form-control form-control-sm current-reading-input mx-auto"
                                            value="{{ $reading->current_reading}}"
                                            style="width: 80px; display: block;"
                                            data-row-index="{{ $loop->index }}">
                                    </form>
                                    @else
                                    <input disabled readonly type="number"
                                        name="current_reading"
                                        class="form-control form-control-sm current-reading-input read-only mx-auto"
                                        value="{{ $reading->current_reading }}"
                                        style="width: 80px; display: block;"
                                        data-row-index="{{ $loop->index }}">
                                    @endif
                                </td>
                                <td class="text-center"><span class="text-primary fw-bold">{{ $reading->cm3 }}</span></td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <span class="text-danger fw-bold">= @money($reading->total)</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <!-- Botón DTE -->
                                        @if($reading->invoice_type == "boleta")
                                            <a href="{{ route('orgs.readings.boleta', [$reading->org_id,  $reading->id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Boleta">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @else
                                            <a href="{{ route('orgs.readings.factura', [$reading->org_id, $reading->id]) }}" class="btn btn-dark btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Ver Factura">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                        @endif
                                        <!-- Botón Editar -->
                                        <button class="btn btn-sm btn-success edit-btn"
                                                data-bs-id="{{ $reading->id }}"
                                                data-bs-current="{{ $reading->current_reading }}"
                                                data-bs-previous="{{ $reading->previous_reading }}"
                                                data-bs-corte_reposicion="{{ $reading->corte_reposicion }}"
                                                data-bs-other="{{ $reading->other }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReadingModal"
                                                data-bs-placement="top"
                                                title="Editar">
                                            <i class="ri-edit-box-line"></i> Editar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            {{-- Miembros sin lectura registrada --}}
                            @if(isset($miembrosExtra) && $miembrosExtra->count())
                                @foreach($miembrosExtra as $miembro)
                                <tr class="table-warning">
                                    <td class="text-center">-</td>
                                    <td class="text-center">{{ $miembro->locality_id ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $miembro->service_id ?? 'N/A' }}</td>
                                    <td class="text-center"><a href="{{route('orgs.members.edit',[$org->id,$miembro->id])}}">{{ $miembro->rut }}</a></td>
                                    <td class="text-center">{{ $miembro->full_name ?? 'N/A' }}</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-end">
                                            <!-- Botón DTE deshabilitado -->
                                            <a href="#" class="btn btn-dark btn-sm me-2 disabled" tabindex="-1" aria-disabled="true" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="Sin lectura">
                                                <i class="ri-file-2-line"></i> DTE
                                            </a>
                                            <!-- Botón Editar que abre el modal -->
                                            <button class="btn btn-sm btn-success edit-btn"
                                                    data-bs-id="-"
                                                    data-bs-current="-"
                                                    data-bs-previous="-"
                                                    data-bs-corte_reposicion="0"
                                                    data-bs-other="0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editReadingModal"
                                                    data-bs-placement="top"
                                                    title="Editar">
                                                <i class="ri-edit-box-line"></i> Editar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>

                    </table>

                </div>

            </div>

            <div class="card-footer">{!! $readings->render('pagination::bootstrap-4') !!}</div>

        </div>

    </section>



    <!-- Modal de Edición de Lectura -->

    <div class="modal fade" id="editReadingModal" tabindex="-1" aria-labelledby="editReadingModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="editReadingModalLabel">Editar Lectura N° <span id="idReadingModal"></span></h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

                    <form method="POST" action="{{ route('orgs.readings.update', $org->id) }}" id="editReadingForm">

                        @csrf

                        @method('POST')



                        <!-- Input para el ID de la lectura -->

                        <input type="hidden" id="reading_id" name="reading_id">



                        <div class="mb-3">

                            <label for="previous_reading" class="form-label">Lectura Anterior</label>

                            <input type="number" class="form-control" id="previous_reading" name="previous_reading" readonly>

                        </div>



                        <div class="mb-3">

                            <label for="current_reading" class="form-label">Lectura Actual</label>

                            <input type="number" class="form-control" id="current_reading" name="current_reading" required>

                        </div>
                        <div class="mb-3">

                            <div class="form-check mb-2">

                                <input

                                    class="form-check-input"

                                    type="checkbox"

                                    id="cargo_corte_reposicion"

                                    name="cargo_corte_reposicion"

                                  >

                                <label class="form-check-label" for="cargo_corte_reposicion">

                                    Cargo Corte Reposición

                                </label>

                            </div>

                        </div>
                        <div class="mb-3">

                            <label for="other" class="form-label">Otros Cargos</label>

                            <input type="number" class="form-control" id="other" name="other" >

                        </div>



                        <div class="mb-3">

                            <button type="submit" class="btn btn-success enhanced-btn">Guardar Cambios</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>



    <!-- Modal para carga masiva -->

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="uploadModalLabel"><i class="fas fa-file-excel me-2"></i>Ingreso Masivo de Lecturas</h5>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <div class="modal-body">

        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(90deg, #4a6491 0%, #2c3e50 100%); color: #fff;">
                    <h4 class="modal-title fw-bold d-flex align-items-center gap-2" id="uploadModalLabel">
                        <i class="fas fa-file-import fa-lg"></i> Ingreso Masivo de Lecturas
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4 pt-3">
                    <div class="step-indicator mb-4 px-2 py-3 rounded-3 bg-white shadow-sm d-flex flex-row align-items-center justify-content-center" style="position:relative;">
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#0d6efd;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(13,110,253,.15);font-weight:700;">1</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#0d6efd;">Cargar Archivo</div>
                        </div>
                        <div class="step-bar flex-grow-0" style="height:4px;width:60px;background:#dee2e6;margin:0 8px;"></div>
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#6c757d;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(108,117,125,.15);font-weight:700;">2</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#6c757d;">Verificar Datos</div>
                        </div>
                        <div class="step-bar flex-grow-0" style="height:4px;width:60px;background:#dee2e6;margin:0 8px;"></div>
                        <div class="step text-center position-relative flex-grow-1" style="min-width:160px;">
                            <div class="step-number d-inline-flex align-items-center justify-content-center mx-auto mb-2" style="width:48px;height:48px;font-size:1.5rem;background:#28a745;color:#fff;border-radius:50%;box-shadow:0 2px 8px rgba(40,167,69,.15);font-weight:700;">3</div>
                            <div class="step-label fw-semibold" style="font-size:1rem;color:#28a745;">Confirmar</div>
                        </div>
                    </div>

                    <div id="step1">
                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <h5 class="fw-semibold mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Instrucciones</h5>
                            <ul class="mb-0 ps-3">
                                <li>El archivo debe estar en formato <span class="fw-bold">Excel (.xlsx)</span></li>
                                <li>Debe contener <span class="fw-bold">únicamente las siguientes columnas</span> en este orden:<br>
                                    <span class="text-primary">numero (servicio), rut, lectura (actual), period (Mes/Año)</span>
                                </li>
                                <li><span class="fw-bold">numero</span>: Debe coincidir con el número de servicio registrado en el sistema (puede tener ceros a la izquierda).</li>
                                <li><span class="fw-bold">rut</span>: Sin puntos, solo guion antes del dígito verificador (ejemplo: 12345678-9).</li>
                                <li><span class="fw-bold">lectura</span>: Valor numérico correspondiente a la lectura actual.</li>
                                <li><span class="fw-bold">period</span>: Formato Año-Mes (ejemplo: 2025-07).</li>
                                <li>Se mostrará una vista previa con los primeros registros.</li>
                            </ul>
                        </div>
                        <div class="file-upload mb-4 p-4 rounded-3 border border-primary bg-white d-flex flex-column align-items-center justify-content-center" id="dropZone" style="min-height: 160px; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5 class="mb-1 fw-bold">Arrastra tu archivo Excel aquí</h5>
                            <p class="text-muted mb-2">o haz clic para seleccionar</p>
                            <input type="file" id="fileInput" class="d-none" accept=".xlsx, .xls">
                        </div>
                        <div class="alert alert-warning d-flex align-items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <span>Ejemplo de formato requerido:</span>
                        </div>
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Numero de Servicio</th>
                                        <th>RUT</th>
                                        <th>Lectura Actual</th>
                                        <th>Período</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00001</td>
                                        <td>12345678-9</td>
                                        <td>1350</td>
                                        <td>2025-07</td>
                                    </tr>
                                    <tr>
                                        <td>00002</td>
                                        <td>98765432-1</td>
                                        <td>2150</td>
                                        <td>2025-07</td>
                                    </tr>
                                    <tr>
                                        <td>00003</td>
                                        <td>23456789-0</td>
                                        <td>3480</td>
                                        <td>2025-07</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="step2" class="d-none">
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                            <i class="fas fa-check-circle fa-lg"></i>
                            <span>Archivo cargado correctamente. Verifique los datos a continuación.</span>
                        </div>
                        <h5 class="fw-semibold mb-3"><i class="fas fa-eye text-success me-2"></i>Vista previa de archivos:</h5>
                        <div class="preview-table mb-4 rounded-3 border">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr class="table-primary">
                                        <th>RUT</th>
                                        <th>Nombre</th>
                                        <th>Lectura Actual</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12345678-9</td>
                                        <td>Juan Ejemplo</td>
                                        <td>1350</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                    <tr>
                                        <td>98765432-1</td>
                                        <td>María Ejemplo</td>
                                        <td>2150</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                    <tr>
                                        <td>23456789-0</td>
                                        <td>Carlos Ejemplo</td>
                                        <td>3480</td>
                                        <td><span class="badge bg-success">Válido</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle fa-lg"></i>
                            <span>Se han detectado 3 registros válidos listos para importar.</span>
                        </div>
                    </div>

                    <div id="step3" class="d-none text-center">
                        <i class="fas fa-check-circle success-icon mb-3"></i>
                        <h3 class="mb-3 fw-bold text-success">¡Importación completada con éxito!</h3>
                        <p class="fs-5">Se han registrado <span id="importedCount">0</span> lecturas correctamente.</p>
                        <div class="alert alert-success mt-4 d-inline-block text-start">
                            <i class="fas fa-clipboard-list me-2"></i> <span class="fw-semibold">Resumen:</span>
                            <ul class="mt-2 mb-0 ps-3">
                                <li>Total de registros en archivo: <span id="totalToImport">0</span></li>
                                <li>Registros importados: <span id="importedCount2">0</span></li>
                                <li>Registros con error: <span id="errorCount">0</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary enhanced-btn px-4 py-2" id="nextButton">
                        <i class="fas fa-arrow-right me-2"></i>Siguiente
                    </button>
                </div>
            </div>
        </div>
    </div
                        <div class="alert alert-success mb-4">

                            <i class="fas fa-check-circle me-2"></i>Archivo cargado correctamente. Verifique los datos a continuación.

                        </div>



                        <h6>Vista previa (primeros 3 registros):</h6>

                        <div class="preview-table mb-4">

                            <table class="table table-striped">

                                <thead>

                                    <tr class="table-primary">

                                        <th>RUT</th>

                                        <th>Nombre</th>

                                        <th>Lectura Actual</th>

                                        <th>Estado</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>12.345.678-9</td>

                                        <td>Juan Pérez</td>

                                        <td>1,350</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                    <tr>

                                        <td>9.876.543-2</td>

                                        <td>María González</td>

                                        <td>2,150</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                    <tr>

                                        <td>23.456.789-0</td>

                                        <td>Carlos Soto</td>

                                        <td>3,480</td>

                                        <td><span class="badge bg-success">Válido</span></td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>



                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-2"></i>Se han detectado 3 registros válidos listos para importar.

                        </div>

                    </div>



                    <div id="step3" class="d-none text-center">

                        <i class="fas fa-check-circle success-icon"></i>

                        <h4 class="mb-3">¡Importación completada con éxito!</h4>

                        <p>Se han registrado 3 lecturas correctamente.</p>

                        <div class="alert alert-success mt-4">

                            <i class="fas fa-clipboard-list me-2"></i>Resumen:

                            <ul class="mt-2 mb-0">

                                <li>Registros totales: 3</li>

                                <li>Registros exitosos: 3</li>

                                <li>Registros con error: 0</li>

                            </ul>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-primary enhanced-btn" id="nextButton">Siguiente</button>

                </div>

            </div>

        </div>

    </div>

@endsection



@section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === FILTRO POR SECTOR ===
    const sectorSelect = document.getElementById('sectorSelect');
    if (sectorSelect) {
        sectorSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    // ===== CÓDIGO PARA LECTURAS =====

    const currentReadingInputs = document.querySelectorAll('.current-reading-input');



    currentReadingInputs.forEach(input => {

        input.addEventListener('focus', function() {

            this.dataset.originalValue = this.value;

        });



        input.addEventListener('keydown', function(e) {

            if (e.key === 'Enter') {

                e.preventDefault();



                const form = this.closest('form');

                const inputElement = this;



                if (this.value !== this.dataset.originalValue) {

                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', form.action, true);

                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');



                    // Manejar la respuesta

                    xhr.onload = function() {

                        if (xhr.status === 200) {

                            // Bloquear el input actual

                            inputElement.setAttribute('readonly', true);

                            inputElement.classList.add('read-only');

                            inputElement.disabled = true;



                            // Buscar el siguiente input vacío y disponible

                            let found = false;

                            for (let i = 0; i < currentReadingInputs.length; i++) {

                                const nextInput = currentReadingInputs[i];

                                if (!nextInput.disabled && nextInput.value === '') {

                                    nextInput.focus();

                                    nextInput.select();

                                    found = true;

                                    break;

                                }

                            }

                            // Si no hay input vacío, no hacer nada

                        }

                    };



                    // Enviar el formulario

                    const formData = new FormData(form);

                    xhr.send(formData);

                }

            }

        });



        // Restaurar valor original al perder el foco sin cambios

        input.addEventListener('blur', function() {

            if (this.value === '') {

                this.value = this.dataset.originalValue;

            }

        });

    });



    // ===== CÓDIGO PARA MODAL DE EDICIÓN =====

    const editModal = document.getElementById('editReadingModal');

    if (editModal) {

        const editModalInstance = new bootstrap.Modal(editModal);



        editModal.addEventListener('show.bs.modal', function(event) {

            const button = event.relatedTarget;

            const readingId = button.getAttribute('data-bs-id');

            const currentReading = button.getAttribute('data-bs-current');

            const previousReading = button.getAttribute('data-bs-previous');

            const corte_reposicion = button.getAttribute('data-bs-corte_reposicion') || 0;

            const other = button.getAttribute('data-bs-other');



            document.getElementById('cargo_corte_reposicion').checked = parseInt(corte_reposicion) > 0;

            document.getElementById('other').value = other;

            document.getElementById('idReadingModal').textContent = readingId;

            document.getElementById('reading_id').value = readingId;

            document.getElementById('current_reading').value = currentReading;

            document.getElementById('previous_reading').value = previousReading;

        });

    }



    // ===== CÓDIGO PARA MODAL DE CARGA MASIVA =====

    const uploadModal = document.getElementById('uploadModal');

    if (uploadModal) {

        const dropZone = document.getElementById('dropZone');

        const fileInput = document.getElementById('fileInput');

        const nextButton = document.getElementById('nextButton');

        const step1 = document.getElementById('step1');

        const step2 = document.getElementById('step2');

        const step3 = document.getElementById('step3');

        const steps = document.querySelectorAll('.step');



        let currentStep = 1;

        let excelData = [];



        // Función para resetear modal

        function resetModal() {

            step1.classList.remove('d-none');

            step2.classList.add('d-none');

            step3.classList.add('d-none');

            nextButton.textContent = 'Siguiente';

            updateSteps(1);

            currentStep = 1;



            // Restaurar área de carga

            dropZone.innerHTML = `

                <i class="fas fa-file-excel"></i>

                <h5>Arrastra tu archivo Excel aquí</h5>

                <p class="text-muted">o haz clic para seleccionar</p>

                <input type="file" id="fileInput" class="d-none" accept=".xlsx, .xls">

            `;



            // Re-asignar eventos después del reset

            initFileInputEvents();

        }



        // Inicializar eventos de file input

        function initFileInputEvents() {

            const newFileInput = document.getElementById('fileInput');

            if (newFileInput) {

                newFileInput.addEventListener('change', handleFileInput);

            }

        }



        // Manejar selección de archivo

        function handleFileInput(e) {

            if (e.target.files.length > 0) {

                const fileName = e.target.files[0].name;

                dropZone.innerHTML = `

                    <i class="fas fa-check-circle text-success"></i>

                    <h5>${fileName}</h5>

                    <p class="text-success">Archivo listo para procesar</p>

                `;

                // Leer el archivo Excel y guardar los datos

                const file = e.target.files[0];

                readExcelFile(file);

            }

        }



        // Leer archivo Excel usando SheetJS

        function readExcelFile(file) {

            const reader = new FileReader();

            reader.onload = function(e) {

                const data = new Uint8Array(e.target.result);

                const workbook = XLSX.read(data, { type: 'array' });



                // Tomar la primera hoja

                const firstSheetName = workbook.SheetNames[0];

                const worksheet = workbook.Sheets[firstSheetName];



                // Convertir a JSON

                excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });



                // Actualizar vista previa

                renderPreviewTable();

            };

            reader.readAsArrayBuffer(file);

        }



        // Renderizar la vista previa en el paso 2

        function renderPreviewTable() {

            // Espera que excelData sea un array de arrays

            // Primer fila: encabezados

            // Siguientes filas: datos

            if (!excelData || excelData.length < 2) return;



            // Limitar a los primeros 3 registros

            const previewRows = excelData.slice(1, 4);



            // Encabezados esperados

            const headers = excelData[0];



            // Construir tabla HTML

            let tableHtml = `<table class="table table-striped mb-0"><thead><tr class="table-primary">`;

            headers.forEach(h => {

                tableHtml += `<th>${h}</th>`;

            });

            tableHtml += `<th>Estado</th></tr></thead><tbody>`;

            previewRows.forEach(row => {

                tableHtml += '<tr>';

                headers.forEach((h, idx) => {

                    tableHtml += `<td>${row[idx] !== undefined ? row[idx] : ''}</td>`;

                });



                // Validación simple: todos los campos presentes

                const isValid = row.length === headers.length && row.every(cell => cell !== undefined && cell !== '');

                tableHtml += `<td><span class="badge bg-${isValid ? 'success' : 'danger'}">${isValid ? 'Válido' : 'Inválido'}</span></td>`;

                tableHtml += '</tr>';

            });

            tableHtml += '</tbody></table>';



            // Insertar en el paso 2

            const previewTableDiv = step2.querySelector('.preview-table');

            if (previewTableDiv) {

                previewTableDiv.innerHTML = tableHtml;

            }

        }



        // Delegación de eventos para el dropZone

        document.body.addEventListener('click', function(e) {

            if (e.target.closest('#dropZone')) {

                fileInput.click();

            }

        });



        // Manejar arrastrar y soltar

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {

            dropZone.addEventListener(eventName, preventDefaults, false);

        });



        function preventDefaults(e) {

            e.preventDefault();

            e.stopPropagation();

        }



        function highlight() {

            dropZone.classList.add('bg-light');

        }



        function unhighlight() {

            dropZone.classList.remove('bg-light');

        }



        ['dragenter', 'dragover'].forEach(eventName => {

            dropZone.addEventListener(eventName, highlight, false);

        });



        ['dragleave', 'drop'].forEach(eventName => {

            dropZone.addEventListener(eventName, unhighlight, false);

        });



        // Manejar la carga del archivo

        dropZone.addEventListener('drop', handleDrop, false);



        function handleDrop(e) {

            const dt = e.dataTransfer;

            const files = dt.files;

            if (files.length > 0) {

                const fileName = files[0].name;

                dropZone.innerHTML = `

                    <i class="fas fa-check-circle text-success"></i>

                    <h5>${fileName}</h5>

                    <p class="text-success">Archivo listo para procesar</p>

                `;

            }

        }



        // Resetear al cerrar modal

        uploadModal.addEventListener('hidden.bs.modal', resetModal);



        // Manejar botón siguiente

        nextButton.addEventListener('click', function() {

            if (currentStep === 1) {

                if (!fileInput.files.length) {

                    alert('Por favor selecciona un archivo primero');

                    return;

                }



                step1.classList.add('d-none');

                step2.classList.remove('d-none');

                nextButton.textContent = 'Confirmar Importación';

                updateSteps(2);

                currentStep = 2;



                // Renderizar vista previa si ya se leyó el archivo

                renderPreviewTable();

            }

            else if (currentStep === 2) {
                // Enviar datos al backend vía AJAX
                if (!excelData || excelData.length < 2) {
                    alert('No se encontraron datos válidos en el archivo.');
                    return;
                }
                // Convertir a objetos (excluyendo encabezados)
                const headers = excelData[0];
                const rows = excelData.slice(1).map(row => {
                    const obj = {};
                    headers.forEach((h, idx) => {
                        obj[h] = row[idx];
                    });
                    return obj;
                });
                // Enviar por AJAX (POST)
                fetch("{{ route('orgs.readings.mass_upload', $org->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ data: rows })
                })
                .then(response => response.json())
                .then(result => {
                    // Mostrar resultado en el paso 3
                    step2.classList.add('d-none');
                    step3.classList.remove('d-none');
                    nextButton.textContent = 'Finalizar';
                    updateSteps(3);
                    currentStep = 3;
                    // Actualizar los contadores
                    const totalRegistros = rows.length;
                    document.getElementById('totalToImport').textContent = totalRegistros;
                    document.getElementById('importedCount').textContent = result.success || totalRegistros;
                    document.getElementById('importedCount2').textContent = result.success || totalRegistros;
                    document.getElementById('errorCount').textContent = result.errors || 0;
                })
                .catch(error => {
                    step2.classList.add('d-none');
                    step3.classList.remove('d-none');
                    nextButton.textContent = 'Finalizar';
                    updateSteps(3);
                    currentStep = 3;
                    const totalRegistros = rows.length;
                    document.getElementById('totalToImport').textContent = totalRegistros;
                    document.getElementById('importedCount').textContent = 0;
                    document.getElementById('importedCount2').textContent = 0;
                    document.getElementById('errorCount').textContent = totalRegistros;
                    alert('Error al importar: ' + error);
                });
            }

            else if (currentStep === 3) {

                const modal = bootstrap.Modal.getInstance(uploadModal);

                modal.hide();

            }

        });



        function updateSteps(activeStep) {

            steps.forEach((step, index) => {

                if (index < activeStep) {

                    step.classList.add('active');

                } else {

                    step.classList.remove('active');

                }

            });

        }



        // Inicializar eventos

        initFileInputEvents();

    }

});

</script>

@endsection

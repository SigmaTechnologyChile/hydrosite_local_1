@extends('layouts.nice', ['active'=>'orgs.readings.index','title'=>'Ingreso de Lecturas'])



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

                    <!-- Año -->

                    <div class="col-md-1">

                        <label class="form-label fw-semibold d-block text-center">Año</label>

                        <select name="year" class="form-select rounded-3 py-2">

                            @for ($i = date('Y'); $i >= 2020; $i--)

                                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>

                            @endfor

                        </select>

                    </div>



                    <!-- Mes -->

                    <div class="col-md-1">

                        <label class="form-label fw-semibold d-block text-center">Mes</label>

                        <select name="month" class="form-select rounded-3 py-2">

                            @foreach (['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $month)

                                <option value="{{ $index + 1 }}" {{ request('month', date('n')) == $index + 1 ? 'selected' : '' }}>

                                    {{ $month }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    <!-- Sectores -->

                    <div class="col-md-3">

                        <label class="form-label fw-semibold d-block text-center">Sectores</label>

                        <select name="sector" class="form-select rounded-3 py-2">

                            @if($locations->isNotEmpty())

                                <option value="">Todos</option>

                                @foreach ($locations as $location)

                                    <option value="{{ $location->id }}" {{ old('sector', request()->sector) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>

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

                            <input type="text" name="search" class="form-control rounded-end-3 py-2" placeholder="Buscar por nombre, apellido, sector" value="{{ request('search') }}">

                        </div>

                    </div>



                    <!-- Botón Filtrar -->

                    <div class="col-md-auto d-flex align-items-center">

                        <button type="submit" class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn">

                            <i class="bi bi-funnel-fill me-2"></i>Filtrar

                        </button>

                    </div>



                    <!-- Botón Exportar -->

                    <div class="col-md-auto d-flex align-items-center ms-2">

                        <a href="{{ route('orgs.readings.export', array_merge(['id' => $org->id], request()->except('page'))) }}"

                           class="btn btn-primary pulse-btn p-1 px-2 rounded-2 enhanced-btn">

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

                                <th scope="col" class="text-center">Acciones</th>

                            </tr>

                        </thead>

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

                    <div class="step-indicator">

                        <div class="step active">

                            <div class="step-number">1</div>

                            <div class="step-label">Cargar Archivo</div>

                        </div>

                        <div class="step">

                            <div class="step-number">2</div>

                            <div class="step-label">Verificar Datos</div>

                        </div>

                        <div class="step">

                            <div class="step-number">3</div>

                            <div class="step-label">Confirmar</div>

                        </div>

                    </div>



                    <div id="step1">

                        <div class="mb-4">

                            <h6>Instrucciones:</h6>

                            <ul>

                                <li>El archivo debe estar en formato Excel (.xlsx)</li>

                                <li>Debe contener las columnas: RUT, Nombre, Lectura Actual</li>

                                <li>Las lecturas deben ser valores numéricos</li>

                                <li>Se mostrará una vista previa con los primeros registros</li>

                            </ul>

                        </div>



                        <div class="file-upload mb-4" id="dropZone">

                            <i class="fas fa-file-excel"></i>

                            <h5>Arrastra tu archivo Excel aquí</h5>

                            <p class="text-muted">o haz clic para seleccionar</p>

                            <input type="file" id="fileInput" class="d-none" accept=".xlsx, .xls">

                        </div>



                        <div class="alert alert-warning">

                            <i class="fas fa-exclamation-triangle me-2"></i>Ejemplo de formato requerido:

                        </div>

                        <div class="table-responsive">

                            <table class="table table-bordered">

                                <thead>

                                    <tr class="table-primary">

                                        <th>RUT</th>

                                        <th>Nombre</th>

                                        <th>Lectura Actual</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>12.345.678-9</td>

                                        <td>Juan Pérez</td>

                                        <td>1,350</td>

                                    </tr>

                                    <tr>

                                        <td>9.876.543-2</td>

                                        <td>María González</td>

                                        <td>2,150</td>

                                    </tr>

                                    <tr>

                                        <td>23.456.789-0</td>

                                        <td>Carlos Soto</td>

                                        <td>3,480</td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>



                    <div id="step2" class="d-none">

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

<script>

document.addEventListener('DOMContentLoaded', function() {

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



                if (this.value !== this.dataset.originalValue) {

                    const xhr = new XMLHttpRequest();

                    xhr.open('POST', form.action, true);

                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');



                    // Manejar la respuesta

                    xhr.onload = function() {

                        if (xhr.status === 200) {

                            console.log('Actualización exitosa');

                            // Puede mostrar un mensaje breve de éxito si lo desea

                        }

                    };

import React, { useState, useRef, useEffect } from 'react';

function LecturaActual({ medidor, lecturaAnterior, onActualizar }) {
  const [lecturaActual, setLecturaActual] = useState("0");
  const [bloqueado, setBloqueado] = useState(false);
  const inputRef = useRef(null);

  // Enfoque automático al input cuando no está bloqueado
  useEffect(() => {
    if (!bloqueado && inputRef.current) {
      inputRef.current.focus();
      if (lecturaActual === "0") {
        inputRef.current.select();
      }
    }
  }, [bloqueado]);

  const handleChange = (e) => {
    setLecturaActual(e.target.value);
  };

  const handleClick = () => {
    if (lecturaActual === "0") {
      setLecturaActual("");
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter') {
      validarYActualizar();
    }
  };

  const validarYActualizar = () => {
    const valor = parseInt(lecturaActual);
    
    if (isNaN(valor)) {
      alert("Por favor ingrese un valor numérico válido");
      return;
    }

    if (valor < lecturaAnterior) {
      alert(`La lectura no puede ser menor que la anterior (${lecturaAnterior})`);
      return;
    }

    setBloqueado(true);
    onActualizar(medidor.id, valor);
  };

  return (
    <tr>
      <td>{medidor.id}</td>
      <td>{medidor.medidor}</td>
      <td>{medidor.ubicacion}</td>
      <td>{medidor.marca}</td>
      <td>{medidor.modelo}</td>
      <td>{medidor.serie}</td>
      <td>{medidor.diametro}</td>
      <td>{medidor.tipo}</td>
      <td>{lecturaAnterior}</td>
      <td>
        <input
          ref={inputRef}
          type="number"
          value={lecturaActual}
          onChange={handleChange}
          onClick={handleClick}
          onKeyDown={handleKeyDown}
          readOnly={bloqueado}
          className="form-control"
        />
      </td>
      <td>
        <button
          onClick={validarYActualizar}
          className="btn btn-success"
        >
          Actualizar
        </button>
      </td>
    </tr>
  );
}

                    // Enviar el formulario

                    const formData = new FormData(form);

                    xhr.send(formData);

                }



                // Obtener el índice actual y buscar el siguiente input

                const currentIndex = parseInt(this.dataset.rowIndex);

                const nextIndex = currentIndex + 1;



                // Buscar el siguiente input si existe

                const nextInput = document.querySelector(`.current-reading-input[data-row-index="${nextIndex}"]`);

                if (nextInput) {

                    // Enfocar el siguiente input

                    nextInput.focus();

                    // Seleccionar todo el texto para facilitar la edición

                    nextInput.select();

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

            }

            else if (currentStep === 2) {

                step2.classList.add('d-none');

                step3.classList.remove('d-none');

                nextButton.textContent = 'Finalizar';

                updateSteps(3);

                currentStep = 3;

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

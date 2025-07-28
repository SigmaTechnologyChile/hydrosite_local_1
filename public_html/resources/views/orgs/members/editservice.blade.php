@extends('layouts.nice', ['active'=>'orgs.members.index','title'=>'Editar Servicio'])

@section('content')
<div class="pagetitle">
    <h1 class="text-primary">Editar Servicio</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orgs.members.index', $org->id) }}">Miembros</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orgs.members.edit', [$org->id, $member->id]) }}">{{ $member->first_name }} {{ $member->last_name }}</a></li>
            <li class="breadcrumb-item active">Editar Servicio</li>
        </ol>
    </nav>
</div>

<div class="card shadow">
    <div class="card-body">
        <!-- Formulario de Edición del Servicio -->
        <h5 class="card-title text-primary">Datos del Servicio</h5>

        <form action="{{ route('orgs.members.services.update', [$org->id, $member->id, $service->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="p-3 border rounded shadow-sm bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nro" class="form-label">ID Servicio</label>
                                <input type="text" class="form-control bg-white @error('nro') is-invalid @enderror" id="nro" name="nro" value="{{ old('nro', $service->nro) }}" readonly>
                                @error('nro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                     <div class="col-md-6">
                        <label for="sector" class="form-label">Sector</label>
                        <select class="form-select @error('sector') is-invalid @enderror" id="sector" name="sector">
                            <option value="">Seleccione el sector</option>
                            @foreach($sectors as $sector)
                                <option value="{{ $sector->id }}"
                                    {{ old('sector', $service->locality_id) == $sector->id ? 'selected' : '' }}>
                                    {{ $sector->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

          <div class="mb-3 col-md-3">
            <label for="service_state" class="form-label">Región del Servicio</label>
            <select class="form-select @error('service_state') is-invalid @enderror" id="service_state" name="service_state">
                <option value="">Seleccionar Región</option>
                @foreach($states as $state)
                    <option value="{{ $state->id }}"  {{ old('service_state', $serviceStateId) == $state->id ? 'selected' : '' }}>
                        {{ $state->name_state }}
                    </option>
                @endforeach

            </select>
            @error('service_state')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 col-md-3">
            <label for="service_commune" class="form-label">Comuna del Servicio</label>
            <select class="form-select @error('service_commune') is-invalid @enderror" id="service_commune" name="service_commune">
                <option value="">Seleccionar Comuna</option>
            </select>
            @error('service_commune')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 col-md-6">
            <label for="service_address" class="form-label">Dirección del Servicio</label>
            <input type="text" class="form-control @error('service_address') is-invalid @enderror"
                                       id="service_address" name="service_address"
                                       value="{{ old('service_address', $service->address) }}" required>
            @error('service_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>


                            <div class="col-md-6">
                                <label for="meter_plan" class="form-label">MIDEPLAN</label>
                                <select class="form-select @error('meter_plan') is-invalid @enderror" id="meter_plan" name="meter_plan">
                                    <option value="">Seleccione</option>
                                    <option value="1" {{ old('meter_plan', $service->meter_plan) == 1 ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('meter_plan', $service->meter_plan) == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('meter_plan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="percentage-container">
                                <label for="percentage" class="form-label">Porcentaje</label>
                                <div class="input-group">
                                    <input type="number" name="percentage" id="percentage" step="0.01" class="form-control @error('percentage') is-invalid @enderror" value="{{ old('percentage', $service->percentage) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('percentage')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="meter_type" class="form-label">Tipo Medidor</label>
                                <select class="form-select @error('meter_type') is-invalid @enderror" id="meter_type" name="meter_type">
                                    <option value="">Seleccione</option>
                                    <option value="analogico" {{ old('meter_type', $service->meter_type) == 'analogico' ? 'selected' : '' }}>Análogo</option>
                                    <option value="digital" {{ old('meter_type', $service->meter_type) == 'digital' ? 'selected' : '' }}>Digital</option>
                                </select>
                                @error('meter_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="meter_number" class="form-label">N° Medidor</label>
                                <input type="text" class="form-control @error('meter_number') is-invalid @enderror" id="meter_number" name="meter_number" value="{{ old('meter_number', $service->meter_number) }}" required>
                                @error('meter_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="invoice_type" class="form-label">Tipo de Documento</label>
                                <select class="form-select @error('invoice_type') is-invalid @enderror" id="invoice_type" name="invoice_type" required>
                                    <option value="">Seleccione</option>
                                    <option value="boleta" {{ old('invoice_type', $service->invoice_type) == 'boleta' ? 'selected' : '' }}>Boleta Exenta</option>
                                    <option value="factura" {{ old('invoice_type', $service->invoice_type) == 'factura' ? 'selected' : '' }}>Factura</option>
                                </select>
                                @error('invoice_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="diameter" class="form-label">Diámetro de Conexión</label>
                                <select class="form-select @error('diameter') is-invalid @enderror" id="diameter" name="diameter" required>
                                    <option value="">Seleccione</option>
                                    <option value="1/2" {{ old('diameter', $service->diameter) == '1/2' ? 'selected' : '' }}>1/2</option>
                                    <option value="3/4" {{ old('diameter', $service->diameter) == '3/4' ? 'selected' : '' }}>3/4</option>
                                </select>
                                @error('diameter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 text-center mt-4">
                                <label for="active" class="form-label d-block">¿Servicio Activo?</label>

                                <div class="form-check form-switch d-inline-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $service->active) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="active">
                                        <span id="active-label">{{ old('active', $service->active) ? 'Sí' : 'No' }}</span>
                                    </label>
                                </div>

                                @error('active')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTONES: CENTRADOS -->
            <div class="d-flex justify-content-center gap-2 mt-4">
                <button type="submit" class="btn btn-primary shadow">Guardar Cambios</button>
                <a href="{{ route('orgs.members.edit', [$org->id, $member->id]) }}" class="btn btn-outline-secondary shadow">Cancelar</a>
            </div>
        </form>

    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const meterPlanSelect = document.getElementById('meter_plan');
    const percentageLabel = document.querySelector('label[for="percentage"]');
    const percentageInput = document.getElementById('percentage');

    function togglePercentageField() {
        if (meterPlanSelect.value === "0") {
            percentageInput.disabled = true;
            percentageInput.value = '';
        } else {
            percentageInput.disabled = false;
        }
    }

    meterPlanSelect.addEventListener('change', togglePercentageField);
    togglePercentageField();

    function updatePercentageField() {

        const isMideplanEnabled = meterPlanSelect.value === "1";


        if (isMideplanEnabled) {

            percentageInput.setAttribute('step', '0.01');
            percentageInput.removeAttribute('readonly');
        } else {

            percentageInput.setAttribute('readonly', true);
            percentageInput.setAttribute('step', '0');
        }
    }


    updatePercentageField();

    if (meterPlanSelect) {
        meterPlanSelect.addEventListener('change', updatePercentageField);
    }

    const activeCheckbox = document.getElementById('active');
    const activeLabel = document.getElementById('active-label');

    if (activeCheckbox && activeLabel) {
        activeCheckbox.addEventListener('change', function() {
            activeLabel.textContent = this.checked ? 'Sí' : 'No';
        });
    }

});


$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function cargarComunas(stateId, comunaSeleccionada = null) {
        console.log('Cargando comunas para estado ID:', stateId, 'Comuna a seleccionar:', comunaSeleccionada);

        $('#service_commune').empty();
        $('#service_commune').append('<option value="">Seleccionar Comuna</option>');

        if (stateId) {
            $.ajax({
                url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region",
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    $('#service_commune').prop('disabled', true);
                },
                success: function(resultado) {
                    console.log('Comunas recibidas:', resultado);
                    if (resultado && resultado.length > 0) {
                        resultado.forEach(function(comuna) {
                            var selected = (comunaSeleccionada && comunaSeleccionada === comuna.name) ? 'selected' : '';
                            $('#service_commune').append('<option value="' + comuna.name + '" ' + selected + '>' + comuna.name + '</option>');
                        });
                        console.log('Comunas cargadas exitosamente');
                    } else {
                        console.log('No se encontraron comunas para esta región');
                    }
                    $('#service_commune').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar comunas del servicio:', {
                        status: status,
                        error: error,
                        response: xhr.responseText,
                        url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region"
                    });
                    $('#service_commune').prop('disabled', false);
                    alert('Error al cargar las comunas del servicio. Por favor, recarga la página.');
                }
            });
        }
    }


    var initialStateId = $("#service_state").val();
    var initialCommune = "{{ $service->commune ?? '' }}";

    console.log('=== CARGA INICIAL ===');
    console.log('Estado inicial ID:', initialStateId);
    console.log('Comuna inicial:', initialCommune);


    if (initialStateId && initialStateId !== '') {
        console.log('Cargando comunas automáticamente al inicio...');
        cargarComunas(initialStateId, initialCommune);
    } else {
        console.log('No hay región preseleccionada');
    }


    $("#service_state").change(function(e) {
        var stateId = $(this).val();
        console.log('=== CAMBIO DE REGIÓN ===');
        console.log('Nueva región seleccionada:', stateId);
        cargarComunas(stateId);
    });


    $("#state").change(function(e) {
        var stateId = $(this).val();
        console.log('Cambio en región personal:', stateId);

        $('#commune').empty();
        $('#commune').append('<option value="">Seleccionar Comuna</option>');

        if (stateId) {
            $.ajax({
                url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region",
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    $('#commune').prop('disabled', true);
                },
                success: function(resultado) {
                    if (resultado && resultado.length > 0) {
                        resultado.forEach(function(comuna) {
                            $('#commune').append('<option value="' + comuna.name + '">' + comuna.name + '</option>');
                        });
                    }
                    $('#commune').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar comunas personales:', error);
                    $('#commune').prop('disabled', false);
                    alert('Error al cargar las comunas. Por favor, recarga la página.');
                }
            });
        }
    });
});
</script>
@endsection

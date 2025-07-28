@extends('layouts.nice', ['active' => 'orgs.members.create', 'title' => 'Crear Socio'])

@section('content')
    <div class="pagetitle">
        <h1>{{$org->name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">Socios</a></li>
                <li class="breadcrumb-item active">Crear Socio</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form id="newMemberForm" action="{{ route('orgs.members.store', $org->id) }}" method="POST">
                    @csrf
                    <div class="row m-3">
                        <!-- Columna izquierda - Datos personales -->
                        <div class="col-md-6 border-end pe-md-4">
                            <h5 class="text-primary mb-3">Datos Personales</h5>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="rut" class="form-label">RUT</label>
                                    <input type="text" class="form-control @error('rut') is-invalid @enderror" id="rut"
                                        name="rut" value="{{ old('rut') }}" required maxlength="12"
                                        oninput="validarInputRut(this)">
                                    @error('rut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="rut-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="partner" class="form-label">Cliente/Socio</label>
                                    <select class="form-select" id="partner" name="partner">
                                        <option value="">Seleccionar...</option>
                                        <option value="cliente" {{ old('partner') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                        <option value="socio" {{ old('partner') == 'socio' ? 'selected' : '' }}>Socio</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="first_name" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="last_name" value="{{ old('last_name') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Género</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Seleccionar...</option>
                                    <option value="MASCULINO" {{ old('gender') == 'MASCULINO' ? 'selected' : '' }}>
                                        Masculino</option>
                                    <option value="FEMENINO" {{ old('gender') == 'FEMENINO' ? 'selected' : '' }}>Femenino
                                    </option>
                                    <option value="OTRO" {{ old('gender') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    value="{{ old('email') }}" name="email" required
                                    pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}"
                                    title="Por favor ingrese un correo electrónico válido">
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="state" class="form-label">Región</label>
                                    <select class="form-select" id="state" name="state">
                                        <option value="">Seleccionar Región</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                                {{$state->name_state}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="commune" class="form-label">Comuna</label>
                                    <select class="form-select" id="commune" name="commune">
                                        <option value="">Seleccionar Comuna</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="address" value="{{ old('address') }}" required>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="mobile_phone" class="form-label">Celular</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+56</span>
                                        <input type="tel" class="form-control" id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Teléfono fijo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+56</span>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha - Servicios -->
                        <div class="col-md-6 ps-md-4">
                            <h5 class="text-primary mb-3">Información de Servicios</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="service_state" class="form-label">Región del Servicio</label>
                                    <select class="form-select" id="service_state" name="service_state">
                                        <option value="">Seleccionar Región</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}" {{ old('service_state') == $state->id ? 'selected' : '' }}>
                                                {{$state->name_state}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="service_commune" class="form-label">Comuna del Servicio</label>
                                    <select class="form-select" id="service_commune" name="service_commune">
                                        <option value="">Seleccionar Comuna</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dirección del Servicio -->
                            <div class="mb-3">
                                <label for="service_address" class="form-label">Dirección del Servicio</label>
                                <input type="text" class="form-control" id="service_address" name="service_address"
                                    value="{{ old('service_address') }}">
                            </div>

                            <div class="mb-3">
                                <label for="locality_id" class="form-label">Sector</label>
                                <select class="form-select" id="locality_id" name="locality_id">
                                    <option value="">Seleccione el sector</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}" {{ old('locality_id') == $location->id ? 'selected' : '' }}>{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meter_plan" class="form-label">MIDEPLAN</label>
                                    <select class="form-select" id="meter_plan" name="meter_plan">
                                        <option value="">Seleccionar</option>
                                        <option value="1" {{ old('meter_plan') == '1' ? 'selected' : '' }}>Sí</option>
                                        <option value="0" {{ old('meter_plan') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="percentage" class="form-label">Porcentaje</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="percentage" name="percentage"
                                               min="0" max="100" value="{{ old('percentage', 0) }}" readonly>
                                        <span class="input-group-text">%</span>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meter_type" class="form-label">Tipo Medidor</label>
                                    <select class="form-select" id="meter_type" name="meter_type">
                                        <option value="">Seleccione el medidor</option>
                                        <option value="analogico" {{ old('meter_type') == 'analogico' ? 'selected' : '' }}>
                                            Análogo</option>
                                        <option value="digital" {{ old('meter_type') == 'digital' ? 'selected' : '' }}>Digital
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meter_number" class="form-label">N° Medidor</label>
                                    <input type="text" class="form-control" id="meter_number" name="meter_number"
                                        value="{{ old('meter_number') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_type" class="form-label">Boleta/Factura</label>
                                    <select class="form-select" id="invoice_type" name="invoice_type">
                                        <option value="">Seleccione Boleta/Factura</option>
                                        <option value="boleta" {{ old('invoice_type') == 'boleta' ? 'selected' : '' }}>Boleta
                                            Exenta</option>
                                        <option value="factura" {{ old('invoice_type') == 'factura' ? 'selected' : '' }}>
                                            Factura</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="diameter" class="form-label">Diámetro de Conexión</label>
                                    <select class="form-select" id="diameter" name="diameter">
                                        <option value="">Seleccione el Diámetro</option>
                                        <option value="1/2" {{ old('diameter') == '1/2' ? 'selected' : '' }}>1/2</option>
                                        <option value="3/4" {{ old('diameter') == '3/4' ? 'selected' : '' }}>3/4</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="observations" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observations" name="observations"
                                    rows="3">{{ old('observations') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox para "Activo" -->
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Dejar <b>Activo</b> si es nuevo cliente y no tiene
                            servicios asignados</label>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Añadir cliente</button>
                    </div>
                </form>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const activoCheckbox = document.getElementById('activo');
    const serviceSection = document.querySelector('.col-md-6.ps-md-4');
    const submitButton = document.querySelector('button[type="submit"]');
    const meterPlanSelect = document.getElementById('meter_plan');
    const percentageInput = document.getElementById('percentage');
    const form = document.getElementById('newMemberForm');

    function togglePercentageField() {
        const meterPlanValue = meterPlanSelect.value;


        if (meterPlanValue === '1') {
            percentageInput.readOnly = false;
            percentageInput.required = true;
            percentageInput.style.backgroundColor = '';
            percentageInput.style.cursor = 'text';
            if (percentageInput.value === '0' || percentageInput.value === '') {
                percentageInput.value = '';
            }
            console.log('Percentage habilitado');
        } else {
            percentageInput.readOnly = true;
            percentageInput.required = false;
            percentageInput.value = '0';
            percentageInput.style.backgroundColor = '#f8f9fa';
            percentageInput.style.cursor = 'not-allowed';
        }
    }

    function toggleServiceFields() {
        if (activoCheckbox.checked) {
            serviceSection.style.display = 'block';
            serviceSection.style.opacity = '1';
            const serviceInputs = serviceSection.querySelectorAll('input, select, textarea');
            serviceInputs.forEach(input => {
                if (input.id !== 'percentage') {
                    input.disabled = false;
                }
            });
        } else {
            serviceSection.style.display = 'block';
            serviceSection.style.opacity = '0.5';
            const serviceInputs = serviceSection.querySelectorAll('input, select, textarea');
            serviceInputs.forEach(input => {
                input.disabled = true;
            });
            clearServiceFields();
        }

        togglePercentageField();
    }

    function clearServiceFields() {
        const serviceInputs = serviceSection.querySelectorAll('input, select, textarea');
        serviceInputs.forEach(input => {
            if (input.type !== 'checkbox') {
                if (input.id === 'percentage') {
                    input.value = '0';
                } else {
                    input.value = '';
                }
            }
        });
    }

    function validateServiceFields() {
        if (!activoCheckbox.checked) return true;


        const criticalFields = ['locality_id', 'meter_plan', 'meter_type', 'meter_number', 'invoice_type', 'diameter'];

        for (let fieldName of criticalFields) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && !field.value.trim()) {
                alert('Para crear un servicio, debe completar todos los campos obligatorios de la sección "Información de Servicios" o desmarcar la casilla "Dejar Activo".');
                return false;
            }
        }

        if (meterPlanSelect.value === '1') {
            const percentageValue = parseInt(percentageInput.value);
            if (isNaN(percentageValue) || percentageValue < 1 || percentageValue > 100) {
                alert('Cuando MIDEPLAN es "Sí", el porcentaje debe ser un valor entre 1 y 100.');
                return false;
            }
        }

        return true;
    }


    activoCheckbox.addEventListener('change', toggleServiceFields);
    meterPlanSelect.addEventListener('change', togglePercentageField);

    form.addEventListener('submit', function (e) {

        if (meterPlanSelect.value !== '1') {
            percentageInput.value = '0';
        }

        // Formatear RUT antes de enviar
        let rutInput = document.getElementById('rut');
        let valor = rutInput.value.replace(/\./g, '').replace(/-/g, '').toLowerCase();
        valor = valor.replace(/[^0-9k]/g, '');
        if (valor.length === 8 || valor.length === 9) {
            let cuerpo = valor.slice(0, -1);
            let dv = valor.slice(-1);
            rutInput.value = cuerpo + '-' + dv;
        }

        if (!validateServiceFields()) {
            e.preventDefault();
        }
    });


    toggleServiceFields();
    togglePercentageField();
});

// CARGAR COMUNAS
$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $("#state").change(function (e) {
        var stateId = $(this).val();


        $('#commune').empty();
        $('#commune').append('<option value="">Seleccionar Comuna</option>');

        if (stateId) {
            $.ajax({
                url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region",
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    $('#commune').prop('disabled', true);
                },
                success: function (resultado) {
                    if (resultado && resultado.length > 0) {
                        resultado.forEach(function (comuna) {
                            $('#commune').append('<option value="' + comuna.name + '">' + comuna.name + '</option>');
                        });
                    }
                    $('#commune').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar comunas:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $('#commune').prop('disabled', false);
                    alert('Error al cargar las comunas. Por favor, recarga la página.');
                }
            });
        }
    });


    $("#service_state").change(function (e) {
        var stateId = $(this).val();


        $('#service_commune').empty();
        $('#service_commune').append('<option value="">Seleccionar Comuna</option>');

        if (stateId) {
            $.ajax({
                url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region",
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    $('#service_commune').prop('disabled', true);
                },
                success: function (resultado) {
                    if (resultado && resultado.length > 0) {
                        resultado.forEach(function (comuna) {
                            $('#service_commune').append('<option value="' + comuna.name + '">' + comuna.name + '</option>');
                        });
                    }
                    $('#service_commune').prop('disabled', false);
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar comunas del servicio:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $('#service_commune').prop('disabled', false);
                    alert('Error al cargar las comunas del servicio. Por favor, recarga la página.');
                }
            });
        }
    });


    function validarYActualizarBotonRut() {
        let rut = $('#rut').val();
        rut = rut.replace(/\./g, '').replace(/-/g, '').toLowerCase();
        rut = rut.replace(/[^0-9k]/g, '');
        let rutFormateado = '';
        if (rut.length === 8 || rut.length === 9) {
            let cuerpo = rut.slice(0, -1);
            let dv = rut.slice(-1);
            rutFormateado = cuerpo + '-' + dv;
            $('#rut').val(rutFormateado);
            if (!validarRut(rutFormateado)) {
                $('#rut').addClass('is-invalid');
                $('#rut-error').text('RUT inválido');
                $('button[type="submit"]').prop('disabled', true);
                return;
            } else {
                $('#rut').removeClass('is-invalid');
                $('#rut-error').text('');
                $('button[type="submit"]').prop('disabled', false);
            }
            $.ajax({
                url: '{{ url("/ajax/check-rut") }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'rut': rutFormateado
                },
                success: function (response) {
                    if (response.exists) {
                        $('#rut').addClass('is-invalid');
                        $('#rut-error').text('Este RUT ya está registrado en el sistema.');
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        $('#rut').removeClass('is-invalid');
                        $('#rut-error').text('');
                        $('button[type="submit"]').prop('disabled', false);
                    }
                },
                error: function () {
                    console.log('Error al verificar RUT');
                }
            });
        } else {
            $('#rut').removeClass('is-invalid');
            $('#rut-error').text('');
            $('button[type="submit"]').prop('disabled', false);
        }
    }

    $('#rut').on('blur', validarYActualizarBotonRut);
    $('#rut').on('input', function () {
        // Valida en tiempo real y habilita/deshabilita el botón
        validarYActualizarBotonRut();
    });
});

function validarInputRut(input) {
    let valor = input.value.replace(/\./g, '').replace(/-/g, '').toLowerCase();
    // Eliminar todo excepto números y k
    valor = valor.replace(/[^0-9k]/g, '');

    

    // Formatear automáticamente si tiene largo suficiente (7 u 8 dígitos + dv)
    if (valor.length === 8 || valor.length === 9) {
        let cuerpo = valor.slice(0, -1);
        let dv = valor.slice(-1);
        input.value = cuerpo + '-' + dv;
        if (!validarRut(input.value)) {
            $('#rut-error').text('RUT inválido');
            $(input).addClass('is-invalid');
        } else {
            $('#rut-error').text('');
            $(input).removeClass('is-invalid');
        }
    } else {
        $('#rut-error').text('Formato RUT inválido. Debe ser 7 u 8 dígitos más dígito verificador. Ej: 12345678-9');
        $(input).addClass('is-invalid');
    }
}

function validarRut(rutCompleto) {
    rutCompleto = rutCompleto.replace(/\./g, '').replace(/-/g, '');
    if (!/^\d{7,8}[0-9kK]$/.test(rutCompleto)) return false;
    let cuerpo = rutCompleto.slice(0, -1);
    let dv = rutCompleto.slice(-1).toLowerCase();
    let suma = 0;
    let multiplo = 2;
    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo.charAt(i), 10) * multiplo;
        multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }
    let resto = suma % 11;
    let dvEsperado;
    if (resto === 1) dvEsperado = 'k';
    else if (resto === 0) dvEsperado = '0';
    else dvEsperado = (11 - resto).toString();
    return dv === dvEsperado;
}
    </script>
@endsection

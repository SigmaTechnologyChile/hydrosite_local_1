@extends('layouts.nice', ['active' => 'orgs.members.index', 'title' => 'Ver/Editar Miembro'])
@php
    use Illuminate\Support\Str;

    $mobile_phone = Str::startsWith($member->mobile_phone, '+56') ? Str::replaceFirst('+56', '', $member->mobile_phone) : $member->mobile_phone;

    $phone = Str::startsWith($member->phone, '+56') ? Str::replaceFirst('+56', '', $member->phone) : $member->phone;
@endphp
@section('content')
    <div class="pagetitle">
        <h1>Detalles de Miembro: {{$member->first_name}} {{$member->last_name}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.dashboard', $org->id)}}">{{$org->name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">Miembros</a></li>
                <li class="breadcrumb-item"><a href="{{route('orgs.members.index', $org->id)}}">{{$member->first_name}}
                        {{$member->last_name}}</a></li>
                <li class="breadcrumb-item active">Ver/Editar Miembro</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Información del Miembro</h5>
            <form action="{{ route('orgs.members.update', [$org->id, $member->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rut" class="form-label">RUT</label>
                            <input type="text" class="form-control @error('rut') is-invalid @enderror" id="rut" name="rut"
                                value="{{ old('rut', $member->rut) }}" required readonly>
                            @error('rut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Nombres</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}"
                                required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Apellidos</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                                name="last_name" value="{{ old('last_name', $member->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" value="{{ old('address', $member->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- NUEVO: Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $member->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 d-none">
                            <label for="city" class="form-label">Comuna</label>
                            <select class="form-select" id="city" name="city">
                                @if($city)
                                    <option value="{{$city->id}}">{{$city->name_city}}</option>
                                @endif
                            </select>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NUEVO: Género -->

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="state" class="form-label">Región</label>
                            <select class="form-select @error('state') is-invalid @enderror" id="state" name="state"
                                required>
                                <option value="">Seleccionar Región</option>
                                @foreach($states as $state)
                                    <option value="{{$state->id}}" {{ (old('state') ?? $member->city_id) == $state->id ? 'selected' : '' }}>
                                        {{$state->name_state}}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="commune" class="form-label">Comuna</label>
                            <select class="form-select @error('commune') is-invalid @enderror" id="commune" name="commune"
                                required>
                                <option value="">Seleccionar Comuna</option>
                                @if($member->commune)
                                    <option value="{{ $member->commune }}" selected>{{ $member->commune }}</option>
                                @endif
                            </select>
                            @error('commune')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="mobile_phone" class=" form-label">Número de celular</label>
                            <div class="input-group">
                                <span class="input-group-text">+56</span>
                                <input type="text" maxlength="9"
                                    class="form-control  @error('mobile_phone') is-invalid @enderror" id="mobile_phone"
                                    name="mobile_phone" value="{{ old('mobile_phone', $mobile_phone) }}" required>
                                @error('mobile_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- NUEVO: Teléfono fijo -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Número de teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text">+56</span>
                                <input type="text" maxlength="9" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Género</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender"
                                required>
                                <option value="">Seleccionar género</option>
                                <option value="MASCULINO" {{ old('gender', $member->gender) == 'MASCULINO' ? 'selected' : '' }}>MASCULINO</option>
                                <option value="FEMENINO" {{ old('gender', $member->gender) == 'FEMENINO' ? 'selected' : '' }}>
                                    FEMENINO</option>
                                <option value="OTRO" {{ old('gender', $member->gender) == 'OTRO' ? 'selected' : '' }}>OTRO
                                </option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Servicios -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
                Servicios Asociados
                <a href="{{ route('orgs.services.createForMember', [$org->id, $member->id]) }}"
                    class="btn btn-outline-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Agregar Servicio
                </a>
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Servicio</th>
                            <th>Sector</th>
                            <th>N°Medidor</th>
                            <th>Boleta/Factura</th>
                            <th>MIDEPLAN</th>
                            <th>Porcentaje</th>
                            <th>Diametro</th>
                            <th>Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($services->isNotEmpty())
                            @foreach($services as $service)
                                <tr>
                                    <td>{{ $service->nro ?? '' }}</td>
                                    <td>
                                        @if($service->sector_name)
                                            {{ $service->sector_name }}
                                        @elseif(is_numeric($service->sector))
                                            {{ \App\Models\Location::find($service->sector)->name ?? 'N/D' }}
                                        @else
                                            {{ $service->sector ?? 'N/D' }}
                                        @endif
                                    </td>
                                    <td>{{ $service->meter_number ?? 'N/D' }}</td>
                                    <td>{{ $service->invoice_type ?? 'N/D' }}</td>
                                    <td>{{ $service->meter_plan ?? 'N/D' }}</td>
                                    <td>{{ $service->percentage ?? 'N/D' }}</td>
                                    <td>{{ $service->diameter ?? 'N/D' }}</td>
                                    <td>
                                        <a href="{{ route('orgs.members.services.edit', [$org->id, $member->id, $service->id]) }}"
                                            class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Editar</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2">No hay servicios disponibles para este miembro.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

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
                                response: xhr.responseText,
                                url: "{{ url('/ajax') }}/" + stateId + "/comunas-por-region"
                            });
                            $('#commune').prop('disabled', false);
                            alert('Error al cargar las comunas. Por favor, recarga la página.');
                        }
                    });
                }
            });

        });
    </script>
@endsection

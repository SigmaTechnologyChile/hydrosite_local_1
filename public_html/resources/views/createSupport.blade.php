@extends('layouts.theme',['title'=>'Ingreso de soporte'])

@section('content')
    <form id="form-entry" action="{{ route('guardar-soporte') }}" method="post">
        @csrf
        <hr class="my-4">
        <h4 class="mb-3">Ingreso de soporte</h4>
        <ul class="list-unstyled">
            <li>
                <label for="type" class="form-label">Tipo</label>
                <select class="form-select" name="type" id="type" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Consultas generales">Consultas generales</option>
                    <option value="Problemas con el pago">Problemas con el pago</option>
                    <option value="Problemas con el ticket">Problemas con el ticket</option>
                    <option value="No llegó el correo de confirmación">No llegó el correo de confirmación</option>
                    <option value="Modificar datos de entrada">Modificar datos de entrada</option>
                    <option value="Otros">Otros</option>
                </select>
                <div class="invalid-feedback">
                    El tipo es requerido.
                </div>
            </li>
            <li>
                <label for="description" class="form-label">Descripción</label>
                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                <div class="invalid-feedback">
                    La descripción es requerida.
                </div>
            </li>
            <li>
                <label for="first_name" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" value="" required>
                <div class="invalid-feedback">
                    El nombre es requerido.
                </div>
            </li>
            <li>
                <label for="last_name" class="form-label">Apellido</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="" required>
                <div class="invalid-feedback">
                    El apellido es requerido.
                </div>
            </li>
            <li>
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="tu@mail.com" required>
                <div class="invalid-feedback">
                    Por favor ingrese un email válido.
                </div>
            </li>
            <li>
                <label for="phone" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="+56912345678" required>
                <div class="invalid-feedback">
                    Por favor ingrese un número de teléfono válido.
                </div>
            </li>
            <hr class="my-4">
            <li>
                <button id="next" type="submit" class="w-100 btn btn-primary btn-lg">Guardar soporte</button>
            </li>
        </ul>
    </form>
@endsection
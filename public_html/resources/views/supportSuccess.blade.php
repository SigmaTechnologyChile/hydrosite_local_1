@extends('layouts.theme',['title'=>'Mensaje de éxito'])

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Éxito</div>

                    <div class="card-body">
                        <p>¡El formulario de soporte se ha guardado correctamente!</p>
                        <p>Nos pondremos en contacto contigo lo antes posible.</p>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ url('/') }}" class="btn btn-success m-2 float-end">Volver</a>
    </div>
    
@endsection
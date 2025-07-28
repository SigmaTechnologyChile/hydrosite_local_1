

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card">

                <div class="card-header">

                    <h2>Enviar Notificación</h2>

                </div>



                <div class="card-body">

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif



                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul>

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif



                    <form method="POST" action="{{ route('orgs.notifications.store', ['id' => $org->id]) }}">

                        @csrf



                        <div class="form-group mb-3">

                            <label for="title">Título de la notificación</label>

                            <input type="text" class="form-control" id="title" name="title" required>

                        </div>



                        <div class="form-group mb-3">

                            <label for="message">Mensaje</label>

                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>

                        </div>



                        <div class="form-group mb-3">

                            <div class="custom-control custom-checkbox">

                                <input type="checkbox" class="custom-control-input" id="send_to_all" name="send_to_all">

                                <label class="custom-control-label" for="send_to_all">Enviar a todos los usuarios</label>

                            </div>

                        </div>



                        <div class="form-group mb-3" id="sectors_container">

                            <label>Seleccionar sectores</label>

                            @foreach($activeLocations as $location)

                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" class="custom-control-input"

                                           id="sector_{{ $location->id }}"

                                           name="sectors[]"

                                           value="{{ $location->id }}">

                                    <label class="custom-control-label" for="sector_{{ $location->id }}">

                                        {{ $location->name }}

                                    </label>

                                </div>

                            @endforeach

                        </div>



                        <button type="submit" class="btn btn-primary">Enviar Notificación</button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>



@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function() {

    const sendToAllCheckbox = document.getElementById('send_to_all');

    const sectorsContainer = document.getElementById('sectors_container');



    sendToAllCheckbox.addEventListener('change', function() {

        sectorsContainer.style.display = this.checked ? 'none' : 'block';



        // Si está marcado "enviar a todos", desmarca todos los sectores

        if (this.checked) {

            document.querySelectorAll('[name="sectors[]"]').forEach(checkbox => {

                checkbox.checked = false;

            });

        }

    });

});

</script>

@endpush

@endsection


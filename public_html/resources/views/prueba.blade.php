<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba</title>
</head>
<body>
    <h1>Prueba de tabla y formulario</h1>
    <form method="POST" action="{{ route('prueba.store') }}">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre de prueba" required>
        <button type="submit">Guardar</button>
    </form>
    <h2>Datos guardados:</h2>
    <ul>
        @foreach($pruebas as $prueba)
            <li>{{ $prueba->nombre }}</li>
        @endforeach
    </ul>
</body>
</html>

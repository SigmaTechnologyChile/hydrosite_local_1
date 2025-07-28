<!-- historial.html -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Historial de Lecturas - HydroSite</title>
    <!-- Reutiliza tus <link> y <style> originales -->
 <!-- Favicons -->
    <link href="https://hydrosite.cl/public/theme/common/img/favicon.png" rel="icon">
    <link href="https://hydrosite.cl/public/theme/common/img/apple-touch-icon.png" rel="apple-touch-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
    <!-- Vendor CSS Files -->
    <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Template Main CSS File -->
    <link href="https://hydrosite.cl/public/theme/nice/assets/css/style.css" rel="stylesheet">
    
    <style>
    .espacio-superior {
        margin-top: 30px; /* Espacio entre controles y tarjetas */
    }
    
    /* Para dispositivos móviles */
    @media (max-width: 768px) {
        .espacio-superior {
            margin-top: 20px; /* Menor espacio en móviles */
        }
    }
    
    #registroMacromedidor {
        padding: 20px 15px; /* 20px arriba/abajo, 15px izquierda/derecha */
    }
    
    .section-title {
        margin-top: 15px;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
    /* Para móviles */
    @media (max-width: 768px) {
        #registroMacromedidor {
            padding: 15px 10px;
        }
        .section-title {
            margin-top: 10px;
        }
    }
        /* Estilos específicos para móviles (ancho máximo de 768px) */
@media (max-width: 768px) {
    /* Ajustes generales */
    body {
        font-size: 14px;
    }
    
    .pagetitle h1 {
        font-size: 1.5rem;
    }
    
    .section-title {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    
    /* Ajustes de tarjetas */
    .medidor-card {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .medidor-header h5 {
        font-size: 1rem;
    }
    
    /* Ajustes de formulario */
    .form-group label {
        font-size: 0.9rem;
    }
    
    .form-control {
        padding: 0.375rem 0.75rem;
        font-size: 0.9rem;
    }
    
    /* Reorganización de columnas */
    .row {
        margin-left: -5px;
        margin-right: -5px;
    }
    
    .col-md-6, .col-md-4, .col-md-12 {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    /* Botones */
    .btn-custom, .btn-secondary {
        font-size: 0.9rem;
        padding: 0.375rem 0.75rem;
        width: 100%;
        margin-bottom: 10px;
    }
    
    /* Tabla de historial */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        margin-right: 10px;
    }
    
    /* Gráfico */
    .chart-container {
        height: 250px;
    }
    
    /* Footer */
    .footer {
        padding: 15px 0;
    }
    
    .copyright, .credits {
        text-align: center;
        font-size: 0.8rem;
    }
}
        .section-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .medidor-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .medidor-header {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .loss-card {
            background-color: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-custom {
            background-color: #3498db;
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background-color: #2980b9;
        }
        .chart-container {
            height: 300px;
            margin-top: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .highlight-loss {
            background-color: #ffebee;
            font-weight: bold;
        }
    </style>
</head>

</head>
<body>

<main class="main container mt-4">
    <h1>Historial de Lecturas</h1>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Fecha</th>
                    <th>Producción (m³)</th>
                    <th>Distribución (m³)</th>
                    <th>Pérdidas (m³)</th>
                    <th>% Pérdidas</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody id="historialLecturas">
                <!-- Se carga por JavaScript -->
            </tbody>
        </table>
    </div>

    <div class="chart-container mt-4">
        <canvas id="graficoPerdidas"></canvas>
    </div>

    <div class="text-center mt-3">
        <a href="registro.html" class="btn btn-custom">
            <i class="bi bi-arrow-left"></i> Volver al Formulario
        </a>
    </div>
</main>

<!-- Footer -->

<!-- Scripts -->
<script src="https://hydrosite.cl/public/theme/common/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Simula datos históricos (o conéctalo a una base real)
    const lecturasHistorial = [
        { fecha: "2023-10-01", extraccion: 1250.0, entrega: 1150.0, responsable: "Juan Pérez" },
        { fecha: "2023-10-08", extraccion: 1350.0, entrega: 1240.0, responsable: "María López" },
        { fecha: "2023-10-15", extraccion: 1470.0, entrega: 1340.0, responsable: "Carlos Ruiz" },
        { fecha: "2023-10-22", extraccion: 1580.0, entrega: 1450.0, responsable: "Ana Martínez" }
    ];

    const ctx = document.getElementById('graficoPerdidas').getContext('2d');
    let perdidasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Extracción (m³)',
                    data: [],
                    borderColor: 'blue',
                    backgroundColor: 'lightblue',
                },
                {
                    label: 'Entrega (m³)',
                    data: [],
                    borderColor: 'green',
                    backgroundColor: 'lightgreen',
                },
                {
                    label: 'Pérdidas (m³)',
                    data: [],
                    borderColor: 'red',
                    backgroundColor: 'pink',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    function cargarHistorial() {
        const tbody = $('#historialLecturas');
        tbody.empty();

        const fechas = [];
        const extracciones = [];
        const entregas = [];
        const perdidas = [];

        lecturasHistorial.forEach(item => {
            const perdida = item.extraccion - item.entrega;
            const porcentaje = (perdida / item.extraccion * 100).toFixed(2);
            fechas.push(item.fecha);
            extracciones.push(item.extraccion);
            entregas.push(item.entrega);
            perdidas.push(perdida);

            tbody.append(`
                <tr>
                    <td>${item.fecha}</td>
                    <td>${item.extraccion.toFixed(3)}</td>
                    <td>${item.entrega.toFixed(3)}</td>
                    <td>${perdida.toFixed(3)}</td>
                    <td>${porcentaje}%</td>
                    <td>${item.responsable}</td>
                </tr>
            `);
        });

        perdidasChart.data.labels = fechas;
        perdidasChart.data.datasets[0].data = extracciones;
        perdidasChart.data.datasets[1].data = entregas;
        perdidasChart.data.datasets[2].data = perdidas;
        perdidasChart.update();
    }

    $(document).ready(() => {
        cargarHistorial();
    });
</script>
</body>
</html>

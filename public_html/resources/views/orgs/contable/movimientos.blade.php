@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos - Sistema Financiero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .filtros-avanzados {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 13px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #20c997;
            box-shadow: 0 0 0 3px rgba(32, 201, 151, 0.1);
        }
        
        .estadisticas-rapidas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #e8f8f5 0%, #d1ecf1 100%);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #20c997;
        }
        
        .stat-card h5 {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 13px;
        }
        
        .stat-card .valor {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .stat-card .detalle {
            font-size: 11px;
            color: #666;
        }
        
        .stat-ingresos {
            border-left-color: #28a745;
        }
        
        .stat-egresos {
            border-left-color: #dc3545;
        }
        
        .stat-transferencias {
            border-left-color: #ffc107;
        }
        
        .stat-balance {
            border-left-color: #17a2b8;
        }
        
        .valor-positivo {
            color: #28a745;
        }
        
        .valor-negativo {
            color: #dc3545;
        }
        
        .valor-neutral {
            color: #6c757d;
        }
        
        .movimientos-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .movimientos-table th {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
        }
        
        .movimientos-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 12px;
        }
        
        .movimientos-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .movimientos-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        
        .tipo-ingreso {
            background: #d1edff;
            color: #0056b3;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .tipo-egreso {
            background: #f8d7da;
            color: #721c24;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .tipo-giro {
            background: #fff3cd;
            color: #856404;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .tipo-deposito {
            background: #d4edda;
            color: #155724;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }
        
        .paginacion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .paginacion-info {
            color: #666;
            font-size: 14px;
        }
        
        .paginacion-controles {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .btn-paginacion {
            padding: 6px 12px;
            border: 2px solid #20c997;
            background: white;
            color: #20c997;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-paginacion:hover {
            background: #20c997;
            color: white;
        }
        
        .btn-paginacion:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-paginacion.activo {
            background: #20c997;
            color: white;
        }
        
        .acciones-masivas {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #ffc107;
        }
        
        .acciones-masivas h5 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            justify-content: center;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            display: none;
        }
        
        .notification.success { background: #28a745; }
        .notification.error { background: #dc3545; }
        .notification.info { background: #17a2b8; }
        .notification.warning { background: #ffc107; color: #212529; }
        
        .nav-breadcrumb {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .nav-breadcrumb a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .sin-movimientos {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .checkbox-seleccion {
            transform: scale(1.1);
            margin: 0;
        }
        
        .btn-accion {
            background: none;
            border: none;
            color: #17a2b8;
            cursor: pointer;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .btn-accion:hover {
            background: #e7f3ff;
        }
        
        .resumen-periodo {
            background: #d4edda;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .resumen-periodo h5 {
            margin: 0 0 10px 0;
            color: #155724;
        }
        
        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
        }
        
        .resumen-item {
            text-align: center;
        }
        
        .resumen-item .etiqueta {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .resumen-item .valor {
            font-size: 16px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .filtros-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .estadisticas-rapidas {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .movimientos-table {
                font-size: 10px;
            }
            
            .movimientos-table th,
            .movimientos-table td {
                padding: 6px 4px;
            }
            
            .paginacion {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
    <div id="notification" class="notification"></div>

    <div class="container">
        <!-- Navegación -->
        <div class="nav-breadcrumb">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <span> / </span>
            <span>Movimientos</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-list-check"></i> Registro de Movimientos</h1>
                    <p>Historial completo de todas las transacciones financieras</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Filtros Avanzados -->
            <div class="filtros-avanzados">
                <h4><i class="bi bi-funnel-fill"></i> Filtros Avanzados</h4>
                <div class="filtros-grid">
                    <div class="form-group">
                        <label for="fechaDesdeMovimientos">
                            <i class="bi bi-calendar3"></i> Desde
                        </label>
                        <input type="date" id="fechaDesdeMovimientos">
                    </div>
                    
                    <div class="form-group">
                        <label for="fechaHastaMovimientos">
                            <i class="bi bi-calendar3"></i> Hasta
                        </label>
                        <input type="date" id="fechaHastaMovimientos">
                    </div>
                    
                    <div class="form-group">
                        <label for="tipoMovimientos">
                            <i class="bi bi-filter"></i> Tipo
                        </label>
                        <select id="tipoMovimientos">
                            <option value="">Todos</option>
                            <option value="ingreso">Ingresos</option>
                            <option value="egreso">Egresos</option>
                            <option value="giro">Giros</option>
                            <option value="deposito">Depósitos</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="montoMinimo">
                            <i class="bi bi-currency-dollar"></i> Monto Mín.
                        </label>
                        <input type="number" id="montoMinimo" step="0.01" placeholder="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label for="montoMaximo">
                            <i class="bi bi-currency-dollar"></i> Monto Máx.
                        </label>
                        <input type="number" id="montoMaximo" step="0.01" placeholder="Sin límite">
                    </div>
                    
                    <div class="form-group">
                        <label for="buscarTexto">
                            <i class="bi bi-search"></i> Buscar
                        </label>
                        <input type="text" id="buscarTexto" placeholder="Buscar en detalles...">
                    </div>
                    
                    <div class="form-group">
                        <label for="ordenarPor">
                            <i class="bi bi-sort-down"></i> Ordenar
                        </label>
                        <select id="ordenarPor">
                            <option value="fecha_desc">Fecha (Reciente)</option>
                            <option value="fecha_asc">Fecha (Antiguo)</option>
                            <option value="monto_desc">Monto (Mayor)</option>
                            <option value="monto_asc">Monto (Menor)</option>
                            <option value="tipo">Tipo</option>
                        </select>
                    </div>
                    
                    <button id="btnAplicarFiltros" class="btn btn-success">
                        <i class="bi bi-check2"></i> Aplicar
                    </button>
                    
                    <button id="btnLimpiarFiltros" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="estadisticas-rapidas">
                <div class="stat-card stat-ingresos">
                    <h5><i class="bi bi-plus-circle"></i> Ingresos</h5>
                    <div class="valor valor-positivo" id="totalIngresosPeriodo">$0</div>
                    <div class="detalle" id="cantidadIngresos">0 movimientos</div>
                </div>
                
                <div class="stat-card stat-egresos">
                    <h5><i class="bi bi-dash-circle"></i> Egresos</h5>
                    <div class="valor valor-negativo" id="totalEgresosPeriodo">$0</div>
                    <div class="detalle" id="cantidadEgresos">0 movimientos</div>
                </div>
                
                <div class="stat-card stat-transferencias">
                    <h5><i class="bi bi-arrow-left-right"></i> Transferencias</h5>
                    <div class="valor valor-neutral" id="totalTransferencias">$0</div>
                    <div class="detalle" id="cantidadTransferencias">0 movimientos</div>
                </div>
                
                <div class="stat-card stat-balance">
                    <h5><i class="bi bi-calculator"></i> Balance</h5>
                    <div class="valor" id="balancePeriodo">$0</div>
                    <div class="detalle" id="totalMovimientos">0 total</div>
                </div>
            </div>

            <!-- Resumen del Período -->
            <div class="resumen-periodo" id="resumenPeriodo" style="display: none;">
                <h5><i class="bi bi-calendar-check"></i> Resumen del Período Filtrado</h5>
                <div class="resumen-grid">
                    <div class="resumen-item">
                        <div class="etiqueta">Promedio Diario</div>
                        <div class="valor" id="promedioDiario">$0</div>
                    </div>
                    <div class="resumen-item">
                        <div class="etiqueta">Mayor Ingreso</div>
                        <div class="valor valor-positivo" id="mayorIngreso">$0</div>
                    </div>
                    <div class="resumen-item">
                        <div class="etiqueta">Mayor Egreso</div>
                        <div class="valor valor-negativo" id="mayorEgreso">$0</div>
                    </div>
                    <div class="resumen-item">
                        <div class="etiqueta">Días Activos</div>
                        <div class="valor" id="diasActivos">0</div>
                    </div>
                </div>
            </div>

            <!-- Acciones Masivas -->
            <div class="acciones-masivas" id="accionesMasivas" style="display: none;">
                <h5><i class="bi bi-check2-square"></i> Acciones con Seleccionados (<span id="contadorSeleccionados">0</span> movimientos)</h5>
                <div class="btn-group">
                    <button id="btnExportarSeleccionados" class="btn btn-info">
                        <i class="bi bi-file-earmark-excel"></i> Exportar
                    </button>
                    <button id="btnMarcarConciliados" class="btn btn-warning">
                        <i class="bi bi-check-circle"></i> Marcar Conciliados
                    </button>
                    <button id="btnEliminarSeleccionados" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="table-responsive">
                <table class="movimientos-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAllMovimientos" class="checkbox-seleccion">
                            </th>
                            <th style="width: 80px;">Fecha</th>
                            <th style="width: 60px;">Tipo</th>
                            <th>Detalle</th>
                            <th style="width: 100px;">Monto</th>
                            <th style="width: 80px;">Comprobante</th>
                            <th style="width: 80px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaMovimientos">
                        <tr class="sin-movimientos">
                            <td colspan="7">
                                <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                No hay movimientos para mostrar
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="paginacion">
                <div class="paginacion-info">
                    Mostrando <span id="registroDesde">0</span> a <span id="registroHasta">0</span> 
                    de <span id="totalRegistros">0</span> registros
                </div>
                <div class="paginacion-controles">
                    <label for="registrosPorPagina">Mostrar:</label>
                    <select id="registrosPorPagina">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                    </select>
                    
                    <button id="btnPaginaAnterior" class="btn-paginacion">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    
                    <span id="paginasNumeros"></span>
                    
                    <button id="btnPaginaSiguiente" class="btn-paginacion">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><i class="bi bi-lightning"></i> Acciones Rápidas</h3>
            </div>
            <div class="btn-group">
                <a href="{{ route('ingresos.index') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nuevo Ingreso
                </a>
                <a href="{{ route('egresos.index') }}" class="btn btn-info">
                    <i class="bi bi-dash-circle"></i> Nuevo Egreso
                </a>
                <a href="{{ route('libro-caja.index') }}" class="btn btn-info">
                    <i class="bi bi-journal-bookmark"></i> Libro de Caja
                </a>
                <button id="btnExportarTodos" class="btn btn-success">
                    <i class="bi bi-download"></i> Exportar Todo
                </button>
            </div>
        </div>
    </div>

    <script>
        // Configuración CSRF para Laravel
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Storage para comunicación entre módulos
        const STORAGE_KEYS = {
            ingresos: 'finanzas_ingresos',
            egresos: 'finanzas_egresos',
            giros: 'finanzas_giros',
            depositos: 'finanzas_depositos'
        };

        let todosLosMovimientos = [];
        let movimientosFiltrados = [];
        let paginaActual = 1;
        let registrosPorPagina = 25;
        let movimientosSeleccionados = [];

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            configurarFechasPorDefecto();
            cargarTodosLosMovimientos();
        });

        function setupEventListeners() {
            document.getElementById('btnAplicarFiltros').addEventListener('click', aplicarFiltros);
            document.getElementById('btnLimpiarFiltros').addEventListener('click', limpiarFiltros);
            document.getElementById('selectAllMovimientos').addEventListener('change', toggleSelectAll);
            document.getElementById('registrosPorPagina').addEventListener('change', cambiarRegistrosPorPagina);
            document.getElementById('btnPaginaAnterior').addEventListener('click', paginaAnterior);
            document.getElementById('btnPaginaSiguiente').addEventListener('click', paginaSiguiente);
            
            // Acciones masivas
            document.getElementById('btnExportarSeleccionados').addEventListener('click', exportarSeleccionados);
            document.getElementById('btnMarcarConciliados').addEventListener('click', marcarConciliados);
            document.getElementById('btnEliminarSeleccionados').addEventListener('click', eliminarSeleccionados);
            document.getElementById('btnExportarTodos').addEventListener('click', exportarTodos);
            
            // Filtros en tiempo real
            document.getElementById('buscarTexto').addEventListener('input', debounce(aplicarFiltros, 500));
            document.getElementById('montoMinimo').addEventListener('input', debounce(aplicarFiltros, 500));
            document.getElementById('montoMaximo').addEventListener('input', debounce(aplicarFiltros, 500));
            
            // Escuchar eventos de otros módulos
            window.addEventListener('ingresoRegistrado', cargarTodosLosMovimientos);
            window.addEventListener('egresoRegistrado', cargarTodosLosMovimientos);
            window.addEventListener('giroRegistrado', cargarTodosLosMovimientos);
            window.addEventListener('depositoRegistrado', cargarTodosLosMovimientos);
        }

        function configurarFechasPorDefecto() {
            const hoy = new Date();
            const hace30Dias = new Date(hoy.getTime() - (30 * 24 * 60 * 60 * 1000));
            
            document.getElementById('fechaDesdeMovimientos').value = hace30Dias.toISOString().split('T')[0];
            document.getElementById('fechaHastaMovimientos').value = hoy.toISOString().split('T')[0];
        }

        function cargarTodosLosMovimientos() {
            todosLosMovimientos = [];
            
            // Cargar ingresos
            const ingresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.ingresos) || '[]');
            ingresos.forEach(ingreso => {
                todosLosMovimientos.push({
                    ...ingreso,
                    tipo: 'ingreso',
                    esCredito: true,
                    categoria: 'Ingreso'
                });
            });

            // Cargar egresos
            const egresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.egresos) || '[]');
            egresos.forEach(egreso => {
                todosLosMovimientos.push({
                    ...egreso,
                    tipo: 'egreso',
                    esCredito: false,
                    categoria: 'Egreso'
                });
            });

            // Cargar giros
            const giros = JSON.parse(localStorage.getItem(STORAGE_KEYS.giros) || '[]');
            giros.forEach(giro => {
                todosLosMovimientos.push({
                    ...giro,
                    tipo: 'giro',
                    esCredito: true,
                    categoria: 'Transferencia'
                });
            });

            // Cargar depósitos
            const depositos = JSON.parse(localStorage.getItem(STORAGE_KEYS.depositos) || '[]');
            depositos.forEach(deposito => {
                todosLosMovimientos.push({
                    ...deposito,
                    tipo: 'deposito',
                    esCredito: false,
                    categoria: 'Transferencia'
                });
            });

            aplicarFiltros();
        }

        function aplicarFiltros() {
            const fechaDesde = document.getElementById('fechaDesdeMovimientos').value;
            const fechaHasta = document.getElementById('fechaHastaMovimientos').value;
            const tipoFiltro = document.getElementById('tipoMovimientos').value;
            const montoMin = parseFloat(document.getElementById('montoMinimo').value) || 0;
            const montoMax = parseFloat(document.getElementById('montoMaximo').value) || Infinity;
            const busqueda = document.getElementById('buscarTexto').value.toLowerCase();
            const ordenar = document.getElementById('ordenarPor').value;

            movimientosFiltrados = todosLosMovimientos.filter(movimiento => {
                // Filtro por fecha
                if (fechaDesde && movimiento.fecha < fechaDesde) return false;
                if (fechaHasta && movimiento.fecha > fechaHasta) return false;
                
                // Filtro por tipo
                if (tipoFiltro && movimiento.tipo !== tipoFiltro) return false;
                
                // Filtro por monto
                if (movimiento.monto < montoMin || movimiento.monto > montoMax) return false;
                
                // Filtro por búsqueda de texto
                if (busqueda && !movimiento.detalle.toLowerCase().includes(busqueda)) return false;
                
                return true;
            });

            // Aplicar ordenamiento
            aplicarOrdenamiento(ordenar);
            
            // Resetear paginación
            paginaActual = 1;
            
            // Actualizar vista
            actualizarEstadisticas();
            actualizarTabla();
            actualizarPaginacion();
            actualizarResumenPeriodo();
        }

        function aplicarOrdenamiento(criterio) {
            switch (criterio) {
                case 'fecha_desc':
                    movimientosFiltrados.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
                    break;
                case 'fecha_asc':
                    movimientosFiltrados.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
                    break;
                case 'monto_desc':
                    movimientosFiltrados.sort((a, b) => b.monto - a.monto);
                    break;
                case 'monto_asc':
                    movimientosFiltrados.sort((a, b) => a.monto - b.monto);
                    break;
                case 'tipo':
                    movimientosFiltrados.sort((a, b) => a.tipo.localeCompare(b.tipo));
                    break;
            }
        }

        function actualizarEstadisticas() {
            const ingresos = movimientosFiltrados.filter(m => m.tipo === 'ingreso' || m.tipo === 'giro');
            const egresos = movimientosFiltrados.filter(m => m.tipo === 'egreso' || m.tipo === 'deposito');
            const transferencias = movimientosFiltrados.filter(m => m.tipo === 'giro' || m.tipo === 'deposito');

            const totalIngresos = ingresos.reduce((sum, m) => sum + m.monto, 0);
            const totalEgresos = egresos.reduce((sum, m) => sum + m.monto, 0);
            const totalTransferencias = transferencias.reduce((sum, m) => sum + m.monto, 0);
            const balance = totalIngresos - totalEgresos;

            document.getElementById('totalIngresosPeriodo').textContent = formatearMoneda(totalIngresos);
            document.getElementById('cantidadIngresos').textContent = `${ingresos.length} movimientos`;
            
            document.getElementById('totalEgresosPeriodo').textContent = formatearMoneda(totalEgresos);
            document.getElementById('cantidadEgresos').textContent = `${egresos.length} movimientos`;
            
            document.getElementById('totalTransferencias').textContent = formatearMoneda(totalTransferencias);
            document.getElementById('cantidadTransferencias').textContent = `${transferencias.length} movimientos`;
            
            document.getElementById('balancePeriodo').textContent = formatearMoneda(balance);
            document.getElementById('totalMovimientos').textContent = `${movimientosFiltrados.length} total`;

            // Colorear balance
            const balanceElement = document.getElementById('balancePeriodo');
            balanceElement.className = `valor ${balance >= 0 ? 'valor-positivo' : 'valor-negativo'}`;
        }

        function actualizarResumenPeriodo() {
            if (movimientosFiltrados.length === 0) {
                document.getElementById('resumenPeriodo').style.display = 'none';
                return;
            }

            document.getElementById('resumenPeriodo').style.display = 'block';

            // Calcular estadísticas adicionales
            const fechas = [...new Set(movimientosFiltrados.map(m => m.fecha))];
            const diasActivos = fechas.length;
            
            const totalBalance = movimientosFiltrados.reduce((sum, m) => {
                return sum + (m.esCredito ? m.monto : -m.monto);
            }, 0);
            
            const promedioDiario = diasActivos > 0 ? totalBalance / diasActivos : 0;
            
            const ingresos = movimientosFiltrados.filter(m => m.esCredito);
            const egresos = movimientosFiltrados.filter(m => !m.esCredito);
            
            const mayorIngreso = ingresos.length > 0 ? Math.max(...ingresos.map(m => m.monto)) : 0;
            const mayorEgreso = egresos.length > 0 ? Math.max(...egresos.map(m => m.monto)) : 0;

            document.getElementById('promedioDiario').textContent = formatearMoneda(promedioDiario);
            document.getElementById('mayorIngreso').textContent = formatearMoneda(mayorIngreso);
            document.getElementById('mayorEgreso').textContent = formatearMoneda(mayorEgreso);
            document.getElementById('diasActivos').textContent = diasActivos;
        }

        function actualizarTabla() {
            const tbody = document.getElementById('tablaMovimientos');
            
            if (movimientosFiltrados.length === 0) {
                tbody.innerHTML = `
                    <tr class="sin-movimientos">
                        <td colspan="7">
                            <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            No hay movimientos que coincidan con los filtros aplicados
                        </td>
                    </tr>
                `;
                return;
            }

            const inicio = (paginaActual - 1) * registrosPorPagina;
            const fin = inicio + registrosPorPagina;
            const movimientosPagina = movimientosFiltrados.slice(inicio, fin);

            let html = '';
            movimientosPagina.forEach((movimiento, index) => {
                const tipoClass = getTipoClass(movimiento.tipo);
                const montoClass = movimiento.esCredito ? 'valor-positivo' : 'valor-negativo';
                const signoMonto = movimiento.esCredito ? '+' : '-';
                const comprobanteNum = generarNumeroComprobante(movimiento.id || (inicio + index), movimiento.tipo);

                html += `
                    <tr>
                        <td>
                            <input type="checkbox" 
                                   class="checkbox-seleccion movimiento-checkbox" 
                                   data-id="${movimiento.id || (inicio + index)}"
                                   data-tipo="${movimiento.tipo}">
                        </td>
                        <td>${formatearFecha(movimiento.fecha)}</td>
                        <td>
                            <span class="${tipoClass}">${movimiento.tipo.toUpperCase()}</span>
                        </td>
                        <td title="${movimiento.detalle}">
                            ${truncarTexto(movimiento.detalle, 40)}
                        </td>
                        <td class="${montoClass}">
                            ${signoMonto}${formatearMoneda(movimiento.monto)}
                        </td>
                        <td>
                            <button class="btn-accion" onclick="verComprobante('${movimiento.id}', '${movimiento.tipo}')">
                                ${comprobanteNum}
                            </button>
                        </td>
                        <td>
                            <button class="btn-accion" onclick="editarMovimiento('${movimiento.id}', '${movimiento.tipo}')" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn-accion" onclick="eliminarMovimiento('${movimiento.id}', '${movimiento.tipo}')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            
            // Configurar eventos de selección
            configurarCheckboxes();
        }

        function configurarCheckboxes() {
            const checkboxes = document.querySelectorAll('.movimiento-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', actualizarSeleccion);
            });
        }

        function actualizarSeleccion() {
            const checkboxes = document.querySelectorAll('.movimiento-checkbox:checked');
            const contadorElement = document.getElementById('contadorSeleccionados');
            const accionesMasivas = document.getElementById('accionesMasivas');
            
            contadorElement.textContent = checkboxes.length;
            
            if (checkboxes.length > 0) {
                accionesMasivas.style.display = 'block';
                movimientosSeleccionados = Array.from(checkboxes).map(cb => ({
                    id: cb.dataset.id,
                    tipo: cb.dataset.tipo
                }));
            } else {
                accionesMasivas.style.display = 'none';
                movimientosSeleccionados = [];
            }
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAllMovimientos');
            const checkboxes = document.querySelectorAll('.movimiento-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            actualizarSeleccion();
        }

        function actualizarPaginacion() {
            const totalPaginas = Math.ceil(movimientosFiltrados.length / registrosPorPagina);
            const inicio = (paginaActual - 1) * registrosPorPagina + 1;
            const fin = Math.min(paginaActual * registrosPorPagina, movimientosFiltrados.length);

            document.getElementById('registroDesde').textContent = movimientosFiltrados.length > 0 ? inicio : 0;
            document.getElementById('registroHasta').textContent = fin;
            document.getElementById('totalRegistros').textContent = movimientosFiltrados.length;

            // Controles de paginación
            document.getElementById('btnPaginaAnterior').disabled = paginaActual <= 1;
            document.getElementById('btnPaginaSiguiente').disabled = paginaActual >= totalPaginas;

            // Números de páginas
            generarNumerosPaginas(totalPaginas);
        }

        function generarNumerosPaginas(totalPaginas) {
            const container = document.getElementById('paginasNumeros');
            let html = '';

            if (totalPaginas <= 7) {
                for (let i = 1; i <= totalPaginas; i++) {
                    html += `<button class="btn-paginacion ${i === paginaActual ? 'activo' : ''}" 
                                     onclick="irAPagina(${i})">${i}</button>`;
                }
            } else {
                // Lógica para muchas páginas
                if (paginaActual <= 4) {
                    for (let i = 1; i <= 5; i++) {
                        html += `<button class="btn-paginacion ${i === paginaActual ? 'activo' : ''}" 
                                         onclick="irAPagina(${i})">${i}</button>`;
                    }
                    html += '<span>...</span>';
                    html += `<button class="btn-paginacion" onclick="irAPagina(${totalPaginas})">${totalPaginas}</button>`;
                } else if (paginaActual >= totalPaginas - 3) {
                    html += `<button class="btn-paginacion" onclick="irAPagina(1)">1</button>`;
                    html += '<span>...</span>';
                    for (let i = totalPaginas - 4; i <= totalPaginas; i++) {
                        html += `<button class="btn-paginacion ${i === paginaActual ? 'activo' : ''}" 
                                         onclick="irAPagina(${i})">${i}</button>`;
                    }
                } else {
                    html += `<button class="btn-paginacion" onclick="irAPagina(1)">1</button>`;
                    html += '<span>...</span>';
                    for (let i = paginaActual - 1; i <= paginaActual + 1; i++) {
                        html += `<button class="btn-paginacion ${i === paginaActual ? 'activo' : ''}" 
                                         onclick="irAPagina(${i})">${i}</button>`;
                    }
                    html += '<span>...</span>';
                    html += `<button class="btn-paginacion" onclick="irAPagina(${totalPaginas})">${totalPaginas}</button>`;
                }
            }

            container.innerHTML = html;
        }

        function irAPagina(pagina) {
            paginaActual = pagina;
            actualizarTabla();
            actualizarPaginacion();
        }

        function paginaAnterior() {
            if (paginaActual > 1) {
                irAPagina(paginaActual - 1);
            }
        }

        function paginaSiguiente() {
            const totalPaginas = Math.ceil(movimientosFiltrados.length / registrosPorPagina);
            if (paginaActual < totalPaginas) {
                irAPagina(paginaActual + 1);
            }
        }

        function cambiarRegistrosPorPagina() {
            registrosPorPagina = parseInt(document.getElementById('registrosPorPagina').value);
            paginaActual = 1;
            actualizarTabla();
            actualizarPaginacion();
        }

        function limpiarFiltros() {
            document.getElementById('fechaDesdeMovimientos').value = '';
            document.getElementById('fechaHastaMovimientos').value = '';
            document.getElementById('tipoMovimientos').value = '';
            document.getElementById('montoMinimo').value = '';
            document.getElementById('montoMaximo').value = '';
            document.getElementById('buscarTexto').value = '';
            document.getElementById('ordenarPor').value = 'fecha_desc';
            
            configurarFechasPorDefecto();
            aplicarFiltros();
        }

        function exportarSeleccionados() {
            if (movimientosSeleccionados.length === 0) {
                mostrarNotificacion('Seleccione al menos un movimiento para exportar', 'warning');
                return;
            }

            exportarMovimientos(movimientosSeleccionados, 'seleccionados');
        }

        function exportarTodos() {
            if (movimientosFiltrados.length === 0) {
                mostrarNotificacion('No hay movimientos para exportar', 'warning');
                return;
            }

            exportarMovimientos(movimientosFiltrados, 'todos');
        }

        function exportarMovimientos(movimientos, tipo) {
            const datos = movimientos.map(movimiento => ({
                'Fecha': formatearFecha(movimiento.fecha),
                'Tipo': movimiento.tipo.toUpperCase(),
                'Detalle': movimiento.detalle,
                'Monto': movimiento.monto,
                'Categoría': movimiento.categoria || 'Sin categoría',
                'Es Crédito': movimiento.esCredito ? 'Sí' : 'No'
            }));

            const csv = convertirACSV(datos);
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `movimientos_${tipo}_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            mostrarNotificacion(`${movimientos.length} movimientos exportados exitosamente`, 'success');
        }

        function marcarConciliados() {
            if (movimientosSeleccionados.length === 0) {
                mostrarNotificacion('Seleccione movimientos para marcar como conciliados', 'warning');
                return;
            }

            // Aquí se implementaría la lógica de conciliación
            mostrarNotificacion(`${movimientosSeleccionados.length} movimientos marcados como conciliados`, 'success');
            
            // Limpiar selección
            document.getElementById('selectAllMovimientos').checked = false;
            actualizarSeleccion();
        }

        function eliminarSeleccionados() {
            if (movimientosSeleccionados.length === 0) {
                mostrarNotificacion('Seleccione movimientos para eliminar', 'warning');
                return;
            }

            if (confirm(`¿Está seguro de que desea eliminar ${movimientosSeleccionados.length} movimiento(s)?`)) {
                // Aquí se implementaría la lógica de eliminación
                mostrarNotificacion(`${movimientosSeleccionados.length} movimientos eliminados`, 'success');
                
                // Recargar datos
                cargarTodosLosMovimientos();
            }
        }

        function verComprobante(id, tipo) {
            mostrarNotificacion(`Abriendo comprobante ${generarNumeroComprobante(id, tipo)}`, 'info');
        }

        function editarMovimiento(id, tipo) {
            const rutas = {
                'ingreso': '{{ route("ingresos.index") }}',
                'egreso': '{{ route("egresos.index") }}',
                'giro': '{{ route("giros-depositos.index") }}',
                'deposito': '{{ route("giros-depositos.index") }}'
            };
            
            if (rutas[tipo]) {
                window.location.href = rutas[tipo];
            }
        }

        function eliminarMovimiento(id, tipo) {
            if (confirm('¿Está seguro de que desea eliminar este movimiento?')) {
                mostrarNotificacion('Movimiento eliminado exitosamente', 'success');
                cargarTodosLosMovimientos();
            }
        }

        function getTipoClass(tipo) {
            const clases = {
                'ingreso': 'tipo-ingreso',
                'egreso': 'tipo-egreso',
                'giro': 'tipo-giro',
                'deposito': 'tipo-deposito'
            };
            return clases[tipo] || 'tipo-neutral';
        }

        function generarNumeroComprobante(id, tipo) {
            const prefijos = {
                'ingreso': 'ING',
                'egreso': 'EGR',
                'giro': 'GIR',
                'deposito': 'DEP'
            };
            const prefijo = prefijos[tipo] || 'MOV';
            const numero = String(id).padStart(6, '0');
            return `${prefijo}-${numero}`;
        }

        function convertirACSV(objetos) {
            if (objetos.length === 0) return '';
            
            const headers = Object.keys(objetos[0]);
            const csvHeaders = headers.join(',');
            const csvRows = objetos.map(obj => 
                headers.map(header => `"${obj[header]}"`).join(',')
            );
            
            return [csvHeaders, ...csvRows].join('\n');
        }

        function truncarTexto(texto, maxLength) {
            return texto.length > maxLength ? texto.substring(0, maxLength) + '...' : texto;
        }

        function formatearMoneda(valor) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP'
            }).format(valor || 0);
        }

        function formatearFecha(fecha) {
            return new Date(fecha).toLocaleDateString('es-CL');
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = mensaje;
            notification.className = `notification ${tipo}`;
            notification.style.display = 'block';
            
            setTimeout(() => {
                notification.style.display = 'none';
            }, 4000);
        }

        // Escuchar cambios de localStorage de otros módulos
        window.addEventListener('storage', function(e) {
            if (Object.values(STORAGE_KEYS).includes(e.key)) {
                cargarTodosLosMovimientos();
            }
        });
    </script>
</body>
</html>
@endsection

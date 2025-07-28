@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conciliación Bancaria - Sistema Financiero</title>
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
            background: linear-gradient(135deg, #fd7e14 0%, #f26b38 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .conciliacion-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .saldos-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .saldo-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #fff3e0 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #fd7e14;
        }
        
        .saldo-card h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        
        .saldo-card .valor {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .saldo-sistema {
            border-left-color: #28a745;
        }
        
        .saldo-banco {
            border-left-color: #17a2b8;
        }
        
        .diferencia {
            border-left-color: #dc3545;
        }
        
        .estado-conciliado {
            border-left-color: #28a745;
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
        
        .filtros-conciliacion {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #fd7e14;
            box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.1);
        }
        
        .movimientos-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .movimientos-section h4 {
            margin: 0 0 20px 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .conciliacion-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .conciliacion-table th {
            background: linear-gradient(135deg, #fd7e14 0%, #f26b38 100%);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        .conciliacion-table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
        }
        
        .conciliacion-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .conciliacion-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        
        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .estado-conciliado {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .estado-diferencia {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .btn-conciliar {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 11px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn-conciliar:hover {
            background: #218838;
        }
        
        .btn-conciliar:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .diferencias-section {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            justify-content: center;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #fd7e14 0%, #f26b38 100%);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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
        .notification.warning { background: #fd7e14; }
        
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
            padding: 30px;
            color: #6c757d;
            font-style: italic;
        }
        
        .checkbox-conciliar {
            transform: scale(1.2);
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .conciliacion-grid {
                grid-template-columns: 1fr;
            }
            
            .saldos-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filtros-grid {
                grid-template-columns: 1fr;
            }
            
            .conciliacion-table {
                font-size: 11px;
            }
            
            .conciliacion-table th,
            .conciliacion-table td {
                padding: 8px 6px;
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
            <span>Conciliación Bancaria</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-bank"></i> Conciliación Bancaria</h1>
                    <p>Comparación entre registros del sistema y extractos bancarios</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="filtros-conciliacion">
                <h4><i class="bi bi-funnel"></i> Filtros de Conciliación</h4>
                <div class="filtros-grid">
                    <div class="form-group">
                        <label for="cuentaConciliacion">
                            <i class="bi bi-bank"></i> Cuenta Bancaria
                        </label>
                        <select id="cuentaConciliacion">
                            <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                            <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fechaDesdeConciliacion">
                            <i class="bi bi-calendar3"></i> Fecha Desde
                        </label>
                        <input type="date" id="fechaDesdeConciliacion">
                    </div>
                    
                    <div class="form-group">
                        <label for="fechaHastaConciliacion">
                            <i class="bi bi-calendar3"></i> Fecha Hasta
                        </label>
                        <input type="date" id="fechaHastaConciliacion">
                    </div>
                    
                    <div class="form-group">
                        <label for="saldoBanco">
                            <i class="bi bi-currency-dollar"></i> Saldo Banco
                        </label>
                        <input type="number" id="saldoBanco" step="0.01" placeholder="Saldo según banco">
                    </div>
                    
                    <button id="btnIniciarConciliacion" class="btn btn-warning">
                        <i class="bi bi-play-circle"></i> Iniciar Conciliación
                    </button>
                </div>
            </div>

            <!-- Resumen de Saldos -->
            <div class="saldos-container">
                <div class="saldo-card saldo-sistema">
                    <h4><i class="bi bi-computer"></i> Saldo Sistema</h4>
                    <div class="valor" id="saldoSistema">$0</div>
                    <small>Según registros internos</small>
                </div>
                
                <div class="saldo-card saldo-banco">
                    <h4><i class="bi bi-bank"></i> Saldo Banco</h4>
                    <div class="valor" id="saldoBancoDisplay">$0</div>
                    <small>Según extracto bancario</small>
                </div>
                
                <div class="saldo-card diferencia">
                    <h4><i class="bi bi-exclamation-triangle"></i> Diferencia</h4>
                    <div class="valor" id="diferenciaSaldo">$0</div>
                    <small>Diferencia por conciliar</small>
                </div>
                
                <div class="saldo-card estado-conciliado">
                    <h4><i class="bi bi-check-circle"></i> Estado</h4>
                    <div class="valor" id="estadoConciliacion">-</div>
                    <small>Estado de conciliación</small>
                </div>
            </div>

            <!-- Secciones de Movimientos -->
            <div class="conciliacion-grid">
                <!-- Movimientos del Sistema -->
                <div class="movimientos-section">
                    <h4><i class="bi bi-list-check"></i> Movimientos del Sistema</h4>
                    <div class="table-responsive">
                        <table class="conciliacion-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="selectAllSistema" class="checkbox-conciliar">
                                    </th>
                                    <th>Fecha</th>
                                    <th>Detalle</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="movimientosSistema">
                                <tr class="sin-movimientos">
                                    <td colspan="5">No hay movimientos para mostrar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Movimientos Pendientes/Diferencias -->
                <div class="movimientos-section">
                    <h4><i class="bi bi-clock"></i> Movimientos Pendientes</h4>
                    <div class="table-responsive">
                        <table class="conciliacion-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Detalle</th>
                                    <th>Monto</th>
                                    <th>Motivo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="movimientosPendientes">
                                <tr class="sin-movimientos">
                                    <td colspan="5">No hay movimientos pendientes</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Diferencias Encontradas -->
            <div class="diferencias-section" id="diferenciasSection" style="display: none;">
                <h4><i class="bi bi-exclamation-triangle"></i> Diferencias Encontradas</h4>
                <div id="listaDiferencias"></div>
                <div class="btn-group">
                    <button id="btnAjustarDiferencias" class="btn btn-warning">
                        <i class="bi bi-wrench"></i> Ajustar Diferencias
                    </button>
                    <button id="btnMarcarRevision" class="btn btn-info">
                        <i class="bi bi-flag"></i> Marcar para Revisión
                    </button>
                </div>
            </div>

            <!-- Acciones de Conciliación -->
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h4><i class="bi bi-gear"></i> Acciones de Conciliación</h4>
                </div>
                <div class="btn-group">
                    <button id="btnConciliarSeleccionados" class="btn btn-success">
                        <i class="bi bi-check2-all"></i> Conciliar Seleccionados
                    </button>
                    <button id="btnDesconciliarTodo" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise"></i> Desconciliar Todo
                    </button>
                    <button id="btnExportarConciliacion" class="btn btn-info">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Conciliación
                    </button>
                    <button id="btnGenerarReporte" class="btn btn-success">
                        <i class="bi bi-file-text"></i> Generar Reporte
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
                <a href="{{ route('giros-depositos.index') }}" class="btn btn-info">
                    <i class="bi bi-arrow-left-right"></i> Giros y Depósitos
                </a>
                <a href="{{ route('libro-caja.index') }}" class="btn btn-info">
                    <i class="bi bi-journal-bookmark"></i> Libro de Caja
                </a>
                <a href="{{ route('balance.index') }}" class="btn btn-info">
                    <i class="bi bi-bar-chart"></i> Balance
                </a>
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
            giros: 'finanzas_giros',
            depositos: 'finanzas_depositos',
            saldos: 'finanzas_saldos',
            conciliaciones: 'finanzas_conciliaciones'
        };

        let movimientosCuenta = [];
        let movimientosConciliados = [];
        let diferenciasEncontradas = [];

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            configurarFechasPorDefecto();
            cargarConciliacionesGuardadas();
        });

        function setupEventListeners() {
            document.getElementById('btnIniciarConciliacion').addEventListener('click', iniciarConciliacion);
            document.getElementById('btnConciliarSeleccionados').addEventListener('click', conciliarSeleccionados);
            document.getElementById('btnDesconciliarTodo').addEventListener('click', desconciliarTodo);
            document.getElementById('btnExportarConciliacion').addEventListener('click', exportarConciliacion);
            document.getElementById('btnGenerarReporte').addEventListener('click', generarReporte);
            document.getElementById('btnAjustarDiferencias').addEventListener('click', ajustarDiferencias);
            document.getElementById('btnMarcarRevision').addEventListener('click', marcarParaRevision);
            
            document.getElementById('selectAllSistema').addEventListener('change', toggleSelectAll);
            document.getElementById('saldoBanco').addEventListener('input', calcularDiferencia);
            
            // Escuchar eventos de otros módulos
            window.addEventListener('giroRegistrado', cargarMovimientosCuenta);
            window.addEventListener('depositoRegistrado', cargarMovimientosCuenta);
        }

        function configurarFechasPorDefecto() {
            const hoy = new Date();
            const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            
            document.getElementById('fechaDesdeConciliacion').value = primerDiaMes.toISOString().split('T')[0];
            document.getElementById('fechaHastaConciliacion').value = hoy.toISOString().split('T')[0];
        }

        function cargarConciliacionesGuardadas() {
            movimientosConciliados = JSON.parse(localStorage.getItem(STORAGE_KEYS.conciliaciones) || '[]');
        }

        function iniciarConciliacion() {
            const cuenta = document.getElementById('cuentaConciliacion').value;
            const fechaDesde = document.getElementById('fechaDesdeConciliacion').value;
            const fechaHasta = document.getElementById('fechaHastaConciliacion').value;
            const saldoBanco = parseFloat(document.getElementById('saldoBanco').value) || 0;

            if (!fechaDesde || !fechaHasta) {
                mostrarNotificacion('Seleccione el rango de fechas', 'error');
                return;
            }

            if (saldoBanco === 0) {
                mostrarNotificacion('Ingrese el saldo según el banco', 'warning');
                return;
            }

            cargarMovimientosCuenta();
            actualizarSaldos();
            mostrarNotificacion('Conciliación iniciada exitosamente', 'success');
        }

        function cargarMovimientosCuenta() {
            const cuenta = document.getElementById('cuentaConciliacion').value;
            const fechaDesde = document.getElementById('fechaDesdeConciliacion').value;
            const fechaHasta = document.getElementById('fechaHastaConciliacion').value;

            movimientosCuenta = [];

            // Cargar giros (aumentan saldo en cuenta)
            const giros = JSON.parse(localStorage.getItem(STORAGE_KEYS.giros) || '[]');
            giros.forEach(giro => {
                if (giro.cuenta_origen === cuenta && 
                    giro.fecha >= fechaDesde && 
                    giro.fecha <= fechaHasta) {
                    movimientosCuenta.push({
                        ...giro,
                        tipo: 'giro',
                        monto: -giro.monto, // Giro reduce saldo de cuenta bancaria
                        conciliado: movimientosConciliados.includes(giro.id)
                    });
                }
            });

            // Cargar depósitos (aumentan saldo en cuenta)
            const depositos = JSON.parse(localStorage.getItem(STORAGE_KEYS.depositos) || '[]');
            depositos.forEach(deposito => {
                if (deposito.cuenta_destino === cuenta && 
                    deposito.fecha >= fechaDesde && 
                    deposito.fecha <= fechaHasta) {
                    movimientosCuenta.push({
                        ...deposito,
                        tipo: 'deposito',
                        monto: deposito.monto, // Depósito aumenta saldo de cuenta bancaria
                        conciliado: movimientosConciliados.includes(deposito.id)
                    });
                }
            });

            // Ordenar por fecha
            movimientosCuenta.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));

            renderizarMovimientosSistema();
            identificarMovimientosPendientes();
        }

        function renderizarMovimientosSistema() {
            const tbody = document.getElementById('movimientosSistema');
            
            if (movimientosCuenta.length === 0) {
                tbody.innerHTML = `
                    <tr class="sin-movimientos">
                        <td colspan="5">No hay movimientos en el período seleccionado</td>
                    </tr>
                `;
                return;
            }

            let html = '';
            movimientosCuenta.forEach((movimiento, index) => {
                const estadoClass = movimiento.conciliado ? 'estado-conciliado' : 'estado-pendiente';
                const estadoTexto = movimiento.conciliado ? 'Conciliado' : 'Pendiente';
                const montoClass = movimiento.monto >= 0 ? 'valor-positivo' : 'valor-negativo';

                html += `
                    <tr>
                        <td>
                            <input type="checkbox" 
                                   class="checkbox-conciliar movimento-checkbox" 
                                   data-index="${index}"
                                   ${movimiento.conciliado ? 'checked disabled' : ''}>
                        </td>
                        <td>${formatearFecha(movimiento.fecha)}</td>
                        <td>${movimiento.detalle}</td>
                        <td class="${montoClass}">
                            ${formatearMoneda(movimiento.monto)}
                        </td>
                        <td>
                            <span class="${estadoClass}">${estadoTexto}</span>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function identificarMovimientosPendientes() {
            const movimientosPendientes = movimientosCuenta.filter(mov => !mov.conciliado);
            const tbody = document.getElementById('movimientosPendientes');
            
            if (movimientosPendientes.length === 0) {
                tbody.innerHTML = `
                    <tr class="sin-movimientos">
                        <td colspan="5">No hay movimientos pendientes</td>
                    </tr>
                `;
                return;
            }

            let html = '';
            movimientosPendientes.forEach(movimiento => {
                html += `
                    <tr>
                        <td>${formatearFecha(movimiento.fecha)}</td>
                        <td>${movimiento.detalle}</td>
                        <td class="${movimiento.monto >= 0 ? 'valor-positivo' : 'valor-negativo'}">
                            ${formatearMoneda(movimiento.monto)}
                        </td>
                        <td>
                            <span class="estado-pendiente">Pendiente conciliación</span>
                        </td>
                        <td>
                            <button class="btn-conciliar" onclick="conciliarIndividual('${movimiento.id}')">
                                Conciliar
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        function actualizarSaldos() {
            const cuenta = document.getElementById('cuentaConciliacion').value;
            const saldoBanco = parseFloat(document.getElementById('saldoBanco').value) || 0;
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{}');
            const saldoSistema = saldos[cuenta] || 0;
            const diferencia = saldoSistema - saldoBanco;

            document.getElementById('saldoSistema').textContent = formatearMoneda(saldoSistema);
            document.getElementById('saldoBancoDisplay').textContent = formatearMoneda(saldoBanco);
            document.getElementById('diferenciaSaldo').textContent = formatearMoneda(diferencia);

            // Colorear diferencia
            const diferenciaElement = document.getElementById('diferenciaSaldo');
            diferenciaElement.className = `valor ${diferencia === 0 ? 'valor-neutral' : (diferencia > 0 ? 'valor-positivo' : 'valor-negativo')}`;

            // Estado de conciliación
            const estadoElement = document.getElementById('estadoConciliacion');
            if (Math.abs(diferencia) < 0.01) { // Considerar conciliado si diferencia < 1 centavo
                estadoElement.textContent = 'Conciliado';
                estadoElement.className = 'valor valor-positivo';
                document.getElementById('diferenciasSection').style.display = 'none';
            } else {
                estadoElement.textContent = 'Diferencias';
                estadoElement.className = 'valor valor-negativo';
                mostrarDiferencias(diferencia);
            }
        }

        function mostrarDiferencias(diferencia) {
            const section = document.getElementById('diferenciasSection');
            const lista = document.getElementById('listaDiferencias');
            
            let html = '<ul>';
            if (diferencia > 0) {
                html += `<li>El sistema registra <strong>${formatearMoneda(diferencia)}</strong> más que el banco</li>`;
                html += '<li>Posibles causas: cheques emitidos no cobrados, depósitos en tránsito</li>';
            } else {
                html += `<li>El banco registra <strong>${formatearMoneda(-diferencia)}</strong> más que el sistema</li>`;
                html += '<li>Posibles causas: comisiones bancarias, intereses, movimientos no registrados</li>';
            }
            html += '</ul>';
            
            lista.innerHTML = html;
            section.style.display = 'block';
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAllSistema');
            const checkboxes = document.querySelectorAll('.movimento-checkbox:not([disabled])');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function conciliarSeleccionados() {
            const checkboxes = document.querySelectorAll('.movimento-checkbox:checked:not([disabled])');
            
            if (checkboxes.length === 0) {
                mostrarNotificacion('Seleccione al menos un movimiento para conciliar', 'warning');
                return;
            }

            checkboxes.forEach(checkbox => {
                const index = parseInt(checkbox.dataset.index);
                const movimiento = movimientosCuenta[index];
                
                if (!movimientosConciliados.includes(movimiento.id)) {
                    movimientosConciliados.push(movimiento.id);
                    movimiento.conciliado = true;
                }
            });

            // Guardar conciliaciones
            localStorage.setItem(STORAGE_KEYS.conciliaciones, JSON.stringify(movimientosConciliados));
            
            // Actualizar vista
            renderizarMovimientosSistema();
            identificarMovimientosPendientes();
            calcularDiferencia();
            
            mostrarNotificacion(`${checkboxes.length} movimiento(s) conciliado(s) exitosamente`, 'success');
        }

        function conciliarIndividual(movimientoId) {
            if (!movimientosConciliados.includes(movimientoId)) {
                movimientosConciliados.push(movimientoId);
                localStorage.setItem(STORAGE_KEYS.conciliaciones, JSON.stringify(movimientosConciliados));
                
                // Actualizar movimiento en array local
                const movimiento = movimientosCuenta.find(mov => mov.id == movimientoId);
                if (movimiento) {
                    movimiento.conciliado = true;
                }
                
                renderizarMovimientosSistema();
                identificarMovimientosPendientes();
                calcularDiferencia();
                
                mostrarNotificacion('Movimiento conciliado exitosamente', 'success');
            }
        }

        function desconciliarTodo() {
            if (confirm('¿Está seguro de que desea desconciliar todos los movimientos?')) {
                movimientosConciliados = [];
                localStorage.setItem(STORAGE_KEYS.conciliaciones, JSON.stringify(movimientosConciliados));
                
                // Actualizar movimientos en array local
                movimientosCuenta.forEach(movimiento => {
                    movimiento.conciliado = false;
                });
                
                renderizarMovimientosSistema();
                identificarMovimientosPendientes();
                calcularDiferencia();
                
                mostrarNotificacion('Todos los movimientos han sido desconciliados', 'info');
            }
        }

        function calcularDiferencia() {
            setTimeout(() => {
                actualizarSaldos();
            }, 100);
        }

        function ajustarDiferencias() {
            const diferencia = parseFloat(document.getElementById('diferenciaSaldo').textContent.replace(/[^\d.-]/g, ''));
            
            if (Math.abs(diferencia) < 0.01) {
                mostrarNotificacion('No hay diferencias que ajustar', 'info');
                return;
            }
            
            if (confirm(`¿Desea crear un ajuste contable por ${formatearMoneda(diferencia)}?`)) {
                // Aquí se podría crear un ajuste automático
                mostrarNotificacion('Funcionalidad de ajuste automático en desarrollo', 'info');
            }
        }

        function marcarParaRevision() {
            mostrarNotificacion('Diferencias marcadas para revisión posterior', 'info');
        }

        function exportarConciliacion() {
            const cuenta = document.getElementById('cuentaConciliacion').value;
            const fechaDesde = document.getElementById('fechaDesdeConciliacion').value;
            const fechaHasta = document.getElementById('fechaHastaBalance').value;
            
            const datos = {
                cuenta,
                periodo: { desde: fechaDesde, hasta: fechaHasta },
                movimientos: movimientosCuenta,
                saldos: {
                    sistema: document.getElementById('saldoSistema').textContent,
                    banco: document.getElementById('saldoBancoDisplay').textContent,
                    diferencia: document.getElementById('diferenciaSaldo').textContent
                },
                fecha_conciliacion: new Date().toISOString()
            };

            const dataStr = JSON.stringify(datos, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `conciliacion_${cuenta}_${fechaDesde}_${fechaHasta}.json`;
            link.click();
            URL.revokeObjectURL(url);

            mostrarNotificacion('Conciliación exportada exitosamente', 'success');
        }

        function generarReporte() {
            mostrarNotificacion('Generando reporte de conciliación...', 'info');
            
            // Simular generación de reporte
            setTimeout(() => {
                mostrarNotificacion('Reporte de conciliación generado exitosamente', 'success');
            }, 2000);
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
                cargarMovimientosCuenta();
            }
        });
    </script>
</body>
</html>
@endsection

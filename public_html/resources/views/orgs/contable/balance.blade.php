@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance - Sistema Financiero</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .balance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .resumen-financiero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .metrica-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #6f42c1;
            transition: transform 0.3s ease;
        }
        
        .metrica-card:hover {
            transform: translateY(-5px);
        }
        
        .metrica-card h4 {
            margin: 0;
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .metrica-card .valor {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .metrica-card .porcentaje {
            font-size: 14px;
            font-weight: 600;
        }
        
        .activos {
            border-left-color: #28a745;
        }
        
        .pasivos {
            border-left-color: #dc3545;
        }
        
        .patrimonio {
            border-left-color: #17a2b8;
        }
        
        .liquidez {
            border-left-color: #ffc107;
        }
        
        .rentabilidad {
            border-left-color: #6f42c1;
        }
        
        .eficiencia {
            border-left-color: #fd7e14;
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
        
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        
        .balance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .balance-table th {
            background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        
        .balance-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }
        
        .balance-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .balance-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        
        .seccion-balance {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .seccion-balance h4 {
            margin: 0 0 15px 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .activos-section {
            border-left: 4px solid #28a745;
        }
        
        .pasivos-section {
            border-left: 4px solid #dc3545;
        }
        
        .patrimonio-section {
            border-left: 4px solid #17a2b8;
        }
        
        .filtros-periodo {
            display: flex;
            gap: 15px;
            align-items: end;
            margin-bottom: 20px;
            flex-wrap: wrap;
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
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
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
        
        .btn-primary {
            background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
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
        
        .indicadores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .indicador {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .indicador h5 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        
        .indicador .valor-indicador {
            font-size: 20px;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .balance-grid {
                grid-template-columns: 1fr;
            }
            
            .resumen-financiero {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filtros-periodo {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filtros-periodo .form-group {
                width: 100%;
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
            <span>Balance</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-bar-chart"></i> Balance General</h1>
                    <p>Análisis financiero integral del sistema</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Filtros de Período -->
            <div class="filtros-periodo">
                <div class="form-group">
                    <label for="fechaDesdeBalance">
                        <i class="bi bi-calendar3"></i> Desde
                    </label>
                    <input type="date" id="fechaDesdeBalance">
                </div>
                
                <div class="form-group">
                    <label for="fechaHastaBalance">
                        <i class="bi bi-calendar3"></i> Hasta
                    </label>
                    <input type="date" id="fechaHastaBalance">
                </div>
                
                <div class="form-group">
                    <label for="periodoBalance">
                        <i class="bi bi-calendar-range"></i> Período
                    </label>
                    <select id="periodoBalance">
                        <option value="mes_actual">Mes Actual</option>
                        <option value="trimestre">Trimestre</option>
                        <option value="semestre">Semestre</option>
                        <option value="anio">Año</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                
                <button id="btnActualizarBalance" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                </button>
            </div>

            <!-- Resumen Financiero -->
            <div class="resumen-financiero">
                <div class="metrica-card activos">
                    <h4><i class="bi bi-wallet2"></i> Total Activos</h4>
                    <div class="valor valor-positivo" id="totalActivos">$0</div>
                    <div class="porcentaje" id="varActivos">0%</div>
                </div>
                
                <div class="metrica-card pasivos">
                    <h4><i class="bi bi-credit-card"></i> Total Pasivos</h4>
                    <div class="valor valor-negativo" id="totalPasivos">$0</div>
                    <div class="porcentaje" id="varPasivos">0%</div>
                </div>
                
                <div class="metrica-card patrimonio">
                    <h4><i class="bi bi-piggy-bank"></i> Patrimonio</h4>
                    <div class="valor" id="patrimonio">$0</div>
                    <div class="porcentaje" id="varPatrimonio">0%</div>
                </div>
                
                <div class="metrica-card liquidez">
                    <h4><i class="bi bi-droplet"></i> Liquidez</h4>
                    <div class="valor" id="indiceLiquidez">0.0</div>
                    <div class="porcentaje">Ratio</div>
                </div>
                
                <div class="metrica-card rentabilidad">
                    <h4><i class="bi bi-graph-up"></i> Rentabilidad</h4>
                    <div class="valor" id="rentabilidad">0%</div>
                    <div class="porcentaje">ROA</div>
                </div>
                
                <div class="metrica-card eficiencia">
                    <h4><i class="bi bi-speedometer2"></i> Eficiencia</h4>
                    <div class="valor" id="eficiencia">0%</div>
                    <div class="porcentaje">Operativa</div>
                </div>
            </div>

            <!-- Gráficos y Balance -->
            <div class="balance-grid">
                <!-- Gráfico de Composición -->
                <div class="seccion-balance">
                    <h4><i class="bi bi-pie-chart"></i> Composición Patrimonial</h4>
                    <div class="chart-container">
                        <canvas id="composicionChart"></canvas>
                    </div>
                </div>

                <!-- Gráfico de Evolución -->
                <div class="seccion-balance">
                    <h4><i class="bi bi-graph-up"></i> Evolución Mensual</h4>
                    <div class="chart-container">
                        <canvas id="evolucionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Balance Detallado -->
            <div class="seccion-balance activos-section">
                <h4><i class="bi bi-plus-circle"></i> ACTIVOS</h4>
                <div class="table-responsive">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>Cuenta</th>
                                <th>Monto</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody id="activosTable">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="seccion-balance pasivos-section">
                <h4><i class="bi bi-dash-circle"></i> PASIVOS</h4>
                <div class="table-responsive">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>Cuenta</th>
                                <th>Monto</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody id="pasivosTable">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="seccion-balance patrimonio-section">
                <h4><i class="bi bi-bank"></i> PATRIMONIO</h4>
                <div class="table-responsive">
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>Cuenta</th>
                                <th>Monto</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody id="patrimonioTable">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Indicadores Financieros -->
            <div class="seccion-balance">
                <h4><i class="bi bi-calculator"></i> Indicadores Financieros</h4>
                <div class="indicadores-grid">
                    <div class="indicador">
                        <h5>Ratio Corriente</h5>
                        <div class="valor-indicador" id="ratioCorriente">0.0</div>
                    </div>
                    <div class="indicador">
                        <h5>Prueba Ácida</h5>
                        <div class="valor-indicador" id="pruebaAcida">0.0</div>
                    </div>
                    <div class="indicador">
                        <h5>ROE</h5>
                        <div class="valor-indicador" id="roe">0%</div>
                    </div>
                    <div class="indicador">
                        <h5>ROA</h5>
                        <div class="valor-indicador" id="roa">0%</div>
                    </div>
                    <div class="indicador">
                        <h5>Endeudamiento</h5>
                        <div class="valor-indicador" id="endeudamiento">0%</div>
                    </div>
                    <div class="indicador">
                        <h5>Autonomía</h5>
                        <div class="valor-indicador" id="autonomia">0%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><i class="bi bi-lightning"></i> Acciones Rápidas</h3>
            </div>
            <div class="btn-group">
                <a href="{{ route('libro-caja.index') }}" class="btn btn-info">
                    <i class="bi bi-journal-bookmark"></i> Libro de Caja
                </a>
                <a href="{{ route('movimientos.index') }}" class="btn btn-info">
                    <i class="bi bi-list-check"></i> Movimientos
                </a>
                <button id="btnExportarBalance" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Balance
                </button>
                <button id="btnImprimirBalance" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Imprimir
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
            depositos: 'finanzas_depositos',
            saldos: 'finanzas_saldos'
        };

        let composicionChart, evolucionChart;
        let datosBalance = {
            activos: {},
            pasivos: {},
            patrimonio: {},
            indicadores: {}
        };

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            configurarFechasPorDefecto();
            cargarDatosBalance();
            inicializarGraficos();
        });

        function setupEventListeners() {
            document.getElementById('btnActualizarBalance').addEventListener('click', cargarDatosBalance);
            document.getElementById('periodoBalance').addEventListener('change', handlePeriodoChange);
            document.getElementById('btnExportarBalance').addEventListener('click', exportarBalance);
            document.getElementById('btnImprimirBalance').addEventListener('click', imprimirBalance);
            
            // Escuchar eventos de otros módulos
            window.addEventListener('ingresoRegistrado', cargarDatosBalance);
            window.addEventListener('egresoRegistrado', cargarDatosBalance);
            window.addEventListener('giroRegistrado', cargarDatosBalance);
            window.addEventListener('depositoRegistrado', cargarDatosBalance);
        }

        function configurarFechasPorDefecto() {
            const hoy = new Date();
            const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            
            document.getElementById('fechaDesdeBalance').value = primerDiaMes.toISOString().split('T')[0];
            document.getElementById('fechaHastaBalance').value = hoy.toISOString().split('T')[0];
        }

        function handlePeriodoChange() {
            const periodo = document.getElementById('periodoBalance').value;
            const hoy = new Date();
            let fechaDesde, fechaHasta;

            switch (periodo) {
                case 'mes_actual':
                    fechaDesde = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    fechaHasta = hoy;
                    break;
                case 'trimestre':
                    fechaDesde = new Date(hoy.getFullYear(), Math.floor(hoy.getMonth() / 3) * 3, 1);
                    fechaHasta = hoy;
                    break;
                case 'semestre':
                    fechaDesde = new Date(hoy.getFullYear(), hoy.getMonth() < 6 ? 0 : 6, 1);
                    fechaHasta = hoy;
                    break;
                case 'anio':
                    fechaDesde = new Date(hoy.getFullYear(), 0, 1);
                    fechaHasta = hoy;
                    break;
                default:
                    return;
            }

            document.getElementById('fechaDesdeBalance').value = fechaDesde.toISOString().split('T')[0];
            document.getElementById('fechaHastaBalance').value = fechaHasta.toISOString().split('T')[0];
        }

        function cargarDatosBalance() {
            const fechaDesde = document.getElementById('fechaDesdeBalance').value;
            const fechaHasta = document.getElementById('fechaHastaBalance').value;

            // Obtener todos los movimientos
            const ingresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.ingresos) || '[]');
            const egresos = JSON.parse(localStorage.getItem(STORAGE_KEYS.egresos) || '[]');
            const giros = JSON.parse(localStorage.getItem(STORAGE_KEYS.giros) || '[]');
            const depositos = JSON.parse(localStorage.getItem(STORAGE_KEYS.depositos) || '[]');
            const saldos = JSON.parse(localStorage.getItem(STORAGE_KEYS.saldos) || '{"caja_general": 0, "cuenta_corriente_1": 0, "cuenta_corriente_2": 0}');

            // Filtrar por fechas
            const movimientosFiltrados = {
                ingresos: filtrarPorFecha(ingresos, fechaDesde, fechaHasta),
                egresos: filtrarPorFecha(egresos, fechaDesde, fechaHasta),
                giros: filtrarPorFecha(giros, fechaDesde, fechaHasta),
                depositos: filtrarPorFecha(depositos, fechaDesde, fechaHasta)
            };

            calcularBalance(movimientosFiltrados, saldos);
            actualizarVisualizacion();
        }

        function filtrarPorFecha(movimientos, fechaDesde, fechaHasta) {
            return movimientos.filter(mov => {
                if (fechaDesde && mov.fecha < fechaDesde) return false;
                if (fechaHasta && mov.fecha > fechaHasta) return false;
                return true;
            });
        }

        function calcularBalance(movimientos, saldos) {
            // ACTIVOS
            datosBalance.activos = {
                'Caja General': saldos.caja_general || 0,
                'Cuenta Corriente 1': saldos.cuenta_corriente_1 || 0,
                'Cuenta Corriente 2': saldos.cuenta_corriente_2 || 0,
                'Cuentas por Cobrar': calcularCuentasPorCobrar(movimientos),
                'Inventario': 0 // Placeholder
            };

            // PASIVOS
            datosBalance.pasivos = {
                'Cuentas por Pagar': calcularCuentasPorPagar(movimientos),
                'Préstamos': 0, // Placeholder
                'Impuestos por Pagar': calcularImpuestosPorPagar(movimientos)
            };

            // PATRIMONIO
            const totalActivos = Object.values(datosBalance.activos).reduce((sum, val) => sum + val, 0);
            const totalPasivos = Object.values(datosBalance.pasivos).reduce((sum, val) => sum + val, 0);
            const patrimonioNeto = totalActivos - totalPasivos;

            datosBalance.patrimonio = {
                'Capital Inicial': 1000000, // Placeholder
                'Utilidades Retenidas': patrimonioNeto - 1000000,
                'Utilidad del Ejercicio': calcularUtilidadEjercicio(movimientos)
            };

            // INDICADORES
            calcularIndicadores(totalActivos, totalPasivos, patrimonioNeto, movimientos);
        }

        function calcularCuentasPorCobrar(movimientos) {
            // Simplificado: ingresos pendientes de cobro
            return movimientos.ingresos.reduce((sum, ing) => sum + (ing.pendiente || 0), 0);
        }

        function calcularCuentasPorPagar(movimientos) {
            // Simplificado: egresos pendientes de pago
            return movimientos.egresos.reduce((sum, egr) => sum + (egr.pendiente || 0), 0);
        }

        function calcularImpuestosPorPagar(movimientos) {
            // Simplificado: 19% IVA sobre ingresos
            const totalIngresos = movimientos.ingresos.reduce((sum, ing) => sum + ing.monto, 0);
            return totalIngresos * 0.19;
        }

        function calcularUtilidadEjercicio(movimientos) {
            const totalIngresos = movimientos.ingresos.reduce((sum, ing) => sum + ing.monto, 0);
            const totalEgresos = movimientos.egresos.reduce((sum, egr) => sum + egr.monto, 0);
            return totalIngresos - totalEgresos;
        }

        function calcularIndicadores(totalActivos, totalPasivos, patrimonioNeto, movimientos) {
            const activosCorrientes = datosBalance.activos['Caja General'] + 
                                    datosBalance.activos['Cuenta Corriente 1'] + 
                                    datosBalance.activos['Cuenta Corriente 2'];
            const pasivosCorrientes = datosBalance.pasivos['Cuentas por Pagar'] + 
                                    datosBalance.pasivos['Impuestos por Pagar'];

            datosBalance.indicadores = {
                totalActivos,
                totalPasivos,
                patrimonioNeto,
                ratioCorriente: pasivosCorrientes > 0 ? activosCorrientes / pasivosCorrientes : 0,
                pruebaAcida: pasivosCorrientes > 0 ? (activosCorrientes - datosBalance.activos['Inventario']) / pasivosCorrientes : 0,
                roe: patrimonioNeto > 0 ? (calcularUtilidadEjercicio(movimientos) / patrimonioNeto) * 100 : 0,
                roa: totalActivos > 0 ? (calcularUtilidadEjercicio(movimientos) / totalActivos) * 100 : 0,
                endeudamiento: totalActivos > 0 ? (totalPasivos / totalActivos) * 100 : 0,
                autonomia: totalActivos > 0 ? (patrimonioNeto / totalActivos) * 100 : 0
            };
        }

        function actualizarVisualizacion() {
            // Actualizar métricas principales
            document.getElementById('totalActivos').textContent = formatearMoneda(datosBalance.indicadores.totalActivos);
            document.getElementById('totalPasivos').textContent = formatearMoneda(datosBalance.indicadores.totalPasivos);
            document.getElementById('patrimonio').textContent = formatearMoneda(datosBalance.indicadores.patrimonioNeto);
            
            // Colorear patrimonio según sea positivo o negativo
            const patrimonioElement = document.getElementById('patrimonio');
            patrimonioElement.className = `valor ${datosBalance.indicadores.patrimonioNeto >= 0 ? 'valor-positivo' : 'valor-negativo'}`;

            // Actualizar indicadores
            document.getElementById('indiceLiquidez').textContent = datosBalance.indicadores.ratioCorriente.toFixed(2);
            document.getElementById('rentabilidad').textContent = `${datosBalance.indicadores.roa.toFixed(1)}%`;
            document.getElementById('eficiencia').textContent = `${datosBalance.indicadores.autonomia.toFixed(1)}%`;

            // Actualizar indicadores detallados
            document.getElementById('ratioCorriente').textContent = datosBalance.indicadores.ratioCorriente.toFixed(2);
            document.getElementById('pruebaAcida').textContent = datosBalance.indicadores.pruebaAcida.toFixed(2);
            document.getElementById('roe').textContent = `${datosBalance.indicadores.roe.toFixed(1)}%`;
            document.getElementById('roa').textContent = `${datosBalance.indicadores.roa.toFixed(1)}%`;
            document.getElementById('endeudamiento').textContent = `${datosBalance.indicadores.endeudamiento.toFixed(1)}%`;
            document.getElementById('autonomia').textContent = `${datosBalance.indicadores.autonomia.toFixed(1)}%`;

            // Actualizar tablas
            actualizarTablaBalance('activosTable', datosBalance.activos, datosBalance.indicadores.totalActivos);
            actualizarTablaBalance('pasivosTable', datosBalance.pasivos, datosBalance.indicadores.totalPasivos);
            actualizarTablaBalance('patrimonioTable', datosBalance.patrimonio, Math.abs(datosBalance.indicadores.patrimonioNeto));

            // Actualizar gráficos
            actualizarGraficos();
        }

        function actualizarTablaBalance(tableId, datos, total) {
            const tbody = document.getElementById(tableId);
            let html = '';

            Object.entries(datos).forEach(([cuenta, monto]) => {
                const porcentaje = total > 0 ? ((Math.abs(monto) / total) * 100).toFixed(1) : 0;
                html += `
                    <tr>
                        <td>${cuenta}</td>
                        <td class="${monto >= 0 ? 'valor-positivo' : 'valor-negativo'}">
                            ${formatearMoneda(monto)}
                        </td>
                        <td>${porcentaje}%</td>
                    </tr>
                `;
            });

            // Agregar total
            html += `
                <tr style="font-weight: bold; border-top: 2px solid #333;">
                    <td>TOTAL</td>
                    <td class="${total >= 0 ? 'valor-positivo' : 'valor-negativo'}">
                        ${formatearMoneda(total)}
                    </td>
                    <td>100%</td>
                </tr>
            `;

            tbody.innerHTML = html;
        }

        function inicializarGraficos() {
            // Gráfico de composición (pie chart)
            const ctx1 = document.getElementById('composicionChart').getContext('2d');
            composicionChart = new Chart(ctx1, {
                type: 'pie',
                data: {
                    labels: ['Activos', 'Pasivos', 'Patrimonio'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['#28a745', '#dc3545', '#17a2b8'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de evolución (line chart)
            const ctx2 = document.getElementById('evolucionChart').getContext('2d');
            evolucionChart = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Patrimonio',
                        data: [0, 0, 0, 0, 0, 0],
                        borderColor: '#6f42c1',
                        backgroundColor: 'rgba(111, 66, 193, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatearMoneda(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function actualizarGraficos() {
            // Actualizar gráfico de composición
            const totalActivos = Math.abs(datosBalance.indicadores.totalActivos);
            const totalPasivos = Math.abs(datosBalance.indicadores.totalPasivos);
            const totalPatrimonio = Math.abs(datosBalance.indicadores.patrimonioNeto);

            composicionChart.data.datasets[0].data = [totalActivos, totalPasivos, totalPatrimonio];
            composicionChart.update();

            // Actualizar gráfico de evolución (datos simulados)
            const patrimonioActual = datosBalance.indicadores.patrimonioNeto;
            evolucionChart.data.datasets[0].data = [
                patrimonioActual * 0.8,
                patrimonioActual * 0.85,
                patrimonioActual * 0.9,
                patrimonioActual * 0.95,
                patrimonioActual * 0.98,
                patrimonioActual
            ];
            evolucionChart.update();
        }

        function exportarBalance() {
            const datos = {
                fecha: new Date().toISOString().split('T')[0],
                activos: datosBalance.activos,
                pasivos: datosBalance.pasivos,
                patrimonio: datosBalance.patrimonio,
                indicadores: datosBalance.indicadores
            };

            const dataStr = JSON.stringify(datos, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `balance_${datos.fecha}.json`;
            link.click();
            URL.revokeObjectURL(url);

            mostrarNotificacion('Balance exportado exitosamente', 'success');
        }

        function imprimirBalance() {
            window.print();
        }

        function formatearMoneda(valor) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP'
            }).format(valor || 0);
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
                cargarDatosBalance();
            }
        });
    </script>
</body>
</html>
@endsection

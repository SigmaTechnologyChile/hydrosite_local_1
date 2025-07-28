@extends('layouts.app')
@php($title = 'Libro de Caja')
@php($active = '')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro de Caja - Sistema Financiero</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .filtros-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
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
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }

        }
        
        .libro-caja-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .libro-caja-table th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        
        .libro-caja-table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }
        
        .libro-caja-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .libro-caja-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        
        .tipo-ingreso {
            background: #d1edff;
            color: #0056b3;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .tipo-egreso {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .tipo-giro {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .tipo-deposito {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .monto-positivo {
            color: #28a745;
            font-weight: 600;
        }
        
        .monto-negativo {
            color: #dc3545;
            font-weight: 600;
        }
        
        .monto-neutral {
            color: #6c757d;
            font-weight: 600;
        }
        
        .resumen-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .resumen-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #28a745;
        }
        
        .resumen-card h4 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .resumen-card .valor {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .saldo-inicial {
            border-left-color: #17a2b8;
        }
        
        .total-ingresos {
            border-left-color: #28a745;
        }
        
        .total-egresos {
            border-left-color: #dc3545;
        }
        
        .saldo-final {
            border-left-color: #ffc107;
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
        
        .tabla-responsive {
            overflow-x: auto;
            border-radius: 8px;
        }
        
        .sin-movimientos {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .comprobante-btn {
            background: none;
            border: none;
            color: #17a2b8;
            text-decoration: underline;
            cursor: pointer;
            font-size: 12px;
        }
        
        .comprobante-btn:hover {
            color: #138496;
        }
        
        @media (max-width: 768px) {
            .filtros-container {
                grid-template-columns: 1fr;
            }
            
            .resumen-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .libro-caja-table {
                font-size: 12px;
            }
            
            .libro-caja-table th,
            .libro-caja-table td {
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
            <a href="/">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <span> / </span>
            <span>Libro de Caja</span>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h1><i class="bi bi-journal-bookmark"></i> Libro de Caja Tabular</h1>
                    <p>Registro completo de todos los movimientos financieros</p>
                </div>
                <a href="/" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Dashboard
                </a>
            </div>

            <!-- Filtros -->
            <div class="filtros-container">
                <div class="form-group">
                    <label for="fechaDesde">
                        <i class="bi bi-calendar3"></i> Fecha Desde
                    </label>
                    <input type="date" id="fechaDesde" value="">
                </div>
                
                <div class="form-group">
                    <label for="fechaHasta">
                        <i class="bi bi-calendar3"></i> Fecha Hasta
                    </label>
                    <input type="date" id="fechaHasta" value="">
                </div>
                
                <div class="form-group">
                    <label for="tipoMovimiento">
                        <i class="bi bi-filter"></i> Tipo de Movimiento
                    </label>
                    <select id="tipoMovimiento">
                        <option value="">Todos</option>
                        <option value="ingreso">Ingresos</option>
                        <option value="egreso">Egresos</option>
                        <option value="giro">Giros</option>
                        <option value="deposito">Depósitos</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="buscarDetalle">
                        <i class="bi bi-search"></i> Buscar en Detalle
                    </label>
                    <input type="text" id="buscarDetalle" placeholder="Buscar por detalle...">
                </div>
                
                <div class="form-group" style="display: flex; align-items: end;">
                    <button id="btnFiltrar" class="btn btn-success" style="width: 100%;">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>

            <!-- Resumen -->
            <div class="totals-container" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                <div class="total-card ingresos" style="flex: 1; background: #4CAF50; border-left: 5px solid #388E3C; border-radius: 10px; padding: 20px; text-align: center; color: #fff;">
                    <h3 style="color: #fff; margin-bottom: 10px;">Total Ingresos</h3>
                    <div class="value" id="totalIngresos" style="font-size: 1.5em; font-weight: bold; color: #fff;">
                      ${{ number_format($totalIngresos ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="total-card egresos" style="flex: 1; background: #f8d7da; border-left: 5px solid #dc3545; border-radius: 10px; padding: 20px; text-align: center;">
                    <h3 style="color: #721c24; margin-bottom: 10px;">Total Egresos</h3>
                    <div class="value" id="totalEgresos" style="font-size: 1.5em; font-weight: bold;">
                      ${{ number_format($totalEgresos ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="total-card saldo" style="flex: 1; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 10px; padding: 20px; text-align: center;">
                    <h3 style="color: #856404; margin-bottom: 10px;">Saldo Final</h3>
                    <div class="value" id="saldoFinal" style="font-size: 1.5em; font-weight: bold;">
                      ${{ number_format($saldoFinal ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Formulario para guardar saldos iniciales -->
            <form method="POST" action="{{ route('configuracion-inicial.store') }}" style="margin-bottom: 30px;">
                @csrf
                <input type="hidden" name="orgId" value="{{ $orgId }}">
                <div class="form-group" style="margin-bottom:20px;">
                    <label for="responsable"><i class="bi bi-person"></i> Responsable General</label>
                    <input type="text" name="responsable" id="responsable" value="{{ old('responsable') }}" class="form-control" placeholder="Responsable" required>
                </div>
                <div class="resumen-container">
                    <div class="resumen-card saldo-inicial">
                        <h4>Caja General</h4>
                        <input type="number" step="0.01" name="cuentas[caja_general][saldo_inicial]" value="{{ old('cuentas.caja_general.saldo_inicial', $cuentaCajaGeneral->saldo_inicial ?? 0) }}" class="form-control" required>
                        <select name="cuentas[caja_general][banco_id]" class="form-control" style="margin-top:5px;" required>
                            <option value="">Seleccione banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ (old('cuentas.caja_general.banco_id', $cuentaCajaGeneral->banco_id ?? '') == $banco->id) ? 'selected' : '' }}>
                                    {{ $banco->nombre }} (ID: {{ $banco->id }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="cuentas[caja_general][numero_cuenta]" value="{{ old('cuentas.caja_general.numero_cuenta', $cuentaCajaGeneral->numero_cuenta ?? '') }}" placeholder="N° Cuenta" class="form-control" style="margin-top:5px;">
                    </div>
                    <div class="resumen-card saldo-inicial">
                        <h4>Cuenta Corriente 1</h4>
                        <input type="number" step="0.01" name="cuentas[cuenta_corriente_1][saldo_inicial]" value="{{ old('cuentas.cuenta_corriente_1.saldo_inicial', $cuentaCorriente1->saldo_inicial ?? 0) }}" class="form-control" required>
                        <select name="cuentas[cuenta_corriente_1][banco_id]" class="form-control" style="margin-top:5px;" required>
                            <option value="">Seleccione banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ (old('cuentas.cuenta_corriente_1.banco_id', $cuentaCorriente1->banco_id ?? '') == $banco->id) ? 'selected' : '' }}>
                                    {{ $banco->nombre }} (ID: {{ $banco->id }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="cuentas[cuenta_corriente_1][numero_cuenta]" value="{{ old('cuentas.cuenta_corriente_1.numero_cuenta', $cuentaCorriente1->numero_cuenta ?? '') }}" placeholder="N° Cuenta" class="form-control" style="margin-top:5px;">
                    </div>
                    <div class="resumen-card saldo-inicial">
                        <h4>Cuenta Corriente 2</h4>
                        <input type="number" step="0.01" name="cuentas[cuenta_corriente_2][saldo_inicial]" value="{{ old('cuentas.cuenta_corriente_2.saldo_inicial', $cuentaCorriente2->saldo_inicial ?? 0) }}" class="form-control" required>
                        <select name="cuentas[cuenta_corriente_2][banco_id]" class="form-control" style="margin-top:5px;" required>
                            <option value="">Seleccione banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ (old('cuentas.cuenta_corriente_2.banco_id', $cuentaCorriente2->banco_id ?? '') == $banco->id) ? 'selected' : '' }}>
                                    {{ $banco->nombre }} (ID: {{ $banco->id }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="cuentas[cuenta_corriente_2][numero_cuenta]" value="{{ old('cuentas.cuenta_corriente_2.numero_cuenta', $cuentaCorriente2->numero_cuenta ?? '') }}" placeholder="N° Cuenta" class="form-control" style="margin-top:5px;">
                    </div>
                    <div class="resumen-card saldo-inicial">
                        <h4>Cuenta de Ahorro</h4>
                        <input type="number" step="0.01" name="cuentas[cuenta_ahorro][saldo_inicial]" value="{{ old('cuentas.cuenta_ahorro.saldo_inicial', $cuentaAhorro->saldo_inicial ?? 0) }}" class="form-control" required>
                        <select name="cuentas[cuenta_ahorro][banco_id]" class="form-control" style="margin-top:5px;" required>
                            <option value="">Seleccione banco</option>
                            @foreach($bancos as $banco)
                                <option value="{{ $banco->id }}" {{ (old('cuentas.cuenta_ahorro.banco_id', $cuentaAhorro->banco_id ?? '') == $banco->id) ? 'selected' : '' }}>
                                    {{ $banco->nombre }} (ID: {{ $banco->id }})
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="cuentas[cuenta_ahorro][numero_cuenta]" value="{{ old('cuentas.cuenta_ahorro.numero_cuenta', $cuentaAhorro->numero_cuenta ?? '') }}" placeholder="N° Cuenta" class="form-control" style="margin-top:5px;">
                    </div>
                </div>
                <div style="text-align:center; margin-top:20px;">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar Saldos Iniciales</button>
                </div>
            </form>

            <!-- Resumen (Partial) -->
            @include('orgs.contable.partials.resumen-saldos')

            <!-- Tabla del Libro de Caja -->
            <div class="tabla-responsive">
                <table class="libro-caja-table">
                    <thead>
                        <tr>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Acciones</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Fecha</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Descripción</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Total Consumo</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Cuotas Incorporación</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Otros Ingresos</th>
                            <th style="background: #4CAF50 !important; color: #fff !important;">Giros</th>
                        </tr>
                    </thead>
                    <tbody id="tablaMovimientos">
                        <!-- Fila de Saldo Inicial -->
                        <tr style="background: #e3f2fd; font-weight: bold;">
                            <td style="color: #17a2b8;">Saldo Inicial</td>
                            <td>-</td>
                            <td>Saldo de apertura de cuentas</td>
                            <td>{{ number_format($saldosIniciales['Caja General'] ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($saldosIniciales['Cuenta Corriente 1'] ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($saldosIniciales['Cuenta Corriente 2'] ?? 0, 0, ',', '.') }}</td>
                            <td>{{ number_format($saldosIniciales['Cuenta de Ahorro'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @forelse($movimientos as $movimiento)
                            <tr>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                                <td>{{ $movimiento->fecha }}</td>
                                <td>{{ $movimiento->descripcion }}</td>
                                <td>{{ number_format($movimiento->total_consumo ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($movimiento->cuotas_incorporacion ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($movimiento->otros_ingresos ?? 0, 0, ',', '.') }}</td>
                                <td>{{ number_format($movimiento->giros ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr class="sin-movimientos">
                                <td colspan="7">
                                    <i class="bi bi-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                    No hay movimientos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3><i class="bi bi-lightning"></i> Acciones Rápidas</h3>
            </div>
            <div class="btn-group">
                @if(isset($orgId) && $orgId)
                    <a href="{{ route('ingresos.index', ['id' => $orgId]) }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Registrar Ingreso
                    </a>
                    <a href="{{ route('egresos.index', ['id' => $orgId]) }}" class="btn btn-info">
                        <i class="bi bi-dash-circle"></i> Registrar Egreso
                    </a>
                    <a href="{{ route('giros-depositos.index', ['id' => $orgId]) }}" class="btn btn-info">
                        <i class="bi bi-arrow-left-right"></i> Giros y Depósitos
                    </a>
                @else
                    <span class="text-danger">No se puede mostrar acciones rápidas: falta el identificador de organización.</span>
                @endif
                <button id="btnExportarExcel" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>

    <!-- El JS de localStorage y sincronización ha sido eliminado. Los datos ahora se obtienen solo desde el backend (base de datos). -->
</body>
</html>
@endsection

<!-- Vista de Libro de Caja Tabular -->
@if(!($mostrarLibroCaja ?? false))

<!DOCTYPE html>
<html lang="es">
<head>
</head>
<style>
  .notification {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    min-width: 320px;
    max-width: 90vw;
    padding: 20px 40px;
    background: #222;
    color: #fff;
    border-radius: 10px;
    font-size: 1.2rem;
    text-align: center;
    box-shadow: 0 4px 24px rgba(0,0,0,0.25);
    display: none;
    opacity: 0.97;
    transition: opacity 0.2s;
  }
  .notification.success { background: #28a745; color: #fff; }
  .notification.error { background: #dc3545; color: #fff; }
</style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestión Contable</title>

  <!-- Favicons -->
  <link href="https://hydrosite.cl/public/theme/common/img/favicon.png" rel="icon">
  <link href="https://hydrosite.cl/public/theme/common/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://hydrosite.cl/public/theme/nice/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- SheetJS para exportar a Excel -->
  <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>

  <link rel="stylesheet" href="{{ asset('css/contable/style.css') }}">
  <style>
    /* Estilos adicionales para el modal de Cuentas Iniciales */
    .warning-box {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 10px 15px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    
    .warning-box i {
      font-size: 24px;
      color: #ffc107;
      margin-right: 10px;
    }
    
    .form-section {
      margin-bottom: 20px;
      padding: 15px;
      border: 1px solid #eee;
      border-radius: 8px;
    }
    
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 15px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }
    
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .required {
      color: #e53935;
    }
  </style>
</head>

<body>
<!-- Notificación centrada y visible -->
<div id="notification" class="notification" style="z-index:99999; display:none; opacity:0.97;"></div>

<div class="container">
  <!-- Vista de Registro de Ingresos/Egresos -->
  <div id="registroSection" class="registro-section">
    <div class="card">
      <div class="card-header">
        <div>Sistema de Gestión Financiera</div>
      </div>
      <div class="card-body">
        <h1>Registro de Ingresos y Egresos</h1>

        <div class="btn-wrapper">
          <button id="ingresosBtn">
            <i class="bi bi-cash-coin"></i>Registro de Ingresos
          </button>
          <button id="egresosBtn">
            <i class="bi bi-credit-card"></i>Registro de Egresos
          </button>
          <button id="giroDepositosBtn">
            <i class="bi bi-arrow-left-right"></i>Giros y Depósitos
          </button>
          <button id="libroCajaBtn">
            <i class="bi bi-journal-bookmark"></i>Libro de Caja Tabular
          </button>
          <button id="balanceBtn">
            <i class="bi bi-bar-chart"></i>Balance
          </button>
          <button id="conciliacionBtn">
            <i class="bi bi-bank"></i>Conciliación Bancaria
          </button>
          <button id="informeRubroBtn">
            <i class="bi bi-pie-chart"></i>Informe por Rubro
          </button>
          <button id="movimientosBtn">
            <i class="bi bi-list-check"></i>Movimientos
          </button>
          <!-- Nuevo botón Cuentas Iniciales -->
          <button id="cuentasInicialesBtn" style="background-color: #d32f2f; color: #fff; border: none;">
            <i class="bi bi-journal-plus"></i>Cuentas Iniciales
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Ingresos MODIFICADO con pestañas -->
  <div id="ingresosModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="bi bi-cash-coin"></i> Registro de Ingresos</h2>
        <button class="modal-close" id="closeIngresosModal">Cerrar</button>
      </div>

      <form id="ingresosForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <!-- Columna 1 -->
          <div>
            <label for="fecha-ingresos">Fecha</label>
            <input type="date" id="fecha-ingresos" name="fecha" required style="width: 100%;" readonly>

            <label for="nro-dcto-ingresos">N° Comprobante</label>
            <input type="text" id="nro-dcto-ingresos" name="nro_dcto" placeholder="Ingrese número de comprobante" required style="width: 100%;" readonly>

            <label for="categoria-ingresos">Categoría de Ingreso</label>
            <select id="categoria-ingresos" name="categoria" required style="width: 100%;">
              <option value="">-- Selecciona una categoría --</option>
              <option value="venta_agua">Venta de Agua (Total Consumo)</option>
              <option value="cuotas_incorporacion">Cuotas de Incorporación Cuotas de Incorporación)</option>
              <option value="venta_medidores">Venta de Medidores (Otros Ingresos)</option>
              <option value="trabajos_domicilio">Trabajos en Domicilio (Otros Ingresos)</option>
              <option value="subsidios">Subsidios (Otros Ingresos)</option>
              <option value="otros_aportes">Otros Aportes (Otros Ingresos)</option>
              <option value="multas_inasistencia">Multas Inasistencia (Otros Ingresos)</option>
              <option value="otras_multas">Otras Multas (Otros Ingresos)</option>
            </select>

            <label for="cuenta-destino">Cuenta Destino</label>
            <select id="cuenta-destino" name="cuenta_destino" required style="width: 100%;">
              <option value="">-- Selecciona una cuenta --</option>
              <option value="caja_general">Caja General</option>
              <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
              <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
              <!-- Agregar Cuenta de Ahorro -->
              <option value="cuenta_ahorro">Cuenta de Ahorro</option>
            </select>
          </div>

          <!-- Columna 2 -->
          <div>
            <label for="descripcion-ingresos">Descripción</label>
            <textarea id="descripcion-ingresos" name="descripcion" required
                      style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

            <label for="monto-ingresos">Monto</label>
            <input type="number" id="monto-ingresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
  </div>
        </div>

        <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
          <button type="submit" class="submit-btn" style="flex: 1;">
            <i class="bi bi-save"></i> Registrar Ingreso
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal de Egresos -->
  <div id="egresosModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2><i class="bi bi-credit-card"></i> Registro de Egresos</h2>
        <button class="modal-close" id="closeEgresosModal">Cerrar</button>
      </div>
      <form id="egresosForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <!-- Columna 1 -->
          <div>
            <label for="fecha-egresos">Fecha</label>
            <input type="date" id="fecha-egresos" name="fecha" required style="width: 100%;" readonly>

            <label for="nro-dcto-egresos">N° Boleta/Factura</label>
            <input type="text" id="nro-dcto-egresos" name="nro_dcto" placeholder="Ingrese número de comprobante" required style="width: 100%;" readonly>

            <label for="categoria-egresos">Categoría de Egreso</label>
            <select id="categoria-egresos" name="categoria" required style="width: 100%;">
              <option value="">-- Selecciona una categoría --</option>
              <option value="energia_electrica">Energía Eléctrica ->(Gastos de Operación)</option>
              <option value="sueldos">Sueldos y Leyes Sociales ->(Gastos de Operación)</option>
              <option value="otras_cuentas">Otras Ctas. (Agua, Int. Cel.) ->(Gastos de Operación)</option>
              <option value="mantencion">Mantención y reparaciones Instalaciones ->(Gastos de Mantención)</option>
              <option value="insumos_oficina">Insumos y Materiales (Oficina) ->(Gastos de Administración)</option>
              <option value="materiales_red">Materiales e Insumos (Red) ->(Gastos de Mejoramiento)</option>
              <option value="viaticos">Viáticos / Seguros / Movilización ->(Otros Gastos)</option>
              <option value="trabajos_domicilio">Gastos por Trabajos en domicilio ->(Gastos de Mantención)</option>
              <option value="mejoramiento">Mejoramiento / Inversiones ->(Gastos de Mejoramiento)</option>
            </select>

            <label for="cuenta-origen">Cuenta Origen</label>
            <select id="cuenta-origen" name="cuenta_origen" required style="width: 100%;">
              <option value="">-- Selecciona una cuenta --</option>
              <option value="caja_general">Caja General</option>
              <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
              <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
              <!-- Agregar Cuenta de Ahorro -->
              <option value="cuenta_ahorro">Cuenta de Ahorro</option>
            </select>
          </div>

          <!-- Columna 2 -->
          <div>
            <label for="proveedor">Razón Social Proveedor</label>
            <input type="text" id="razon_social" name="razon_social" placeholder="Razón Social" required style="width: 100%;">

            <label for="domicilio">R.U.T.</label>
            <input type="text" id="rut" name="rut_proveedor" placeholder="RUT del Proveedor" style="width: 100%;">

            <label for="descripcion-egresos">Descripción</label>
              <textarea id="descripcion-egresos" name="descripcion" required
                        style="width: 100%; padding: 14px; min-height: 100px; border: 1px solid #ddd; border-radius: 8px; resize: vertical;"></textarea>

            <label for="monto-egresos">Monto</label>
            <input type="number" id="monto-egresos" name="monto" step="0.01" required class="monto-input" style="width: 100%;">
          </div>
        </div>

        <div style="grid-column: span 2; margin-top: 20px; display: flex; gap: 15px;">
          <button type="submit" class="submit-btn" style="flex: 1;">
            <i class="bi bi-save"></i> Registrar Egreso
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Nueva Vista: Giros y Depósitos -->
  <div id="girosDepositosSection" class="giros-depositos-section" style="display: none;">
    <div class="section-header">
      <h1>Giros y Depósitos</h1>
      <p>Movimientos entre cuentas</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
      <!-- Sección Giros -->
      <div class="form-section" style="border-right: 1px solid #eee; padding-right: 20px;">
        <h3><i class="bi bi-send"></i> GIROS (Desde Cta. Cte./ Cta. Ahorro -> Caja General)</h3>
        <form id="girosForm">
          <div style="display: grid; grid-template-columns: 1fr; gap: 0px 0px;">
            <div class="form-group">
              <label for="fecha-giro">Fecha</label>
              <input type="date" id="fecha-giro" name="fecha" required style="width: 100%;" readonly>
            </div>
            <div class="form-group">
              <label for="nro-dcto-giro">N° Comprobante</label>
              <input type="text" id="nro-dcto-giro" name="nro_dcto" required style="width: 100%;" readonly>
            </div>
            <div class="form-group">
              <label for="monto-giro">Monto</label>
              <input type="number" id="monto-giro" name="monto" step="0.01" required style="width: 100%;">
            </div>
            <div class="form-group">
              <label for="cuenta-giro">Cuenta Origen</label>
              <select id="cuenta-giro" name="cuenta" required style="width: 100%;">
                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                <option value="cuenta_ahorro">Cuenta de Ahorro</option>
              </select>
            </div>
            <div class="form-group">
              <label for="detalle-giro">Detalle</label>
              <input type="text" id="detalle-giro" name="detalle" placeholder="Detalle del giro" required style="width: 100%;">
            </div>
          </div>
          <div style="margin-top: 20px; text-align: center;">
            <button type="submit" class="submit-btn">
              <i class="bi bi-save"></i> Registrar Giro
            </button>
          </div>
        </form>
      </div>

      <!-- Sección Depósitos -->
      <div class="form-section">
        <h3><i class="bi bi-bank"></i> DEPÓSITOS (Desde Caja General -> Cta. Cte./ Cta. Ahorro)</h3>
        <form id="depositosForm">
          <div style="display: grid; grid-template-columns: 1fr; gap: 0px px;">
            <div class="form-group">
              <label for="fecha-deposito">Fecha</label>
              <input type="date" id="fecha-deposito" name="fecha" required style="width: 100%;" readonly>
            </div>
            <div class="form-group">
              <label for="nro-dcto-deposito">N° Comprobante</label>
              <input type="text" id="nro-dcto-deposito" name="nro_dcto" required style="width: 100%;" readonly>
            </div>
            <div class="form-group">
              <label for="monto-deposito">Monto</label>
              <input type="number" id="monto-deposito" name="monto" step="0.01" required style="width: 100%;">
            </div>
            <div class="form-group">
              <label for="cuenta-deposito">Cuenta Destino</label>
              <select id="cuenta-deposito" name="cuenta" required style="width: 100%;">
                <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
                <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
                <option value="cuenta_ahorro">Cuenta de Ahorro</option>
              </select>
            </div>
            <div class="form-group">
              <label for="detalle-deposito">Detalle</label>
              <input type="text" id="detalle-deposito" name="detalle" placeholder="Detalle del depósito" required style="width: 100%;">
            </div>
          </div>

          <div style="margin-top: 20px; text-align: center;">
            <button type="submit" class="submit-btn">
              <i class="bi bi-save"></i> Registrar Depósito
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="button-group" style="margin-top: 30px; text-align: center; grid-column: span 2;">
      <button id="volverGirosDepositosBtn" class="action-button volver">
        <i class="bi bi-arrow-left"></i> Volver al Registro
      </button>
    </div>
  </div>

  <!-- Modal Cuentas Iniciales -->
  <div id="cuentasInicialesModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h2><i class="bi bi-journal-plus"></i> Configuración de Cuentas Iniciales</h2>
        <button class="modal-close" id="closeCuentasInicialesModal">Cerrar</button>
      </div>
      
      <form id="cuentasInicialesForm">
        <div class="warning-box">
          <i class="bi bi-exclamation-triangle"></i>
          <p>¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.</p>
        </div>
        
        <div class="form-section">
          <h3>Saldo Inicial de Cuentas</h3>
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-caja-general">Saldo Caja General</label>
              <input type="number" id="saldo-caja-general" name="saldo_caja_general" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-caja-general">Banco</label>
              <select id="banco-caja-general" name="banco_caja_general">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-caja-general">Número de Cuenta</label>
              <input type="text" id="numero-caja-general" name="numero_caja_general">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-1">Saldo Cuenta Corriente 1</label>
              <input type="number" id="saldo-cta-corriente-1" name="saldo_cta_corriente_1" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-1">Banco</label>
              <select id="banco-cta-corriente-1" name="banco_cta_corriente_1">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cta-corriente-1">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-1" name="numero_cta_corriente_1">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cta-corriente-2">Saldo Cuenta Corriente 2</label>
              <input type="number" id="saldo-cta-corriente-2" name="saldo_cta_corriente_2" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cta-corriente-2">Banco</label>
              <select id="banco-cta-corriente-2" name="banco_cta_corriente_2">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cta-corriente-2">Número de Cuenta</label>
              <input type="text" id="numero-cta-corriente-2" name="numero_cta_corriente_2">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-grid">
            <div class="form-group">
              <label for="saldo-cuenta-ahorro">Saldo Cuenta de Ahorro</label>
              <input type="number" id="saldo-cuenta-ahorro" name="saldo_cuenta_ahorro" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
              <label for="banco-cuenta-ahorro">Banco</label>
              <select id="banco-cuenta-ahorro" name="banco_cuenta_ahorro">
                <option value="">Sin banco</option>
                <option value="banco_estado">Banco Estado</option>
                <option value="banco_chile">Banco de Chile</option>
                <option value="banco_bci">BCI</option>
                <option value="banco_santander">Santander</option>
                <option value="banco_itau">Itaú</option>
                <option value="banco_scotiabank">Scotiabank</option>
                <option value="banco_bice">BICE</option>
                <option value="banco_security">Security</option>
                <option value="banco_falabella">Falabella</option>
                <option value="banco_ripley">Ripley</option>
                <option value="banco_consorcio">Consorcio</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="numero-cuenta-ahorro">Número de Cuenta</label>
              <input type="text" id="numero-cuenta-ahorro" name="numero_cuenta_ahorro">
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <div class="form-group">
            <label for="responsable">Nombre Responsable <span class="required">*</span></label>
            <input type="text" id="responsable" name="responsable" required>
          </div>
        </div>
        
        <div class="button-group" style="margin-top: 20px;">
          <button type="submit" class="submit-btn">
            <i class="bi bi-save"></i> Guardar Cuentas Iniciales
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Vista de Balance -->
  <div id="balanceSection" class="balance-section" style="display: none;">
    <div class="section-header">
      <h1>Balance Financiero</h1>
      <p>Resumen gráfico y analítico de la situación financiera</p>
    </div>

    <div class="summary-card">
      <div class="summary-grid">
        <div class="summary-item ingresos">
          <div class="label">Total Ingresos</div>
          <div class="value" id="balanceTotalIngresos">$0</div>
        </div>
        <div class="summary-item egresos">
          <div class="label">Total Egresos</div>
          <div class="value" id="balanceTotalEgresos">$0</div>
        </div>
        <div class="summary-item saldo">
          <div class="label">Saldo Final</div>
          <div class="value" id="balanceSaldoFinal">$0</div>
        </div>
      </div>
    </div>

    <div class="dashboard-grid">
      <div class="chart-card">
        <h3>Distribución de Ingresos</h3>
        <canvas id="ingresosChart"></canvas>
      </div>
      <div class="chart-card">
        <h3>Distribución de Egresos</h3>
        <canvas id="egresosChart"></canvas>
      </div>
      <div class="chart-card">
        <h3>Flujo Mensual</h3>
        <canvas id="flujoChart"></canvas>
      </div>
      <div class="chart-card">
        <h3>Conciliación Bancaria</h3>
        <canvas id="conciliacionChart"></canvas>
      </div>
    </div>

    <div class="balance-grid">
      <div class="balance-card">
        <h3><i class="bi bi-arrow-up-circle"></i> Ingresos por Categoría</h3>
        <ul id="balanceIngresos">
          <!-- Los ingresos por categoría se generarán aquí -->
        </ul>
      </div>

      <div class="balance-card">
        <h3><i class="bi bi-arrow-down-circle"></i> Egresos por Categoría</h3>
        <ul id="balanceEgresos">
          <!-- Los egresos por categoría se generarán aquí -->
        </ul>
      </div>

      <div class="balance-card">
        <h3><i class="bi bi-clock-history"></i> Últimos Movimientos</h3>
        <ul id="balanceMovimientos">
          <!-- Los últimos movimientos se generarán aquí -->
        </ul>
      </div>

      <div class="balance-card">
        <h3><i class="bi bi-graph-up"></i> Análisis Financiero</h3>
        <div style="padding: 15px;">
          <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
              <span>Flujo de efectivo:</span>
              <span id="flujoEfectivo" style="font-weight: 600;">$0</span>
            </div>
            <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
              <div id="flujoBar" style="height: 100%; width: 50%; background: var(--success-color);"></div>
            </div>
          </div>

          <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
              <span>Proporción ingresos/egresos:</span>
              <span id="proporcionIngEgr" style="font-weight: 600;">1:1</span>
            </div>
            <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
              <div id="proporcionBar" style="height: 100%; width: 50%; background: var(--primary-color);"></div>
            </div>
          </div>

          <div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
              <span>Porcentaje de ahorro:</span>
              <span id="porcentajeAhorro" style="font-weight: 600;">0%</span>
            </div>
            <div style="height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
              <div id="ahorroBar" style="height: 100%; width: 0%; background: var(--secondary-color);"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="button-group">
      <button id="volverBalanceBtn" class="action-button volver">
        <i class="bi bi-arrow-left"></i> Volver al Registro
      </button>
    </div>
  </div>

  <!-- Vista de Conciliación Bancaria -->
  <div id="conciliacionSection" class="conciliacion-section" style="display: none;">
    <div class="section-header">
      <h1>Conciliación Bancaria</h1>
      <p>Comparación de movimientos registrados con extractos bancarios</p>
    </div>

    <div class="conciliacion-grid">
      <div class="conciliacion-card">
        <h3><i class="bi bi-journal-check"></i> Movimientos Registrados</h3>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Descripción</th>
                <th>Cuenta</th>
                <th>Monto</th>
                <th>Conciliado</th>
              </tr>
            </thead>
            <tbody id="tablaConciliacion">
              <!-- Movimientos para conciliación -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="conciliacion-card">
        <h3><i class="bi bi-bank"></i> Extracto Bancario</h3>
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Conciliado</th>
              </tr>
            </thead>
            <tbody id="tablaExtracto">
              <!-- Extracto bancario -->
            </tbody>
          </table>
        </div>

        <div class="conciliacion-card">
          <h3><i class="bi bi-check-circle"></i> Estado de Conciliación</h3>
          <div class="conciliacion-status">
            <div class="status-item">
              <span>Conciliados</span>
              <div class="status-bar">
                <div class="status-fill" style="width: 75%"></div>
              </div>
              <span>75%</span>
            </div>
            <div class="status-item">
              <span>Pendientes</span>
              <div class="status-bar">
                <div class="status-fill pending" style="width: 25%"></div>
              </div>
              <span>25%</span>
            </div>
          </div>
        </div>

        <div class="button-group" style="margin-top: 20px;">
          <button onclick="cargarExtracto()" class="action-button">
            <i class="bi bi-upload"></i> Cargar Extracto
          </button>
          <button onclick="conciliar()" class="action-button">
            <i class="bi bi-check-circle"></i> Conciliar
          </button>
        </div>
      </div>
    </div>

    <div class="button-group">
      <button id="volverConciliacionBtn" class="action-button volver">
        <i class="bi bi-arrow-left"></i> Volver al Registro
      </button>
    </div>
  </div>

  <!-- Vista de Informe por Rubro -->
  <div id="informeRubroSection" class="balance-section" style="display: none;">
    <div class="section-header">
      <h1>Informe por Rubro</h1>
      <p>Comparación de ingresos y egresos por categorías</p>
    </div>

    <div class="rubro-container">
      <div class="rubro-header">
        <div class="rubro-title">Operaciones vs. Administración</div>
        <div class="rubro-value">$0 / $0</div>
      </div>
      <div class="rubro-bar">
        <div class="rubro-bar-fill" style="width: 50%;"></div>
      </div>
      <div class="rubro-info">
        <div class="rubro-label">Gastos Operativos:</div>
        <div class="rubro-value" id="gastoOperativo">$0</div>
      </div>
      <div class="rubro-info">
        <div class="rubro-label">Gastos Administrativos:</div>
        <div class="rubro-value" id="gastoAdministrativo">$0</div>
      </div>
    </div>

    <div class="rubro-container">
      <div class="rubro-header">
        <div class="rubro-title">Mantención vs. Multas</div>
        <div class="rubro-value">$0 / $0</div>
      </div>
      <div class="rubro-bar">
        <div class="rubro-bar-fill" style="width: 50%;"></div>
      </div>
      <div class="rubro-info">
        <div class="rubro-label">Gasto en Mantención:</div>
        <div class="rubro-value" id="gastoMantencion">$0</div>
      </div>
      <div class="rubro-info">
        <div class="rubro-label">Recaudado por Multas:</div>
        <div class="rubro-value" id="recaudoMultas">$0</div>
      </div>
    </div>

    <div class="button-group">
      <button id="volverInformeRubroBtn" class="action-button volver">
        <i class="bi bi-arrow-left"></i> Volver al Registro
      </button>
      <button onclick="exportarPDF('informeRubro')" class="action-button pdf">
        <i class="bi bi-file-earmark-pdf"></i> Exportar Informe
      </button>
    </div>
  </div>

  <!-- Nueva Vista: Movimientos -->
  <div id="movimientosSection" class="movimientos-section" style="display: none;">
    <div class="section-header">
      <h1>Registro de Movimientos</h1>
      <p>Historial completo de todos los movimientos financieros</p>
    </div>

    <div class="table-container">
      <div class="table-header">
        <h2>Movimientos Financieros</h2>
        <div class="filters">
          <div class="filter-group">
            <label>Tipo</label>
            <select id="filtroTipo">
              <option value="">Todos</option>
              <option value="ingreso">Ingreso</option>
              <option value="egreso">Egreso</option>
              <option value="transferencia">Transferencia</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Categoría</label>
            <select id="filtroCategoria">
              <option value="">Todas</option>
              <!-- Se llenará dinámicamente -->
            </select>
          </div>
          <div class="filter-group">
            <label>Cuenta</label>
            <select id="filtroCuenta">
              <option value="">Todas</option>
              <option value="caja_general">Caja General</option>
              <option value="cuenta_corriente_1">Cuenta Corriente 1</option>
              <option value="cuenta_corriente_2">Cuenta Corriente 2</option>
              <!-- Agregar Cuenta de Ahorro -->
              <option value="cuenta_ahorro">Cuenta de Ahorro</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Desde</label>
            <input id="filtroFechaDesde" type="date">
          </div>
          <div class="filter-group">
            <label>Hasta</label>
            <input id="filtroFechaHasta" type="date">
          </div>
          <div class="filter-group">
            <label>&nbsp;</label>
            <button onclick="filtrarMovimientos()" class="action-button" style="padding: 10px 15px;">
              <i class="bi bi-funnel"></i> Filtrar
            </button>
          </div>
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table>
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Tipo</th>
              <th>Categoría</th>
              <th>Descripción</th>
              <th>Cuenta</th>
              <th>Monto</th>
              <th>N° Comprobante</th>
              <th>Proveedor</th>
              <th>RUT Proveedor</th>
            </tr>
          </thead>
          <tbody id="tablaTodosMovimientos">
            <!-- Todos los movimientos se generarán aquí -->
          </tbody>
        </table>
      </div>
    </div>

    <div class="button-group">
      <button id="volverMovimientosBtn" class="action-button volver">
        <i class="bi bi-arrow-left"></i> Volver al Registro
      </button>
      <button onclick="exportarExcel()" class="action-button excel">
        <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
      </button>
    </div>
  </div>
</div>

<!-- Contenedor oculto para generar el PDF del comprobante -->
<div id="comprobantePDF" style="display: none;"></div>

<script>
  // Variables globales
  let movimientos = JSON.parse(localStorage.getItem('movimientos')) || [];
  let saldosCuentas = {
    caja_general: parseFloat(localStorage.getItem('saldoCajaGeneral')) || 0,
    cuenta_corriente_1: parseFloat(localStorage.getItem('saldoCuentaCorriente1')) || 0,
    cuenta_corriente_2: parseFloat(localStorage.getItem('saldoCuentaCorriente2')) || 0,
    // Agregar Cuenta de Ahorro
    cuenta_ahorro: parseFloat(localStorage.getItem('saldoCuentaAhorro')) || 0
  };
  
  // Detalles de cuentas
  let accountDetails = JSON.parse(localStorage.getItem('accountDetails')) || {
    caja_general: { banco: '', numero: '' },
    cuenta_corriente_1: { banco: '', numero: '' },
    cuenta_corriente_2: { banco: '', numero: '' },
    cuenta_ahorro: { banco: '', numero: '' }
  };
  
  let comprobanteCounter = parseInt(localStorage.getItem('comprobanteCounter')) || 1;
  let chartIngresos, chartEgresos, chartFlujo, chartConciliacion;
  let movimientoEditando = null;
  let initialAccountsSet = localStorage.getItem('initialAccountsSet') === 'true';

  // Mapeo de categorías
  const categoriasIngresos = {
    venta_agua: "Venta de Agua (Total Consumo)",
    cuotas_incorporacion: "Cuotas de Incorporación",
    venta_medidores: "Venta de Medidores",
    trabajos_domicilio: "Trabajos en Domicilio",
    subsidios: "Subsidios",
    otros_aportes: "Otros Aportes",
    multas_inasistencia: "Multas Inasistencia",
    otras_multas: "Otras Multas"
  };

  const categoriasEgresos = {
    energia_electrica: "ENERGÍA ELECTRICA",
    sueldos: "SUELDOS/LEYES SOCIALES",
    otras_cuentas: "Otras Ctas. (Agua, Int. Cel.)",
    mantencion: "Mantención y reparaciones Instalaciones",
    insumos_oficina: "Insumos y Materiales (Oficina)",
    materiales_red: "Materiales e Insumos (Red)",
    viaticos: "Viáticos / Seguros / Movilización",
    trabajos_domicilio: "Gastos por Trabajos en domicilio",
    mejoramiento: "Mejoramiento / Inversiones"
  };

  // Mapeo de categorías de egresos a grupos para el libro de caja
  const gruposEgresos = {
    energia_electrica: "ENERGÍA ELÉCTRICA",
    sueldos: "SUELDOS/LEYES SOCIALES",
    otras_cuentas: "OTROS GASTOS DE OPERACIÓN",
    mantencion: "GASTOS MANTENCION",
    trabajos_domicilio: "GASTOS MANTENCION",
    insumos_oficina: "GASTOS ADMINISTRACION",
    materiales_red: "GASTOS MEJORAMIENTO",
    mejoramiento: "GASTOS MEJORAMIENTO",
    viaticos: "OTROS EGRESOS"
  };

  // Mapeo de cuentas
  const cuentas = {
    caja_general: "Caja General",
    cuenta_corriente_1: "Cuenta Corriente 1",
    cuenta_corriente_2: "Cuenta Corriente 2",
    // Agregar Cuenta de Ahorro
    cuenta_ahorro: "Cuenta de Ahorro"
  };

  // Función para formatear valores monetarios
  function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-CL', {
      style: 'currency',
      currency: 'CLP'
    }).format(valor);
  }

  // Función para obtener la fecha actual en formato YYYY-MM-DD
  function obtenerFechaActual() {
    const hoy = new Date();
    const mes = (hoy.getMonth() + 1).toString().padStart(2, '0');
    const dia = hoy.getDate().toString().padStart(2, '0');
    return `${hoy.getFullYear()}-${mes}-${dia}`;
  }

  // Función para generar número de comprobante con prefijo
  function generarNumeroComprobantePrefijo(tipo) {
    const numero = comprobanteCounter.toString().padStart(4, '0');
    comprobanteCounter++;
    localStorage.setItem('comprobanteCounter', comprobanteCounter);
    if (tipo === 'ingreso') return `ING-${numero}`;
    if (tipo === 'egreso') return `EGR-${numero}`;
    return numero;
  }

  // Función para mostrar notificación
  function mostrarNotificacion(mensaje, tipo = 'success') {
    const notification = document.getElementById('notification');
    if (!notification) {
      alert(mensaje); // fallback
      return;
    }
    notification.textContent = mensaje;
    notification.className = `notification ${tipo}`;
    notification.style.display = 'block';
    notification.style.opacity = '0.97';
    notification.style.zIndex = '99999';
    notification.style.pointerEvents = 'none';
    console.log('Notificación mostrada:', mensaje, tipo);
    setTimeout(() => {
      notification.style.display = 'none';
    }, 3000);
  }

  // Función para actualizar los saldos de las cuentas
  function actualizarSaldosCuentas() {
    // Guardar en localStorage
    localStorage.setItem('saldoCajaGeneral', saldosCuentas.caja_general);
    localStorage.setItem('saldoCuentaCorriente1', saldosCuentas.cuenta_corriente_1);
    localStorage.setItem('saldoCuentaCorriente2', saldosCuentas.cuenta_corriente_2);
    localStorage.setItem('saldoCuentaAhorro', saldosCuentas.cuenta_ahorro);
  }

  // Función para guardar detalles de cuentas
  function guardarDetallesCuentas() {
    localStorage.setItem('accountDetails', JSON.stringify(accountDetails));
  }

  // Función para configurar cuentas iniciales
  function configurarCuentasIniciales(e) {
    e.preventDefault();
    if (!confirm('¿Está seguro(a) de guardar los cambios? Esta operación solo se podrá realizar una sola vez.')) {
      return;
    }

    // Obtener valores del formulario y validar visualmente
    const saldoCajaGeneral = parseFloat(document.getElementById('saldo-caja-general').value) || 0;
    const saldoCtaCorriente1 = parseFloat(document.getElementById('saldo-cta-corriente-1').value) || 0;
    const saldoCtaCorriente2 = parseFloat(document.getElementById('saldo-cta-corriente-2').value) || 0;
    const saldoCuentaAhorro = parseFloat(document.getElementById('saldo-cuenta-ahorro').value) || 0;
    const responsable = document.getElementById('responsable') ? document.getElementById('responsable').value.trim() : '';

    // Validación visual de campos obligatorios
    let valid = true;
    const campos = [
      { id: 'saldo-caja-general', value: saldoCajaGeneral },
      { id: 'saldo-cta-corriente-1', value: saldoCtaCorriente1 },
      { id: 'saldo-cta-corriente-2', value: saldoCtaCorriente2 },
      { id: 'saldo-cuenta-ahorro', value: saldoCuentaAhorro },
      { id: 'responsable', value: responsable }
    ];
    campos.forEach(campo => {
      const input = document.getElementById(campo.id);
      if (input) {
        if (campo.value === '' || campo.value === null || (typeof campo.value === 'number' && isNaN(campo.value))) {
          input.classList.add('is-invalid');
          valid = false;
        } else {
          input.classList.remove('is-invalid');
        }
      }
    });
    if (!valid) {
      mostrarNotificacion('Por favor complete todos los campos obligatorios', 'error');
      return;
    }

    const data = {
      saldo_caja_general: saldoCajaGeneral,
      saldo_cta_corriente_1: saldoCtaCorriente1,
      saldo_cta_corriente_2: saldoCtaCorriente2,
      saldo_cuenta_ahorro: saldoCuentaAhorro,
      responsable: responsable
    };

    // Guardar en localStorage (mantener funcionalidad actual)
    saldosCuentas.caja_general = data.saldo_caja_general;
    saldosCuentas.cuenta_corriente_1 = data.saldo_cta_corriente_1;
    saldosCuentas.cuenta_corriente_2 = data.saldo_cta_corriente_2;
    saldosCuentas.cuenta_ahorro = data.saldo_cuenta_ahorro;
    localStorage.setItem('saldosCuentas', JSON.stringify(saldosCuentas));
    localStorage.setItem('initialAccountsSet', 'true');
    initialAccountsSet = true;

    // Bloquear formulario
    const inputs = document.querySelectorAll('#cuentasInicialesForm input, #cuentasInicialesForm select');
    inputs.forEach(input => {
      input.disabled = true;
    });

    // Actualizar UI
    actualizarSaldosCuentas();
    mostrarNotificacion('Cuentas iniciales guardadas correctamente');

    // Enviar datos al backend para registrar en la base de datos
    fetch('/configuracion-cuentas-iniciales', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(data)
    })
    .then(async response => {
      if (!response.ok) {
        let errorMsg = 'Error de conexión con el servidor';
        try {
          const errorData = await response.json();
          errorMsg = errorData.message || errorMsg;
        } catch {}
        throw new Error(errorMsg);
      }
      return response.json();
    })
    .then(result => {
      if (result.success) {
        mostrarNotificacion('Cuentas iniciales registradas en la base de datos');
        // Si el backend retorna los saldos actualizados, reflejarlos en la UI
        if (result.data) {
          let mensaje = 'Saldos actualizados:';
          if (typeof result.data.saldo_caja_general !== 'undefined') {
            saldosCuentas.caja_general = result.data.saldo_caja_general;
            localStorage.setItem('saldoCajaGeneral', result.data.saldo_caja_general);
            mensaje += `\nCaja General: $${Number(result.data.saldo_caja_general).toLocaleString('es-CL')}`;
          }
          if (typeof result.data.saldo_cta_corriente_1 !== 'undefined') {
            saldosCuentas.cuenta_corriente_1 = result.data.saldo_cta_corriente_1;
            localStorage.setItem('saldoCuentaCorriente1', result.data.saldo_cta_corriente_1);
            mensaje += `\nCuenta Corriente 1: $${Number(result.data.saldo_cta_corriente_1).toLocaleString('es-CL')}`;
          }
          if (typeof result.data.saldo_cta_corriente_2 !== 'undefined') {
            saldosCuentas.cuenta_corriente_2 = result.data.saldo_cta_corriente_2;
            localStorage.setItem('saldoCuentaCorriente2', result.data.saldo_cta_corriente_2);
            mensaje += `\nCuenta Corriente 2: $${Number(result.data.saldo_cta_corriente_2).toLocaleString('es-CL')}`;
          }
          if (typeof result.data.saldo_cuenta_ahorro !== 'undefined') {
            saldosCuentas.cuenta_ahorro = result.data.saldo_cuenta_ahorro;
            localStorage.setItem('saldoCuentaAhorro', result.data.saldo_cuenta_ahorro);
            mensaje += `\nCuenta de Ahorro: $${Number(result.data.saldo_cuenta_ahorro).toLocaleString('es-CL')}`;
          }
          actualizarSaldosCuentas();
          actualizarTotales();
          mostrarNotificacion(mensaje, 'success');
        }
      } else {
        mostrarNotificacion(result.message || 'Error al guardar en base de datos', 'error');
      }
    })
    .catch(error => {
      mostrarNotificacion(error.message || 'Error de conexión con el servidor', 'error');
    });

    // Cerrar modal después de 2 segundos
    setTimeout(() => {
      document.getElementById('cuentasInicialesModal').classList.remove('show');
    }, 2000);
  }

  // Función para registrar un ingreso
  function registrarIngreso(e) {
    e.preventDefault();
    
    const fecha = document.getElementById('fecha-ingresos').value;
    const nro_dcto = document.getElementById('nro-dcto-ingresos').value;
    const categoria = document.getElementById('categoria-ingresos').value;
    const cuenta_destino = document.getElementById('cuenta-destino').value;
    const descripcion = document.getElementById('descripcion-ingresos').value;
    const monto = parseFloat(document.getElementById('monto-ingresos').value);
    
    // Validar monto positivo
    if (monto <= 0) {
      mostrarNotificacion('El monto debe ser mayor a cero', 'error');
      return;
    }
    
    // Crear movimiento
    const movimiento = {
      id: Date.now(),
      fecha,
      tipo: 'ingreso',
      nro_dcto,
      categoria,
      cuenta_destino,
      descripcion,
      monto,
      timestamp: new Date().getTime()
    };
    
    // Agregar a la lista de movimientos
    movimientos.push(movimiento);
    localStorage.setItem('movimientos', JSON.stringify(movimientos));
    
    // Actualizar saldo de la cuenta destino
    saldosCuentas[cuenta_destino] += monto;
    actualizarSaldosCuentas();
    
    // Actualizar UI
    actualizarTotales();
    actualizarTablaMovimientos();
    
    // Actualizar libro de caja tabular si está disponible
    if (window.actualizarTablaLibroCaja) window.actualizarTablaLibroCaja();
    // Mostrar notificación y cerrar modal
    mostrarNotificacion('¡Ingreso registrado correctamente!', 'success');
    document.getElementById('ingresosModal').classList.remove('show');
    document.getElementById('ingresosForm').reset();
  }

  // Función para registrar un egreso
  function registrarEgreso(e) {
    e.preventDefault();
    
    const fecha = document.getElementById('fecha-egresos').value;
    const nro_dcto = document.getElementById('nro-dcto-egresos').value;
    const categoria = document.getElementById('categoria-egresos').value;
    const cuenta_origen = document.getElementById('cuenta-origen').value;
    const razon_social = document.getElementById('razon_social').value;
    const rut_proveedor = document.getElementById('rut').value;
    const descripcion = document.getElementById('descripcion-egresos').value;
    const monto = parseFloat(document.getElementById('monto-egresos').value);
    
    // Validar monto positivo
    if (monto <= 0) {
      mostrarNotificacion('El monto debe ser mayor a cero', 'error');
      return;
    }
    
    // Validar saldo suficiente
    if (saldosCuentas[cuenta_origen] < monto) {
      mostrarNotificacion('Saldo insuficiente en la cuenta seleccionada', 'error');
      return;
    }
    
    // Crear movimiento
    const movimiento = {
      id: Date.now(),
      fecha,
      tipo: 'egreso',
      nro_dcto,
      categoria,
      cuenta_origen,
      razon_social,
      rut_proveedor,
      descripcion,
      monto,
      timestamp: new Date().getTime()
    };
    
    // Agregar a la lista de movimientos
    movimientos.push(movimiento);
    localStorage.setItem('movimientos', JSON.stringify(movimientos));
    
    // Actualizar saldo de la cuenta origen
    saldosCuentas[cuenta_origen] -= monto;
    actualizarSaldosCuentas();
    
    // Actualizar UI
    actualizarTotales();
    actualizarTablaMovimientos();
    
    // Actualizar libro de caja tabular si está disponible
    if (window.actualizarTablaLibroCaja) window.actualizarTablaLibroCaja();
    // Mostrar notificación y cerrar modal
    mostrarNotificacion('¡Egreso registrado correctamente!', 'success');
    document.getElementById('egresosModal').classList.remove('show');
    document.getElementById('egresosForm').reset();
  }

  // Función para enviar giro al backend
  function enviarGiroBackend(movimientoEgreso, movimientoIngreso) {
    fetch('/movimiento-giros-depositos', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        tipo: 'giro',
        comprobante: movimientoEgreso.nro_dcto,
        fecha: movimientoEgreso.fecha,
        monto: movimientoEgreso.monto,
        cuenta_origen: movimientoEgreso.cuenta_origen,
        cuenta_destino: movimientoIngreso.cuenta_destino,
        detalle: movimientoEgreso.descripcion
      })
    })
    .then(async response => {
      if (!response.ok) {
        let errorMsg = 'Error de conexión con el servidor';
        try {
          const errorData = await response.json();
          errorMsg = errorData.message || errorMsg;
        } catch {}
        throw new Error(errorMsg);
      }
      return response.json();
    })
    .then(result => {
      if (result.success) {
        mostrarNotificacion('Giro registrado en la base de datos', 'success');
      } else {
        mostrarNotificacion(result.message || 'Error al guardar giro en base de datos', 'error');
      }
    })
    .catch(error => {
      mostrarNotificacion(error.message || 'Error de conexión con el servidor', 'error');
    });
  }
  // Función para enviar depósito al backend
  function enviarDepositoBackend(movimientoEgreso, movimientoIngreso) {
    fetch('/movimiento-giros-depositos', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        tipo: 'deposito',
        comprobante: movimientoEgreso.nro_dcto,
        fecha: movimientoEgreso.fecha,
        monto: movimientoEgreso.monto,
        cuenta_origen: movimientoEgreso.cuenta_origen,
        cuenta_destino: movimientoIngreso.cuenta_destino,
        detalle: movimientoEgreso.descripcion
      })
    })
    .then(async response => {
      if (!response.ok) {
        let errorMsg = 'Error de conexión con el servidor';
        try {
          const errorData = await response.json();
          errorMsg = errorData.message || errorMsg;
        } catch {}
        throw new Error(errorMsg);
      }
      return response.json();
    })
    .then(result => {
      if (result.success) {
        mostrarNotificacion('Depósito registrado en la base de datos', 'success');
      } else {
        mostrarNotificacion(result.message || 'Error al guardar depósito en base de datos', 'error');
      }
    })
    .catch(error => {
      mostrarNotificacion(error.message || 'Error de conexión con el servidor', 'error');
    });
  }
  // Modificar registrarGiro y registrarDeposito para enviar al backend
  function registrarGiro(e) {
    e.preventDefault();
    const fecha = document.getElementById('fecha-giro').value;
    const nro_dcto = document.getElementById('nro-dcto-giro').value;
    const monto = parseFloat(document.getElementById('monto-giro').value);
    const cuenta_origen = document.getElementById('cuenta-giro').value;
    const detalle = document.getElementById('detalle-giro').value;
    // Validar monto positivo
    if (monto <= 0) {
      mostrarNotificacion('El monto debe ser mayor a cero', 'error');
      return;
    }
    // Validar saldo suficiente
    if (saldosCuentas[cuenta_origen] < monto) {
      mostrarNotificacion('Saldo insuficiente en la cuenta seleccionada', 'error');
      return;
    }
    // Crear movimientos (uno de egreso y uno de ingreso)
    const id = Date.now();
    const timestamp = new Date().getTime();
    // Movimiento de egreso (desde cuenta origen)
    const movimientoEgreso = {
      id: id,
      fecha,
      tipo: 'transferencia',
      subtipo: 'giro',
      nro_dcto: nro_dcto,
      categoria: 'transferencia',
      cuenta_origen,
      descripcion: `Giro a Caja General: ${detalle}`,
      monto,
      timestamp
    };
    // Movimiento de ingreso (a caja general)
    const movimientoIngreso = {
      id: id + 1,
      fecha,
      tipo: 'transferencia',
      subtipo: 'giro',
      nro_dcto: nro_dcto,
      categoria: 'transferencia',
      cuenta_destino: 'caja_general',
      descripcion: `Giro desde ${cuentas[cuenta_origen]}: ${detalle}`,
      monto,
      timestamp: timestamp + 1
    };
    movimientos.push(movimientoEgreso, movimientoIngreso);
    localStorage.setItem('movimientos', JSON.stringify(movimientos));
    saldosCuentas[cuenta_origen] -= monto;
    saldosCuentas.caja_general += monto;
    actualizarSaldosCuentas();
    actualizarTotales();
    actualizarTablaMovimientos();
    if (window.actualizarTablaLibroCaja) window.actualizarTablaLibroCaja();
    mostrarNotificacion('Giro registrado correctamente');
    document.getElementById('girosForm').reset();
    // Enviar al backend
    enviarGiroBackend(movimientoEgreso, movimientoIngreso);
}
function registrarDeposito(e) {
    e.preventDefault();
    const fecha = document.getElementById('fecha-deposito').value;
    const nro_dcto = document.getElementById('nro-dcto-deposito').value;
    const monto = parseFloat(document.getElementById('monto-deposito').value);
    const cuenta_destino = document.getElementById('cuenta-deposito').value;
    const detalle = document.getElementById('detalle-deposito').value;
    // Validar monto positivo
    if (monto <= 0) {
      mostrarNotificacion('El monto debe ser mayor a cero', 'error');
      return;
    }
    // Validar saldo suficiente en caja general
    if (saldosCuentas.caja_general < monto) {
      mostrarNotificacion('Saldo insuficiente en Caja General', 'error');
      return;
    }
    // Crear movimientos (uno de egreso y uno de ingreso)
    const id = Date.now();
    const timestamp = new Date().getTime();
    // Movimiento de egreso (desde caja general)
    const movimientoEgreso = {
      id: id,
      fecha,
      tipo: 'transferencia',
      subtipo: 'deposito',
      nro_dcto: nro_dcto,
      categoria: 'transferencia',
      cuenta_origen: 'caja_general',
      descripcion: `Depósito a ${cuentas[cuenta_destino]}: ${detalle}`,
      monto,
      timestamp
    };
    // Movimiento de ingreso (a cuenta destino)
    const movimientoIngreso = {
      id: id + 1,
      fecha,
      tipo: 'transferencia',
      subtipo: 'deposito',
      nro_dcto: nro_dcto,
      categoria: 'transferencia',
      cuenta_destino,
      descripcion: `Depósito desde Caja General: ${detalle}`,
      monto,
      timestamp: timestamp + 1
    };
    movimientos.push(movimientoEgreso, movimientoIngreso);
    localStorage.setItem('movimientos', JSON.stringify(movimientos));
    saldosCuentas.caja_general -= monto;
    saldosCuentas[cuenta_destino] += monto;
    actualizarSaldosCuentas();
    actualizarTotales();
    actualizarTablaMovimientos();
    if (window.actualizarTablaLibroCaja) window.actualizarTablaLibroCaja();
    mostrarNotificacion('Depósito registrado correctamente');
    document.getElementById('depositosForm').reset();
    // Enviar al backend
    enviarDepositoBackend(movimientoEgreso, movimientoIngreso);
}
</script>
@php
    $isCrc = Auth::user()->isCrc();
@endphp

<script>
    const sesionEspecialActiva = @json($isCrc);
    console.log("sesion activa", sesionEspecialActiva);

    const volverBtn = document.getElementById('volverBtn');
    const volverBtnTexto = document.getElementById('volverBtnTexto');
    const volverBtnIcon = document.getElementById('volverBtnIcon');

    if (volverBtn && sesionEspecialActiva) {
        if (volverBtnTexto) {
            volverBtnTexto.textContent = 'Cerrar Sesión';
        }
        if (volverBtnIcon) {
            volverBtnIcon.classList.remove('bi-arrow-left');
            volverBtnIcon.classList.add('bi-box-arrow-right'); // ícono de logout
        }
    }

    if (volverBtn) {
        volverBtn.addEventListener('click', () => {
            if (sesionEspecialActiva) {
                window.location.href = '/logout';
            } else {
                volverARegistro();
            }
        });
    }
</script>

<!-- rutaje web libro caja tabular -->
@php
    $mostrarLibroCaja = $mostrarLibroCaja ?? false;
@endphp

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mostrarLibroCaja = @json($mostrarLibroCaja);

        if (mostrarLibroCaja) {
            // Oculta todas las secciones
            var secciones = [
                'girosDepositosSection',
                'registroSection',
                'balanceSection',
                'conciliacionSection',
                'informeRubroSection',
                'movimientosSection'
            ];
            secciones.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });

            // Muestra la sección de Libro de Caja
            document.getElementById('libroCajaSection')?.style.display = 'block';

            // Setea las fechas por defecto
            const hoy = new Date();
            const primerDiaMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            const ultimoDiaMes = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);

            document.getElementById('fechaDesde').valueAsDate = primerDiaMes;
            document.getElementById('fechaHasta').valueAsDate = ultimoDiaMes;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
  // Manejar TODOS los botones "volver"
  document.querySelectorAll('button.volver').forEach(function(btn) {
    btn.addEventListener('click', volverARegistro);
  });

  // Configurar evento para formulario de cuentas iniciales
  document.getElementById('cuentasInicialesForm').addEventListener('submit', configurarCuentasIniciales);

  // Botón para abrir modal de cuentas iniciales
  document.getElementById('cuentasInicialesBtn').addEventListener('click', () => {
    document.getElementById('cuentasInicialesModal').classList.add('show');
  });

  // Cerrar modal de cuentas iniciales
  document.getElementById('closeCuentasInicialesModal').addEventListener('click', () => {
    document.getElementById('cuentasInicialesModal').classList.remove('show');
  });

  // Configurar eventos para botones principales
  document.getElementById('ingresosBtn').addEventListener('click', () => {
    document.getElementById('ingresosModal').classList.add('show');
    document.getElementById('nro-dcto-ingresos').value = generarNumeroComprobantePrefijo('ingreso');
    document.getElementById('nro-dcto-ingresos').readOnly = true;
    document.getElementById('fecha-ingresos').value = obtenerFechaActual();
    document.getElementById('fecha-ingresos').readOnly = true;
  });

  document.getElementById('egresosBtn').addEventListener('click', () => {
    document.getElementById('egresosModal').classList.add('show');
    document.getElementById('nro-dcto-egresos').value = generarNumeroComprobantePrefijo('egreso');
    document.getElementById('nro-dcto-egresos').readOnly = true;
    document.getElementById('fecha-egresos').value = obtenerFechaActual();
    document.getElementById('fecha-egresos').readOnly = true;
  });

  document.getElementById('giroDepositosBtn').addEventListener('click', () => {
    document.getElementById('girosDepositosSection').style.display = 'block';
    document.getElementById('registroSection').style.display = 'none';
    // Establecer fecha actual y comprobante en ambos formularios
    if (document.getElementById('fecha-giro')) {
      document.getElementById('fecha-giro').value = obtenerFechaActual();
      document.getElementById('fecha-giro').readOnly = true;
    }
    if (document.getElementById('nro-dcto-giro')) {
      document.getElementById('nro-dcto-giro').value = `G-${generarNumeroComprobante()}`;
      document.getElementById('nro-dcto-giro').readOnly = true;
    }
    if (document.getElementById('fecha-deposito')) {
      document.getElementById('fecha-deposito').value = obtenerFechaActual();
      document.getElementById('fecha-deposito').readOnly = true;
    }
    if (document.getElementById('nro-dcto-deposito')) {
      document.getElementById('nro-dcto-deposito').value = `D-${generarNumeroComprobante()}`;
      document.getElementById('nro-dcto-deposito').readOnly = true;
    }
  });

  // Event listeners para cierre de modales
  document.getElementById('closeIngresosModal').addEventListener('click', () => {
    document.getElementById('ingresosModal').classList.remove('show');
    movimientoEditando = null;
    document.getElementById('ingresosForm').reset();
  });

  document.getElementById('closeEgresosModal').addEventListener('click', () => {
    document.getElementById('egresosModal').classList.remove('show');
    document.getElementById('egresosForm').reset();
  });

  // Event listeners para formularios
  document.getElementById('ingresosForm').addEventListener('submit', registrarIngreso);
  document.getElementById('egresosForm').addEventListener('submit', registrarEgreso);
  document.getElementById('girosForm').addEventListener('submit', registrarGiro);
  document.getElementById('depositosForm').addEventListener('submit', registrarDeposito);

  // Cargar datos iniciales
  actualizarSaldosCuentas();
  actualizarTotales();
  actualizarTablaMovimientos();
});
</script>
@endif

@include('orgs.contable.libroCajaTabular', ['id' => $org->id ?? $orgId ?? null])
</body>
</html>
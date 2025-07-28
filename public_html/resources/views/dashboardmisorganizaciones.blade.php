<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Organizaciones</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            --secondary-gradient: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            
            --bg-primary: #f8f9fc;
            --bg-secondary: #f0f2f7;
            --bg-card: #ffffff;
            --text-primary: #1e3a8a;
            --text-secondary: #1e40af;
            --border-color: #e2e8f0;
            --shadow-main: 0 10px 40px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(600px circle at 20% 30%, rgba(124, 58, 237, 0.22) 0%, transparent 50%),
                radial-gradient(800px circle at 80% 70%, rgba(168, 85, 247, 0.18) 0%, transparent 50%),
                radial-gradient(400px circle at 40% 60%, rgba(236, 72, 153, 0.15) 0%, transparent 50%);
            z-index: -1;
            animation: backgroundShift 20s ease-in-out infinite;
        }

        @keyframes backgroundShift {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, -20px) rotate(1deg); }
        }

        /* Modern Navigation */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--text-primary);
        }

        /* Main Container */
        .main-container {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Modern Cards */
        .modern-card {
            background: var(--bg-card);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-main);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .modern-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(124, 58, 237, 0.6);
        }

        .card-body {
            padding: 2rem;
        }

        /* KPI Cards */
        .kpi-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-main);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .kpi-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
            background: var(--primary-gradient);
            color: white;
        }

        .kpi-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin: 1rem 0;
            color: var(--text-primary);
        }

        .kpi-change {
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .kpi-change.positive { color: #68d391; }
        .kpi-change.negative { color: #fc8181; }

        /* Filter Bar */
        .filter-bar {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-main);
        }

        .filter-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        .form-select {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            background: var(--bg-card);
            border-color: rgba(124, 58, 237, 0.8);
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.35);
            color: var(--text-primary);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
            padding: 1rem;
        }

        .chart-large {
            height: 450px;
        }

        /* Card Titles */
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        /* Modern Table */
        .table-modern {
            background: transparent;
            color: var(--text-primary);
        }

        .table-modern th {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .table-modern td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table-modern tbody tr:hover {
            background: rgba(124, 58, 237, 0.15);
        }

        /* Modern Badges */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .badge-success { background: linear-gradient(135deg, #10b981, #34d399); }
        .badge-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .badge-danger { background: linear-gradient(135deg, #ef4444, #f97316); }
        .badge-info { background: linear-gradient(135deg, #7c3aed, #a855f7); }

        /* Modern Buttons */
        .btn-modern {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        /* Back Button */
        .btn-back {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #6d28d9, #9333ea);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-back:active {
            transform: translateY(0);
        }

        /* Footer */
        .footer-modern {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 2rem 0;
            margin-top: 4rem;
            text-align: center;
            color: var(--text-primary);
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-line me-2"></i>
                Dashboard Organizaciones
            </a>
            <div class="d-flex">
                <button class="btn-back" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </button>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Filtros -->
        <section class="filter-bar">
            <h6 class="filter-title">
                <i class="fas fa-filter me-2"></i>
                Filtros de Análisis
            </h6>
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label for="time-period" class="form-label">Período</label>
                    <select id="time-period" class="form-select">
                        <option value="monthly">Últimos 6 meses</option>
                        <option value="quarterly">Últimos 4 trimestres</option>
                        <option value="yearly">Últimos 3 años</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="customer-type" class="form-label">Tipo de Cliente</label>
                    <select id="customer-type" class="form-select">
                        <option value="all">Todos</option>
                        <option value="new">Nuevos</option>
                        <option value="recurrent">Recurrentes</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="plan-type" class="form-label">Plan</label>
                    <select id="plan-type" class="form-select">
                        <option value="all">Todos</option>
                        <option value="essential">Esencial</option>
                        <option value="intermediate">Intermedio</option>
                        <option value="advanced">Avanzado</option>
                        <option value="total">Control Total</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="region" class="form-label">Región</label>
                    <select id="region" class="form-select">
                        <option value="all">Todas</option>
                        <option value="north">Norte</option>
                        <option value="center">Centro</option>
                        <option value="south">Sur</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- KPIs Principales -->
        <section class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="kpi-title">MRR (Ingresos Mensuales)</div>
                    <div class="kpi-value">$14.25M</div>
                    <div class="kpi-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +12% vs mes anterior
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #ef4444, #f97316);">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <div class="kpi-title">Tasa de Cancelación</div>
                    <div class="kpi-value">4.3%</div>
                    <div class="kpi-change negative">
                        <i class="fas fa-arrow-up"></i>
                        +0.5% vs mes anterior
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="kpi-title">LTV (Valor por Cliente)</div>
                    <div class="kpi-value">$285K</div>
                    <div class="kpi-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +8% vs trimestre anterior
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="kpi-title">NPS (Satisfacción)</div>
                    <div class="kpi-value">62</div>
                    <div class="kpi-change positive">
                        <i class="fas fa-arrow-up"></i>
                        +5 vs mes anterior
                    </div>
                </div>
            </div>
        </section>

        <!-- Gráficos Principales -->
        <section class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>
                            MRR vs Tasa de Cancelación
                        </h5>
                        <div class="chart-container">
                            <canvas id="mrrChurnChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-pie-chart me-2"></i>
                            Distribución de Planes
                        </h5>
                        <div class="chart-container">
                            <canvas id="planDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-users me-2"></i>
                            Adquisición de Clientes
                        </h5>
                        <div class="chart-container">
                            <canvas id="acquisitionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-check me-2"></i>
                            Retención por Cohortes
                        </h5>
                        <div class="chart-container">
                            <canvas id="cohortChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-5">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-cube me-2"></i>
                            Análisis RFM (Recencia, Frecuencia, Valor)
                        </h5>
                        <div class="chart-container chart-large">
                            <canvas id="rfmChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-cogs me-2"></i>
                            Utilización de Servicios
                        </h5>
                        <div class="chart-container">
                            <canvas id="servicesUsageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-trending-up me-2"></i>
                            Tendencia de Crecimiento
                        </h5>
                        <div class="chart-container">
                            <canvas id="growthTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tabla de Alertas -->
        <section class="row">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Alertas de Clientes
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Tipo de Alerta</th>
                                        <th>Severidad</th>
                                        <th>Detalle</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Empresa A</strong></td>
                                        <td>Posible churn</td>
                                        <td><span class="badge badge-modern badge-warning">Media</span></td>
                                        <td>Uso disminuido en 45%</td>
                                        <td><button class="btn btn-modern btn-primary-modern btn-sm">Contactar</button></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Empresa B</strong></td>
                                        <td>Pago atrasado</td>
                                        <td><span class="badge badge-modern badge-danger">Alta</span></td>
                                        <td>15 días de atraso</td>
                                        <td><button class="btn btn-modern btn-primary-modern btn-sm">Seguimiento</button></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Empresa C</strong></td>
                                        <td>Oportunidad upsell</td>
                                        <td><span class="badge badge-modern badge-info">Baja</span></td>
                                        <td>Uso consistente de servicios básicos</td>
                                        <td><button class="btn btn-modern btn-success-modern btn-sm">Ofrecer plan superior</button></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Empresa D</strong></td>
                                        <td>Cliente satisfecho</td>
                                        <td><span class="badge badge-modern badge-success">Positiva</span></td>
                                        <td>NPS alto y uso consistente</td>
                                        <td><button class="btn btn-modern btn-primary-modern btn-sm">Testimonial</button></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Empresa E</strong></td>
                                        <td>Bajo uso</td>
                                        <td><span class="badge badge-modern badge-warning">Media</span></td>
                                        <td>Disminución del 30% en actividad</td>
                                        <td><button class="btn btn-modern btn-primary-modern btn-sm">Capacitar</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container">
            <p>&copy; 2025 Dashboard Organizaciones. Diseño moderno y elegante.</p>
        </div>
    </footer>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- JavaScript para los gráficos -->
    <script>
        // Verificar que Chart.js está cargado
        console.log('Chart.js version:', Chart?.version || 'No cargado');
        
        window.addEventListener('load', function() {
            console.log('🚀 Iniciando creación de gráficos...');
            
            // Verificar elementos canvas
            const canvasElements = ['mrrChurnChart', 'planDistributionChart', 'acquisitionChart', 'cohortChart', 'rfmChart', 'servicesUsageChart', 'growthTrendChart'];
            canvasElements.forEach(id => {
                const element = document.getElementById(id);
                console.log(`Canvas ${id}:`, element ? '✅ Encontrado' : '❌ No encontrado');
            });

            // Configuración global simplificada
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.font.size = 11;

            // Colores más intensos y vibrantes
            const colors = {
                primary: '#7c3aed',
                secondary: '#a855f7',
                success: '#10b981',
                warning: '#f59e0b',
                danger: '#ef4444',
                info: '#3b82f6'
            };

            // 1. Gráfico MRR vs Churn
            try {
                const ctx1 = document.getElementById('mrrChurnChart');
                if (ctx1) {
                    new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                            datasets: [{
                                label: 'MRR (Millones $)',
                                data: [12.5, 13.0, 12.8, 13.5, 14.0, 14.25],
                                backgroundColor: colors.primary + 'CC',
                                borderColor: colors.primary,
                                borderWidth: 2
                            }, {
                                label: 'Tasa Cancelación (%)',
                                data: [5.2, 4.8, 6.1, 5.5, 4.3, 4.3],
                                backgroundColor: colors.danger + 'CC',
                                borderColor: colors.danger,
                                borderWidth: 2,
                                type: 'line'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: true }
                            }
                        }
                    });
                    console.log('✅ Gráfico MRR creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico MRR:', error);
            }

            // 2. Distribución de Planes
            try {
                const ctx2 = document.getElementById('planDistributionChart');
                if (ctx2) {
                    new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: ['Esencial', 'Intermedio', 'Avanzado', 'Total'],
                            datasets: [{
                                data: [800, 900, 700, 600],
                                backgroundColor: [
                                    colors.primary + 'D0',
                                    colors.success + 'D0',
                                    colors.warning + 'D0',
                                    colors.secondary + 'D0'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                    console.log('✅ Gráfico Planes creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico Planes:', error);
            }

            // 3. Adquisición de Clientes
            try {
                const ctx3 = document.getElementById('acquisitionChart');
                if (ctx3) {
                    new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                            datasets: [{
                                label: 'Nuevos',
                                data: [120, 150, 110, 180, 200, 190],
                                backgroundColor: colors.success + 'CC'
                            }, {
                                label: 'Perdidos',
                                data: [40, 35, 60, 45, 30, 35],
                                backgroundColor: colors.danger + 'CC'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                    console.log('✅ Gráfico Adquisición creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico Adquisición:', error);
            }

            // 4. Retención por Cohortes
            try {
                const ctx4 = document.getElementById('cohortChart');
                if (ctx4) {
                    new Chart(ctx4, {
                        type: 'line',
                        data: {
                            labels: ['Mes 1', 'Mes 2', 'Mes 3', 'Mes 4', 'Mes 5', 'Mes 6'],
                            datasets: [{
                                label: 'Enero',
                                data: [100, 85, 78, 72, 68, 65],
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '33'
                            }, {
                                label: 'Febrero',
                                data: [100, 88, 80, 75, 70, null],
                                borderColor: colors.success,
                                backgroundColor: colors.success + '33'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                    console.log('✅ Gráfico Cohortes creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico Cohortes:', error);
            }

            // 5. RFM Scatter
            try {
                const ctx5 = document.getElementById('rfmChart');
                if (ctx5) {
                    new Chart(ctx5, {
                        type: 'scatter',
                        data: {
                            datasets: [{
                                label: 'Clientes Leales',
                                data: [{x: 5, y: 5}, {x: 4, y: 4}],
                                backgroundColor: colors.success + 'CC'
                            }, {
                                label: 'En Riesgo',
                                data: [{x: 2, y: 3}, {x: 1, y: 2}],
                                backgroundColor: colors.danger + 'CC'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { min: 0, max: 6 },
                                y: { min: 0, max: 6 }
                            }
                        }
                    });
                    console.log('✅ Gráfico RFM creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico RFM:', error);
            }

            // 6. Uso de Servicios (Radar)
            try {
                const ctx6 = document.getElementById('servicesUsageChart');
                if (ctx6) {
                    new Chart(ctx6, {
                        type: 'radar',
                        data: {
                            labels: ['APP', 'Contabilidad', 'Financiera', 'Asesoría', 'Soporte', 'Reportes'],
                            datasets: [{
                                label: 'Uso Actual',
                                data: [65, 59, 90, 81, 56, 55],
                                backgroundColor: colors.primary + '55',
                                borderColor: colors.primary
                            }, {
                                label: 'Meta',
                                data: [80, 80, 80, 80, 80, 80],
                                backgroundColor: colors.success + '55',
                                borderColor: colors.success
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                    console.log('✅ Gráfico Servicios creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico Servicios:', error);
            }

            // 7. Tendencia de Crecimiento
            try {
                const ctx7 = document.getElementById('growthTrendChart');
                if (ctx7) {
                    new Chart(ctx7, {
                        type: 'line',
                        data: {
                            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Crecimiento MRR',
                                data: [8, 12, -2, 15, 18, 14],
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '55',
                                fill: true
                            }, {
                                label: 'Crecimiento Clientes',
                                data: [5, 8, 3, 12, 15, 10],
                                borderColor: colors.success,
                                backgroundColor: colors.success + '55',
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                    console.log('✅ Gráfico Crecimiento creado');
                }
            } catch (error) {
                console.error('❌ Error creando gráfico Crecimiento:', error);
            }

            console.log('🎉 TODOS LOS GRÁFICOS PROCESADOS');
        });

        // Función para volver a la página anterior
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Si no hay historial, redirigir a una página por defecto
                window.location.href = '/';
            }
        }
    </script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

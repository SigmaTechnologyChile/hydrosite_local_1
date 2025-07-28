<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Boleta</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container-boleta {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #212529;
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .small {
            font-size: 0.85em;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-primary {
            color: #007bff;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .flex-direction-column {
            flex-direction: column;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .gap-10 {
            gap: 10px;
        }

        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mb-3 { margin-bottom: 15px; }
        .me-3 { margin-right: 15px; }
        .py-1 { padding-top: 5px; padding-bottom: 5px; }
        .p-1 { padding: 5px; }
        .p-2 { padding: 10px; }
        .p-3 { padding: 15px; }

        .row-flex {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .col-flex-auto {
            flex: 1 1 auto;
            padding: 5px;
            box-sizing: border-box;
        }

        .col-flex-50 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 5px;
            box-sizing: border-box;
        }

        .col-flex-40 {
            flex: 0 0 40%;
            max-width: 40%;
            padding: 5px;
            box-sizing: border-box;
        }

        .col-flex-60 {
            flex: 0 0 60%;
            max-width: 60%;
            padding: 5px;
            box-sizing: border-box;
        }

        .col-flex-70 {
            flex: 0 0 70%;
            max-width: 70%;
            padding: 5px;
            box-sizing: border-box;
        }


        .col-flex-30 {
            flex: 0 0 30%;
            max-width: 30%;
            padding: 5px;
            box-sizing: border-box;
        }

        .col-flex-100 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .logo-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo-info img {
            width: 120px;
            height: 120px;
            margin-right: 15px;
            object-fit: contain;
        }

        .rut-box {
            border: 2px solid #dc3545;
            padding: 10px;
            text-align: center;
            line-height: 1.3;
        }

        .alert-info-custom {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 8px 15px;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .table-custom th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
        }

        .table-custom thead.bg-secondary th {
            background-color: #6c757d;
            color: white;
        }

        .table-custom.bg-light tbody tr:nth-child(even) {
             background-color: #f8f9fa;
        }

        .bg-primary-custom {
            background-color: #007bff;
            color: white;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .bg-info-custom {
            background-color: #17a2b8;
            color: white;
            padding: 8px;
            text-align: center;
            font-size: 0.9em;
        }

        .chart-container {
            position: relative;
            height: 150px;
            width: 100%;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .chart-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-print:hover {
            background-color: #218838;
        }

        @media print {
            html, body {
                margin: 0;
                padding: 0;
                height: 100% !important;
                width: 100% !important;
                overflow: hidden !important; /* Esconde scrollbars del iframe */
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
                background-color: #fff !important;

                font-size: 10px;
            }
        .boleta-print-button {
                display: none !important;
            }

            .container-boleta {
                width: 100% !important;
                height: 100% !important;
                max-height: 100% !important;
                padding: 5mm !important;
                margin: 0 !important;
                border: none;
                box-shadow: none;
                background-color: #fff !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                box-sizing: border-box !important;
                transform: none !important;
                transform-origin: unset !important;
                page-break-inside: avoid !important;
            }

            p, td, th, .small {
                font-size: 0.6em !important;
                line-height: 1.1 !important;
                margin-bottom: 0.1rem !important;
            }
            .table-custom th, .table-custom td {
                padding: 1mm 2mm !important;
            }
            h5 { font-size: 0.8em !important; }
            h6 { font-size: 0.7em !important; }

            .p-3 { padding: 3mm !important; }
            .mt-2 { margin-top: 1.5mm !important; }
            .mb-0 { margin-bottom: 0 !important; }
            .mb-1 { margin-bottom: 0.5mm !important; }
            .mb-2 { margin-bottom: 1mm !important; }
            .mb-3 { margin-bottom: 1.5mm !important; }

            .logo-info img {
                max-width: 30mm !important;
                max-height: 30mm !important;
                object-fit: contain;
                margin-right: 3mm !important;
            }
            .chart-container {
                height: 20mm !important;
                max-height: 20mm !important;
                margin-top: 2mm !important;
                page-break-inside: avoid !important;
            }
            .chart-container img {
                max-width: 100% !important;
                max-height: 100% !important;
                object-fit: contain !important;
            }

            .row-flex {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-bottom: 1mm !important;
            }
            .col-flex-50, .col-flex-40, .col-flex-60, .col-flex-70, .col-flex-30, .col-flex-100 {
                flex: 0 0 auto !important;
                padding: 1mm !important;
                box-sizing: border-box !important;
                page-break-inside: avoid !important; /* Evita que las columnas se rompan */
            }
            .col-flex-50 { width: 50% !important; max-width: 50% !important; }
            .col-flex-40 { width: 40% !important; max-width: 40% !important; }
            .col-flex-60 { width: 60% !important; max-width: 60% !important; }
            .col-flex-70 { width: 70% !important; max-width: 70% !important; }
            .col-flex-30 { width: 30% !important; max-width: 30% !important; }
            .col-flex-100 { width: 100% !important; max-width: 100% !important; }


            .table-custom, .table-custom tr, .table-custom td,
            .text-center.mt-2, .mt-2 > .bg-info-custom {
                page-break-inside: avoid !important;
            }

            .bg-primary-custom, .bg-info-custom, .rut-box, .alert-info-custom,
            .text-primary, .text-danger, .text-success {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-color: inherit !important;
                color: inherit !important;
            }
            .bg-primary-custom { background-color: #007bff !important; color: white !important; padding: 2mm !important;}
            .bg-info-custom { background-color: #17a2b8 !important; color: white !important; padding: 2mm !important;}
            .rut-box { border-color: #dc3545 !important; }
            .alert-info-custom { background-color: #d1ecf1 !important; color: #0c5460 !important; padding: 2mm !important;}
            .table-custom thead.bg-secondary th { background-color: #6c757d !important; color: white !important; }
        }

        @media (max-width: 768px) {
            .col-flex-50,
            .col-flex-40,
            .col-flex-60,
            .col-flex-70,
            .col-flex-30 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .logo-info {
                flex-direction: column;
                text-align: center;
            }

            .logo-info img {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container-boleta">
        <div class="p-0">
            <div class="row-flex">
                <div class="col-flex-70">
                    <div class="logo-info">
                        <img src="{{ $org->logo ?? 'https://via.placeholder.com/120x120?text=Logo' }}" alt="Logo de la Organización">
                        <div>
                            <h5 class="fw-bold text-primary mb-0">COMITÉ AGUA POTABLE RURAL LAS ARBOLEDAS</h5>
                            <p class="mb-0 small">Captación, Purificación y Venta de agua potable</p>
                            <p class="mb-0 small">Callejón La Copa S/N Las Arboledas - Teno</p>
                            <p class="mb-0 small">Cel: +569 5858 6377</p>
                            <p class="mb-0 small">Fono Emergencia: +569 8646 8372</p>
                            <p class="mb-0 small">Correo: comiteaprlasarboledas@gmail.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-flex-30">
                    <div class="rut-box">
                        <p class="mb-1 fw-bold">RUT 71.108.700-1</p>
                        <p class="mb-1 text-danger fw-bold">FACTURA EXENTA<br>ELECTRÓNICA</p>
                        <p class="mb-1 fw-bold">N° {{ $reading->folio ?? 'XXXXX' }}</p>
                        <p class="mb-1">S.I.I. CURICÓ</p>
                        <p class="mb-0">Fecha: {{ \Carbon\Carbon::parse($reading->fecha_lectura)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="alert-info-custom">
                Recuerda proteger tu medidor... en tiempos de invierno
            </div>

            <div class="row-flex">
                <div class="col-flex-60">
                    <table class="table-custom">
                        <tr>
                            <td class="fw-bold" style="width: 100px;">RUT</td>
                            <td>{{ $reading->member->rut ?? 'XXXXXXXX-X' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">NOMBRE</td>
                            <td>{{ $reading->member->full_name ?? 'Nombre del Cliente' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">DIRECCIÓN</td>
                            <td>{{ $reading->member->address ?? 'Dirección del Cliente' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">SECTOR</td>
                            <td>{{ $reading->service->sector ?? 'Sector del Servicio' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-flex-40">
                    <table class="table-custom">
                        <tr>
                            <td class="fw-bold">SERVICIO</td>
                            <td>{{ isset($reading->service->nro) ? str_pad($reading->service->nro, 5, '0', STR_PAD_LEFT) : '00000' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">MIDEPLAN</td>
                            <td>{{ $reading->service->mideplan ?? '0' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">DIÁMETRO</td>
                            <td>{{ $reading->service->diameter ?? '1/2' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">MEDIDOR</td>
                            <td>{{ $reading->service->meter_type ?? 'Tipo de Medidor' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row-flex">
                <div class="col-flex-50">
                    <div class="bg-primary-custom">Su detalle de consumo en M3</div>
                    <table class="table-custom mb-2">
                        <tr>
                            <td>Lectura Actual</td>
                            <td class="text-end"> {{ isset($reading->period) ? \Carbon\Carbon::parse($reading->period . '-01')->endOfMonth()->format('d-m-Y') : 'DD-MM-YYYY' }}</td>
                            <td class="text-end">{{ $reading->current_reading ?? '0' }}</td>
                        </tr>
                        <tr>
                            <td>Lectura Anterior</td>
                             @if(isset($readingAnterior))
                                <td class="text-end"> {{ \Carbon\Carbon::parse($readingAnterior->period . '-01')->endOfMonth()->format('d-m-Y') }} </td>
                                <td class="text-end">{{ $reading->previous_reading ?? '0' }}</td>
                            @else
                                <td class="text-end">-</td>
                                <td class="text-end">-</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Consumo Calculado</td>
                            <td></td>
                            <td class="text-end">{{ $reading->cm3 ?? '0' }}</td>
                        </tr>
                    </table>

                    <table class="table-custom bg-light">
                        <thead class="bg-secondary">
                            <tr>
                                <th class="text-start">TRAMOS</th> <th class="text-end">M3</th>
                                <th class="text-end">$</th>
                                <th class="text-end">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Assuming $tier is an array or collection of objects with range_from, range_to, m3, precio, and total --}}
                            @forelse($tier ?? [] as $tierConfig)
                                <tr>
                                    <td class="text-start">{{ $tierConfig->range_from ?? '0' }} Hasta {{ $tierConfig->range_to ?? '0' }}</td>
                                    <td class="text-end">{{ $tierConfig->m3 ?? '0' }} X</td>
                                    <td class="text-end">{{ isset($tierConfig->precio) ? number_format($tierConfig->precio, 0, ',', '.') : '$0' }}</td>
                                    <td class="text-end">{{ isset($tierConfig->total) ? number_format($tierConfig->total, 0, ',', '.') : '$0' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay tramos de consumo disponibles.</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td class="text-start fw-bold">Facturado</td> <td class="text-end fw-bold">{{ $reading->cm3 ?? '0' }}</td>
                                <td></td>
                                <td class="text-end fw-bold">{{ isset($tier) ? number_format($tier->sum('total'), 0, ',', '.') : '$0' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-flex-50">
                    <div class="bg-primary-custom">Su detalle de consumo en Pesos ($)</div>
                    <table class="table-custom">
                        <tr>
                            <td>(+) Cargo Fijo</td>
                            <td class="text-end">{{ isset($configCost->fixed_charge_penalty) ? number_format($configCost->fixed_charge_penalty, 0, ',', '.') : '$0' }}</td>
                        </tr>
                        <tr>
                            <td>(+) Consumo Agua Potable</td>
                            <td class="text-end">{{ isset($reading->vc_water) ? number_format($reading->vc_water, 0, ',', '.') : '$0' }}</td>
                        </tr>
                        <tr>
                            <td>(-) Subsidio @if (isset($reading->service->percentage) && $reading->service->percentage != 0) {{ $reading->service->percentage }}% @else 0% @endif Tope {{ $configCost->max_covered_m3 ?? '0' }} M3 </td>
                            <td class="text-end"> {{ isset($reading->v_subs) && is_numeric($reading->v_subs) ? number_format($reading->v_subs, 0, ',', '.') : '$0' }} </td>
                        </tr>
                        <tr>
                            <td>(=) SubTotal Consumo Mes</td>
                            <td class="text-end">{{ isset($reading->total_mounth) ? number_format($reading->total_mounth, 0, ',', '.') : '$0' }}</td>
                        </tr>
                        <tr>
                            <td>(+) Saldo Anterior</td>
                            <td class="text-end"> {{ isset($reading->s_previous) ? number_format($reading->s_previous, 0, ',', '.') : '$0' }} </td>
                        </tr>
                        <tr>
                            <td>(+) Multas Vencidas</td>
                            <td class="text-end"> {{ isset($reading->multas_vencidas) ? number_format($reading->multas_vencidas, 0, ',', '.') : '$0' }} </td>
                        </tr>
                        <tr>
                            <td>(+) Otros Cargos</td>
                            <td class="text-end"> {{ isset($reading->other) ? number_format($reading->other, 0, ',', '.') : '$0' }} </td>
                        </tr>
                        <tr>
                            <td>(+) Corte y Reposición</td>
                            <td class="text-end"> {{ isset($reading->corte_reposicion) ? number_format($reading->corte_reposicion, 0, ',', '.') : '$0' }} </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row-flex justify-content-end">
                <div class="col-flex-50">
                    <table class="table-custom">
                        <tr>
                            <td class="fw-bold text-end">SubTotal</td>
                            <td class="text-end">@money($reading->sub_total)</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-end">IVA (19%)</td>
                            <td class="text-end">@money($iva)</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-end">Total IVA incluido</td>
                            <td class="text-end text-success fw-bold">@money($total_con_iva)</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="chart-container">
                    <img src="data:image/png;base64, {{ $reading->invoice_bell ?? '' }}"      alt="Lectura {{$reading->id}} de {{$reading->invoice_type}}"/>
                </div>

            <div class="text-center mt-2">
                <p class="small mb-0">Último Pago {{ \Carbon\Carbon::now()->subDays(30)->format('d-m-Y') }} Monto {{ isset($reading->total) ? number_format($reading->total, 0, ',', '.') : '$0' }}</p>
                <p class="small mb-0">Res.SII N° 99 de 2014</p>
                <p class="small mb-0">Verifique su documento: www.sii.cl</p>
                <p class="fw-bold text-primary">¡El agua es vida, cuídala!</p> </div>

            <div class="mt-2">
                <div class="bg-info-custom">
                    Horario de Atención: Lunes a Viernes desde las 14:00 a 18:00 horas HORARIO Verano
                </div>
            </div>

            <div class="text-center d-print-none mt-3">
                <button onclick="window.print()" class="btn-print boleta-print-button" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0 1h11a.5.5 0 1 0 0-1z"/>
                         <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M2 1h12a1 1 0 0 1 1 1v2H1V2a1 1 0 0 1 1-1M1 14V8h14v6a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1m14-5H1V5h14z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // No hay gráfico de Chart.js aquí, la imagen se carga directamente.
            // Si en el futuro necesitas un gráfico interactivo, se podría integrar Chart.js.
        });
    </script>

    </body>
</html>



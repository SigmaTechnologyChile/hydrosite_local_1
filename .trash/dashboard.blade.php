@extends('layouts.nice', ['active'=>'orgs.dashboard','title'=>'Dashboard'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item active">{{$org->name}}</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <!-- Filtros de A�1�70�1�79o y Mes -->
<div class="filters mb-4">
    <form method="GET" action="{{ route('orgs.dashboard', $org->id) }}">
        <div class="row g-3 align-items-end"> <!-- Cambio clave: align-items-end -->
            <!-- Filtro de A�1�70�1�79o -->
            <div class="col-md-4">
                <label for="year" class="form-label mb-1">�9�1 A�0�9o</label> <!-- mb-1 para reducir espacio -->
                <select id="year" name="year" class="form-select"> <!-- Cambiado a form-select -->
                    @php
                        $currentYear = date('Y');
                        $selectedYear = request('year', $currentYear);
                    @endphp
                    
                    @foreach(range(2020, $currentYear + 1) as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bot�1�7�1�7n de filtrado - Correcci�1�7�1�7n: eliminado mt-4 y ajustada alineaci�1�7�1�7n -->
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>
</div>
    <br>

    <section class="section">
      <div class="row">
        <!-- CONSUMO TOTAL -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�1 M3 CONSUMO TOTAL</h5>
              <div style="height: 450px;">
                <canvas id="lineChart"></canvas>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#lineChart'), {
                    type: 'line',
                    data: {
                      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                      datasets: [{
                        label: 'M3 de Consumo mensual',
                        data: [
                            @foreach($m3_consume as $m3)
                            {{$m3}},
                            @endforeach
                            ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                      }]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
        <!-- CONSUMO ACTUAL POR SECTOR -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�2�9�1 CONSUMO ACTUAL POR SECTOR</h5>
              <div style="height: 450px;">
                <canvas id="currentMonthChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
            const m3Consume = @json($m3_consume);
            const paymentConsume = @json($payment_consume);
            const totalRecaudado = @json($total_recaudado);
            const sectorConsumo = @json($sector_consumo);
    
            const sectorLabels = sectorConsumo.map(sector => sector.sector);
            const sectorData = sectorConsumo.map(sector => sector.consumo_mes);
    
            console.log(sectorConsumo);
            // Gr��fico: Consumo actual por sector
            new Chart(document.querySelector('#currentMonthChart'), {
              type: 'bar',
              data: {
                labels: sectorLabels,
                datasets: [{
                  label: 'Consumo Actual (m�0�6)',
                  data: sectorData,
                  backgroundColor: 'rgb(75, 192, 192)',
                }]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                  title: {
                    display: true,
                    text: 'Consumo Actual por Sector'
                  },
                  legend: {
                    display: false
                  }
                },
                scales: {
                  x: {
                    beginAtZero: true,
                    title: {
                      display: true,
                      text: 'Consumo Total (m�0�6)'
                    }
                  }
                }
              }
            });
          });
        </script>
      

        <!-- TOTAL FACTURADO -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�8 TOTAL FACTURADO</h5>
              <div style="height: 450px;">
                <canvas id="barChart"></canvas>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#barChart'), {
                    type: 'bar',
                    data: {
                      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                      datasets: [{
                        label: '$ Monto de consumo',
                        data:[
                            @foreach($payment_consume as $payment)
                            {{$payment}},
                            @endforeach
                            ],
                        backgroundColor: [
                          'rgba(255, 99, 132, 0.2)',
                          'rgba(255, 159, 64, 0.2)',
                          'rgba(255, 205, 86, 0.2)',
                          'rgba(75, 192, 192, 0.2)',
                          'rgba(54, 162, 235, 0.2)',
                          'rgba(153, 102, 255, 0.2)',
                          'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                          'rgb(255, 99, 132)',
                          'rgb(255, 159, 64)',
                          'rgb(255, 205, 86)',
                          'rgb(75, 192, 192)',
                          'rgb(54, 162, 235)',
                          'rgb(153, 102, 255)',
                          'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1
                      }]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
        <!-- TOTAL RECAUDADO -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�3 TOTAL RECAUDADO</h5>
              <div style="height: 450px;">
                <canvas id="barChartRecaudado"></canvas>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#barChartRecaudado'), {
                    type: 'bar',
                    data: {
                      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                      datasets: [{
                        label: '$ Total recaudado',
                        data:[
                            @foreach($total_recaudado as $recaudado)
                            {{ $recaudado }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.4)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
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
                              return '$' + value.toLocaleString();
                            }
                          }
                        }
                      },
                      plugins: {
                        legend: {
                          display: true,
                          position: 'top'
                        },
                        tooltip: {
                          callbacks: {
                            label: function(context) {
                              return '$' + context.raw.toLocaleString();
                            }
                          }
                        }
                      }
                    }
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
        <!-- ESTADO DE PAGO POR CLIENTE -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�7�3�7�4 ESTADO DE PAGO POR CLIENTE</h5>
              <div style="height: 450px;">
                <canvas id="barChartPagosEstado"></canvas>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#barChartPagosEstado'), {
                    type: 'bar',
                    data: {
                      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                      datasets: [
                        {
                          label: 'Pagados',
                          data: [
                            @for($i = 1; $i <= 12; $i++)
                              {{ $payment_status_count[$i]['pagado'] ?? 0 }},
                            @endfor
                          ],
                          backgroundColor: 'rgba(54, 162, 235, 0.6)'
                        },
                        {
                          label: 'No Pagados',
                          data: [
                            @for($i = 1; $i <= 12; $i++)
                              {{ $payment_status_count[$i]['no_pagado'] ?? 0 }},
                            @endfor
                          ],
                          backgroundColor: 'rgba(255, 99, 132, 0.6)'
                        }
                      ]
                    },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      },
                      plugins: {
                        legend: {
                          display: true,
                          position: 'top'
                        }
                      }
                    }
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
        <!-- DEUDORES POR CONDICI�0�7N -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�7 DEUDORES POR CONDICI�0�7N</h5>
              <div style="height: 450px;">
                <canvas id="deudoresCondicionChart"></canvas>
              </div>
              <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#deudoresCondicionChart'), {
                      type: 'pie',
                      data: {
                        labels: [
                          '30 d��as ',
                          '60 d��as ',
                          '90 d��as '
                        ],
                        datasets: [{
                          label: 'Deudores',
                          data: @json($deudores),
                          backgroundColor: [
                            '#FFD700',  // Amarillo
                            '#4B9CD3',  // Azul
                            '#E9AFA3'   // Rojo
                          ],
                          hoverOffset: 4
                        }]
                      },
                      options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                          legend: {
                            display: true,
                            position: 'top'  
                          },
                          tooltip: {
                            callbacks: {
                              label: function(context) {
                                let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                let percentage = Math.round((context.raw / total) * 100);
                                return `${context.label}: ${context.raw} deudores (${percentage}%)`;
                              }
                            }
                          }
                        }
                      }
                    });
                  });
                </script>
            </div>
          </div>
        </div>
        
        <!-- TOTAL EXTRA�0�1DO / TOTAL P�0�7RDIDA -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�7�2�1�5 TOTAL EXTRA�0�1DO / TOTAL P�0�7RDIDA (m�0�6)</h5>
              <p class="text-muted mb-2" style="font-weight: 500; text-align: center;">Total Extra&iacute;do v/s Total P&eacute;rdida (m&sup3;)</p> 
              <div style="height: 410px;">
                <canvas id="extraccionPerdidaChart"></canvas>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const labelsData = @json($labels);
                    const extraidoData = Object.values(@json($totalExtraido)).map(Number);
                    const perdidaData = Object.values(@json($totalPerdida)).map(Number);
   
                    new Chart(document.querySelector('#extraccionPerdidaChart'), {
                      type: 'line',
                      data: {
                        labels: labelsData,
                        datasets: [{
                          label: 'Total Extra��do',
                          data: extraidoData,
                          backgroundColor: 'rgba(75, 192, 192, 0.2)',
                          borderColor: 'rgba(75, 192, 192, 1)',
                          fill: true,
                          tension: 0.4
                        },
                        {
                          label: 'Total P��rdida',
                          data: perdidaData,
                          backgroundColor: 'rgba(255, 99, 132, 0.2)',
                          borderColor: 'rgba(255, 99, 132, 1)',
                          fill: true,
                          tension: 0.4
                        }]
                      },
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      plugins: {
                        legend: { position: 'top' }
                      },
                      scales: {
                        y: {
                          title: { display: true, text: 'Volumen (m&sup3;)' }
                        },
                        x: {
                          title: { display: true, text: 'Meses' }
                        }
                      }
                    }
                  });
                });
              </script>
            </div>
          </div>
        </div>
        
        <!-- CONSUMO ANUAL POR SECTOR -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">�9�4 CONSUMO ANUAL POR SECTOR</h5>
              <div style="height: 450px;">
                <canvas id="summaryChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            
            const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            const sectorConsumo = @json($sector_consumo);
            const sectorLabels = sectorConsumo.map(sector => sector.sector);  
            const sectorData = sectorConsumo.map(sector => sector.consumo_anio);
        
            new Chart(document.querySelector('#summaryChart'), {
              type: 'bar',  
              data: {
                labels: sectorLabels,  
                datasets: [{
                  label: 'Consumo Total Anual',  
                  data: sectorData,  
                  backgroundColor: 'rgb(75, 192, 192)', 
                }]
              },
              options: {
                responsive: true,  
                maintainAspectRatio: false,  
                indexAxis: 'y', 
                plugins: {
                  title: {
                    display: true,  
                    text: 'Resumen Anual por Sector' 
                  },
                  legend: {
                    display: false  
                  }
                },
                scales: {
                  x: {
                    beginAtZero: true,  
                    title: {
                      display: true,
                      text: 'Consumo Total (m�0�6)'  
                    }
                  }
                }
              }
            });
          });
        </script>

        <!-- Ocultar los gr��ficos adicionales con d-none -->
        <div class="col-lg-6 d-none">
          <!-- Contenido oculto que existe en el c��digo original -->
        </div>
      </div>
    </section>
@endsection
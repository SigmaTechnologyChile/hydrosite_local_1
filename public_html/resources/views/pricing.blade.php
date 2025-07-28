@extends('layouts.app', ['active'=>'pricing','title'=>'Precios'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Precios</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Precios</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Planes</h2>
        <p>Elige el plan que mejor se adapte a tus necesidades</p>
        <br>
      </div><!-- End Section Title -->

      <div class="container" data-aos="zoom-in" data-aos-delay="100">

        <div class="row g-4">

          <div class="col-lg-4">
            <div class="pricing-item">
              <h3>Básico</h3>
              <div class="icon">
                <i class="bi bi-box"></i>
              </div>

              
              <!-- Categoría: módulo control de clientes -->
            <h5 class="category-title">módulo control de clientes</h5>
              <ul>
                <li><i class="bi bi-check"></i> <span>registro de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>tabla de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>lectura de medidores</span></li>
                <li><i class="bi bi-check"></i> <span>historial de lecturas</span></li>
                <li><i class="bi bi-check"></i> <span>historial de consumos</span></li>
                <li><i class="bi bi-check"></i> <span>generar boletas sii</span></li>
                <li><i class="bi bi-check"></i> <span>calculo de tarifas</span></li>
              </ul>
              <div class="text-center"><a href="#" class="buy-btn">Empezar</a></div>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-4">
            <div class="pricing-item featured">
              <h3>Intermedio</h3>
              <div class="icon">
                <i class="bi bi-rocket"></i>
              </div>
           
              <!-- Categoría: Módulo Control de Clientes -->
            <h5 class="category-title">módulo control de clientes</h5>
              <ul>
                <li><i class="bi bi-check"></i> <span>registro de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>tabla de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>lectura de medidores</span></li>
                <li><i class="bi bi-check"></i> <span>historial de lecturas</span></li>
                <li><i class="bi bi-check"></i> <span>historial de consumos</span></li>
                <li><i class="bi bi-check"></i> <span>generar boletas sii</span></li>
                <li><i class="bi bi-check"></i> <span>calculo de tarifas</span></li>
              </ul>
            <!-- Categoría: Módulo de Pagos -->
            <h5 class="category-title">módulo de pagos</h5>
            <ul>
                <li><i class="bi bi-check"></i> <span>pago transbank</span></li>
                <li><i class="bi bi-check"></i> <span>pago online webpay</span></li>
                <li><i class="bi bi-check"></i> <span>historial de pagos</span></li>
            </ul>  
              <div class="text-center"><a href="#" class="buy-btn">Empezar</a></div>
            </div>
          </div><!-- End Pricing Item -->


          <div class="col-lg-4">
            <div class="pricing-item">
              <h3>Pro</h3>
              <div class="icon">
                <i class="bi bi-send"></i>
              </div>
            
              <!-- Categoría: módulo control de clientes -->
            <h5 class="category-title">módulo control de clientes</h5>
              <ul>
                <li><i class="bi bi-check"></i> <span>registro de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>tabla de clientes</span></li>
                <li><i class="bi bi-check"></i> <span>lectura de medidores</span></li>
                <li><i class="bi bi-check"></i> <span>historial de lecturas</span></li>
                <li><i class="bi bi-check"></i> <span>historial de consumos</span></li>
                <li><i class="bi bi-check"></i> <span>generar boletas sii</span></li>
                <li><i class="bi bi-check"></i> <span>calculo de tarifas</span></li>
              </ul>
              <!-- Categoría: Módulo de Pagos -->
            <h5 class="category-title">módulo de pagos</h5>
            <ul>
                <li><i class="bi bi-check"></i> <span>pago transbank</span></li>
                <li><i class="bi bi-check"></i> <span>pago online webpay</span></li>
                <li><i class="bi bi-check"></i> <span>historial de pagos</span></li>
            </ul> 
            <!-- Categoría: Módulo contable -->
            <h5 class="category-title">módulo contable</h5>
            <ul>
                <li><i class="bi bi-check"></i> <span>dashboard(panel principal)</span></li>
                <li><i class="bi bi-check"></i> <span>ingresos y egresos</span></li>
                <li><i class="bi bi-check"></i> <span>libro de caja tabular</span></li>
            </ul> 
            <!-- Categoría: Módulo inventario -->
            <h5 class="category-title">módulo inventario</h5>
            <ul>
                <li><i class="bi bi-check"></i> <span>registro de inventario</span></li>
                <li><i class="bi bi-check"></i> <span>historial de inventario</span></li>
            </ul>
              <div class="text-center"><a href="#" class="buy-btn">Empezar</a></div>
            </div>
          </div><!-- End Pricing Item -->

        </div>

      </div>

    </section><!-- /Pricing Section -->

@endsection
@extends('layouts.app', ['active'=>'abouts','title'=>'Quienes Somos'])

@section('content')
<!-- Page Title -->
<div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Quienes Somos</h1>
        <nav class="breadcrumbs">
            <ol>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="current">Quienes Somos</li>
            </ol>
        </nav>
    </div>
</div><!-- End Page Title -->

<!-- Starter Section Section -->
<section id="starter-section" class="starter-section section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Quienes Somos</h2>
        <p>Podra encontrar la informacion sobre el uso de nuestra plataforma</p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up">
        <!-- Sección de presentación HydroSite mejorada -->
        <div class="row px-xl-5 mb-5">
            <div class="col-lg-12">
                <div class="about-card">
                    <div class="about-header" >
                        <h3 style="color: white;">Nuestra Misión</h3>
                    </div>
                    <div class="about-body">
                        <div class="row g-0">
                            <div class="col-md-4 about-sidebar">
                                <div class="about-logo">
                                    <div class="water-drop">
                                        <i class="bi bi-droplet-fill"></i>
                                    </div>
                                    <h4>HydroSite</h4>
                                    <p>Una solución hecha contigo y para tu gestión</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="about-content">
                                    <p class="highlight-text">HydroSite nace desde la experiencia en terreno y con una visión de futuro y sustentable.</p>

                                    <p>Nos construimos a partir de la necesidad real de los comités: Con quienes nos comprometemos a brindar una solución robusta, estable y escalable, que resuelva la gestión administrativa y contable con tecnología moderna, soporte real y una experiencia amigable.</p>
                                </div>
                            </div>
                        </div>

                        <div class="features-section">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <h5>Aliado Estratégico</h5>
                                        <p>Somos mucho más que un software: somos un aliado estratégico para modernizar y profesionalizar la operación de los APR del país.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="bi bi-gear-fill"></i>
                                        </div>
                                        <h5>Desarrollo Experto</h5>
                                        <p>Nuestra plataforma ha sido desarrollada desde cero por Sigma Technology Spa, en conjunto con expertos del mundo rural, contadores, ingenieros y operadores.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="feature-card">
                                        <div class="feature-icon">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <h5>Estándares Superiores</h5>
                                        <p>HydroSite no solo cumple con los estándares normativos y técnicos; los anticipa y los mejora, ofreciendo una plataforma web segura y escalable.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="closing-statement">
                            <p>Somos cercanos, pero también potentes.</p>
                            <p>Somos simples, pero profundamente completos.</p>
                            <p class="brand-statement">Somos HydroSite.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Términos y condiciones -->
        <div class="row px-xl-5">
            <div class="col-lg-12 mb-5">
                <div class="terms-card">
                    <div class="terms-header">
                        <h3>Términos y Condiciones</h3>
                    </div>
                    <div class="terms-body">
                        <p>Bienvenido a nuestro sitio web. Si persiste en navegar y usar este sitio web, acepta cumplir y estar sujeto a los siguientes términos y condiciones de uso, que en conjunto con nuestra política de privacidad rigen HydroSite de la relación con usted en relación con este sitio web. Si no está de acuerdo con alguno de los términos y condiciones mencionados a continuación, no utilice nuestro sitio.</p>

                        <p>El término HydroSiteo "nosotros" se refiere al propietario del sitio web. El término "usted" se refiere al visitante de nuestra web.</p>

                        <p>El uso de este sitio está sujeto a las siguientes condiciones:</p>

                        <ul class="terms-list">
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>El contenido de este sitio web es solo para información general y uso. Están sujetos a cambios sin previo aviso</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>Utilizamos cookies para monitorear las preferencias de navegación de los visitantes. Si permite las cookies, nosotros almacenaremos la siguiente información para uso de terceros: información sobre el país, el estado y la ciudad</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>Ni nosotros ni terceros brindamos ninguna garantía de exactitud, puntualidad, rendimiento, integridad o idoneidad de la información ofrecida en este sitio web para ningún propósito en particular. Usted reconoce que la información provista puede contener inexactitudes o errores y excluimos explícitamente la responsabilidad por tales inexactitudes en la cantidad máxima permitida por la ley</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>El uso de cualquier información en este sitio web es completamente bajo su propio riesgo, por lo cual no seremos legalmente responsables. Será su responsabilidad asegurarse de que cualquier producto, servicio o información disponible a través de este sitio web satisfaga sus necesidades específicas</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>Este sitio web contiene información que es de nuestra propiedad. Esta información incluye, pero no está restringida a, el diseño, diseño, apariencia y gráficos. Se prohíbe la duplicación, salvo que esté de acuerdo con el aviso de copyright que forma parte de estos términos y condiciones</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>Todas las marcas comerciales replicadas en este sitio web que no son propiedad del propietario se reconocen en el sitio web</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>El uso no autorizado de cualquier información puede dar lugar a un delito penal</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>Este sitio web también puede incorporar enlaces a otros sitios. Estos enlaces se ofrecen para su conveniencia para presentar información adicional. No indican que aprobemos el sitio web (s). No tenemos ninguna responsabilidad por el contenido de la información vinculada</span>
                            </li>
                            <li>
                                <span class="terms-icon"><i class="bi bi-check-circle-fill"></i></span>
                                <span>El uso de este sitio web y cualquier disputa que surja de dicho uso del sitio web está sujeto a las leyes de Chile</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<style>
    /* Variables globales */
:root {
    --primary-color: #2b6cb0;
    --primary-light: #ebf8ff;
    --primary-dark: #2c5282;
    --accent-color: #4299e1;
    --text-color: #2d3748;
    --text-muted: #718096;
    --bg-light: #f7fafc;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 1rem;
    --transition: all 0.3s ease;
}

/* Estilo general para sección "Quienes Somos" */
.about-card {
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    margin-bottom: 3rem;
    border: none;
    background-color: #fff;
    position: relative;
}

.about-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 1.5rem 2rem;
    border: none;
    position: relative;
}

.about-header h3 {
    margin: 0;
    font-weight: 700;
    font-size: 1.75rem;
    position: relative;
    z-index: 2;
}

.about-header::after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
    top: -50px;
    right: -50px;
    z-index: 1;
}

.about-body {
    padding: 0;
}

/* Sidebar con logo */
.about-sidebar {
    background-color: var(--bg-light);
    position: relative;
    overflow: hidden;
}

.about-sidebar::before {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: rgba(66, 153, 225, 0.1);
    border-radius: 50%;
    bottom: -100px;
    left: -100px;
}

.about-logo {
    padding: 3rem 1.5rem;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.water-drop {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--swiper-theme-color), var(--primary-color));
    border-radius: 50% 50% 50% 5px;
    transform: rotate(-45deg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
}

.water-drop::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    top: 25px;
    left: 25px;
}

.water-drop i {
    font-size: 3rem;
    color: white;
    transform: rotate(45deg);
}

.about-logo h4 {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
}

.about-logo p {
    color: var(--text-muted);
    font-style: italic;
}

/* Contenido principal */
.about-content {
    padding: 3rem;
}

.highlight-text {
    font-size: 1.5rem;
    font-weight: 500;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    line-height: 1.5;
    position: relative;
    padding-left: 1.5rem;
    border-left: 4px solid var(--swiper-theme-color);
}

/* Sección de características */
.features-section {
    padding: 3rem;
    background-color: var(--bg-light);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.feature-card {
    background-color: white;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-md);
    padding: 2rem;
    height: 100%;
    text-align: center;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--swiper-theme-color));
    top: 0;
    left: 0;
    transform: scaleX(0);
    transform-origin: left;
    transition: var(--transition);
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.feature-card:hover::before {
    transform: scaleX(1);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary-light), var(--bg-light));
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    position: relative;
    border: 2px solid transparent;
    background-clip: padding-box;
}

.feature-icon::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(135deg, var(--primary-color), var(--swiper-theme-color));
    border-radius: 50%;
    z-index: -1;
}

.feature-icon i {
    font-size: 2rem;
    color: var(--primary-color);
}

.feature-card h5 {
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text-color);
}

.feature-card p {
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 0;
}

/* Sección de cierre */
.closing-statement {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    padding: 4rem 2rem;
    text-align: center;
    color: white;
}

.closing-statement p {
    font-size: 1.5rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.brand-statement {
    font-size: 2.5rem !important;
    font-weight: 700 !important;
    margin-top: 1.5rem !important;
    opacity: 1 !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    position: relative;
    display: inline-block;
}

.brand-statement::after {
    content: '';
    position: absolute;
    width: 80px;
    height: 4px;
    background: white;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

/* Estilo para términos y condiciones */
.terms-card {
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    border: none;
    background-color: #fff;
}

.terms-header {
    background-color: var(--bg-light);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.terms-header h3 {
    margin: 0;
    font-weight: 600;
    color: var(--text-color);
}

.terms-body {
    padding: 2rem;
}

.terms-list {
    list-style: none;
    padding: 0;
    margin: 2rem 0 0;
}

.terms-list li {
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: flex-start;
}

.terms-list li:last-child {
    border-bottom: none;
}

.terms-icon {
    margin-right: 1rem;
    color: var(--primary-color);
    font-size: 1.25rem;
    flex-shrink: 0;
    padding-top: 0.15rem;
}

/* Efectos de animación */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.about-card,
.terms-card {
    animation: fadeIn 0.8s ease-out forwards;
}

@media (max-width: 767px) {
    .about-content {
        padding: 2rem;
    }

    .features-section {
        padding: 2rem;
    }

    .highlight-text {
        font-size: 1.25rem;
    }

    .closing-statement p {
        font-size: 1.25rem;
    }

    .brand-statement {
        font-size: 2rem !important;
    }
}
</style>

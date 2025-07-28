@extends('layouts.app', ['active'=>'termsofservice','title'=>'Terminos y Condiciones'])

@section('content')
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Terminos y Condiciones</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="current">Terminos y Condiciones</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
        <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Terminos y Condiciones</h2>
        <p>Podra encontrar la informacion sobre el uso de nuestra plataforma</p>
      </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <div class="row px-xl-5">
                <div class="col-lg-12 mb-5">
                        <p>Bienvenido a nuestro sitio web. Si persiste en navegar y usar este sitio web, acepta cumplir y estar sujeto a los siguientes términos y condiciones de uso, que en conjunto con nuestra política de privacidad rigen HydroSite de la relación con usted en relación con este sitio web. Si no está de acuerdo con alguno de los términos y condiciones mencionados a continuación, no utilice nuestro sitio.</p>
                        <p>El término HydroSiteo "nosotros" se refiere al propietario del sitio web. El término "usted" se refiere al visitante de nuestra web.</p>
                        <p>El uso de este sitio está sujeto a las siguientes condiciones:</p>
                        <ul class="list-group">
                                <li class="list-group-item">El contenido de este sitio web es solo para información general y uso. Están sujetos a cambios sin previo aviso</li>
                                <li class="list-group-item">Utilizamos cookies para monitorear las preferencias de navegación de los visitantes. Si permite las cookies, nosotros almacenaremos la siguiente información para uso de terceros: información sobre el país, el estado y la ciudad</li>
                                <li class="list-group-item">Ni nosotros ni terceros brindamos ninguna garantía de exactitud, puntualidad, rendimiento, integridad o idoneidad de la información ofrecida en este sitio web para ningún propósito en particular. Usted reconoce que la información provista puede contener inexactitudes o errores y excluimos explícitamente la responsabilidad por tales inexactitudes en la cantidad máxima permitida por la ley</li>
                                <li class="list-group-item">El uso de cualquier información en este sitio web es completamente bajo su propio riesgo, por lo cual no seremos legalmente responsables. Será su responsabilidad asegurarse de que cualquier producto, servicio o información disponible a través de este sitio web satisfaga sus necesidades específicas</li>
                                <li class="list-group-item">Este sitio web contiene información que es de nuestra propiedad. Esta información incluye, pero no está restringida a, el diseño, diseño, apariencia y gráficos. Se prohíbe la duplicación, salvo que esté de acuerdo con el aviso de copyright que forma parte de estos términos y condiciones</li>
                                <li class="list-group-item">Todas las marcas comerciales replicadas en este sitio web que no son propiedad del propietario se reconocen en el sitio web</li>
                                <li class="list-group-item">El uso no autorizado de cualquier información puede dar lugar a un delito penal</li>
                                <li class="list-group-item">Este sitio web también puede incorporar enlaces a otros sitios. Estos enlaces se ofrecen para su conveniencia para presentar información adicional. No indican que aprobemos el sitio web (s). No tenemos ninguna responsabilidad por el contenido de la información vinculada</li>
                                <li class="list-group-item">El uso de este sitio web y cualquier disputa que surja de dicho uso del sitio web está sujeto a las leyes de Chile</li>
                            </ul>
                </div>
            </div>
        </div>

    </section>
@endsection
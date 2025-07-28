    <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="/" class="logo d-flex align-items-center">
            <span class="sitename">HydroSite</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Valdivia</p>
            <p>Chile</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+56 952 46 8967</span></p>
            <p><strong>Email:</strong> <span>info@hydrosite.cl</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Enlaces Útiles</h4>
          <ul>
            <li><a href="{{ route('welcome') }}">Inicio</a></li>
            <li><a href="{{ route('termsofservice') }}">Terminos y Condiciones</a></li>
            <li><a href="{{ route('privacypolicy') }}">Politicas de privacidad</a></li>
          </ul>
        </div>

                <div class="col-lg-2 col-md-3 footer-links">
          <h4>Otros Servicios</h4>
          <ul>
            <li><a href="#">Contabilidad HydroSite</a></li>
            <li><a href="#">Diseño de Página Web</a></li>
            <li><a href="#">Inscripción en el Registro de Derechos de Aguas</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Nuestro Boletín</h4>
          <p>¡Suscríbete a nuestro boletín y recibe las últimas noticias sobre nuestros productos y servicios!</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form">
              <input type="email" name="email" placeholder="Tu correo electrónico">
              <input type="submit" value="Suscribirse">
            </div>
            <div class="loading">Cargando</div>
            <div class="error-message"></div>
            <div class="sent-message">Tu solicitud de suscripción ha sido enviada. ¡Gracias!</div>
          </form>
        </div>


      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">HydroSite®</strong> <span>Todos los derechos reservados</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Desarrollado por <strong >Sigma Technology SpA</strong>
      </div>
    </div>

    </footer>

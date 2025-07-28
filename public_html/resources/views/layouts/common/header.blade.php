<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
    
      <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
        <img src="{{asset('theme/common/img/hydrosite_favicon.png')}}" alt="">
        <h1 class="sitename">HydroSite</h1>
      </a>
    
      <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="{{route('welcome')}}" @if($active == 'welcome') class="active" @endif>Home</a></li>
            <li><a href="{{route('abouts')}}"@if($active == 'abouts') class="active" @endif>Sobre HydroSite</a></li>
            <li class="dropdown"><a href="#"><span>Servicio al Cliente</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                    <li><a href="{{route('faq')}}"@if($active == 'faq') class="active" @endif>FAQ</a></li>
                    <li><a href="{{route('contact')}}"@if($active == 'contact') class="active" @endif>Contacto</a></li>
                    <li><a href="{{route('pricing')}}"@if($active == 'pricing') class="active" @endif>Precios</a></li>
                </ul>
            </li>
            @if (Route::has('login'))
                <li class="d-xl-none auth-buttons-mobile">
                    @auth
                        <a href="{{ route('account.dashboard') }}" class="btn-getstarted">Mi Perfil</a>
                    @else
                        <a href="{{ route('accounts.rutificador') }}" class="btn-getstarted">Pagar Cuenta</a>
                        <a href="{{ route('login') }}" class="btn-getstarted">Mi Cuenta</a>
                    @endauth
                </li>
            @endif
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      @if (Route::has('login'))
          <div class="auth-buttons d-none d-xl-block">
              @auth
                  <a href="{{ route('account.dashboard') }}" class="btn-getstarted">Mi Perfil</a>
              @else
                  <a href="{{ route('accounts.rutificador') }}" class="btn-getstarted">Pagar Cuenta</a>
                  <a href="{{ route('login') }}" class="btn-getstarted">Mi Cuenta</a>
              @endauth
          </div>
      @endif
    </div>
</header>

<style>
    .auth-buttons-mobile {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 10px;
    }
    
    .auth-buttons-mobile a {
        display: block;
        width: 200px;
        margin-bottom: 25px;
        text-align: center;
        padding: 10px 20px;
        box-sizing: border-box;
        border-radius: 4px;
    }
    
    .auth-buttons-mobile a:last-child {
        margin-bottom: 0;
    }
    
    .auth-buttons-mobile a:first-child {
        margin-top: 10px;
    }
    
    @media (max-width: 1199px) {
        .auth-buttons {
            display: none !important;
        }
    }
    
    @media (max-width: 350px) {
        .auth-buttons-mobile a {
            width: 160px;
            font-size: 0.9rem;
            margin-bottom: 20px; /* Ajustamos la separación para pantallas pequeñas */
        }
    }
    
    .mobile-menu-active .navmenu ul {
        display: block;
        padding: 10px 0;
    }
</style>
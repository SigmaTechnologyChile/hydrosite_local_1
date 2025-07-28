<section class="section">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <h5 class="card-header">Accesos Directos</h5>
                <div class="card-body">
                    <nav class="navbar navbar-expand-lg navbar-light">
                      <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                          <ul class="navbar-nav">
                            @isset($org)
                              <li class="nav-item">
                                <a href="{{ route('orgs.dashboard', $org->id) }}" class="nav-link @if($active=='dashboard') active @endif" id="orgs-dashboard">Dashboard</a>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.members.create', $org->id) }}" class="nav-link @if($active=='members') active @endif" id="orgs-members">Agregar Cliente</a>
                              </li>
                              <li class="nav-item d-none">
                                <a href="{{ route('orgs.services.index', $org->id) }}" class="nav-link @if($active=='services') active @endif" id="orgs-members">Servicios</a>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.readings.index', $org->id) }}" class="nav-link @if($active=='readings') active @endif" id="orgs-tab">Ingreso de Lecturas</a>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.documents.index', $org->id) }}" class="nav-link @if($active=='documents') active @endif" id="orgs-tab">Realizar un pago</a>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.notifications.index', $org->id) }}" class="nav-link @if($active=='notifications') active @endif">Notificaciones</a>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.members.index', $org->id) }}" class="nav-link @if($active=='debts') active @endif" id="orgs-tab">Generar Boletas</a>
                              </li>
                            @else
                              <li class="nav-item">
                                <span class="nav-link text-danger">Variable $org no disponible</span>
                              </li>
                            @endisset
                          </ul>
                        </div>
                      </div>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <h5 class="card-header">M��dulos</h5>
                <div class="card-body">
                    <nav class="navbar navbar-expand-lg navbar-light">
                      <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown2" aria-controls="navbarNavDropdown2" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown2">
                          <ul class="navbar-nav">
                            @isset($org)
                              <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  Control de Clientes
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <li><a class="dropdown-item" href="{{ route('orgs.members.create', $org->id) }}">Registro de clientes</a></li>
                                  <li><a class="dropdown-item" href="{{ route('orgs.members.index', $org->id) }}">Lista de Clientes..</a></li>
                                  <li><a class="dropdown-item" href="#">Lectura de Medidores</a></li>
                                </ul>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.services.index', $org->id) }}" class="nav-link @if($active=='services') active @endif" id="orgs-members">Ingreso y Egreso</a>
                              </li>
                              <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  Facturaci��n
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                  <li><a class="dropdown-item" href="{{ route('orgs.sections.index', $org->id) }}">C��lculo de tarifas</a></li>
                                  <li><a class="dropdown-item" href="{{ route('orgs.folios.index', $org->id) }}">DTE / Boleta Electr��nica</a></li>
                                  <li><a class="dropdown-item" href="#">Pagos</a></li>
                                </ul>
                              </li>
                              <li class="nav-item">
                                <a href="{{ route('orgs.inventories.index', $org->id) }}" class="nav-link @if($active=='inventories') active @endif" id="orgs-tab">Inventario</a>
                              </li>
                            @else
                              <li class="nav-item">
                                <span class="nav-link text-danger">M��dulos no disponibles</span>
                              </li>
                            @endisset
                          </ul>
                        </div>
                      </div>
                    </nav>
                </div>
              </div>
            </div>
      </div>
</section>

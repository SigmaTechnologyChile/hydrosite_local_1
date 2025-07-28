@extends('layouts.nice', ['active'=>'orgs','title'=>'Documentos'])

@section('content')
    <div class="pagetitle">
      <h1>{{$org->name}}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Organizaciones</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.dashboard',$org->id)}}">{{$org->name}}</a></li>
          <li class="breadcrumb-item active">Tramos</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    @include('orgs.menu',['active'=>'documents'])
    
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="" class="btn btn-primary me-md-2 btn-sm rounded-pill px-4 shadow-sm pulse-btn"><!-- route -->
            <i class="bi bi-box-arrow-right me-2"></i>Exportar
        </a>
        
    </div>
    <section class="section dashboard">
        <div class="row gy-4">
           @foreach($documents as $document) 
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filtro</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Editar</a></li>
                    <li><a class="dropdown-item" href="#">Eliminar</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Folio: {{$document->folio}} <span>| {{$document->updated_at}}</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6>@money($document->total)</h6>
                      <span class="text-muted small pt-2 ps-1">Desde</span><span class="text-success small pt-1 fw-bold">{{$document->from_to}}</span>  <span class="text-muted small pt-2 ps-1">Hasta </span><span class="text-success small pt-1 fw-bold">{{$document->until_to}}</span>

                    </div>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>
        {!! $documents->render('pagination::bootstrap-4') !!}
    </section>
@endsection
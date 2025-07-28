@extends('layouts.nice', ['active'=>'billings-create','title'=>'Crear Folio'])

@section('content')
    <div class="pagetitle">
      <h1>Crear Folios</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('orgs.index')}}">Facturaciones</a></li>
          <li class="breadcrumb-item active">Crear Folios</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Solicitar nuevos Folios al SII</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3"action="{{ route('billings.folios.store') }}" method="POST">
                @csrf
                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectOrg" name="org_id" aria-label="Organización" required>
                        @foreach($orgs as $org)
                        <option value="{{$org->id}}">{{$org->rut}} {{$org->name}}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelectOrg">Organización</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="floatingFolios" name="qxt" placeholder="100" min="1" required>
                    <label for="floatingFolios">Cantidad Folios</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelectType" name="typeDte" aria-label="Tipo DTE" required>
                        <option value="34">34 | Factura No Afecta o Exenta Electrónica</option>
                        <option value="39">39 | Boleta Electrónica</option>
                        <option value="41">41 | Boleta No Afecta o Exenta Electrónica</option>
                        <option value="52">52 | Guía de Despacho Electrónica</option>
                        <option value="110">110 | Factura de Exportación Electrónica</option>
                        <option value="111">111 | Nota de Débito de Exportación Electrónica</option>
                        <option value="112">112 | Nota de Crédito de Exportación Electrónica</option>
                    </select>
                    <label for="floatingSelectType">Tipo DTE</label>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Solicitar</button>
                  <button type="reset" class="btn btn-secondary">Borrar</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
    </section>
@endsection
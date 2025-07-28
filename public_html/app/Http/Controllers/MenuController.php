<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\View;

class MenuController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

     /**
     * Obtiene la ruta correspondiente a una vista
     */
    // public function getRouteForView($viewName, $orgId = null)
    // {
    //     $routes = [
    //         'AGREGAR CLIENTES' => 'orgs.members.create',
    //         'LISTA DE CLIENTES' => 'orgs.members.index',
    //         'LISTA DE SERVICIOS' => 'orgs.services.index',
    //         'INGRESO DE LECTURAS' => 'orgs.readings.index',
    //         'GENERAR BOLETAS ELECTRONICAS' => 'orgs.folios.create',
    //         'CALCULO DE TARIFAS' => 'orgs.sections.index',
    //         'HISTORIAL DE DTE' => 'orgs.historyDTE',
    //         'HISTORIAL DE FOLIOS' => 'orgs.folios.index',
    //         'REALIZAR PAGOS (TRANSBANK)' => 'orgs.payments.index',
    //         'REALIZAR PAGOS (WEBPAY)' => 'orgs.payments.index',
    //         'HISTORIAL DE PAGOS' => 'orgs.payments.history',
    //         'REGISTRO DE LECTURAS MACROMEDIDOR' => 'orgs.aguapotable.macromedidor.index',
    //         'HISTORIAL DE LECTURAS MACROMEDIDOR' => 'orgs.readings.history',
    //         'DASHBOARD PRINCIPAL' => 'orgs.dashboard',
    //         'LISTA DE SECTORES' => 'orgs.locations.index',
    //         'PANEL DE CONTROL RUTAS' => 'orgs.operator.index',
    //         'REGISTRO DE INGRESOS Y EGRESOS' => 'orgs.payments.index',
    //         'LIBRO DE CAJA TABULAR CON FIRMA ELECTRONICA' => 'orgs.payments.history',
    //         'REPORTE AUTOMATIZADO PARA SSR' => 'orgs.dashboard',
    //         'SOLICITUD DE AJUSTE TARIFARIO ONLINE' => 'orgs.sections.index',
    //         'DASHBOARD' => 'orgs.dashboard',
    //         'REGISTRO DE CLORACION' => 'orgs.aguapotable.cloracion.index',
    //         'HISTORIAL DE CLORACIÓN' => 'orgs.aguapotable.cloracion.index',
    //         'REGISTRO DE ANALISIS DE AGUA' => 'orgs.aguapotable.analisis.index',
    //         'HISTORIAL DE ANALISIS DE AGUA' => 'orgs.aguapotable.analisis.index',
    //         'REGISTRO DE INVENTARIO' => 'orgs.inventories.create',
    //         'HISTORIAL DE INVENTARIO' => 'orgs.inventories.index',
    //         'PANEL DE CONFIGURACION' => 'orgs.dashboard',
    //     ];

    //     return $routes[$viewName] ?? 'orgs.dashboard';
    // }

    /**
     * Obtiene el icono correspondiente a un módulo
     */
    // public function getIconForModule($moduleName)
    // {
    //     $icons = [
    //         'MODULO CONTROL DE CLIENTES' => 'ri-folder-user-line',
    //         'MODULO FACTURACION' => 'ri-bill-line',
    //         'AGUA POTABLE' => 'ri-drop-line',
    //         'MODULO CONTABLE' => 'ri-calculator-line',
    //         'INVENTARIO' => 'ri-archive-line',
    //     ];

    //     return $icons[$moduleName] ?? 'bi-circle';
    // }
}

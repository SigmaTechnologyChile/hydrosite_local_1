<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

//     public function getRouteForView($viewName, $orgId = null)
// {
//     // Puedes usar un switch o un array asociativo para mapear
//     $routes = [
//         'Ingreso de Lecturas' => 'orgs.readings.index',
//         'Generar Boletas' => 'orgs.folios.create',
//         'Ingresar un pago' => 'orgs.payments.index',
//         // Agrega todos los que necesites aquí
//     ];

//     if (!isset($routes[$viewName])) {
//         return '#'; // o abort(404)
//     }

//     if ($orgId) {
//         return route($routes[$viewName], $orgId);
//     }

//     return route($routes[$viewName]);
// }

// public function getIconForModule($moduleName)
// {
//     $icons = [
//         'Usuarios' => 'bi bi-people',
//         'Lecturas' => 'bi bi-clipboard-data',
//         'Boletas' => 'bi bi-receipt',
//         'Pagos' => 'bi bi-credit-card',
//         'Dashboard' => 'bi bi-speedometer2',
//         // Agrega todos los módulos que uses
//     ];

//     return $icons[$moduleName] ?? 'bi bi-box'; // ícono por defecto
// }
}

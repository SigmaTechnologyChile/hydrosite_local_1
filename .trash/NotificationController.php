<?php
namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use App\Models\User;
use App\Models\Org;

class NotificationController extends Controller
{
    public function index($id)
    {
        $org = Org::findOrFail($id);
        $activeLocations = Location::where('org_id', $org->id)->get();
        // Puedes agregar aquí la consulta de notificaciones si lo necesitas
        return view('orgs.notifications.index', compact('org', 'activeLocations'));
    }
    public function store(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'sectors' => 'required_without:send_to_all|array',
        ]);

        $org = Org::findOrFail($id);
        $title = $request->input('title');
        $message = $request->input('message');
        $sendToAll = $request->has('send_to_all');

        // Obtener destinatarios
        if ($sendToAll) {
            $users = User::where('org_id', $org->id)->whereNotNull('email')->get();
        } else {
            $sectorIds = $request->input('sectors', []);
            $users = User::where('org_id', $org->id)
                ->whereIn('location_id', $sectorIds)
                ->whereNotNull('email')
                ->get();
        }

        // Enviar notificación por email
        \Log::info('Iniciando envío de notificaciones');
        foreach ($users as $user) {
            try {
                \Log::info('Enviando correo a: ' . $user->email);
                Mail::to($user->email)->send(new NotificationMail($title, $message, $org, $user));
                \Log::info('Correo enviado exitosamente a: ' . $user->email);
            } catch (\Exception $e) {
                \Log::error('Error enviando correo a ' . $user->email . ': ' . $e->getMessage());
            }
        }
        \Log::info('Proceso de envío finalizado');

        // Opcional: guardar registro de la notificación
        // Notification::create([...]);

        return redirect()->back()->with('success', 'Notificación enviada correctamente.');
    }
}

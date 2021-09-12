<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Inbox;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('es');

        $auth_user_id = auth()->user()->id;

        $auth_user = User::find($auth_user_id);

        // Verficar el rol del usuario
        // Si es admin
        if ($auth_user->hasRole('admin')) {
            $role = 'admin';

            // Totales
            $total_patients = User::whereHas('roles', function ($q) { $q->where('name', 'patient'); })->count();
            $total_professionals = User::whereHas('roles', function ($q) { $q->where('name', 'professional'); })->count();
            $total_finished = Consultation::where('consultation.status', 'Finalizada')->count();
            $total_pending  = Consultation::where('consultation.status', 'Pendiente')->count();

            // Sesiones siguientes
            $sessions = Consultation::select('consultation.*')
                                        ->leftJoin('time_slot','time_slot.id','=' ,'consultation.time_slot_id')
                                        ->where('status', 'Pendiente')
                                        ->orderBy('time_slot.year', 'ASC')
                                        ->orderBy('time_slot.week', 'ASC')
                                        ->orderBy('time_slot.day', 'ASC')
                                        ->orderBy('time_slot.hour', 'ASC')
                                        ->paginate(10);

            // Respuesta
            return view('dashboard', compact('role', 'total_patients', 'total_professionals', 'total_finished', 'total_pending', 'sessions'));

        // Si es profesional
        } else {
            $role = 'professional';

            // Totales
            $total_finished  = Consultation::where('professional_id', $auth_user_id)->where('consultation.status', 'Finalizada')->count();
            $total_pending   = Consultation::where('professional_id', $auth_user_id)->where('consultation.status', 'Pendiente')->count();
            $total_no_assign = Consultation::where('professional_id', $auth_user_id)->where('consultation.status', 'Sin Asignar')->count();
            $total_upcoming = $total_pending + $total_no_assign;
            $total_patients = $auth_user->patients->groupBy('email')->count();
            $unread_messages = count(Inbox::where('professional_id', $auth_user_id)->where('unread', 1)->get());

            // Sesiones siguientes
            $sessions = Consultation::select('consultation.*')
                                        ->leftJoin('time_slot','time_slot.id','=' ,'consultation.time_slot_id')
                                        ->where('consultation.professional_id', $auth_user_id)
                                        ->where('status', 'Pendiente')
                                        ->orderBy('time_slot.year', 'ASC')
                                        ->orderBy('time_slot.week', 'ASC')
                                        ->orderBy('time_slot.day', 'ASC')
                                        ->orderBy('time_slot.hour', 'ASC')
                                        ->paginate(10);

            // Respuesta
            return view('dashboard', compact('role', 'total_patients', 'total_finished', 'total_upcoming', 'unread_messages', 'sessions'));
        }
    }
}
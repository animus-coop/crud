<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Inbox;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //Uncomment if uses i18n
//        Carbon::setLocale('es');

        // Respuesta
        return view('dashboard');
    }
}
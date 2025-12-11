<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD CONTROLLER - DASHBOARD ADMIN
|--------------------------------------------------------------------------
|
| Controller ini mengatur dashboard admin untuk:
| - Menampilkan statistik sistem
| - Monitoring aktivitas
| - Quick access ke fitur admin
|
*/

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     * Fitur: Central control panel untuk admin
     */
    public function index()
    {
        // Tampilkan view dashboard admin
        // Berisi statistik: total posts, users, categories, comments
        // Grafik aktivitas, recent activities, dll
        return view('admin.dashboard');
    }
}

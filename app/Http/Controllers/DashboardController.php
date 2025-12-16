<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = ['user' => $user];

        $data['totalUsers'] = User::count();
        $data['totalBus'] = Bus::count();
        // $data['newDamage'] = 0;
        // $data['inProgress'] = 0;
        // $data['totalDamage'] = 0;
        // $data['completedDamage'] = 0;

        return view('dashboard', $data);
    }
}

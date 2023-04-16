<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            if (Auth::user()->is_admin) {
                return view('admin.index');
            }
        }
        return redirect()->route('dashboard');
    }
}
//czy użytkownik jest zalogowany i czy jest on administratorem.
// Jeśli użytkownik jest zalogowany i jest administratorem, zostanie wyświetlona strona główna panelu administracyjnego.
// W przeciwnym razie użytkownik zostanie przekierowany do pulpitu nawigacyjnego.

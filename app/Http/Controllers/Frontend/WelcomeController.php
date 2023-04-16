<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


class WelcomeController extends Controller
{
    //Metoda "index" tego kontrolera pobiera kategorię o nazwie "specials" z bazy danych i przypisuje ją do zmiennej "$specials".
    // Następnie jest wywoływana funkcja "view" z argumentem "welcome" i tablicą zmiennych "compact('specials')".
    //
    //Metoda "thankyou" tego kontrolera wywołuje funkcję "view" z argumentem "thankyou".
    public function index()
    {
        $specials = Category::where('name','specials')->first();

        return view('welcome',compact('specials'));
    }
    public function thankyou()
    {
        return view('thankyou');
    }

}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    //index(): Ta metoda pobiera wszystkie kategorie z bazy danych i zwraca widok "categories.index", który jest załadowany z kategoriami.
    //
    //show(): Ta metoda pobiera określoną kategorię z bazy danych i zwraca widok "categories.show", który jest załadowany z tą kategorią.
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuStoreRequest;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //zwraca widok z listą wszystkich menu zapisanych w bazie danych
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //zwraca widok formularza tworzenia nowego menu, w którym przekazywana jest również lista wszystkich kategorii.
    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //zapisuje nowe menu w bazie danych. Przed zapisem wykonywane jest sprawdzenie poprawności danych za pomocą obiektu MenuStoreRequest.
    // Zdjęcie jest zapisywane w magazynie (Storage), a po zapisie menu powiązane jest z wybranymi kategoriami

    public function store(MenuStoreRequest $request)
    {
        $image= $request->file('image')->store('public/menus');
        $menu = Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'price' => $request->price
        ]);
        if ($request->has('categories')) {
            $menu->categories()->attach($request->categories);
        }

        return to_route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // zwraca widok formularza edycji istniejącego menu, w którym przekazywana jest również lista wszystkich kategorii oraz dane menu do edycji.
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit',compact('menu', 'categories' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //aktualizuje istniejące menu w bazie danych. Przed aktualizacją sprawdzane jest, czy wszystkie wymagane pola są wypełnione.
    // Jeśli zostało dodane nowe zdjęcie, to poprzednie jest usuwane z magazynu i zastępowane nowym. Po aktualizacji powiązania między menu a kategoriami są synchronizowane
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);
        $image = $menu->image;
        if ($request->hasFile('image')) {
            Storage::delete($menu->image);
            $image = $request->file('image')->store('public/menus');
        }

        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'price' => $request->price
        ]);

        if ($request->has('categories')) {
            $menu->categories()->sync($request->categories);
        }
        return to_route('admin.menus.index')->with('success', 'Menu updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //usuwa menu z bazy danych, a także usuwa zdjęcie z magazynu oraz usuwa powiązania między menu a kategoriami.
    public function destroy(Menu $menu)
    {
        Storage::delete($menu->image);
        $menu->categories()->detach();
        $menu->delete();
        return to_route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }
}

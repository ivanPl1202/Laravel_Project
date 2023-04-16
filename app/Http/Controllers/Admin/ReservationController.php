<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TableStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStoreRequest;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Funkcja "index":
    //Pobiera wszystkie rezerwacje z bazy danych i przekazuje je do widoku "admin.reservations.index" jako parametr "reservations".
    public function index()
    {
        $reservations =Reservation::all();
        return view('admin.reservations.index',compact('reservations') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Funkcja "create":
    //Pobiera z bazy danych wszystkie stoły o statusie "Avalaiable" i przekazuje je do widoku "admin.reservations.create" jako parametr "tables".
    public function create()
    {
        $tables = Table::where('status', TableStatus::Avalaiable)->get();
        return view('admin.reservations.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //Funkcja "store":
    //
    //Sprawdza czy liczba gości w rezerwacji nie jest większa niż maksymalna liczba gości dla wybranego stolika.
    //Sprawdza, czy wybrany stolik nie jest już zarezerwowany w dniu podanym w rezerwacji.
    //Jeżeli powyższe warunki są spełnione, to tworzy nową rezerwację na podstawie danych przesłanych w żądaniu ($request).

    public function store(ReservationStoreRequest $request)
    {
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_number > $table->guest_number) {
            return back()->with('warning', 'Please choose the table base on guests.');
        }
        $request_date = Carbon::parse($request->res_date);
        foreach ($table->reservations as $res) {
            if ($res->res_date->format('Y-m-d') == $request_date->format('Y-m-d')) {
                return back()->with('warning', 'This table is reserved for this date.');
            }
        }
        Reservation::create($request->validated());

        return to_route('admin.reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Funkcja "edit": Pobiera z bazy danych wszystkie stoły o statusie "Avalaiable".
    //Przekazuje do widoku "admin.reservations.edit" rezerwację o podanym identyfikatorze jako parametr "reservation" i wszystkie stoły jako parametr "tables"

    public function edit(Reservation $reservation)
    {
        $tables = Table::where('status', TableStatus::Avalaiable)->get();
        return view('admin.reservations.edit', compact('reservation', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Funkcja "update":
    //Analogicznie jak w funkcji "store", sprawdza czy liczba gości w rezerwacji nie jest większa niż
    // maksymalna liczba gości dla wybranego stolika oraz czy wybrany stolik nie jest już zarezerwowany w dniu podanym w rezerwacji.
    //Aktualizuje istniejącą rezerwację na podstawie danych przesłanych w żądaniu ($request).
    public function update(ReservationStoreRequest $request, Reservation $reservation)
    {
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_number > $table->guest_number) {
            return back()->with('warning', 'Please choose the table base on guests.');
        }
        $request_date = Carbon::parse($request->res_date);
        $reservations = $table->reservations()->where('id', '!=', $reservation->id)->get();
        foreach ($reservations as $res) {
            if ($res->res_date->format('Y-m-d') == $request_date->format('Y-m-d')) {
                return back()->with('warning', 'This table is reserved for this date.');
            }
        }

        $reservation->update($request->validated());
        return to_route('admin.reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Funkcja "destroy":Usuwa rezerwację o podanym identyfikatorze.

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return to_route('admin.reservations.index')->with('warning', 'Reservation deleted successfully.');
    }
}

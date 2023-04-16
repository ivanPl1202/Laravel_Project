<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TableStatus;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    //Metoda stepOne wyświetla formularz rezerwacji.
    // W formularzu należy podać dane osoby rezerwującej
    public function stepOne(Request $request)
    {
        $reservation = $request->session()->get('reservation');
        $min_date = Carbon::today();
        $max_date = Carbon::now()->addWeek();
        return view('reservations.step-one', compact('reservation', 'min_date', 'max_date'));
    }

    //Metoda storeStepOne przetwarza dane wprowadzone przez użytkownika i sprawdza, czy są one prawidłowe.
    // Następnie przechowuje te dane w sesji, aby były dostępne w kolejnych krokach procesu rezerwacji.
    public function storeStepOne(Request $request)
    {
//        dd($request->user());
        $validated = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'res_date' => ['required', 'date', new DateBetween, new TimeBetween],
            'tel_number' => ['required'],
            'guest_number' => ['required'],
        ]);
//        dd($validated);

        if (empty($request->session()->get('reservation'))) {
            $reservation = new Reservation();
            $reservation->fill($validated);
            $request->session()->put('reservation', $reservation);
        } else {
            $reservation = $request->session()->get('reservation');
            $reservation->fill($validated);
            $request->session()->put('reservation', $reservation);
        }

        return to_route('reservations.step.two');
    }

    //Metoda stepTwo wyświetla widok z dostępnymi stolikami, które pasują do liczby gości i daty rezerwacji.
    public function stepTwo(Request $request)
    {
        $reservation = $request->session()->get('reservation');
        $res_table_ids = Reservation::orderBy('res_date')->get()->filter(function ($value) use ($reservation) {
            return $value->res_date->format('Y-m-d') == $reservation->res_date->format('Y-m-d');
        })->pluck('table_id');
        $tables = Table::where('status', TableStatus::Avalaiable)
            ->where('guest_number', '>=', $reservation->guest_number)
            ->whereNotIn('id', $res_table_ids)->get();
        return view('reservations.step-two', compact('reservation', 'tables'));
    }

    //Metoda storeStepTwo zapisuje rezerwację do bazy danych i usuwa ją z sesji.
    // Po zakończeniu procesu rezerwacji użytkownik jest przekierowywany do widoku podziękowania.
    public function storeStepTwo(Request $request)
    {
        $validated = $request->validate([
            'table_id' => ['required'],
        ]);

        $reservation = $request->session()->get('reservation');
//        dd($reservation);
        $reservation->fill($validated);
        $reservation->used_id = $request->user()->id;
//        dd($reservation);
        $reservation->save();
        $request->session()->forget('reservation');

        return to_route('thankyou');
    }
}

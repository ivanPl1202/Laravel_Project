<?php

namespace App\Models;

use App\Enums\TableLocation;
use App\Enums\TableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'guest_number','status','location'];
    protected  $casts =[
        'status'=>TableStatus::class,
        'location'=>TableLocation::class
    ];
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}















//Funkcja "reservations" jest funkcją relacji "hasMany" i określa, że jeden stolik może mieć wiele rezerwacji.
// Funkcja ta zwraca wszystkie rekordy z modelu "Reservation", które są powiązane z aktualnym rekordem (obiektem) modelu "Table".

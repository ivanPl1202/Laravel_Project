<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable =[
        'first_name',
        'last_name',
        'tel_number',
        'email',
        'table_id',
        'res_date',
        'guest_number',
        'used_id',
    ];
    protected  $dates =[
        'res_date'
    ];
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}








































//W klasie zdefiniowano właściwości fillable i dates.
// Właściwość fillable zawiera listę pól, które mogą być uzupełnione przy tworzeniu nowego obiektu tej klasy.
// Właściwość dates zawiera listę pól, które powinny być traktowane jako obiekty typu daty (date).
//
//Klasa zawiera też metodę table(), która jest funkcją powiązania (relacją) względem innej klasy (Table).
// Metoda ta umożliwia pobieranie informacji o powiązanej tabeli poprzez wywołanie np. $reservation->table.

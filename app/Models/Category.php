<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name','image','description'];

  public function menus()
  {
      return $this->belongsToMany(Menu::class,'category_menu');

  }
  //Model definiuje relację "belongsToMany" z modelem Menu.
    // Oznacza to, że jedna kategoria może być powiązana z wieloma elementami menu, a jeden element menu może być powiązany z wieloma kategoriami.
    // Klasa Category posiada również właściwość "fillable", która określa, które pola mogą być uzupełniane masowo.
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    //Metoda "authorize" zwraca wartość boolowską informującą, czy użytkownik jest upoważniony do wykonania żądania.
    // W tym przypadku zawsze zwraca "true", co oznacza, że każdy użytkownik jest upoważniony do wysłania żądania dodawania kategorii.

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    //Metoda "rules" zwraca tablicę reguł walidacji dla danych wejściowych w żądaniu. Reguły te mówią, że:
    //
    //Pole "name" jest wymagane.
    //Pole "image" jest wymagane i musi być plikiem obrazu.
    //Pole "description" jest wymagane.
    //Te reguły będą sprawdzane, zanim dane zostaną zapisane w bazie danych.
    public function rules()
    {
        return [
            'name'=>['required'],
            'image'=>['required', 'image'],
            'description'=>['required'],
        ];
    }
}

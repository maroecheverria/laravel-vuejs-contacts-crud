<?php


namespace App\Domains\Contact\Validators;


use Illuminate\Http\Request;

class ContactValidator
{
    public function validate(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email'],
            'address' => ['required'],
            'phones' => ['required'],
        ],
        [
            'first_name.required' => 'El campo nombre es requerido',
            'first_name.max' => 'El campo nombre no debe superar los 255 caracteres',
            'last_name.required' => 'El campo apellido es requerido',
            'last_name.max' => 'El campo apellido no debe superar los 255 caracteres',
            'email.required' => 'El campo email es requerido',
            'email.max' => 'El campo email no debe superar los 255 caracteres',
            'email.email' => 'El campo email tiene un formato inválido',
            'address.required' => 'El campo dirección es requerida',
            'phones.required' => 'El campo teléfonos es requerido',
        ]);
    }
}

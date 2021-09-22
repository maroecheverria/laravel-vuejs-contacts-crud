<?php


namespace App\Domains\Contact\Helpers;


use Illuminate\Http\Request;

class ContactPhoneRequestHelper
{
    public function getPhonesFromRequest(Request $request): array
    {
        return array_map(function ($phoneNumber) {
            return ['number' => trim($phoneNumber)];
        }, explode(',', $request->get('phones')));
    }
}

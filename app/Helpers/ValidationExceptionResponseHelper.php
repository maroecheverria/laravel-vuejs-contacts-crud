<?php


namespace App\Helpers;



class ValidationExceptionResponseHelper
{
    public function getErrorMessage(\Throwable $exception): array
    {
        return [
            'errorMessage' => array_merge(...array_values($exception->errors()))
        ];
    }

}

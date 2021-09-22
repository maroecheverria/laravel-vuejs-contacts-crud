<?php


namespace App\Helpers;



class JsonResponseHelper
{
    public function getSuccessfulResponse($data = [], $message = ''): array
    {
        $response = [
            'status' => 'OK',
            'data' => $data
        ];

        if ($message)
            $response['message'] = $message;

        return $response;
    }

    public function getErrorResponse($errorMessage): array
    {
        return [
            'status' => 'error',
            'errorMessage' => $errorMessage
        ];
    }

}

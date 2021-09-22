<?php

namespace App\Domains\Contact\Controllers;

use App\Domains\Contact\Models\Contact;
use App\Domains\Contact\Helpers\ContactPhoneRequestHelper;
use App\Domains\Contact\Validators\ContactValidator;
use App\Helpers\JsonResponseHelper;
use App\Helpers\ValidationExceptionResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{

    private ContactPhoneRequestHelper $contactPhoneRequestHelper;
    private ContactValidator $contactValidator;
    private ValidationExceptionResponseHelper $validationExceptionResponseHelper;
    private JsonResponseHelper $jsonResponseHelper;

    public function __construct(
        ContactPhoneRequestHelper $contactPhoneRequestHelper,
        ContactValidator $contactValidator,
        JsonResponseHelper $jsonResponseHelper,
        ValidationExceptionResponseHelper $validationExceptionResponseHelper
    )
    {
        $this->contactPhoneRequestHelper = $contactPhoneRequestHelper;
        $this->contactValidator = $contactValidator;
        $this->jsonResponseHelper = $jsonResponseHelper;
        $this->validationExceptionResponseHelper = $validationExceptionResponseHelper;
    }

    /**
     * Display a list of the resource
     */
    public function index(): JsonResponse
    {
        try {
            $contacts = Contact::orderBy('id', 'desc')->get();

            $data = $contacts->map(function (Contact $contact) {
                return $contact;
            });

            return response()->json(
                $this->jsonResponseHelper->getSuccessfulResponse($data)
            );
        } catch (\Exception $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse('Error al obtener la lista de contactos'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display a resource
     */
    public function show($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);
            if (!$contact)
                return response()->json(
                    $this->jsonResponseHelper->getErrorResponse('El contacto no existe'),
                    Response::HTTP_NOT_FOUND
                );

            return response()->json(
                $this->jsonResponseHelper->getSuccessfulResponse($contact)
            );
        } catch (\Exception $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse('Error obtener el contacto'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->contactValidator->validate($request);

            $existingContact = Contact::where('email' , '=', $request->get('email'))->first();

            if ($existingContact)
                return response()->json(
                    $this->jsonResponseHelper->getErrorResponse('Ya existe un contacto con el mismo email'),
                    Response::HTTP_BAD_REQUEST
                );

            $contact = new Contact();
            $contact->create($request->only(['first_name', 'last_name', 'email', 'address']))
                ->phones()->createMany(
                    $this->contactPhoneRequestHelper->getPhonesFromRequest($request)
                );

            return response()->json(
                $this->jsonResponseHelper->getSuccessfulResponse(
                    [],
                    'El contacto ha sido creado exitosamente'
                ),
                Response::HTTP_CREATED
            );
        } catch (ValidationException $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse(
                    $this->validationExceptionResponseHelper->getErrorMessage($e)
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Exception $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse('Error al crear el contacto'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $this->contactValidator->validate($request);
            $contact = Contact::find($id);

            if (!$contact)
                return response()->json(
                    $this->jsonResponseHelper->getErrorResponse('El contacto a actualizar no existe'),
                    Response::HTTP_NOT_FOUND
                );

            $contact->update($request->only(['first_name', 'last_name', 'email', 'address']));
            $contact->phones()->delete();
            $contact->phones()->createMany(
                    $this->contactPhoneRequestHelper->getPhonesFromRequest($request)
                );

            return response()->json(
                $this->jsonResponseHelper->getSuccessfulResponse(
                    $contact,
                    'El contacto ha sido actualizado exitosamente'
                )
            );
        } catch (ValidationException $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse(
                    $this->validationExceptionResponseHelper->getErrorMessage($e)
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Exception $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse('Error al actualizar el contacto'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id): JsonResponse
    {
        try {
            $contact = Contact::find($id);
            if (!$contact)
                return response()->json(
                    $this->jsonResponseHelper->getErrorResponse('El contacto a eliminar no existe'),
                    Response::HTTP_NOT_FOUND
                );

            $contact->delete();
            return response()->json(
                $this->jsonResponseHelper->getSuccessfulResponse(
                    [],
                    'El contacto ha sido eliminado exitosamente'),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return response()->json(
                $this->jsonResponseHelper->getErrorResponse('Error al eliminar el contacto'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

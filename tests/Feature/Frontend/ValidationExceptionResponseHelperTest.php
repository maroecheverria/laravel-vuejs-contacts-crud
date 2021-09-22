<?php
namespace Tests\Feature\Frontend;

use App\Helpers\ValidationExceptionResponseHelper;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Validation\ValidationException;
use Tests\CreatesApplication;

/**
 * Class ValidationExceptionResponseHelperTest.
 */
class ValidationExceptionResponseHelperTest extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var ValidationExceptionResponseHelper
     */
    private $validationExceptionResponseHelper;

    public function setUp(): void {

        parent::setUp();

        $this->validationExceptionResponseHelper = $this->app->make('App\Helpers\ValidationExceptionResponseHelper');
    }

    /** @test */
    public function it_should_return_a_valid_error_message()
    {
        $validationException = ValidationException::withMessages([
            'first_name' => ['El campo nombre es requerido'],
            'last_name' => ['El campo apellido es requerido'],
        ]);

        $errorMessage = $this->validationExceptionResponseHelper->getErrorMessage($validationException);

        $expected = [
            'errorMessage' => [
                'El campo nombre es requerido',
                'El campo apellido es requerido'
            ]
        ];

        $this->assertEquals($expected, $errorMessage);
    }
}

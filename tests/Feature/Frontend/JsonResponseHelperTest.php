<?php
namespace Tests\Feature\Frontend;

use App\Helpers\JsonResponseHelper;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

/**
 * Class JsonResponseHelperTest.
 */
class JsonResponseHelperTest extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var JsonResponseHelper
     */
    private $jsonResponseHelper;

    public function setUp(): void {

        parent::setUp();

        $this->jsonResponseHelper = $this->app->make('App\Helpers\JsonResponseHelper');
    }

    /** @test */
    public function it_should_return_a_valid_successful_response()
    {
        $data = [
            'first_name' => 'Mariano',
            'last_name' => 'Echeverría',
            'email' => 'maro.echeverria@gmail.com',
            'phones' => '+541151758731, +541155628725',
            'address' => 'Ayacucho 6789 5B'
        ];

        $message = 'El contacto ha sido actualizado exitosamente';

        $response = $this->jsonResponseHelper->getSuccessfulResponse($data, $message);

        $expected = [
            'status' => 'OK',
            'data' => [
                'first_name' => 'Mariano',
                'last_name' => 'Echeverría',
                'email' => 'maro.echeverria@gmail.com',
                'phones' => '+541151758731, +541155628725',
                'address' => 'Ayacucho 6789 5B'
            ],
            'message' => 'El contacto ha sido actualizado exitosamente'
        ];

        $this->assertEquals($expected, $response);
    }

    /** @test */
    public function it_should_return_a_valid_error_response()
    {
        $message = 'El contacto a eliminar no existe';

        $expected = [
            'status' => 'error',
            'errorMessage' => 'El contacto a eliminar no existe'
        ];

        $response = $this->jsonResponseHelper->getErrorResponse($message);

        $this->assertEquals($expected, $response);
    }

}

<?php
namespace Tests\Feature\Frontend\Contact;

use App\Domains\Contact\Helpers\ContactPhoneRequestHelper;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class ContactPhoneRequestHelperTest.
 */
class ContactPhoneRequestHelperTest extends TestCase
{
    use CreatesApplication;

    /**
     * @var ContactPhoneRequestHelper
     */
    private $contactPhoneRequestHelper;

    public function setUp(): void {

        parent::setUp();

        $this->contactPhoneRequestHelper = $this->app
            ->make('App\Domains\Contact\Helpers\ContactPhoneRequestHelper');
    }

    /** @test */
    public function it_should_return_a_valid_result()
    {
        $request = new Request([], [], ['phones' => '+541151758731, +541155628725']);

        $result = $this->contactPhoneRequestHelper->getPhonesFromRequest($request);

        $expected = [
            ['number' => '+541151758731'],
            ['number' => '+541155628725']
        ];

        $this->assertEquals($expected, $result);
    }
}

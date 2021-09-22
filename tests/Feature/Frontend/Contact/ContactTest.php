<?php
namespace Tests\Feature\Frontend\Contact;

use Tests\TestCase;

/**
 * Class ContactTest.
 */
class ContactTest extends TestCase
{
    /** @test */
    public function the_home_route_exists()
    {
        $this->get('/')->assertOk();
    }

    /** @test */
    public function the_contacts_route_exists()
    {
        $this->get('/contacts')->assertOk();
    }

    /** @test */
    public function a_contact_can_be_shown_with_valid_response()
    {
        $response = $this->get('/contacts/1');
        $response->assertOk();

        $expected = '{"status":"OK","data":{"id":1,"first_name":"Mariano","last_name":"Echeverria","email":"maro.echeverr\u00eda@gmail.com","phones":"+541156456721","address":"Ayacucho 1678 4D"}}';
        $this->assertEquals($expected, $response->decodeResponseJson()->json);
    }

    /** @test */
    public function a_contact_can_be_created_with_valid_response()
    {
        $response = $this->post('/contacts',
            [
                "first_name" => "Jose",
                "last_name" => "Perez",
                "email" => "jose.p@gmail.com",
                "phones" => "+541151778731, +541165628763",
                "address" => "Cabildo 1456 1B"
            ]
        );

        $response->assertCreated();

        $expected = '{"status":"OK","data":[],"message":"El contacto ha sido creado exitosamente"}';
        $this->assertEquals($expected, $response->decodeResponseJson()->json);
    }

    /** @test */
    public function a_contact_can_be_updated_with_valid_response()
    {
        $response = $this->put('/contacts/1',
            [
                "first_name" => "Mariano",
                "last_name" => "Echeverría",
                "email" => "maro.echeverria@gmail.com",
                "phones" => "+541151758731, +541155628725",
                "address" => "Ayacucho 6789 5B"
            ]
        );

        $response->assertOk();

        $expected = '{"status":"OK","data":{"id":1,"first_name":"Mariano","last_name":"Echeverr\u00eda","email":"maro.echeverria@gmail.com","phones":"+541151758731, +541155628725","address":"Ayacucho 6789 5B"},"message":"El contacto ha sido actualizado exitosamente"}';
        $this->assertEquals($expected, $response->decodeResponseJson()->json);
    }

    /** @test */
    public function a_contact_can_be_deleted_with_valid_response()
    {
        $response = $this->delete('/contacts/1');
        $response->assertOk();

        $expected = '{"status":"OK","data":[],"message":"El contacto ha sido eliminado exitosamente"}';
        $this->assertEquals($expected, $response->decodeResponseJson()->json);
    }
}

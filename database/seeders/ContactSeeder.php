<?php

namespace Database\Seeders;

use App\Domains\Contact\Models\Contact;
use App\Domains\Contact\Models\ContactPhone;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;

/**
 * Class ContactSeeder.
 */
class ContactSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->truncate('contacts');

        if (app()->environment(['local', 'testing'])) {

            $phone = ContactPhone::factory()->makeOne([
                'number' => '+541156456721'
            ]);

            Contact::factory()->createOne([
                'first_name' => 'Mariano',
                'last_name' => 'Echeverria',
                'email' => 'maro.echeverría@gmail.com',
                'address' => 'Ayacucho 1678 4D',
            ])->phones()->save($phone);

            Contact::factory(10)->create()->each(function ($contact) {
                $phones = ContactPhone::factory(2)->make();
                $contact->phones()->saveMany($phones);
            });
        }

        $this->enableForeignKeys();
    }
}

<?php

namespace Tests\Unit;

use Faker\Factory as Faker;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    /**
     * Testing register route.
     *
     * @return void
     */
    public function test_register()
    {
        parent::setUp();

        $client = new Client();

        $faker = Faker::create();
        $password = $faker->password;

        $data = [
            'form_params' => [
                'name'              => $faker->firstName,
                'email'             => $faker->email,
                'password'          => $password,
                'confirm_password'  => $password
            ]
        ];
    
        $request = $client->post(Config::get('app.url').'/api/register', $data);
        $this->assertEquals(200, $request->getStatusCode());
    }
}

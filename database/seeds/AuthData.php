<?php

use Illuminate\Database\Seeder;

class AuthData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert(
                array('id' => 'kafLV1CX1OU7MIG3V3G6kd6sWKRGpE88',
                    'secret' => 'EIWFCco34wqTcHgqp3ehU9rjBcJSwn4J',
                    'name' => 'APP')
        );
        DB::table('oauth_scopes')->insert(
                array('id'=>'6C7aQNqxfdY4mM2mzivSBZM1OZ7fXT9k','description' => 'basic')
        );
        DB::table('oauth_client_scopes')->insert(
                array('client_id' =>'kafLV1CX1OU7MIG3V3G6kd6sWKRGpE88','scope_id'=>'6C7aQNqxfdY4mM2mzivSBZM1OZ7fXT9k')
        );
    }
}

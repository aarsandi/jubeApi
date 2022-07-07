<?php

use App\Models\User;
// use InteractsWithExceptionHandling;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;

class PeopleTest extends TestCase
{

    
    public function sendEmailTest()
    {

        Mail::raw('Raw string email', function($msg) { $msg->to(['tamamirzk@gmail.com']); $msg->from(['hehe@gmail.com']); });

        // Mail::send('emails.test', ['user' => 'hi'], function($m) {
        //     $m->from('tuanphpvn@gmail.com', 'You application');
        //     $m->subject("Test email");

        //     $m->to('tamamirzk@gmail.com','Tuanphpvn');
        // });

    }

    // public function testGetPeople()
    // {

    //     $this->get('/peoples')
    //          ->seeStatusCode(200);

    // }

    // public function testGetByIdPeople()
    // {

    //     $this->get('/peoples/1')
    //         ->seeStatusCode(200);

    // }




    // public function testCreatePeople()
    // {

    //     $this->post('/peoples/create', [
    //         'name' => 'tamamirzk',
    //         'email' => 'tamamirzk@gmail.com',
    //         'no_telfon' => '1234'
    //         ])
    //          ->seeJson([
    //             'status' => 'success',
    //          ]);

    // }

    // public function testUpdatePeople()
    // {
    //     $this->patch('/peoples/update/21', [
    //         'name' => 'oktaHC',
    //         'email' => 'oktaHC@gmail.com',
    //         'no_telfon' => '123456'
    //         ])
    //          ->seeJson([
    //             'status' => 'success',
    //          ]);

    // }
    
    // public function testDeletePeople()
    // {
    //     $this->delete('/peoples/delete/24')
    //          ->seeJson([
    //             'status' => 'success',
    //          ]);

    // }

    // public function testLoginPeople()
    // {

    //     $this->post('/users/login', [
    //         'email' => 'Limo@gmail.com',
    //         'password' => '1234s'
    //         ])
    //          ->seeJson([
    //             'status' => 'success',
    //          ]);

    // }
}

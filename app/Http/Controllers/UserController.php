<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Roocket\PocketCore\Controller;

class UserController extends Controller {

    public function index()
    {
        
        $users = (new User())->create([

            "name" => "moein",

            "email"=> "mpoein@gmail.com",

            "password"=> md5("moein"),

        ]);

    }

   


}


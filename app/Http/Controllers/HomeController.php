<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function welcome($hi, $name)
    {
        return view('home', [
            'name' => $name,
            'hi' => $hi
        ]);
        /*
        [
            'name' => $name,
            'hi' => $hi,
        ]
        */
    }

    public function hi($fname, $lname = '')
    {
        return 'Hi, ' . $fname . ' ' . $lname;
    }
}

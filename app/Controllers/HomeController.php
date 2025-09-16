<?php

namespace App\Controllers;

class HomeController
{
    public function index()
    {
        return view('index', [
            'name' => 'June',
            'items' => ['Docs', 'Blog', 'Tutorials']
        ]);
    }

    public function about()
    {
        return view('about');
    }
}

<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['welcome']);
    }

    public function welcome()
    {
        return view('welcome');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getTokens()
    {
        return view('home.personal-tokens');
    }
    public function getAutorizedClients()
    {
        return view('home.authorized-clients');
    }
    public function getClients()
    {
        return view('home.personal-clients');
    }
}

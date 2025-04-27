<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Students;
use App\Models\Classess as ClassDivision;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'users' => User::count(),
            'students' => Students::count(),
            'classes' => ClassDivision::count(),
        ];

        return view('dashboard', compact('data'));
    }
}

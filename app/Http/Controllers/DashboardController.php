<?php

namespace App\Http\Controllers;

use App\Contracts\CompanyUserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected CompanyUserRepository $users;

    public function __construct(CompanyUserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->users->findAll();
        $users->orderBy('name');

        return view('dashboard.index', ['users' => $users->get()]);
    }
}

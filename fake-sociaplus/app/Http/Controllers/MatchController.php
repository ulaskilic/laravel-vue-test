<?php

namespace App\Http\Controllers;

use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(MatchService $service)
    {
        dd($service);
        return [];
    }
}

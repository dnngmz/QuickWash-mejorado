<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'student') {
            abort(403);
        }
        $machines = Machine::all();
        return view('machines.index', compact('machines'));
    }
}

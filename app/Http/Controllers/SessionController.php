<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function verrouiller()
    {
        // logique de verrouillage

        return back()->with(
            'success',
            'Session académique verrouillée.'
        );
    }
}

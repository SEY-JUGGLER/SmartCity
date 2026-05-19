<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use App\Models\User;

class RapportController extends Controller
{
    public function print(Rapport $rapport)
    {
        // Only ADMINs can print reports
        abort_unless(auth()->user()?->role === 'ADMIN', 403);

        return view('rapports.print', compact('rapport'));
    }
}

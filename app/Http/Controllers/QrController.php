<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // This URL is what other people will scan:
        $url  = route('transfers.send', $user);

        //Documentation
        // We are passing the entire User model to route() and Laravel will pluck its “route key” (the username):
        // Under the hood it sees your User model, notices the route wants a recipient, and calls $user->getRouteKey() (your username) automatically.
        // Cool, isn't it? :)

        return view('profile.qr', compact('url'));
    }
}

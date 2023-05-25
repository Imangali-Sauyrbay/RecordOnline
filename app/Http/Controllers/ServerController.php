<?php

namespace App\Http\Controllers;

use App\Events\ReloadServerEvent;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function reload()
    {

        event(ReloadServerEvent::class);

        return redirect()->route('voyager.dashboard')->with([
            'alerts' => [
                [
                    'type'    => 'success',
                    'message' => __('Reloaded'),
                ]
            ]
        ]);
    }
}

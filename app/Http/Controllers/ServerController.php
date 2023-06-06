<?php

namespace App\Http\Controllers;

use App\Events\ReloadServerEvent;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function reload()
    {

        if(!(array_key_exists('LARAVEL_OCTANE', $_SERVER) && $_SERVER['LARAVEL_OCTANE'] === '1'))
            return redirect()
                ->route('voyager.dashboard')
                ->with($this->notify(__('Server isn\'t octane server! Reload not required!'), 'info'));

        event(ReloadServerEvent::class);

        return redirect()
            ->route('voyager.dashboard')
            ->with($this->notify(__('Reloaded'), 'success'));
    }

    protected function notify($msg, $type) {
        return [
            'alerts' => [
                [
                    'type'    => $type,
                    'message' => $msg
                ]
            ]
        ];
    }
}

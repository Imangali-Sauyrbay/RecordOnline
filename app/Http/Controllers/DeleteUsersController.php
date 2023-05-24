<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class DeleteUsersController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        $groups = User::whereNotNull('group')
        ->get()
        ->pluck('group');

        $groupsTrashed = User::onlyTrashed()
        ->whereNotNull('group')
        ->get()
        ->pluck('group');

        $years = $groups->map(function ($group) {
            if (isset($group) && mb_ereg("-(\d+)-", $group, $matches)) {
                return (int) $matches[1];
            }

            return null;
        })->unique();

        $yearsTrashed = $groupsTrashed->map(function ($group) {
            if (isset($group) && mb_ereg("-(\d+)-", $group, $matches)) {
                return (int) $matches[1];
            }

            return null;
        })->unique();

        return view('delete-users', [
            'groups' => $groups,
            'years' => $years,

            'yearsTrashed' => $yearsTrashed,
            'groupsTrashed' => $groupsTrashed
        ]);
    }

    public function delete(Request $request) {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        User::where('group', 'like', $this->getValidGroup($request))->delete();

        return back()->with($this->alert(__('Users was deleted!'), 'success'));
    }

    public function restore(Request $request) {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        User::onlyTrashed()
        ->where('group', 'like', $this->getValidGroup($request))
        ->restore();

        return back()->with($this->alert(__('Users was restored!'), 'success'));
    }

    protected function getValidGroup($request) {
        $data = $request->validate([
            'group' => ['required']
        ]);

        $group = $data['group'];

        if(is_numeric($group)) {
            $group = '%-'. $group .'-%';
        }

        return $group;
    }

    protected function alert($message, $type)
    {
        return [
            'alerts' => [
                [
                    'type'    => $type,
                    'message' => $message,
                ]
            ]
        ];
    }

    protected function alertSuccess($message)
    {
        return $this->alert($message, 'success');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\SheduledDelete;
use Carbon\Carbon;
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

        return back()->with($this->alertSuccess(__('Users was trashed!')));
    }

    public function restore(Request $request) {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        User::onlyTrashed()
        ->where('group', 'like', $this->getValidGroup($request))
        ->restore();

        return back()->with($this->alertSuccess(__('Users was restored!')));
    }

    public function deleteRecords(Request $request) {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        $lessThan = $this->getTime(trim($request->input('time', 'week')));

        $deletedCount = SheduledDelete::deleteRecords($lessThan);

        return back()->with($this->alertSuccess(trans_choice('admin.deleted', $deletedCount)));
    }

    public function deleteUsers(Request $request) {
        /** @var User */
        $user = auth()->user();
        if(!$user->isAdmin()) return redirect(RouteServiceProvider::HOME);

        $lessThan = $this->getTime(trim($request->input('time', 'week')));

        $deletedCount = SheduledDelete::deleteUsers($lessThan);

        return back()->with($this->alertSuccess(trans_choice('admin.deleted', $deletedCount)));
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

    protected function getTime(string $time)
    {
        if($time === 'year')
            return Carbon::now()->subYear();

        if($time === 'six_month')
            return Carbon::now()->subMonths(6);

        if($time === 'three_month')
            return Carbon::now()->subMonths(3);

        if($time === 'month')
            return Carbon::now()->subMonth();

        if($time === 'week')
            return Carbon::now()->subWeek();

        if($time === 'day')
            return Carbon::now()->subDay();

        if($time === 'hour')
            return Carbon::now()->subHour();

        if($time === 'minute')
            return Carbon::now()->subMinute();

        return Carbon::now()->subWeek();
    }
}

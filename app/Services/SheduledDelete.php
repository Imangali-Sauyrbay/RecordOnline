<?php

namespace App\Services;

use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
use Storage;

class SheduledDelete {
    public static function deleteRecords(Carbon $ltTime)
    {
        return Record::where('recorded_to', '<', $ltTime)->delete();
    }

    public static function deleteUsers(Carbon $ltTime)
    {
        $users = User::onlyTrashed()
        ->where('deleted_at', '<', $ltTime)
        ->get();

        foreach ($users as $user) {
            if(Storage::disk('public')->fileExists($user->avatar)
            && !mb_ereg_match('.*default.png$', $user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        return User::onlyTrashed()
            ->where('deleted_at', '<', $ltTime)
            ->forceDelete();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

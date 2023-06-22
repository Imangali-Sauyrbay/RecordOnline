<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRecord
 */
class Record extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'recorded_to', 'duration', 'user_id', 'record_status_id', 'lits'];
    protected $casts = [
        'recorded_to' => 'datetime'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recordStatus()
    {
        return $this->belongsTo(RecordStatus::class);
    }
}

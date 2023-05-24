<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

/**
 * @mixin IdeHelperRecordStatus
 */
class RecordStatus extends Model
{
    use HasFactory, Translatable;

    protected $translatable = ['title', 'subject', 'content'];

    protected $fillable = [
        'title',
        'subject',
        'content',
        'html',
        'isDefault',
        'badge'
    ];

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    // CONSTANTS

    public const PRIMARY = 'text-bg-primary';
    public const SECONDARY = 'text-bg-secondary';
    public const SUCCESS = 'text-bg-success';
    public const DANGER = 'text-bg-danger';
    public const WARNING = 'text-bg-warning';
    public const INFO = 'text-bg-info';
    public const LIGHT = 'text-bg-light';
    public const DARK = 'text-bg-dark';
}

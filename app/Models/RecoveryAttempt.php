<?php

namespace App\Models;

/**
 * @property int $user_id
 * @property string $time
 * @property string $key
 * @property User $user
 */
class RecoveryAttempt extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'time', 'key'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

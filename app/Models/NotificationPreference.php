<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id', 'notification_systeme', 'notification_email', 'notification_push',
    ];

    protected $casts = [
        'notification_systeme' => 'boolean',
        'notification_email'   => 'boolean',
        'notification_push'    => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
}

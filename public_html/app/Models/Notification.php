<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'org_id',
        'created_by',
        'status',
        'target_type',
        'recipient_id',
        'recipient_name',
        'send_app',
        'send_email',
        'send_sms',
        'app_status',
        'email_status',
        'sms_status',
        'app_sent_at',
        'email_sent_at',
        'sms_sent_at',
        'app_read_at',
        'app_error',
        'email_error',
        'sms_error',
        'scheduled_at',
    ];

    protected $casts = [
        'send_app' => 'boolean',
        'send_email' => 'boolean',
        'send_sms' => 'boolean',
        'app_sent_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'sms_sent_at' => 'datetime',
        'app_read_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];
}

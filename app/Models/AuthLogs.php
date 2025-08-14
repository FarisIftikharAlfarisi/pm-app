<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'email',
        'action_time',
        'action_type',
        'ip_address',
        'device',
        'browser',
        'os',
        'status',
        'error_message',
        'time_spent',
        'session_id'
    ];

    protected $casts = [
        'action_time' => 'datetime',
    ];

    // Relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk mendapatkan detail browser
    public static function getBrowser($userAgent)
    {
        $browser = "Unknown Browser";

        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Netscape/i', $userAgent)) {
            $browser = 'Netscape';
        }

        return $browser;
    }

    // Helper untuk mendapatkan OS
    public static function getOS($userAgent)
    {
        $os = "Unknown OS";

        if (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'Mac OS X';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
        }

        return $os;
    }

    // Helper untuk mendapatkan device
    public static function getDevice($userAgent)
    {
        if (preg_match('/(mobile|iphone|ipad|android)/i', $userAgent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }
}

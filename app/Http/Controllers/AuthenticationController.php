<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuthLogs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login()
    {
        $title = 'Login';
        return view('Auth.login', compact('title'));
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Catat percobaan login
        $user = User::where('email', $request->email)->first();
        $userAgent = $request->header('User-Agent');

        $loginAttemptData = [
            'user_id' => $user ? $user->id : null,
            'username' => $user ? $user->nama : 'Unknown',
            'email' => $request->email,
            'action_time' => now(),
            'action_type' => 'Login Attempt',
            'ip_address' => $request->ip(),
            'device' => $this->getDevice($userAgent),
            'browser' => $this->getBrowser($userAgent),
            'os' => $this->getOS($userAgent),
            'status' => 0,
            'error_message' => 'Authentication in progress'
        ];

        $loginAttempt = AuthLogs::create($loginAttemptData);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $sessionId = Str::uuid();

            $request->session()->put('auth_session_id', $sessionId);

            // Update login attempt to success
            $loginAttempt->update([
                'action_type' => 'Login Success',
                'status' => 1,
                'error_message' => null,
                'session_id' => $sessionId
            ]);

            return $this->redirectByRole($user);
        }

        // Update login attempt to failed
        $errorMessage = $user ? 'Invalid password' : 'User not found';
        $loginAttempt->update([
            'action_type' => 'Login Failed',
            'status' => 0,
            'error_message' => $errorMessage
        ]);

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->session()->get('auth_session_id');
        $userAgent = $request->header('User-Agent');

        if ($user) {
            AuthLogs::create([
                'user_id' => $user->id,
                'username' => $user->nama,
                'email' => $user->email,
                'action_time' => now(),
                'action_type' => 'Logout',
                'ip_address' => $request->ip(),
                'device' => $this->getDevice($userAgent),
                'browser' => $this->getBrowser($userAgent),
                'os' => $this->getOS($userAgent),
                'status' => 1,
                'session_id' => $sessionId
            ]);

            $this->calculateSessionDuration($sessionId);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectByRole($user)
    {
        switch ($user->role) {
            case 'PROJECT_LEADER':
                return redirect()->route('dashboard.project_leader');
            case 'ACCOUNTING':
                return redirect()->route('dashboard.accounting');
            case 'ADMIN_SITE':
                return redirect()->route('dashboard.admin_site');
            default:
                return redirect()->route('absensi.index');
        }
    }

    protected function calculateSessionDuration($sessionId)
    {
        if (!$sessionId) return;

        $loginRecord = AuthLogs::where('session_id', $sessionId)
                            ->where('action_type', 'Login Success')
                            ->where('status', 1)
                            ->first();

        if ($loginRecord) {
            $timeSpent = now()->diffInMinutes($loginRecord->action_time);
            $loginRecord->update(['time_spent' => $timeSpent]);
        }
    }

    // Device detection methods
    protected function getDevice($userAgent)
    {
        if (preg_match('/(mobile|iphone|ipad|android)/i', $userAgent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    protected function getBrowser($userAgent)
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
        } elseif (preg_match('/Edge/i', $userAgent)) {
            $browser = 'Microsoft Edge';
        }

        return $browser;
    }

    protected function getOS($userAgent)
    {
        $os = "Unknown OS";

        if (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'Mac OS X';
        } elseif (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $os = 'iOS';
        }

        return $os;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HakAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Jika user tidak login atau tidak memiliki role yang sesuai
        if (!$user || !in_array($user->role, $roles)) {
            return $this->unauthorizedResponse();
        }

        // Jika user adalah Admin Site atau Karyawan Site, cek site-nya
        if (in_array($user->role, ['admin_site', 'karyawan_site'])) {
            $userSite = $user->userDetail->Kode_Site ?? null; // Ambil site dari user_details
            $requestSite = $request->route('site_id'); // Ambil site dari route parameter

            if (!$userSite || !$requestSite || $userSite != $requestSite) {
                return $this->unauthorizedResponse();
            }
        }

        return $next($request);
    }

    private function unauthorizedResponse()
    {
        if (route('403')) {
            return redirect()->route('403')->with(json_encode(['message' => 'Anda tidak memiliki hak akses.', 'status' => 403]));
        } else {
            return response()->json(['message' => 'Anda tidak memiliki hak akses.', 'status' => 403], 403);
        }
    }
}

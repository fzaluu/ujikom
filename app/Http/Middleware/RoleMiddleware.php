<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Silahkan login terlebih dahulu.']);
        }

        // Ambil nama role dari relasi user ke role dengan aman, ubah ke lowercase
        $userRole = strtolower(optional($request->user()->role)->name);
        $roleId = $request->user()->role_id;

        // Ubah semua parameter role yang dikirim di web.php menjadi lowercase untuk pencocokan
        $roles = array_map('strtolower', $roles);

        // Izinkan jika nama rolenya cocok ATAU role_id nya cocok (misal admin = 1, kasir = 2)
        $isAllowed = in_array($userRole, $roles) || 
                     (in_array('admin', $roles) && $roleId == 1) || 
                     (in_array('kasir', $roles) && $roleId == 2);

        if (!$isAllowed) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
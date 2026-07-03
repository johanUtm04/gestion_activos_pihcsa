<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        $sucursalesPermitidas = array_keys(config('sucursales.disponibles'));

        $sucursal = $user->sucursal ?: config('sucursales.default');

        if (! in_array($sucursal, $sucursalesPermitidas, true)) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your user does not have a valid branch assigned.',
            ]);
        }

        $request->session()->put('sucursal_activa', $sucursal);

        $this->applyBranchConnection($sucursal);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('sucursal_activa');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function applyBranchConnection(string $sucursal): void
    {
        config(['database.default' => $sucursal]);

        DB::setDefaultConnection($sucursal);
        DB::purge($sucursal);
        DB::reconnect($sucursal);
    }
}
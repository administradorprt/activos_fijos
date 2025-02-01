<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Foundation\Validation\ValidatesRequests;


class AuthenticatedSessionController extends Controller
{
    use ValidatesRequests;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /* public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    } */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'n_empleado' => 'required|string',
            'password' => 'required|string'
        ]);

        $credenciales = [
            'n_empleado' => $credentials['n_empleado'],
            'password' => $credentials['password']
        ];
        //dd($credenciales,Auth::attempt($credenciales));
        if (Auth::attempt($credenciales)) {
            $user = $request->user();

            /* Alert::html(
                '<b> Bienvenido <u class="text-primary">' . Auth::user()->empleado->nombres . '</u> </b>',
                " Al <b class='text-danger'> Sistema de Activos fijos</b>",
                'success'
            ); */

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Credenciales inválidas.']);
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

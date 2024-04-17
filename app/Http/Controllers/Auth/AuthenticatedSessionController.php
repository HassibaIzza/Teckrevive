<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * To determine which url to go after logging in.
     */
    private function getRightRedirectRoute(): string{
        $role = Auth::user()->role;
        switch ($role) {
            case 'admin':
                $url = '/admin/profile';
                break;
            case 'vendor':
                $url = '/Fabricant/profile';
                break; // Ajout du break ici
            case 'reparateur':
                $url = 'reparateur/profile';
                break; // Ajout du break ici
            case 'client' :
                $url = 'client/profile';
                break; 
            default:
                $url = '/profile';
                break; // Ajout du break ici
        }
        return $url;
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->getRightRedirectRoute());
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

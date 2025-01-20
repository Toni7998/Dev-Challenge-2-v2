<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback()
    {
        try {
            $twitterUser = Socialite::driver('twitter')->user();

            // Verifica si el usuario ya existe en la base de datos
            $authUser = User::where('twitter_id', $twitterUser->getId())->first();

            // Si el usuario ya existe, simplemente inicias sesión
            if ($authUser) {
                Auth::login($authUser);
                return redirect()->intended('dashboard'); // Redirige a donde necesites
            } else {
                // Registra un nuevo usuario si no existe
                $authUser = User::create([
                    'name' => $twitterUser->getName(),
                    'email' => $twitterUser->getEmail() ?: $twitterUser->getId() . '@twitter.com',
                    'twitter_id' => $twitterUser->getId(),
                    'password' => bcrypt('default_password'), // Cambia según tu lógica
                ]);

                Auth::login($authUser);
                return redirect()->intended('dashboard'); // Redirige a donde necesites
            }
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Manejo del caso cuando el usuario cancela la autorización
            return redirect('/login')->with('error', 'Autorización cancelada. Por favor, inicia sesión o regístrate.');
        } catch (\Exception $e) {
            // Maneja cualquier otro error durante el proceso de autenticación
            return redirect('/login')->with('error', 'Error al iniciar sesión con Twitter: ' . $e->getMessage());
        }
    }

    // Nuevo método para confirmar el inicio de sesión
    public function confirmLogin($userId)
    {
        $authUser = User::findOrFail($userId);
        Auth::login($authUser);
        return redirect()->intended('dashboard'); // Redirige a donde necesites
    }
}


<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|---------------------------------------------------------------------------
| Web Routes
|---------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider y están 
| dentro del grupo de middleware "web".
|
*/

// Ruta principal de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación de Google
Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate([
        'email' => $user_google->email,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
    ]);

    Auth::login($user, true);

    return redirect('/dashboard');
});

// Ruta del dashboard (usando la Opción 1, pasando el usuario directamente desde la ruta)
Route::get('/dashboard', function () {
    $user = auth()->user();  // Obtén el usuario autenticado
    $recentActivities = []; // Aquí deberías agregar la lógica para obtener las actividades recientes
    return view('dashboard', compact('user', 'recentActivities'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil, protegidas por middleware de autenticación
Route::middleware('auth')->group(function () {
    // Ruta para mostrar el perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');  // Esta línea es nueva

    // Ruta para mostrar el formulario de edición
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Ruta para actualizar el perfil
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Ruta para eliminar el perfil
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutas de autenticación de Twitter
Route::get('auth/twitter', [LoginController::class, 'redirectToTwitter'])->name('auth.twitter');
Route::get('auth/twitter/callback', [LoginController::class, 'handleTwitterCallback']);
Route::post('/confirm-login/{userId}', [LoginController::class, 'confirmLogin'])->name('confirm.login');

// Cargar rutas adicionales de autenticación
require __DIR__ . '/auth.php';
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

/*
|--------------------------------------------------------------------------
| Controlador destinado para Personalizar el perfil (Actualizar nombre, contrasenia, eliminar, etc.)
|--------------------------------------------------------------------------
*/

class ProfileController extends Controller
{
    //Metodo mostrar vista de edicion del usuario
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    //Metodo para actualizar registro del usuario en la base de datos
    public function update(Request $request)
    {
        $user = User::findOrFail(auth()->id());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $user->id,
            'current_password' => ['required', function($attribute, $value, $fail) use ($user){

            if (!Hash::check($value, $user->password)) {
                $attribute = 'Contraseña Actual';
                $fail("El campo {$attribute} es incorrecto");
            }
            }],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            ]);

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            return back()->with('saved', true);   
    }

    //Metodo paara borrar un registro de la base de datos DB
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

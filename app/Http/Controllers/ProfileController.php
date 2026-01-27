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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
public function update(Request $request)
{
//Encontramos el usuario autenticado de acuerdo a su ID 
$user = User::findOrFail(auth()->id());

    // Validación de los campos, nombre, Correo contraseña actual y contrasña nueva
    $data = $request->validate([
        'name' => 'required|string|max:255',
        //Concatenamos el 
        'email' => 'required|string|max:255|unique:users,email,' . $user->id,
        //Esto sera Igual a un arreglo con attrubute que no lo usamos, value que bueno sera user contraseña y
        //$attribute es el nombre de current_password, $valu lo escribido por el usuario
        'current_password' => ['required', function($attribute, $value, $fail) use ($user){

            if (!Hash::check($value, $user->password)) {

                $attribute = 'Contraseña Actual';
               $fail("El campo {$attribute} es incorrecto");

            }
        }],
        //LO importante aqui es que tomamos el Modelo de Passwprd de brazeee ??
        'password' => ['nullable', 'confirmed', Password::defaults()],
    ]);

    // Si no escribió contraseña, no la cambia
    if (empty($data['password'])) {
        unset($data['password']);
    } else {
        $data['password'] = Hash::make($data['password']);
    }

    $user->update($data);

    return back()->with('saved', true);   
}

    /**
     * Delete the user's account.
     */
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

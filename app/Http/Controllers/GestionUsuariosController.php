<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GestionUsuariosController extends Controller
{
    // Constante para centralizar el paginado
    const PER_PAGE = 10;

    public function index()
    {
        $users = User::paginate(self::PER_PAGE);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // En un create de usuario, normalmente no necesitas pasar todos los usuarios
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:8|confirmed',
            'rol'          => 'required|string|max:35',
            'departamento' => 'required|string|max:35',
            'estatus'      => 'required|string|max:35',
        ]);

        // Encriptar contraseña antes de guardar
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);

        return redirect()->route('users.index', ['page' => $this->getReturnPage($user->id)])
            ->with('new_id', $user->id)
            ->with('success', 'Usuario agregado correctamente');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => 'nullable|string|max:255',
            'email'        => 'nullable|string|email|max:255|unique:users,email,'.$user->id,
            'rol'          => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'estatus'      => 'nullable|string|max:255',
        ]);

        $user->update($data);

        return redirect()->route('users.index', ['page' => $this->getReturnPage($user->id)])
        ->with('actualizado->id', $user->id)
        ->with('warning', 'Usuario editado correctamente');
    }

    public function destroy(User $user)
    {
        // Calculamos la página ANTES de borrarlo
        $page = $this->getReturnPage($user->id);
        
        $user->delete();

        return redirect()->route('users.index', ['page' => $page])
            ->with('danger', 'Usuario eliminado correctamente');
    }

    /**
     * MÉTODO HELPER: Calcula la página en la que se encuentra un registro
     */
    private function getReturnPage($userId)
    {
        $position = User::where('id', '<=', $userId)->count();
        return ceil($position / self::PER_PAGE);
    }
}
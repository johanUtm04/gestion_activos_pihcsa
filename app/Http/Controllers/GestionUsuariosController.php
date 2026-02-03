<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| Controlador destinado para manejar CRUD(CREATE, READ, UPDATE, DELETE) de Catalogo de Usuarios
|--------------------------------------------------------------------------
*/
class GestionUsuariosController extends Controller
{
    const PER_PAGE = 10;

    //Metodo para mostrar vista
    public function index()
    {
        $users = User::paginate(self::PER_PAGE);
        return view('users.index', compact('users'));
    }

    //Metodo para cargar formulario de creacion
    public function create()
    {
        return view('users.create');
    }

    //Metodo para crear registro en Base de datos
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

        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);

        return redirect()->route('users.index', ['page' => $this->getReturnPage($user->id)])
        ->with('new_id', $user->id)
        ->with('success', 'Usuario agregado correctamente');
    }

    //Metodo para cargar formulario de edicion
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    //Metodo para editar registro en Base de datos
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

    //Metodo para eliminar registro de base de datos
    public function destroy(User $user)
    {
        $page = $this->getReturnPage($user->id);
        
        $user->delete();

        return redirect()->route('users.index', ['page' => $page])
            ->with('danger', 'Usuario eliminado correctamente');
    }

    //METODO HELPER: Calcula la página en la que se encuentra un registro
    private function getReturnPage($userId)
    {
        $position = User::where('id', '<=', $userId)->count();
        return ceil($position / self::PER_PAGE);
    }
}
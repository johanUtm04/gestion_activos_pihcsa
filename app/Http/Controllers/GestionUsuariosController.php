<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GestionUsuariosController extends Controller
{
    const PER_PAGE = 10;

    public function index(Request $request)
    {
        $query = User::query(); 

        if ($request->filled('usuario_id')) {
            $query->where('id', $request->usuario_id);
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }

        $users = $query->orderBy('id', 'asc') 
            ->paginate(self::PER_PAGE)
            ->withQueryString(); 

        $todosLosUsuarios = User::orderBy('name', 'asc')->get();

        return view('users.index', compact('users', 'todosLosUsuarios'));
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

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

    return redirect()->route('users.index', ['page' => $this->getReturnPage($user->id)])
        ->with('new_id', $user->id) 
        ->with('success', 'Usuario agregado correctamente');
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
    ->with('actualizado_id', $user->id)
    ->with('warning', 'Usuario editado correctamente');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function destroy(User $user)
    {
        $page = $this->getReturnPage($user->id);
        $user->delete();

        return redirect()->route('users.index', ['page' => $page])
            ->with('danger', 'Usuario eliminado correctamente');
    }

private function getReturnPage($userId)
{
    $usuarioTarget = User::find($userId);

    if (!$usuarioTarget) return 1;

    $position = User::where('id', '<=', $userId)->count();

    return ceil($position / self::PER_PAGE);
}
}
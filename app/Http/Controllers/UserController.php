<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected UserRepository $users;

    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function updateForm(Request $request, User $object)
    {
        $roles = Role::all();

        return view('user.edit', ['object' => $object, 'roles' => $roles]);
    }

    public function doUpdate(Request $request, User $object)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);

        $object->update($validated);
        if (! $object->hasRole($validated['role'])) {
            $object->syncRoles([$validated['role']]);
        }

        return redirect($request->query('next') ?? route('dashboard'));
    }

    public function deleteForm(Request $request, User $object)
    {
        return view('user.delete', ['object' => $object]);
    }

    public function doDelete(Request $request, User $object)
    {
        $object->delete();

        return redirect($request->query('next') ?? route('dashboard'));
    }
}

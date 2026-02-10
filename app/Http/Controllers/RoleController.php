<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar roles.
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Menampilkan form untuk membuat role baru.
     */
    public function create()
    {
        // Dapatkan daftar route yang memiliki nama sebagai pilihan permission
        $allRoutes = $this->getAllRoutes();
        return view('roles.create', compact('allRoutes'));
    }

    /**
     * Menyimpan role baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:roles',
            // Validasi permission sebagai array, boleh kosong
            'permissions' => 'nullable|array',
        ]);

        Role::create([
            'name'        => $request->name,
            'permissions' => $request->permissions, // simpan array route name
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Menampilkan form edit untuk role tertentu.
     */
    public function edit(Role $role)
    {
        $allRoutes = $this->getAllRoutes();
        return view('roles.edit', compact('role', 'allRoutes'));
    }

    /**
     * Mengupdate role.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update([
            'name'        => $request->name,
            'permissions' => $request->permissions,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Menghapus role.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * Mengambil semua route yang memiliki nama untuk dijadikan pilihan permission.
     */
    private function getAllRoutes()
{
    $routes = Route::getRoutes();
    $authRoutes = [];

    foreach ($routes as $route) {
        // Ambil hanya route dalam middleware auth
        if ($route->getName() &&
            (in_array('auth', $route->gatherMiddleware()) ||
             (isset($route->action['middleware']) &&
              (is_array($route->action['middleware']) &&
               in_array('auth', $route->action['middleware']))))) {
            $prefix = explode('.', $route->getName())[0];
            if (!in_array($prefix, $authRoutes)) {
                $authRoutes[] = $prefix;
            }
        }
    }

    // Urutkan array
    sort($authRoutes);

    return $authRoutes;
}
}

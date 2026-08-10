<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreRequest; // 1. Import StoreRequest di atas
use Illuminate\Support\Facades\Hash;     // 1. Import Hash untuk enkripsi password
use App\Http\Requests\User\UpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data user dengan filter pencarian berdasarkan nama atau email
        $users = User::with('role')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        // 2. Ubah method create agar mengirim data roles ke view
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(StoreRequest $request)
    {
        // 3. Ubah method store menggunakan StoreRequest dan simpan semua field termasuk role_id
        $dataReq = $request->validated();

        $data['name'] = $dataReq['name'];
        $data['email'] = $dataReq['email'];
        $data['password'] = Hash::make($dataReq['password']);
        $data['role_id'] = $dataReq['role_id'];

        User::create($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $dataReq = $request->validated();

        $user->name = $dataReq['name'];
        $user->email = $dataReq['email'];
        $user->role_id = $dataReq['role_id'];

        // Jika kolom password diisi, maka enkripsi dan perbarui passwordnya. Jika kosong, biarkan tetap.
        if (!empty($dataReq['password'])) {
            $user->password = Hash::make($dataReq['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
    }
    public function destroy(User $user)
    {


        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
    }
}
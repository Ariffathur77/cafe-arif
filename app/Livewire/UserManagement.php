<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UserManagement extends Component
{
    public $user_id, $name, $email, $password, $role_id;
    public $isModalOpen = false;

    // Fungsi ini akan dijalankan sebelum komponen di-render
    public function mount()
    {
        // Otorisasi: Hanya user dengan role 'Owner' yang bisa mengakses halaman ini
        $this->authorize('is-owner');
    }

    public function render()
    {
        // Ambil semua user kecuali Owner itu sendiri
        $users = User::with('role')->where('id', '!=', auth()->id())->get();

        // Ambil semua role KECUALI 'Owner' dan 'Customer' untuk pilihan di form
        $roles = Role::whereNotIn('name', ['Owner', 'Customer'])->get();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->password = ''; // Kosongkan password saat edit

        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'role_id' => 'required|exists:roles,id',
            'password' => [$this->user_id ? 'nullable' : 'required', 'min:8'],
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
        ];

        // Hanya update password jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message', $this->user_id ? 'User berhasil diperbarui.' : 'User berhasil dibuat.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_id = '';
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User berhasil dihapus.');
    }
}

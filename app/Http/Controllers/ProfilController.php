<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = User::findOrFail(Auth::id());

        return view('halaman.profil-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate(
            [
                'name'     => ['required', 'string', 'max:255'],
                'contact'  => ['required', 'string', 'max:30'],
                'password' => ['nullable', 'string', 'min:8', 'max:100'],
            ],
            [
                'name.required'    => 'Nama wajib diisi.',
                'contact.required' => 'No. telp wajib diisi.',
                'contact.max'      => 'No. telp maksimal 30 karakter.',
                'password.min'     => 'Password minimal 8 karakter.',
            ]
        );

        $gantiPassword = filled($data['password'] ?? null);

        if ($gantiPassword) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        User::findOrFail(Auth::id())->update($data);

        return back()->with('success', $gantiPassword
            ? 'Profil dan password sudah diperbarui.'
            : 'Profil sudah diperbarui.');
    }
}
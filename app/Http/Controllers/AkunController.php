<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    private const PERAN = ['sales' => 'Sales', 'owner' => 'Owner', 'admin' => 'Admin'];

    public function index()
    {
        $menunggu = User::where('is_active', false)
            ->latest()->latest('id')->get();

        $aktif = User::where('is_active', true)
            ->latest()->latest('id')->get();

        return view('halaman.owner.index', compact('menunggu', 'aktif'));
    }

    // Mengaktifkan / menonaktifkan akun.
    // Saat MENGAKTIFKAN akun baru, admin boleh sekalian memilih peran (sales / owner / admin).
    public function ubahStatus(Request $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa mengubah status akunmu sendiri.');
        }

        $data = $request->validate(
            ['role' => ['nullable', Rule::in(array_keys(self::PERAN))]],
            ['role.in' => 'Peran tidak valid.']
        );

        // Menonaktifkan akun
        if ($user->is_active) {
            $user->update(['is_active' => false]);

            return back()->with('success', "Akun {$user->name} sudah dinonaktifkan.");
        }

        // Mengaktifkan akun (peran ikut diubah kalau admin memilihnya)
        $perubahan = ['is_active' => true];
        if (! empty($data['role'])) {
            $perubahan['role'] = $data['role'];
        }
        $user->update($perubahan);

        $peran = self::PERAN[$user->role] ?? ucfirst($user->role);

        return back()->with('success', "Akun {$user->name} sudah diaktifkan sebagai {$peran}.");
    }
}
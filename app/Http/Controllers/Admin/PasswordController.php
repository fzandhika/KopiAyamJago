<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Tampilkan form ganti password admin.
     */
    public function edit(): View
    {
        return view('admin.password.edit');
    }

    /**
     * Simpan password baru setelah memverifikasi password lama.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak cocok.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password belum sama.',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        $request->session()->regenerate();

        return back()->with('success', 'Password admin berhasil diganti. Gunakan password baru saat login berikutnya.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Form edit profil kepala ruangan.
     */
    public function edit()
    {
        $user        = auth()->user();
        $hospitals   = Hospital::all();
        $departments = Department::all();

        return view('profile.edit', compact('user', 'hospitals', 'departments'));
    }

    /**
     * Simpan perubahan profil.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'position'      => ['nullable', 'string', 'max:255'],
            'hospital_id'   => ['required', 'exists:hospitals,id'],
            'department_id' => ['required', 'exists:departments,id'], // typo sebelumnya "ex ists"
            'photo'         => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();

        // Upload Foto (opsional)
        if ($request->file('photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Update kolom lain
        $user->update([
            'name'          => $request->name,
            'position'      => $request->position,
            'hospital_id'   => $request->hospital_id,
            'department_id' => $request->department_id,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Hapus akun user.
     */public function destroy()
{
    $user = Auth::user();

    // Hapus semua staff milik user ini (jika relasinya benar)
    $user->staff()->delete(); // <--- ini penting

    if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
        Storage::disk('public')->delete($user->profile_photo_path);
    }

    Auth::logout();
    $user->delete();

    return redirect('/')->with('success', 'Akun berhasil dihapus.');
}

}

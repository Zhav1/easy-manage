<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Form edit profil kepala ruangan.
     */
    public function edit()
    {
        $user        = auth()->user();
        $hospitals   = Hospital::all();
        $departments = Department::all();         // list dropdown Ruangan

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
            'department_id' => ['required', 'ex ists:departments,id'],
            'photo' => ['nullable', 'image', 'max:2048'],   // nama input file di view
        ]);

        $user = auth()->user();

        // ------------ Upload Foto (opsional) ------------
        if ($request->file('photo')) {

            // hapus file lama jika ada
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // simpan file baru
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // ------------ Update kolom lainnya ------------
        $user->update([
            'name'          => $request->name,
            'position'      => $request->position,
            'hospital_id'   => $request->hospital_id,
            'department_id' => $request->department_id,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}

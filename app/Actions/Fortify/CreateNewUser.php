<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'id_pegawai' => ['required', 'string', 'unique:users,id_pegawai'],
            'hospital_id' => ['required', 'exists:hospitals,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'id_pegawai' => $input['id_pegawai'],
            'hospital_id' => $input['hospital_id'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}

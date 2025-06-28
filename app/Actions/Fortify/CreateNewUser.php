<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\ValidationException;
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
            'department_id' => ['required', 'exists:departments,id'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        return tap(User::create([
            'name' => $input['name'],
            'id_pegawai' => $input['id_pegawai'],
            'hospital_id' => $input['hospital_id'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'department_id' => $input['department_id'],
        ]),
        function (User $user) {
            $token = $user->createToken('register-token')->plainTextToken;
            session(['token' => $token]);
        });
    }
}

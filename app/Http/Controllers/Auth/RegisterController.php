<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'prefixname' => ['nullable','in:Mr,Mrs,Ms', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable','string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'suffixname' => ['nullable','string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable','mimes:png,jpg,jpeg'],
            'type' => ['nullable','string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if(isset($data['photo'])) {
            $path = public_path('photos');
            $file = $data['photo'] ?? null;
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move($path, $filename);
        }

        return User::create([
            'prefixname' => $data['prefixname'] ?? null,
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'] ?? null,
            'lastname' => $data['lastname'],
            'suffixname' => $data['suffixname'] ?? null,
            'username' => $data['username'],
            'email' => $data['email'],
            'photo' => isset($file) ? $filename : null,
            'type' => $data['type'] ?? 'user',
            'password' => Hash::make($data['password']),
        ]);
    }
}

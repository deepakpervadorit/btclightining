<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\CheckbookService;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = '/login';
    protected $checkbookService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct(CheckbookService $checkbookService)
    {
        $this->middleware('guest');
        $this->checkbookService = $checkbookService;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        // Attempt to create the user using CheckbookService
        $createuser = $this->checkbookService->createUser($data['name']);
        // dd($createuser);
        // Check for errors in the Checkbook API response
        if (isset($createuser['error'])) {
            // Abort user creation if username is already in use
            session()->flash('error', 'Username is already in use. Please choose a different name.');
            abort(400, 'Username already in use.');
        }

        // Save the user in the application's database
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'User',
        ]);

        // Save Checkbook API keys in the checkbook_users table
        DB::table('checkbook_users')->insert([
            'userid' => $user->id,
            'user_id' => $createuser['user_id'],
            'checkbook_id' => $createuser['id'],
            'api_key' => $createuser['key'],
            'api_secret_key' => $createuser['secret'],
            'created_at' => now(),
        ]);

        // Flash a success message and redirect
        session()->flash('success', 'Registration successful! Please log in to continue.');
        return $user;
    }
}

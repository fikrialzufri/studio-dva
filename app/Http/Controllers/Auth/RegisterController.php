<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
use Symfony\Component\HttpFoundation\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'min:11', 'max:255', 'unique:anggota'],
            'no_hp' => ['required', 'string', 'min:11', 'max:255', 'unique:anggota'],
            'alamat' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'password_confrim' => 'required|same:password|min:8',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = new User;
        $user->name = $request->nama;
        $user->username = $request->username;
        $user->slug = Str::slug($request->username);
        $user->email = request()->input('email');
        $user->password =  Hash::make($request->password);
        $user->save();

        $Anggota = new Anggota;
        $Anggota->nama = $request->nama;
        $Anggota->nik = $request->nik;
        $Anggota->alamat = $request->alamat;
        $Anggota->no_hp = $request->no_hp;
        $Anggota->user_id = $user->id;
        $Anggota->save();

        return redirect($this->redirectPath())->with('message', 'Pendaftaran Berhasil, mohon login kembali');
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

        // Untuk validasi password Register
    use PasswordValidationRules


    public function login(Request $request){
        // gunakan try catcth untuk konjdisi terpenuhi/tidak
        try{
        // Validasi
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // Mengecek credentials (login)
            $credentials = request(['email', 'password']);

            if(!Auth::attempt($credentials)){
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500 );
            }

            // Jika hash tidak sesuai maka beri error
            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])){
                throw new \Exception('Invalid Credentials');
            }

            // Jika berhasil maka loginkan
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return  ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch(Exception $error){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

     public function register(Request $request){
        try{
            $request->validate([
                'name' => ['required', 'string', 'max:20'],
                'email' => ['required', 'string', 'email', 'max:20', 'unique:users'],
                'password' => $this->passwordRules()
            ]);

            User::create([
                'name' => $request-> name,
                'email' => $request->email,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'phoneNumber' => $request->phoneNumber,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]);

            // setUser
            $user = User::where('email', $request->email)->first();

            // sekalian login berikan token
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ]);

        }catch(Exception $error){
            return ResponseFormatter::error([
                'message' => 'something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }

    }

    public function logout(Request $request){
        // ambil user yang sudah login kemudian delete tokennya

        $token = $request->user()->currentAccesToken()->delete();

        // Kemablikan datanya
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    // API User
    public function fetch(Request $request){
        return ResponseFormatter::success(
            $request->user(), 'Data profile berhasil diambil'
        );
    }

    public function updateProfile(Request $request){
        // ambil semua data inputan user
        $data = $request->all();

        // panggil data user saat ini
        $user = Auth::user();
        // Tiban dengan data inputan terbaru
        $user->update($data);

        return ResponseFormatter::success($user, "Profile Updated");
    }

    public function updatePhoto(Request $request){
// cek validasi yang di upload gambar atau bukan
        $validator = Validator::make($request->all(),
        [
            'file' => 'required|image|max:2048'
        ]);

        if($validator->fails())
        {
            return ResponseFormatter::error(
                [ 'error' => $validator->errors() ],
                'Update photo fails',
                401
            );
        }

        if($request->file('file'))
        {
            // upload fotonya
            $file = $request->file->store('assets/user', 'public');

            // Simpen foto ke database(Urlnya)

            // gambar tetep di folder hanya upload URL nya

            // panggil model user nya
            $user = Auth::user();
            // panggil field nya dan gantikan dengan yang sudah di upload
            $user->profile_photo_path = $file;
            // UPdated user nya
            $user->update();

            return ResponseFormatter::success([$file], 'File succesfully Uploaded');
        }
    }

}

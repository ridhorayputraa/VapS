<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Buat Variable user dan panagill data user pagination 10
        $user = User::paginate(10);

        return view('users.index', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // Mengambil Data Semua Request dari Validasi UserRequest
        $data = $request->all();
        $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');

        User::Create($data);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Langsung panggil si user nya
    public function edit(User $user)
    {
        //
        return view('users.edit', [
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //  Gunakan validasi UserRequest
    public function update(UserRequest $request, User $user)
    {
        // Ganti Id menjadi user agar tidak panggil panggil lagi
        $data = $request->all();
        if($request->file('profile_photo_path')){
              $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        }

        // Assign kembali value nya
        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //  Langsung ganti menjadi User biar ga chengli
    public function destroy(User $user)
    {
        // langsung di delete
        $user->delete();
        // Kemudian redirect ke halaman dashboard
        return redirect()->route('users.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\VapeRequest;
use App\Models\Vape;
use Illuminate\Http\Request;

class VapeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vape = Vape::paginate(10);
        return view('vape.index', [
            'vape' => $vape
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('vape.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  Untuk create tapi logic nya
    public function store(VapeRequest $request)
    {
    //    Menambil daya request dari Validasi
    // Vape Request

    $data = $request->all();
    $data['picturePath'] = $request->file('picturePath')
    ->store('assets/vape', 'public');

    // Masukan query create
    Vape::create($data);

    return redirect()->route('vape.index');

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

    //  Langsung ke model Vape
    public function edit(Vape $vape)
    {
        return view('vape.edit' , [
            'item' => $vape
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VapeRequest $request, Vape $vape)
    {
        //
        $data = $request->all();
        if($request->file('picturePath'))
        {
          $data['picturePath'] = $request->file('picturePath')->store('assets/food', 'public');
        }

        // Done All

    // Assign kembali value nya
        $vape->update($data);

        // langsung redirect ke index Vape
        return redirect()->route('vape.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vape $vape)
    {
        // langsung di delete
        $vape->delete();
        // Kemudian redirect ke halaman dashboard
        return redirect()->route('vape.index');
    }
}

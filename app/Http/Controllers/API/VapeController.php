<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VapeController extends Controller
{
    //
    public function all(Request $request){
     // 1 fungsi ini  akan menghandle semua request
    // perlu menyiapkan beberapa opsi untuk filter
    // Berdasarkan ID, Berdasarlan Harga, Berdasarkan Tipe

    $id = $request->input('id');
    $limit = $request->input('limit',6);
    $name = $request->input('name');
    $types = $request->input('types');


    // Berdasarkan harga dari yang terkecil -> terbesar
    
    }
}

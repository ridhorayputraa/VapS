<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Vape;
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
    $price_from = $request->input('price_from');
    $price_to = $request->input('price_to');

    // Berdasarkan Rating
    $rate_from = $request->input('rate_from');
    $rate_to = $request->input('rate_to');


    // Buat filtering datanya berdasarkan id
    if($id){
        $vape = Vape::find($id);

        if($vape){
            return ResponseFormatter::success(
                $vape,
                'Data produk berhasil di ambil'
            );
        }else{
            return ResponseFormatter::error(
                null,
                'Data produk not found',
                404
            );
        }
    }


    }
}

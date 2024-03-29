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

// Finish Creating API

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

    // Buat filtering sisanya
    // buat dulu query dasarnya
    $vape = Vape::query();

    // Berdasarkan nama
    if($name){
        $vape->where('name', 'like', '%' . $name . '%');
    }

    // Berdasarkan types
    if($types){
        $vape->where('types', 'like', '%' . $types . '%');
    }

    // Berdasarkan harga dari yang terkecil
    if($price_from){
        $vape->where('price', '>=', $price_from);
    }

    if($price_to){
        $vape->where('price', '<=' , $price_to);
    }

    // Berdasarkan rating dari yang terkecil
    if($rate_from){
        $vape->where('rate', '>=', $rate_from);
    }

    if($rate_to){
        $vape->where('rate', '<=' , $rate_to);
    }

    return ResponseFormatter::success(
        $vape->paginate($limit),
        // tamabhkan paginasi
        'Data list produk berhasil di ambil'
    );

    }
}

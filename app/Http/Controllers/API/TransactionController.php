<?php

namespace App\Http\Controllers\API;

use App\Models\Vape;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    //
        //
    public function all(Request $request){
     // 1 fungsi ini  akan menghandle semua request
    // perlu menyiapkan beberapa opsi untuk filter
    // Berdasarkan ID, Berdasarlan Harga, Berdasarkan Tipe

    $id = $request->input('id');
    $limit = $request->input('limit',6);
    // pengembngambilin transaksi dengan vape_id tertentu
    $vape_id = $request->input('vape_id');
    // pengembngambilin transaksi dengan status tertentu
    $status = $request->input('status');

    // Buat filtering datanya berdasarkan id
    if($id){
        $transaction = Transaction::with(['vape', 'user'])->find($id);
        // Tamabhkan with untuk relasinya
        // ['vape', 'user'] -> dari model transaction


        if($transaction){
            return ResponseFormatter::success(
                $transaction,
                'Data transaksi berhasil di ambil'
            );
        }else{
            return ResponseFormatter::error(
                null,
                'Data transaksi not found',
                404
            );
        }
    }

    // Buat filtering sisanya
    // buat dulu query dasarnya menggunakan where
    // kenapa where() ? karena kita ingin mengambil transksi hanya 'dia saja'
    // bukan orang lain
    $transaction = Transaction::with(['vape', 'user'])->where('user_id', Auth::user()->id);

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

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
    $transaction = Transaction::with(['vape', 'user'])
    ->where('user_id', Auth::user()->id);

    // Berdasarkan nama vape ID
    if($vape_id){
        // bisa dikatakan begini 'vape_id = $vape_id'
        $transaction->where('vape_id', $vape_id);
    }

    // Berdasarkan status
    if($status){
        $transaction->where('status',  $status );
    }

    return ResponseFormatter::success(
        $transaction->paginate($limit),
        // tamabhkan paginasi
        'Data list transaksi berhasil di ambil'
    );

    }


    // Api Transaksi Update
    public function update(Request $request, $id){
        $transaction = Transaction::findOrFail($id);

        // update data setelah di ambil
        $transaction->update($request->all());

        return ResponseFormatter::success($transaction,
         'Transaksi berhasil di perbarui');

    }

}

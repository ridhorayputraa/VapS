<?php

namespace App\Http\Controllers\API;

use App\Models\Vape;
use App\Transaction;
use Midtrans\Config;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
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
                'Data transaksi tidak di temukan',
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

      // API Checkout dengan midtrans
      public function checkout(Request $request){
        $request->validate([
            'vape_id' => 'required|exists:vapes,id',
            // Arahin ke table vape dan cek id nya ada atau nggak
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);

        $transaction = Transaction::create([
            'vape_id' => $request->vape_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => '',
            // akan di update setelah nembak midtras
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Panggil transaksi yang tadi dibuat
        $tramsaction = Transaction::with(['vape', 'user'])->find($transaction->id);

        // Membuat Transaski Midtrans
        $midtrans = [
            // Referensi datanya ada snap-docs.midtrans.com->request Body
            'transaction_details' => [
               'order_id' => $transaction->id,
               'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enable_payment' => [
                // Enable payment isi untuk payment apa aja
                'gopay',
                'bank_transfer'
            ],
            'vtweb' => []
        ];
    }

}

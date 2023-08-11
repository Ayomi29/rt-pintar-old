<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\ImportantNumber;
use Illuminate\Http\Request;

class ApiImportantNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $important_number = ImportantNumber::all();
        if (count($important_number) < 1) {
            $important_number = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data nomor penting';
        $data = ['important_number' => $important_number];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }


    public function store(Request $request)
    {
        $phone_number_unavailable = ImportantNumber::where('phone_number', request('phone_number'))->first();
        if ($phone_number_unavailable != null) {
            $status = 'error';
            $status_code = 400;
            $message = 'nomor telepon sudah tersedia';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $important_number = ImportantNumber::create([
            'name' => request('name'),
            'phone_number' => request('phone_number')
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah data nomor penting'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menambahkan data';
        return response()->json(compact('status', 'status_code', 'message', 'important_number'), 200);
    }


    public function edit($phone)
    {
        $important_number = ImportantNumber::findOrFail($phone);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'important_number'), 200);
    }


    public function update(Request $request, ImportantNumber $importantNumber)
    {
        $importantNumber->update([
            'name' => request('name'),
            'phone_number' => request('phone_number')
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Mengubah data nomor penting'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'importantNumber'), 200);
    }

    public function destroy(ImportantNumber $importantNumber)
    {
        $importantNumber->delete();
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Menghapus data nomor penting'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus data';
        return response()->json(compact('status', 'status_code', 'message', 'importantNumber'), 200);
    }
}

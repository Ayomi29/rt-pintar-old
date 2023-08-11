<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\House;
use Illuminate\Http\Request;

class ApiHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $house = House::whereHas('family_card', function ($q) {
            $q->where('status', 'aktif');
        })->get();
        $empty_house = House::whereDoesntHave('family_card')->get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'houses', 'empty_house'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $house_number_unavailable = House::where('house_number', request('house_number'))->first();
        if ($house_number_unavailable != null) {
            $status = 'success';
            $status_code = 400;
            $message = 'nomor rumah tidak dapat digunakan';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $house = House::create([
            'house_number' => request('house_number'),
        ]);

        if ($request->filled('family_card_number')) {
            FamilyCard::create([
                'house_id' => $house->id,
                'family_card_number' => request('family_card_number'),
                'status' => 'aktif'
            ]);
        }

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah rumah'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil membuat data';
        return response()->json(compact('status', 'status_code', 'message', 'houses'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function edit($rumah)
    {

        $house = House::findOrFail($rumah);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'house'), 200);
    }

    public function update(Request $request, House $house)
    {
        $house_number_unavailable = House::where('house_number', request('house_number'))->first();
        if ($house_number_unavailable != null) {
            if ($house->id != $house_number_unavailable->id) {

                $status = 'success';
                $status_code = 400;
                $message = 'nomor rumah tidak dapat digunakan';
                return response()->json(compact('status', 'status_code', 'message'), 400);
            }
        }
        $house->update([
            'house_number'  => request('house_number'),
            'longitude' => request('longitude'),
            'latitude' => request('latitude')
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Ubah rumah'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'house'), 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FamilyCard;
use App\Http\Requests\StoreFamilyCardRequest;
use App\Http\Requests\UpdateFamilyCardRequest;
use App\Models\ActivityHistory;
use App\Models\House;
use Illuminate\Http\Request;

class ApiFamilyCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $family_cards = FamilyCard::orderBy('id', 'asc')->get();
        $houses = House::get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_cards', 'houses'), 200);
    }

    public function store(Request $request)
    {
        $family_card_number_unavailable = FamilyCard::where('family_card_number', request('family_card_number'))->first();
        if ($family_card_number_unavailable != null) {
            $status = 'success';
            $status_code = 400;
            $message = 'nomor KK tidak dapat digunakan';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $family_card = FamilyCard::create([
            'house_id' => request('house_id'),
            'family_card_number' => request('family_card_number'),
            'status' => 'aktif'
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah data KK'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menambahkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_card'), 200);
    }


    public function edit($familyCard)
    {
        $family_card = FamilyCard::findOrFail($familyCard);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_card'), 200);
    }
    public function update(Request $request, FamilyCard $familyCard)
    {

        $familyCard->update([
            'house_id' => request('house_id'),
            'family_card_number' => request('family_card_number'),
            'status' => request('status')
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Ubah data KK'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'familyCard'), 200);
    }
    public function destroy(FamilyCard $familyCard)
    {
        $familyCard->delete();
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Hapus data KK'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus data';
        return response()->json(compact('status', 'status_code', 'message'), 200);
    }
}

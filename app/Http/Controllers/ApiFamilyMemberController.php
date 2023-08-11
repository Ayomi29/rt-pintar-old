<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyCard;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class ApiFamilyMemberController extends Controller
{

    public function index()
    {
        $family_members = FamilyMember::orderBy('family_card_id', 'asc')->get();
        $family_cards = FamilyCard::get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_members', 'family_cards'), 200);
    }

    public function store(Request $request)
    {
        $nik_unavailable = FamilyMember::where('nik', request('nik'))->first();
        if ($nik_unavailable != null) {
            $status = 'success';
            $status_code = 200;
            $message = 'NIK tidak dapat digunakan';
            return response()->json(compact('status', 'status_code', 'message'), 200);
        }

        $family_member = FamilyMember::create([
            'family_card_id'  => request('family_card_id'),
            'family_member_name' => request('family_member_name'),
            'family_status' => request('family_status'),
            'nik' => request('nik'),
            'gender' => request('gender'),
            'birth_place' => request('birth_place'),
            'birth_date' => request('birth_date'),
            'religious' => request('religious'),
            'citizenship' => request('citizenship'),
            'education' => request('education'),
            'marital_status' => request('marital_status'),
            'address' => request('address'),
            'job' => request('job'),
        ]);

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah anggota keluarga'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menambahkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_member'), 200);
    }

    /**
     * Display the specified resource.
     */
    public function edit(FamilyMember $familyMember)
    {
        $family_member = FamilyMember::with('family_card')->findOrFail($familyMember);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'family_member'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FamilyMember $familyMember)
    {
        $familyMember->update([
            'family_member_name' => request('family_member_name'),
            'family_status' => request('family_status'),
            'nik' => request('nik'),
            'gender' => request('gender'),
            'birth_place' => request('birth_place'),
            'birth_date' => request('birth_date'),
            'religious' => request('religious'),
            'citizenship' => request('citizenship'),
            'education' => request('education'),
            'marital_status' => request('marital_status'),
            'address' => request('address'),
            'job' => request('job'),
        ]);
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Ubah data anggota keluarga'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'familyMember'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FamilyMember $familyMember)
    {
        $familyMember->delete();
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Hapus data anggota keluarga'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus data';
        return response()->json(compact('status', 'status_code', 'message'), 200);
    }
}

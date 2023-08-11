<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereHas('family_member', function ($q) {
            $q->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            });
        })->orderBy('id', 'asc')->get();
        $family_members = FamilyMember::whereDoesntHave('user')->orderBy('nik', 'asc')->get();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'users', 'family_members'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $family_member = FamilyMember::where('nik', request('nik'))
            ->whereHas('family_card', function ($q1) {
                $q1->whereHas('house');
            })->first();
        // dd($family_member);

        $unavailable_phone_number = User::where('phone_number', request('phone_number'))->first();
        if ($unavailable_phone_number != null) {
            $status = 'error';
            $status_code = 400;
            $message = 'nomor telepon tidak dapat digunakan';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Tambah warga'
        ]);

        $user = User::create([
            'email' => request('email'),
            'phone_number' => request('phone_number'),
            'password' => bcrypt(request('password'))
        ]);

        $family_member->update([
            'user_id'  => $user->id,
            'verified' => '1'

        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menyimpan data';
        return response()->json(compact('status', 'status_code', 'message', 'user', 'family_member'), 200);
    }


    public function edit($warga)
    {
        $user = User::with('family_member')->findOrFail($warga);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data';
        return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Ubah warga'
        ]);

        $user->update([
            'phone_number' => request('phone_number'),
            'password' => bcrypt(request('password'))
        ]);

        $user->family_member->update([
            'status' => request('status'),
            'administrator' => request('administrator')
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mengubah data';
        return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'Hapus warga'
        ]);
        $user->family_member->update([
            'user_id' => null
        ]);
        $user->delete();
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menghapus data';
        return response()->json(compact('status', 'status_code', 'message', 'user'), 200);
    }
}

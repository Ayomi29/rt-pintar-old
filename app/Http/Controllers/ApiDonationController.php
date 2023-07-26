<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\DashboardNotification;
use App\Models\Donation;
use App\Models\DonationBill;
use Illuminate\Http\Request;

class ApiDonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donation = Donation::orderBy('created_at', 'desc')->get();

        if (count($donation) < 1) {
            $donation = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data iuran';
        $data = ['iuran' => $donation];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan detail data iuran';
        $data = ['iuran' => $donation];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
    public function storeBill(Request $request, $id)
    {
        if (request('nominal') == null || request('nominal') == '' || request('file') == null || request('file') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }

        $save = $request->file('file')->store('public/donation-bill');
        $filename = $request->file('file')->hashName();
        $file = url('/') . '/storage/donation-bill/' . $filename;
        $donation_bills = DonationBill::create([
            'donation_id' => request('donation_id'),
            'family_member_id' => auth('api')->user()->family_member->id,
            'nominal' => request('nominal'),
            'file' => $file,
            'status' => 'lunas'
        ]);

        ActivityHistory::create([
            'user_id' => auth('api')->user()->id,
            'description' => 'warga bayar iuran'
        ]);

        DashboardNotification::create([
            'category' => 'Bayar Iuran',
            'description' => $donation_bills->status . '(' . auth('api')->user()->family_member->family_member_name . ')'
        ]);

        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil menyimpan bukti iuran';
        $data = ['iuran_bills' => $donation_bills];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }
}

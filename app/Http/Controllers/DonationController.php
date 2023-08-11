<?php

namespace App\Http\Controllers;


use App\Models\ActivityHistory;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations["donations"] = Donation::orderBy('id', 'asc')->get();
        return view('dashboard.donation.index', $donations);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $save = $image->storeAs('public/picture', $image);
            // $img = url('/') . '/storage/picture/' . $filename2;
        }

        Donation::create([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $save
        ]);
        ActivityHistory::create([
            'user_id' => auth()->user()->id,
            'description' => 'Membuat iuran'
        ]);
        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $donation = Donation::findOrFail($id);
        return $donation;
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::find($id);
        // dd($donation);
        if ($request->image) {
            $donation->image = $request->file('image');
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $request->file('image')->store('public/picture');
            $filename = $request->file('image')->hashName();
            // $img = url('/') . '/storage/picture/' . $filename;
            $img = '/storage/picture/' . $filename;
        }
        $donation->update([
            'title' => request('title'),
            'description' => request('description'),
            'nominal' => request('nominal'),
            'image' => $img
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mengubah data iuran'
        ]);
        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Menghapus data iuran'
        ]);
        Donation::destroy($id);


        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
}

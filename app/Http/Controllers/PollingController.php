<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Polling;
use App\Models\PollingOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pollings['pollings'] = Polling::orderBy('id', 'asc')->get();
        return view('dashboard.polling.index', $pollings);
    }
    public function store(Request $request)
    {
        $polling = Polling::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'pending'
        ]);
        $polling_option = request('option_name');
        for ($i = 0; $i < count($polling_option); $i++) {
            PollingOption::create([
                'polling_id' => $polling->id,
                'option_name' => $polling_option[$i]
            ]);
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Tambah polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $polling = Polling::with('polling_option')->findOrFail($id);
        return $polling;
    }
    public function update($id)
    {
        $polling = Polling::with('polling_option')->findOrFail($id);
        $polling_option = PollingOption::where('polling_id', $polling->id);
        $polling_option->delete();

        $polling->update([
            'title' => request('title'),
            'description' => request('description')
        ]);
        $polling_option = request('option_name');
        for ($i = 0; $i < count($polling_option); $i++) {
            PollingOption::create([
                'polling_id' => $polling->id,
                'option_name' => $polling_option[$i]
            ]);
        }
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Ubah polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil mengubah data');
    }
    public function destroy(Polling $polling)
    {
        $polling->delete();
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Hapus polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil menghapus data');
    }
    public function startPolling(Polling $polling)
    {
        $polling->update(['status' => 'start']);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mulai polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil memulai polling');
    }
    public function finishPolling(Polling $polling)
    {
        $polling->update(['status' => 'finish']);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Akhiri polling'
        ]);

        return redirect()->back()->with('OK', 'Berhasil mengakhiri polling');
    }
}

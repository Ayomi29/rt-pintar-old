<?php

namespace App\Http\Controllers;

use App\Models\ActivityHistory;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notices['notices'] = Notice::orderBy('id', 'asc')->get();
        return view('dashboard.notice.index', $notices);
    }



    public function store(Request $request)
    {
        Notice::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'aktif'
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Membuat pengumuman'
        ]);
        return redirect()->back()->with('OK', "Berhasil menambahkan data");
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return $notice;
    }

    public function update(Request $request, Notice $notice)
    {
        $notice->update([
            'title' => request('title'),
            'description' => request('description'),
            'status' => request('status')
        ]);
        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Mengubah pengumuman'
        ]);
        return redirect()->back()->with('OK', "Berhasil mengubah data");
    }

    public function destroy(Notice $notice)
    {

        ActivityHistory::create([
            'user_id' => Auth::user()->id,
            'description' => 'Menghapus pengumuman'
        ]);
        $notice->delete();

        return redirect()->back()->with('OK', "Berhasil menghapus data");
    }
}

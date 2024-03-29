<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class ApiNoticeController extends Controller
{
    public function index()
    {
        if (auth('api')->user()->family_member == true) {
            $notice = Notice::where('status', 'aktif')->orderBy('created_at', 'desc')->get();
        }
        if (count($notice) < 1) {
            $notice = null;
        }
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request('title') == null || request('title') == '' || request('description') == null || request('description') == '') {
            $status = 'error';
            $status_code = 400;
            $message = 'Semua kolom wajib diisi';
            return response()->json(compact('status', 'status_code', 'message'), 400);
        }
        $notice = Notice::create([
            'title' => request('title'),
            'description' => request('description'),
            'status' => 'aktif'
        ]);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil membuat pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);
        $status = 'success';
        $status_code = 200;
        $message = 'Berhasil mendapatkan data pengumuman';
        $data = ['notice' => $notice];
        return response()->json(compact('status', 'status_code', 'message', 'data'), 200);
    }

    public function update(Request $request, Notice $notice)
    {
        //
    }

    public function destroy(Notice $notice)
    {
        //
    }
}

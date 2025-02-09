<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KlubController extends Controller
{
    public function index()
    {
        $klub = Klub::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar klub',
            'data' => $klub,
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $path = $request->file('logo')->store('public/logo');
            $klub = new Klub;
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();
            return response()->json([
                'success' => true,
                'message' => 'Data klub berhasil ditambah',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $klub = Klub::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail Liga',
                'data' => $klub,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $klub = Klub::findOrFail($id);
            if ($request->hasFile('logo')) {

                Storage::delete($klub->logo);
                $path = $request->file('logo')->store('public/logo');
                $klub->logo = $path;
            }
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $request->logo;
            $klub->save();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $klub,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ada',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }

    public function destroy($id)
    {
        try {
            $klub = Klub::findOrFail($id);
            Storage::delete($klub->logo);
            $klub->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $klub->nama_klub . ' Berhasil DiHapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data Tidak Ada',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }
}

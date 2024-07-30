<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemain;
use Illuminate\Http\Request;
use Validator;

class PemainController extends Controller
{
    public function index()
    {
        $pemains = Pemain::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Pemain Sepak Bola',
            'data' => $pemains,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'tgl_lahir' => 'required|date',
            'harga_pasar' => 'required|integer',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'id_klub' => 'required|exists:klubs,id',
            'foto' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $pemain = new Pemain();
            $pemain->fill($request->all());

            if ($request->hasFile('foto')) {
                Storage::delete($pemain->foto);
                $path = $request->file('foto')->store('public/fotos');
                $pemain->foto = $path;
            }

            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Ditambahkan',
                'data' => $pemain,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pemain = Pemain::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Detail Pemain',
                'data' => $pemain,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pemain Tidak Ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pemain' => 'required',
            'tgl_lahir' => 'required|date',
            'harga_pasar' => 'required|integer',
            'posisi' => 'required|in:gk,df,mf,fw',
            'negara' => 'required',
            'id_klub' => 'required|exists:klubs,id',
            'foto' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $pemain = Pemain::findOrFail($id);
            $pemain->fill($request->all());

            if ($request->hasFile('foto')) {
                Storage::delete($pemain->foto);
                $path = $request->file('foto')->store('public/fotos');
                $pemain->foto = $path;
            }

            $pemain->save();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Diupdate',
                'data' => $pemain,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pemain = Pemain::findOrFail($id);
            $pemain->delete();
            return response()->json([
                'success' => true,
                'message' => 'Pemain Berhasil Dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pemain Tidak Ditemukan',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }
}

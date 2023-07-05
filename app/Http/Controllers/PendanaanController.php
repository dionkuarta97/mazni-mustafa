<?php

namespace App\Http\Controllers;


use App\Models\Pendanaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendanaanController extends Controller
{

    public function getPendanaan(Request $request)
    {
        try {

            $limit = $request->limit ? $request->limit : 10;
            $search = $request->search;
            $orderBy = $request->orderBy ? $request->orderBy : 'id';
            $sort = $request->sort ? $request->sort : 'DESC';
            $where = [
                ['nama', 'like', "%$search%"]
            ];
            if ($request->from) {
                $where = [...$where, ['tanggal', ">=", $request->from], ['tanggal', "<=", $request->to]];
            }
            $pendanaan = Pendanaan::where($where)->orderBy($orderBy, $sort)->paginate($limit);
            $totalSeluruh = Pendanaan::where($where)->sum('infak');
            $min = Pendanaan::min('tanggal');
            $max = Pendanaan::max('tanggal');
            return response()->json([
                'data' => $pendanaan,
                'total_seluruh' => intval($totalSeluruh),
                'min' => $min,
                'max' => $max
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getMetaData(Request $request)
    {
        try {
            $bulan = Carbon::now()->locale('ID')->settings(['formatFunction' => 'translatedFormat'])->format('F Y');
            $from = Carbon::now()->startOfMonth()->format('y-m-d');
            $to = Carbon::now()->endOfMonth()->format('y-m-d');
            $totalBulanIni = Pendanaan::where([['tanggal', '>=', $from], ['tanggal', '<=', $to]])->sum('infak');
            $totalKeseluruhan = Pendanaan::sum('infak');
            $lasted = Pendanaan::all()->last();
            $response = [
                "bulan" => $bulan,
                "total_bulan_ini" => intval($totalBulanIni),
                "total_keseluruhan" => intval($totalKeseluruhan),
                "terakhir_masuk" => $lasted
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'nama' => "required",
                'infak' => "required",
                'tanggal' => "date|required"
            ], [
                'nama.required' => 'nama tidak boleh kosong',
                'infak.required' => 'infak tidak boleh kosong',
                'tanggal.date' => "tidak format tanggal",
                'tanggal.required' => 'tanggal tidak boleh kosong',
            ]);
            if ($validation->fails()) return response()->json($validation->errors(), 400);
            $pendanaan = Pendanaan::create([
                'nama' => $request->nama,
                'tanggal' => $request->tanggal,
                'infak' => $request->infak,
            ]);
            return response()->json(['message' => 'data berhasil ditambahkan', 'pendanaan' => $pendanaan], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $pendanaan = Pendanaan::find($request->route('id'));
            $pendanaan->delete();
            return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $pendanaan = Pendanaan::find($request->route('id'));
            $pendanaan->update([
                'nama' => $request->nama,
                'tanggal' => $request->tanggal,
                'infak' => $request->infak,
            ]);
            return response()->json(['message' => 'data berhasil diupdate', 'pendanaan' => $pendanaan], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

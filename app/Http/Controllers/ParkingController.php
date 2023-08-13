<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ParkingController extends Controller
{
    public function index()
    {
        $title = 'Masuk';
        return view('parking.index', compact('title'));
    }
    public function checkout()
    {
        $title = 'Keluar';
        return view('parking.checkout', compact('title'));
    }
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'no_police' => 'required',
            'category' => 'required'
        ], [
            'no_police.required' => 'No polisi harus diisi',
            'category.required' => 'Kategori harus dipilih'
        ]);
        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }
        $check_no_police = Parking::where('no_police', trim($request->no_police))->where('status', 'IN')->count();

        if ($check_no_police != 0) {
            return response()->json(['errors' => ['no_police' => 'Kendaraan belum keluar']]);
        }
        $count_parking = Parking::count();

        $parking = new Parking();
        $parking->no_police = trim($request->no_police);
        $parking->category_id = $request->category;
        $parking->parking_code = 'PKR-' . sprintf('%04d', $count_parking + 1);
        $parking->date_in = date('Y-m-d');
        $parking->check_in = date('H:i:s');
        $parking->status = 'IN';
        $parking->save();
        return response()->json(['success', 'Parkir disimpan']);
    }
    public function datatable(Request $request)
    {
        $parking = Parking::select('parkings.*', 'categories.name as name')
            ->join('categories', 'categories.id', '=', 'parkings.category_id')
            ->where('date_in', date('Y-m-d'))
            ->where('status', $request->status)
            ->get();
        return DataTables::of($parking)
            ->addColumn('no', '')
            ->make(true);
    }
    public function update(Request $request)
    {
        $validasi = Validator::make(
            $request->all(),
            [
                'parking_code' => 'required'
            ],
            [
                'parking_code.required' => 'Kode parkir harus diisi'
            ]
        );
        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()]);
        }
        $parking = Parking::where('parking_code', 'PKR-' . $request->parking_code)->where('status', 'IN')->first();

        if ($parking == null) {
            return response()->json(['errors' => ['parking_code' => 'Kode parkir salah atau tidak ditemukan']]);
        }
        $jam =   floor((strtotime(date('Y-m-d H:i:s')) - strtotime($parking->created_at)) / (60 * 60));
        $total_payment = $parking->category->charge + $jam * 2000;
        $parking->date_out = date('Y-m-d');
        $parking->check_out = date('H:i:s');
        $parking->status = 'OUT';
        $parking->duration = $jam + 1;
        $parking->total_payment = $total_payment;
        $parking->save();

        return response()->json(['success', 'Chekout berhasil']);
    }
}

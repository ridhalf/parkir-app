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
        $title = 'Parking';
        return view('parking.index', compact('title'));
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
        $count_parking = Parking::where('date_in', date('Y-m-d'))->count();

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
        $parking = Parking::select('parkings.id', 'no_police', 'parking_code', 'check_in', 'categories.name')
            ->join('categories', 'categories.id', '=', 'parkings.category_id')
            ->where('date_in', date('Y-m-d'))
            ->where('status', 'IN')
            ->get();
        return DataTables::of($parking)
            ->addColumn('no', '')
            ->make(true);
    }
}

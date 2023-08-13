<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Categories';
        return view('categories.index', compact('title'));
    }

    /**
     * Serverside Datatables json
     *
     * @return json
     */
    public function datatable(Request $request)
    {
        $categories = Category::select('id', 'name', 'charge');
        return DataTables::of($categories)
            ->addColumn('no', '')
            ->addColumn('edit', 'categories.action')
            ->rawColumns(['edit'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = Validator::make(
            $request->all(),
            [
                'name' => 'required|unique:categories',
                'charge' => 'required|numeric'
            ],
            [
                'name.required' => 'Nama harus diisi',
                'name.unique' => 'Nama sudah ada',
                'charge.required' => 'Tarif harus diisi',
                'charge.numeric' => 'Tarif harus dalam bentuk angka'
            ]
        );
        if ($validasi->fails()) {
            return response()->json([
                'errors' => $validasi->errors()
            ]);
        }
        $category = new Category();
        $category->name = $request->name;
        $category->charge = $request->charge;
        $category->save();
        return response()->json(['success' => 'Sukses Menyimpan Data']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('id', $id)->select('id', 'name', 'charge')->first();
        return response()->json(['result' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validasi = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'charge' => 'required|numeric'
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'charge.required' => 'Tarif wajib diisi',
                'charge.numeric' => 'Tarif harus dalam format angka'
            ]
        );
        if ($validasi->fails()) {
            return response()->json([
                'errors' => $validasi->errors(),
            ]);
        } else {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->charge = $request->charge;
            $category->save();
            return response()->json(['success' => 'Sukses Mengubah Data']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function get_all_categories()
    {
        $categories = Category::select('id', 'name', 'charge')->get();
        return response()->json([
            'result' => $categories
        ]);
    }
}

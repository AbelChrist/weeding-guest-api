<?php

namespace App\Http\Controllers\API;

use App\Models\FormGuest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class FormGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'message' => 'List Data Form Guest',
            'data' => FormGuest::orderBy('created_at', 'desc')->get(),
        ];
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'note' => 'required|string',
        ];
        $messages = [
            'required' => 'Atribut :attribute wajib diisi!.',
            'email' => 'Atribut :attribute harus berupa email yang valid!.',
            'string' => 'Atribut :attribute harus berupa string!.'
        ];
        $validator = Validator::make($data, $rules, $messages);
        $response = [
            'message' => 'Data tidak valid!',
            'errors' => $validator->errors()
        ];
        if($validator->fails()) return response()->json($response, 400);
        
        try {
            $formguest = FormGuest::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'note' => $data['note']
            ]);
            $response = [
                'message' => 'Form guest berhasil dibuat!',
                'data' => $formguest
            ];

            return response()->json($response, 201);
        } catch (QueryException $e) {
            $response = [
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $formguest = FormGuest::findOrFail($id);
        } catch(ModelNotFoundException $e) {
            $response = [
                'message' => 'Data Form guest tidak ditemukan!',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 404);
        }
        try {
            $formguest->delete();
            $response = [
                'message' => 'Data Form guest berhasil dihapus!',
            ];
    
            return response()->json($response, 200);
        } catch (QueryException $e) {
            $response = [
                'message' => 'Terjadi kesalahan!',
                'error' => $e->getMessage()
            ];
            return response()->json($response, 500);
        }

    }

    public function gallery()
    {
        $response = [
            'status' => 'Data Form Guest',
            'data' => FormGuest::orderBy('created_at', 'desc')->get(['name', 'note', 'created_at', 'updated_at'])
        ];
        return response()->json($response, 200);
    }
}

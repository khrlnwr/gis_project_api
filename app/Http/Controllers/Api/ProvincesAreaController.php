<?php

namespace App\Http\Controllers\Api;

use App\Models\ProvinceArea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProvincesAreaController extends Controller
{
    public function index() {
        $provinces = ProvinceArea::all();
        if ($provinces->count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $provinces
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'record not found'
            ], 404);
        }
    }


    public function insert(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'notes' => 'required|string|max:100',
            'lat' => 'required|numeric',
            'long' => 'required|numeric'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'result' => $validator->messages()
            ], 422);
        } else {
            $province = ProvinceArea::create([
                'name' => $request->name,
                'notes' => $request->notes,
                'lat' => $request->lat,
                'long' => $request->long
            ]);

            if ($province) {
                return response()->json([
                    'status' => 200,
                    'result' => 'Record province created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'result' => 'Record province failed to create'
                ], 500);
            }
        }

    }
}

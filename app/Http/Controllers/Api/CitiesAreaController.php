<?php

namespace App\Http\Controllers\Api;

use App\Models\CityArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CitiesAreaController extends Controller
{
    public function index() {
        $cities = CityArea::all();
        if ($cities->count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $cities
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'no city record found'
            ], 404);
        }
    }

    public function insert(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_province' => 'required|numeric',
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
            $cityCreated = CityArea::create([
                'id_province' => $request->id_province,
                'name' => $request->name,
                'notes' => $request->notes,
                'lat' => $request->lat,
                'long' => $request->long
            ]);

            if ($cityCreated) {
                return response()->json([
                    'status' => 200,
                    'result' => 'City created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'result' => 'City failed to be created'
                ], 500);
            }
        }
    }

    public function showCitiesByProvinceId($idProvince) {
        $cities = DB::table('cities_area')->where('id_province', $idProvince)->get();
        if ($cities->count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $cities
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'no city record found'
            ], 404);
        }
    }  
}

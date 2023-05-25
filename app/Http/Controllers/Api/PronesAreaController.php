<?php

namespace App\Http\Controllers\Api;

use App\Models\PronesArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PronesAreaController extends Controller
{
    public function index() {
        $prones_area = PronesArea::all();
        if ($prones_area->count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $prones_area
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'no record found'
            ], 404);
        }
    }

    public function insert(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_type' => 'required|numeric',
            'id_province' => 'required|numeric',
            'id_city' => 'required|numeric',
            'name' => 'required|string|max:100',
            'lat' => 'required|numeric',
            'long' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'response' => $validator->messages()
            ], 422);    
        } else {
            $prones_area = PronesArea::create([
                'id_type' => $request->id_type,
                'id_province' => $request->id_province,
                'id_city' => $request->id_city,
                'name' => $request->name,
                'lat' => $request->lat,
                'long' => $request->long
            ]);
            
            if ($prones_area) {
                return response()->json([
                    'status' => 200,
                    'response' => 'prones area record created succesfully'
                ], 200);    
            } else {
                return response()->json([
                    'status' => 500,
                    'response' => 'prones area record failed to create'
                ], 500);
            }
        }
    }

    public function filterPronesArea(Request $request) {
        // method post:
        // param 1: $request->id_province (required)
        // param 2: $request->id_city (opsional)
        // param 3: $request->id_type (opsional)
    
        $query_prones_area = DB::table('prones_area')->where('id_province', $request->id_province);        
        if ($request->filled('id_city')) {
            $query_prones_area->where('id_city', $request->id_city);
        }
        if ($request->filled('id_type')) {
            $query_prones_area->where('id_type', $request->id_type);
        }

        $prones_area = $query_prones_area->get();        
        if ($prones_area->count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $prones_area
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'No record found'
            ], 404);
        }
    }
}

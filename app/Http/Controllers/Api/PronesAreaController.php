<?php

namespace App\Http\Controllers\Api;

use App\Models\PronesArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
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

        $query__ = DB::table('prones_area')
            ->join('cities_area', 'prones_area.id_city', '=', 'cities_area.id')
            ->join('event_type', 'prones_area.id_type', '=', 'event_type.id')
            ->where('prones_area.id_province', '=', $request->id_province);

        if ($request->filled('id_city')) {
            $query__->where('prones_area.id_city', '=', $request->id_city);
        }

        if ($request->filled('id_type')) {
            $list_type = $request->input('id_type');
            $query__->where(function (Builder $query) use ($list_type) {
                $query->where('prones_area.id_type', '=', $list_type[0]);
                foreach($list_type as $type) {
                    $query->orWhere('prones_area.id_type', '=', $type);
                }
            });
        }

        $query_prones_area =  $query__->orderBy('prones_area.id_type', 'asc')
            ->select('prones_area.*', 'event_type.name AS NAMA_EVENT', 'cities_area.lat AS CITY_LATITUDE', 'cities_area.long AS CITY_LONGITUDE')
            ->get();


        if ($query_prones_area->count() < 1) {
            return response()->json([
                'status' => 200,
                'result' => 'no record found'
            ], 200);
        }

        // $query_prones_area = DB::table('prones_area')
        //     ->join('cities_area', 'prones_area.id_city', '=', 'cities_area.id')
        //     ->join('event_type', 'prones_area.id_type', '=', 'event_type.id')
        //     ->where('prones_area.id_province', '=', 23)
        //     ->where('prones_area.id_city', '=', 1630)
        //     ->where(function (Builder $query) {
        //         $query->where('prones_area.id_type', '=', 6)
        //             ->orWhere('prones_area.id_type', '=', 10);
        //     })
        //     ->orderBy('prones_area.id_type', 'asc')
        //     ->select('prones_area.*', 'event_type.name AS NAMA_EVENT', 'cities_area.lat AS CITY_LATITUDE', 'cities_area.long AS CITY_LONGITUDE')
        //     ->get();

        $groupResponseAreaObjects = [];
        $subGroupObject = [];
        
        $tempTypeId = -1;
        $tempEventName = "";
        $centerCoordinatLat = $query_prones_area[0]->CITY_LATITUDE;
        $centerCoordinatLng = $query_prones_area[0]->CITY_LONGITUDE;

        foreach($query_prones_area as $result) {
            $proneArea = new ResultProneArea($result->id, $result->id_type, $result->id_province, $result->id_city, $result->name, $result->lat, $result->long);            
            if ($result->id_type === $tempTypeId) {                 
                $subGroupObject[] = $proneArea;
            } else {
                if (count($subGroupObject) > 0) {
                    $areaObject = new ResponseArea($tempEventName, $tempTypeId, $subGroupObject);
                    $groupResponseAreaObjects[] = $areaObject;
                }

                $tempTypeId = $result->id_type;
                $tempEventName = $result->NAMA_EVENT;

                $subGroupObject = [];
                $subGroupObject[] = $proneArea;
            }
        }

        $areaObject = new ResponseArea($tempEventName, $tempTypeId, $subGroupObject);
        $groupResponseAreaObjects[] = $areaObject;

        return response()->json([
            'status' => 200,
            'zoom_level' => 10,
            'center_coordinates_latitude' => $centerCoordinatLat,
            'center_coordinates_longitude' => $centerCoordinatLng,
            'result' => $groupResponseAreaObjects,
        ], 200);
    }

    public function filterPronesAreaOld(Request $request) {
        // method post:
        // param 1: $request->id_province (required)
        // param 2: $request->id_city (opsional)
        // param 3: $request->id_type (opsional) (list)
    
        $query_prones_area = DB::table('prones_area')->where('id_province', '=', $request->id_province);

        if ($request->filled('id_city')) {
            $query_prones_area->where('id_city', '=', $request->id_city);
        }

        if ($request->filled('id_type')) {
            $list_type = $request->input('id_type');
            $query_prones_area->where(function (Builder $query) use ($list_type) {
                $query->where('id_type', '=', $list_type[0]);
                foreach ($list_type as $type) {
                    $query->orWhere('id_type', '=', $type);
                }
            });
        }

        $prones_area = $query_prones_area->orderBy('id_type', 'asc')->get();

        if ($prones_area->count() > 0) {
            $groupedData = collect($prones_area)->groupBy('id_type');

            return response()->json([
                'status' => 200,
                'result' => $groupedData
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'result' => 'No record found'
            ], 404);
        }
    }
}

class ResultProneArea {
    public $id;
    public $id_type;
    public $id_province;
    public $id_city;

    public $name;
    public $latitude;
    public $longitude;
    
    public function __construct($id, $id_type, $id_province, $id_city, $name, $latitude, $longitude) {
        $this->id = $id;
        $this->id_type = $id_type;
        $this->id_province = $id_province;
        $this->id_city = $id_city;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;                
    }

}

class ResponseArea {
    public $name_type;    
    public $id_type;
    public $list_area;

    public function __construct($name__, $id_type__, $list_area__) {
        $this->name_type = $name__;
        $this->id_type = $id_type__;
        $this->list_area = $list_area__;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getIdType() {
        return $this->id_type;
    }

    public function getListArea() {
        return $this->list_area;
    }
}


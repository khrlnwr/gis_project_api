<?php

namespace App\Http\Controllers\Api;

use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EventTypeController extends Controller
{
    public function index() {
        $event_types = EventType::all();
        if ($event_types -> count() > 0) {
            return response()->json([
                'status' => 200,
                'result' => $event_types
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
            'name' => 'required|string|max:100',
            'notes' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'result' => $validator->messages()
            ], 422);
        } else {
            $event_type = EventType::create([
                'name' => $request->name,
                'notes' => $request->notes
            ]);

            if ($event_type) {
                return response()->json([
                    'status' => 200,
                    'result' => 'Event type created successfully'
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'result' => 'Event type failed to create'
                ], 500);
            }

        }
    }

    public function show($id) {
        $event = EventType::find($id);
        if ($event) {
            return response()->json([
                'status' => 200,
                'result' => $event
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'result' => 'Event type not found'
            ], 500);

        }
    }
}

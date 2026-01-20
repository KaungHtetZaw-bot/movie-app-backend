<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'=>true,
            'data'=>Plan::all()
        ]);
    }
    public function change(Request $request,$id)
    {
        $user = Auth::user(); 

        if (!$user || $user->role_id == 1) { 
            return response()->json([
                'status' => false, 
                'message' => 'Unauthorized. Admin access required.',
                'data'    => $user
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'amount' => 'sometimes|required|integer',
            'month' => 'sometimes|required|integer',
        ]);

        $plan = Plan::findOrFail($id);

        $plan->update($validated);

        return response()->json([
            'message' => 'Plan updated successfully',
            'data'    => $plan,
        ], 200);
    }

    public function add(Request $request){
        $user = Auth::user(); 

        if (!$user || $user->role_id == 1) { 
            return response()->json([
                'status' => false, 
                'message' => 'Unauthorized. Admin access required.',
                'data'    => $user
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'amount' => 'sometimes|required|integer',
            'month' => 'sometimes|required|integer',
        ]);

        $plan = Plan::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Plan created successfully',
            'data'    => $plan
        ], 201);
    }

    public function delete($id){
        $user = Auth::user(); 

        if (!$user || $user->role_id == 1) { 
            return response()->json([
                'status' => false, 
                'message' => 'Unauthorized. Admin access required.',
                'data'    => $user
            ], 403);
        }
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Plan deleted successfully',
        ], 201);

    }
}

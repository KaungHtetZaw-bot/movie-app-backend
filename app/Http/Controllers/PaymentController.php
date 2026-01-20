<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'=>true,
            'data'=>Payment::all()
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
            'type' => 'sometimes|required|string',
            'name' => 'sometimes|required|string',
            'number' => 'sometimes|required|string',
        ]);

        $Payment = Payment::findOrFail($id);

        $Payment->update($validated);

        return response()->json([
            'message' => 'payment updated successfully',
            'data'    => $Payment,
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

        $Payment = Payment::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Payment created successfully',
            'data'    => $Payment
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
        $Payment = Payment::findOrFail($id);
        $Payment->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Payment deleted successfully',
        ], 201);

    }
}

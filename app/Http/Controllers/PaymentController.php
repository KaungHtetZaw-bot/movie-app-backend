<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Payment;
use App\Models\PaymentType;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'=>true,
            'data'   => Payment::with('paymentType')->get()
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
            'payment_type_id' => 'required|exists:payment_types,id',
            'name' => 'sometimes|required|string',
            'number' => 'sometimes|required|string',
        ]);

        $Payment = Payment::findOrFail($id);

        $Payment->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Payment account added successfully',
            'data'    => $payment->load('paymentType')
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
            'payment_type_id' => 'sometimes|required|exists:payment_types,id',
            'amount' => 'sometimes|required|integer',
            'month' => 'sometimes|required|integer',
        ]);

        $Payment = Payment::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Payment created successfully',
            'data'    => $payment->load('paymentType'),
        ], 200);
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

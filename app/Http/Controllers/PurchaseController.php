<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Plan;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'photo'   => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ]);
            
        $user = $request->user();
        $plan = Plan::findOrFail($validated['plan_id']);

        $screenshot = $request->file('photo')->store('purchases', 'public');

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'provider_id' => null,
            'status' => 'pending',
            'photo' => $screenshot,
        ]);

        return response()->json([
            'message' => 'Purchase submitted. Awaiting admin approval.',
            'purchase' => $purchase,
        ], 201);
    }

    public function approve(Request $request,$id)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:users,id',
        ]);

        $purchase = Purchase::findOrFail($id);

        if($purchase->status !== 'pending'){
            return response()->json([
                'status'  => false,
                'message' => 'Purchase already processed',
            ], 400);
        }

        $purchase->update([
            'status' => 'approved',
            'provider_id' => $validated['provider_id']
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Purchase approved successfully',
        ]);
    }
    public function reject(Request $request,$id)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:users,id',
        ]);

        $purchase = Purchase::findOrFail($id);

        if($purchase->status !== 'pending'){
            return response()->json([
                'status'  => false,
                'message' => 'Purchase already processed',
            ], 400);
        }

        $purchase->update([
            'status' => 'rejected',
            'provider_id' => $validated['provider_id']
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Purchase rejected',
        ]);
    }
}

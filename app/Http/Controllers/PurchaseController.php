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
            'photo'   => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);
            
        $user = $request->user();
        $plan = Plan::findOrFail($validated['plan_id']);

        // Correct field name
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
        ], 201); // ✅ Now Laravel WILL return 201
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\PurchaseApproved;
use App\Events\PurchaseRejected;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'=>true,
            'data'=>Purchase::all()
        ]);
    }
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
        $purchase->loadMissing('plan');

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

        $customer = User::find($purchase->user_id);

        $customer->update([
            'is_vip' => 1,
            'vip_expires_at' => $customer->vip_expires_at 
                ? Carbon::parse($customer->vip_expires_at)->addDays($purchase->plan->month) 
                : now()->addDays($purchase->plan->month),
        ]);

        $customer = $customer->fresh(['role']);

        event(new PurchaseApproved(
            $customer->id,
            $customer->toArray(),
            $purchase->id,
        ));

        return response()->json([
            'status'  => true,
            'message' => 'Purchase approved successfully',
            'customer' => $customer,
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

        event(new PurchaseRejected((int) $purchase->user_id, (int) $purchase->id));

        return response()->json([
            'status'  => true,
            'message' => 'Purchase rejected',
        ]);
    }
}

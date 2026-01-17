<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}

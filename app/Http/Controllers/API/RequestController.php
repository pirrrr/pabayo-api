<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class RequestController extends Controller
{
    public function store(Request $request)
{
    Log::info('➡️ Reached store() method');
    Log::info('📝 Incoming payload:', $request->all());

    try {
        $validated = $request->validate([
            'ownerID'       => 'required|exists:users_tbl,userID',
            'customerID'    => 'required|exists:users_tbl,userID',
            'serviceID'     => 'required|exists:services_tbl,serviceID',
            'statusID'      => 'required|exists:status_tbl,statusID',
            'pickupDate'    => 'nullable|string',
            'deliveryDate'  => 'nullable|string',
            'sackQuantity'  => 'nullable|integer',
            'comment'       => 'nullable|string|max:300',
            'modeID'       => 'required|exists:paymentmethods_tbl,modeID',
        ]);

        // Parse dates if provided
        if (!empty($validated['pickupDate'])) {
            $validated['pickupDate'] = \Carbon\Carbon::parse($validated['pickupDate'])->format('Y-m-d');
        }

        if (!empty($validated['deliveryDate'])) {
            $validated['deliveryDate'] = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['deliveryDate'])->format('Y-m-d');
        }

        // Hardcode courierID
        $validated['courierID'] = 1;

        Log::info('🧪 Final payload to insert:', $validated);

        // Create the request
        $newRequest = RequestModel::create($validated);

        Log::debug('customerID being used:', ['customerID' => $validated['customerID'] ?? null]);


        $payment = \App\Models\Payment::create([
            'requestID' => $newRequest->requestID,
            'modeID' => $validated['modeID'],
            'paymentStatusID' => 1, // e.g., "Pending"
            'paymentDate' => now(),
            'userID' => $validated['customerID'], // ✅ Required to prevent SQL error
            'amount' => 0,
        ]);


        Log::debug('🧾 Payment payload:', [
        'requestID' => $newRequest->requestID,
        'modeID' => $validated['modeID'],
        'paymentStatusID' => 1,
        'paymentDate' => now(),
        'userID' => $validated['customerID'],
    ]);


        return response()->json([
            'message' => 'Request created successfully',
            'data' => $newRequest
        ], 201);

    } catch (\Throwable $e) {
        Log::error('Request creation failed: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create request'], 500);
    }
}


    public function index(Request $request){


        $ownerID = $request->user()->userID;

    $requests = RequestModel::with([
        'customer:userID,firstName,lastName',
        'service:serviceID,serviceName',
        'payment.method:modeID,modeName'
    ])
    ->where('ownerID', $ownerID)
    ->get();

    $formatted = $requests->map(function ($req) {
        
        $firstName = $req->customer->firstName ?? '';
        $lastName = $req->customer->lastName ?? '';
        $fullName = trim("$firstName $lastName");

        // Handle service name substitution
        $serviceName = $req->service->serviceName ?? 'Unknown';
        if ($req->serviceID == 1) {
            $serviceName = 'All Services';
        }

        // Handle payment method substitution
        $paymentName = $req->payment->method->modeName ?? 'Unspecified';
        if ($req->payment && $req->payment->modeID == 1) {
            $paymentName = 'Cash on Delivery';
        } elseif ($req->payment && $req->payment->modeID == 2) {
            $paymentName = 'Gcash';
        }

        return [
            'requestID'       => $req->requestID,
            'customerName'    => $fullName ?: 'Unknown',
            'sackQuantity'    => $req->sackQuantity,
            'serviceName'     => $serviceName,
            'schedule'        => $req->pickupDate,
            'paymentMethod'   => $paymentName,
            'comment'         => $req->comment,
            'submittedAt'     => $req->dateCreated ?? $req->created_at->toDateString(),
        ];
    });

    return response()->json(['requests' => $formatted]);
}

      public function ownerRequests(Request $request)
{
    $user = $request->user();

    $requests = RequestModel::with([
        'customer:userID,firstName,lastName',
        'service:serviceID,serviceName',
        'payment.method:modeID,modeName'
    ])
    ->whereHas('service', function ($query) use ($user) {
        $query->where('ownerID', $user->userID);
    })
    ->get();

    $formatted = $requests->map(function ($req) {
        $firstName = $req->customer->firstName ?? '';
        $lastName = $req->customer->lastName ?? '';
        $fullName = trim("$firstName $lastName");

        $serviceName = $req->service->serviceName ?? 'Unknown';
        if ($req->serviceID == 1) {
            $serviceName = 'All Services';
        }

        $paymentName = $req->payment->method->modeName ?? 'Unspecified';
        if ($req->payment && $req->payment->modeID == 1) {
            $paymentName = 'Cash on Delivery';
        } elseif ($req->payment && $req->payment->modeID == 2) {
            $paymentName = 'Gcash';
        }

        return [
            'requestID'       => $req->requestID,
            'customerName'    => $fullName ?: 'Unknown',
            'sackQuantity'    => $req->sackQuantity,
            'serviceName'     => $serviceName,
            'schedule'        => $req->pickupDate ?? 'Not set',
            'paymentMethod'   => $paymentName,
            'comment'         => $req->comment,
            'submittedAt'     => $req->dateCreated ?? $req->created_at?->toDateString(),
        ];
    });

    return response()->json(['requests' => $formatted]);
}

}

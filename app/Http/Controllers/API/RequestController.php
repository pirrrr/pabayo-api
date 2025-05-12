<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class RequestController extends Controller
{
    public function store(Request $request){


        Log::info('➡️ Reached store() method');
        Log::info('📝 Incoming payload:', $request->all());

        Log::info($request->all());

        try{
            $validated = $request->validate([
            'ownerID'       => 'required|exists:users_tbl,userID',
            'customerID'     => 'required|exists:users_tbl,userID',
            'serviceID'      => 'required|exists:services_tbl,serviceID',
            'statusID'       => 'required|exists:status_tbl,statusID',
            'pickupDate'     => 'nullable|string',
            'deliveryDate'   => 'nullable|string',
            'sackQuantity'   => 'nullable|integer',
            'comment'        => 'nullable|string|max:300',
        ]);

        $snakeCased = [];
foreach ($validated as $key => $value) {
    $snakeCased[Str::snake($key)] = $value;
}



        // Format dates
        if (!empty($snakeCased['pickup_date'])) {
            $snakeCased['pickup_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $snakeCased['pickup_date'])->format('Y-m-d');
        }

        if (!empty($snakeCased['delivery_date'])) {
            $snakeCased['delivery_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $snakeCased['delivery_date'])->format('Y-m-d');
        }

         // Hardcode courier_id for now
        $snakeCased['courier_id'] = 1;


        dd($snakeCased);
        // Create the request
        $newRequest = RequestModel::create($snakeCased);

        return response()->json([
            'message' => 'Request created successfully',
            'data' => $newRequest
        ], 201);


        } catch(\Throwable $e) {
        Log::error('Request creation failed: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to create request'], 500); // ✅ Error response
    }
        
        
    }

    public function index(Request $request){

        


        $ownerID = $request->user()->userID;

    $requests = RequestModel::with([
        'customer:userID,firstName,lastName',
        'service:serviceID,serviceID,serviceName', // adjust if many-to-many
        'paymentMethod:modeID,modeName'
    ])
    ->where('ownerID', $ownerID)
    ->get();

    $formatted = $requests->map(function ($req) {
        
        $firstName = $req->customer->firstName ?? '';
        $lastName = $req->customer->lastName ?? '';
        $fullName = trim("$firstName $lastName");

        return [
            'requestID'       => $req->requestID,
            'customerName'    => $fullName ?: 'Unknown',
            'sackQuantity'    => $req->sackQuantity,
            'serviceName'     => $req->service->serviceName ?? 'Unknown',
            'schedule'        => $req->pickupDate ?? 'Not set',
            'paymentMethod'   => $req->paymentMethod->modeName ?? 'Unspecified',
            'comment'         => $req->comment,
            'submittedAt'     => $req->dateCreated ?? $req->created_at->toDateString(),
        ];
    });

    return response()->json(['requests' => $formatted]);
}

        public function ownerRequests(Request $request){
            $user = $request->user();

            $requests = RequestModel::whereHas('service', function ($query) use ($user) {
                $query->where('ownerID', $user->userID);
            })->get();

            return response()->json([
                'requests' => $requests
            ]);
        }



}

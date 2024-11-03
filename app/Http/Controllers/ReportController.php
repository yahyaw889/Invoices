<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Status;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function search(Request $request)
    {
//        dd($request->all());
        // Validate the request data
        $request->validate([
            'radio' =>'required|in:1,2',
            'type' => 'nullable|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date',
        ]);
        $start_at = $request['start_at'];
        $end_at = $request['end_at'] ?? date('Y-m-d H:i:s');
        try {


            if ($request['radio'] == 1) {
                if ($start_at) {
                    $invoices = Invoices::query()
                        ->when(is_numeric($request->input('type')), function ($query) use ($request) {
                            return $query->where('status_id', $request->input('type'));
                        })
                        ->with('section' , 'product')
                        ->whereBetween('created_at', [$start_at, $end_at])
                        ->get();
                } else {
                    $invoices = Invoices::query()
                        ->when(is_numeric($request->input('type')), function ($query) use ($request) {
                            return $query->where('status_id', $request->input('type'));
                        })
                        ->with('section' , 'product')
                        ->get();
                }
            } else {
                $invoices = Invoices::query()
                    ->where('invoice_number', 'like', '%' . $request['invoice_number'] . '%')
                    ->with('section' , 'product')
                    ->get();

            }
            // return invoice json
            return json_encode($invoices);
        }catch (\Exception $e) {
            return json_encode($e);

        }

//       return json_encode($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function invoice()
    {
        $statuses = Status::all();
        return view('reports.invoice' , compact('statuses'));
    }
}

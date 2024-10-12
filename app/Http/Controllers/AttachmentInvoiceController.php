<?php

namespace App\Http\Controllers;

use App\Models\Attachment_invoice;
use App\Models\Invoices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttachmentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($fileName)
    {

        $filePath = public_path('img/' . $fileName); // Assuming the URL directly reflects the structure

        if (file_exists($filePath) && is_file($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
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
        try {
            $invoice = Invoices::query()->findOrFail($request['invoice_id']);

            $request->validate([
                'img' => 'required|mimes:jpg,bmp,png,pdf,jpeg,gif'
            ]);


            $file = $request->file('img');
            $path = public_path('img/' . $invoice['invoice_number']);
            $file_name = $invoice['invoice_number'] . '/' . time() . rand() . '.' . $file->getClientOriginalExtension();
            if ($file->move($path, $file_name)) {
                Attachment_invoice::query()->create([
                    'invoice_id' => $request['invoice_id'],
                    'attachment' => $file_name,
                    'name' => $file_name,
                    'type' => $request->file('img')->getClientOriginalExtension(),
                    'user_id' => Auth::user()->id,
                ]);
            }
            return redirect()->back()->with('success', 'تم أضافة المرفق بنجاح');
        }catch (Exception $e) {
            return redirect()->back()->with('error', 'فشل اضافة المرفق');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment_invoice $attachment_invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment_invoice $attachment_invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment_invoice $attachment_invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment_invoice $attachment_invoice)
    {
        try {
            $attachment_invoice->delete();
            return redirect()->back()->with('success', 'تم حذف المرفق بنجاح');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'فشل حذف المرفق');
        }
    }
}

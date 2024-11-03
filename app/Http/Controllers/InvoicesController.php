<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\Attachment_invoice;
use App\Models\InvoiceDetails;
use App\Models\Invoices;
use App\Models\Section;
use App\Models\status;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;

class InvoicesController extends Controller
{



    public function __construct()
    {
        $this->middleware('permission:view-invoice')->only(['index', 'filter']);
        $this->middleware('permission:show-invoice')->only(['show']);
        $this->middleware('permission:create-invoice')->only(['create', 'store']);
        $this->middleware('permission:edit-invoice')->only(['edit', 'update']);
        $this->middleware('permission:delete-invoice')->only(['destroy']);
        $this->middleware('permission:show-deleted-invoice')->only(['archive', 'archiveFilter', 'delete', 'restore']);
        $this->middleware('permission:excel-import-invoice')->only(['export']);
        $this->middleware('permission:print-invoice')->only(['print']);
        $this->middleware('permission:edit-status-invoice')->only(['status', 'updateStatus']);
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $invoices = Invoices::query()->latest('id')->with(['section', 'product'])->get();
        $statuses = Status::all();

        return view('invoices.index', compact('invoices' ,'statuses'));
    }

    public function create()
    {
        try {
            $latestInvoice = Invoices::withTrashed()->latest('created_at')->pluck('invoice_number')->first();
            $invoice_number = $latestInvoice && is_numeric($latestInvoice) ? $latestInvoice + 1 : rand(100, 10000);

            while (Invoices::withTrashed()->where('invoice_number', $invoice_number  )->exists()) {
                $invoice_number = rand(100, 10000);
            }

            $sections = Section::all();
            return view('invoices.create', compact('invoice_number', 'sections'));
        } catch (\Exception $e) {
            return view('404');
        }
    }

    public function store(Request $request)
    {
        $checkInvoices = new Invoices();
        $request->validate($checkInvoices->validateInvoice(), $checkInvoices->validationMessages());

        try {
            $total_win = $checkInvoices->getTotal($request['amount_commission'], $request['discount'], $request['rate_vat']);
            $total = $checkInvoices->total($total_win, $request['amount_collection']);
            $vat = $checkInvoices->get_vat($request['amount_commission'], $request['discount'], $request['rate_vat']);

            if ($total <= 0) {
                return json_encode(['error' => 'خطأ في حسابات الفاتورة']);
            }

            if ($checkInvoices->error) {
                return json_encode(['error' => $checkInvoices->error]);
            }

            $created = Invoices::query()->create([
                'invoice_number' => $request->input('invoice_number'),
                'invoice_date' => $request->input('invoice_date', date('Y-m-d H:i:s')),
                'due_date' => $request['due_date'],
                'section_id' => $request['section'],
                'product_id' => $request['product'],
                'amount_collection' => $request['amount_collection'],
                'amount_commission' => $request['amount_commission'],
                'discount' => $request['discount'],
                'rate_vat' => $request['rate_vat'],
                'value_vat' => $vat,
                'total' => $total_win,
                'note' => $request->input('note', 'default'),
                'status_id' => 1
            ]);

            if (!$created) {
                return json_encode(['error' => 'error in can not create']);
            }

            $Invoice_id = Invoices::query()->latest('id')->pluck('id')->first();

            $createDetails = InvoiceDetails::query()->create([
                'invoice_id' => $Invoice_id,
                'remaining_payment' => $total,
                'total_amount' => $total,
                'total_payment' => 0,
                'status_id' => 1,
                'user_id' => Auth::user()->id,
                'payment_note' => $request->input('note', null),
            ]);

            if (!$createDetails) {
                return json_encode(['error' => 'error in can not create details']);
            }

            if ($request->hasFile('img')) {
                $request->validate([
                    'img' => 'mimes:jpg,bmp,png,pdf,jpeg,gif'
                ]);

                $file = $request->file('img');
                $path = public_path('img/' . $request['invoice_number']);
                $file_name = $request['invoice_number'] . '/' . time() . rand() . '.' . $file->getClientOriginalExtension();

                if($file->move($path, $file_name)) {
                    $createAttachments = Attachment_invoice::query()->create([
                        'invoice_id' => $Invoice_id,
                        'attachment' => $file_name,
                        'name' => $file_name,
                        'type' => $request->file('img')->getClientOriginalExtension(),
                        'user_id' => Auth::user()->id,
                    ]);
                }

                if (!isset($createAttachments) || !$createAttachments) {
                    return json_encode(['error' => 'error in can not create attachments']);
                }
            }

            $latestInvoice = $request['invoice_number'];
            $invoice_number = $latestInvoice && is_numeric($latestInvoice) ? $latestInvoice + 1 : rand(100, 10000);

            while (Invoices::withTrashed()->where('invoice_number', $invoice_number  )->exists()) {
                $invoice_number = rand(100, 10000);
            }
            return json_encode(['success' => 'تم انشاء الفاتورة بنجاح' , 'invoice_number' => $invoice_number]);

        } catch (Exception $e) {
            return json_encode(['error' => 'خطأ في تسجيل الفاتورة']);
        }
    }



    public function show(Invoices $invoice)
    {
        $details = InvoiceDetails::where('invoice_id', $invoice->id)->with(['user', 'section', 'product'])->get();
        $attachments = Attachment_invoice::where('invoice_id', $invoice->id)->with('user')->get();
        $invoices = $invoice;
        $statuses = Status::all();
        return view('invoices.show', compact('details' , 'attachments' , 'invoices' , 'statuses'));
    }
    public function edit(Invoices $invoice)
    {
        $sections = Section::all();
        return view('invoices.edit', compact('invoice' , 'sections'));
    }
    public function update(Request $request, Invoices $invoice)
    {
        $invoices = new Invoices();

        $request->validate($invoices->validateInvoice());
        try {

            DB::beginTransaction();



            $total_win = $invoices->getTotal($request['amount_commission'], $request['discount'], $request['rate_vat']);
            $vat = $invoices->get_vat($request['amount_commission'], $request['discount'], $request['rate_vat']);
            $total = $invoices->total($total_win , $request['amount_collection']);
            if ($invoices->error) {
                DB::commit();
                return redirect()->back()->with('error', $invoices->error);
            }


            $details =  InvoiceDetails::query()->latest()->where('invoice_id', $invoice->id)->first();
            if ($details){
                if($details->total_payment > $total) {
                    DB::commit();
                    return redirect()->back()->withErrors(['error' => 'المبلغ المتلقي اصغر من المجموع المدفوع']);
                }
                if( $details->total_payment < $total ){
                    $invoice->status_id == 2 || $invoice->status_id == 4 ? $newStatus = 4 : $newStatus = 1;
                }else if($details->total_payment == $total){
                    $newStatus = $invoice->status_id;
                }else{
                    $newStatus = 5;
                }
            }else{
                $newStatus = $invoice->status_id;
            }

            if ($details->total_payment != $total){
                $createDetails = InvoiceDetails::query()->create([
                    'invoice_id' => $invoice->id,
                    'remaining_payment' => ($total - $details->total_amount) + $details->remaining_payment,
                    'total_amount'  => $total,
                    'total_payment' =>$details->total_payment ,
                    'status_id' => $newStatus,
                    'user_id' => Auth::user()->id,
                    'payment_note' => $request->input('note', null),
                ]);
                if (!$createDetails) {
                    DB::commit();
                    return redirect()->back()->withErrors('error', 'خطأ في انشاء تفاصيل الفاتورة');
                }
            }

            $invoice->update([
                'invoice_number' => $request->input('invoice_number'),
                'invoice_date' => $request->input('invoice_date', date('Y-m-d H:i:s')),
                'due_date' => $request['due_date'],
                'section_id' => $request['section'],
                'product_id' => $request['product'],
                'amount_collection' => $request['amount_collection'],
                'amount_commission' => $request['amount_commission'],
                'discount' => $request['discount'],
                'rate_vat' => $request['rate_vat'],
                'value_vat' => $vat,
                'total' => $total_win,
                'note' => $request->input('note', 'default'),
                'status_id' => $newStatus
            ]);
            DB::commit();

                        return redirect('invoices/')->with('success' , 'تم تحديث الفاتوة بنجاح');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating invoice: '. $e->getMessage());
            return redirect()->back()->with('error', 'خطأ في تحديث الفاتورة');
        }


    }
    public function destroy(Invoices $invoice)
    {

        try {

            if ($invoice->trashed()) {
                return redirect()->back()->with('success', 'الفتورة محذوفة بالفعل');
            }
            DB::beginTransaction();
            $invoice->delete();
            DB::commit();
            return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
        }catch (\Exception $e) {
            DB::rollBack();
            // Handle any exception and log for debugging
            Log::error('Error deleting invoice: '. $e->getMessage());
            return redirect()->back()->with('error', 'فشل حذف الفاتورة');
        }
    }
    public function getProducts($id): false|string
    {
        $state = DB::table('products')->where('section_id', $id)->pluck('id' , 'name');
        return json_encode($state);
    }
    public function status(Request $request)
    {
            $invoices = Invoices::query()->findOrFail($request->id);
            $statuses = status::all();
            $details = InvoiceDetails::query()->latest()->where('invoice_id', $invoices->id)->first();
        return view('invoices.status' , compact('invoices' , 'statuses' , 'details'));
    }
    public function updateStatus(Request $request)
    {
        $request->validate([
            'payment_date' =>'required|date',
            'status' => 'required|exists:statuses,id',
            ]);
//                dd($request->payment_method);
            if ($request['status'] == 4) {
                $request->validate([
                    'total_payment' => 'required|numeric',
                    'payment_method' => 'required|string',
                    'payment_note' => 'nullable|string',
                ]);
            } elseif ($request['status'] == 2) {
                $request->validate([
                    'payment_method1' => 'required|string',
                    'payment_note' => 'nullable|string',
                ], [
                    'payment_method.required' => 'The payment method is required.',
                ]);
            }

        try {

            $invoice = Invoices::query()->findOrFail($request->id);
            $detail = InvoiceDetails::query()->latest()->where('invoice_id' , $invoice->id)->first();
            if ($invoice['total'] <= 0 || $invoice->amount_collection < 0 ) {
                        $invoice->update(['status_id' => 5]);
                   return  redirect()->route('invoices.index')->with('error', 'المبلغ المدفوع يحتوي علي اخطاء');
            }else {


                if ($request['status'] == 4) {
                          if ($detail->remaining_payment < $request['total_payment'] ) {
                              return  redirect()->back()->with('error', 'المبلغ المدفوع يتعدى المبلغ الكلي');
                          }
                    if ($detail->remaining_payment - $request['total_payment'] == 0){
                            $status = 2;
                    }else{
                        $status = $request['status'];
                    }
                    $invoice->update([
                        'status_id' => $status,
                        'payment_date' => $request['payment_date']
                    ]);
                    InvoiceDetails::query()->create([
                        'total_payment' => $request['total_payment'],
                        'payment_method' => $request['payment_method'],
                        'status_id' => $status,
                        'payment_date' => $request['payment_date'],
                        'invoice_id' => $request->id,
                        'user_id' => Auth::user()->id,
                        'total_amount'  => $detail->total_amount,
                        'payment_note' => $request['payment_note'],
                        'remaining_payment' => ($detail->remaining_payment - $request['total_payment'])
                    ]);

                } elseif ($request['status'] == 2) {

                    $invoice->update([
                        'status_id' => $request['status'],
                        'payment_date' => $request['payment_date']
                    ]);
                    InvoiceDetails::query()->create([
                        'total_payment' => $detail->remaining_payment,
                        'payment_method' => $request['payment_method1'],
                        'status_id' => $request['status'],
                        'payment_date' => $request['payment_date'],
                        'total_amount'  => $detail->total_amount,
                        'invoice_id' => $request->id,
                        'user_id' => Auth::user()->id,
                        'payment_note' => $request['payment_note'],
                        'remaining_payment' => 0,
                    ]);
                        $users = User::where('status' , 1)->get();
                    Notification::send($users, new InvoicePaid($invoice , $invoice->total + $invoice->amount_collection));


                } else {
//                        $request['status'] == 1 || $request['status'] == 3 ? $remaining_payment =    $invoice->total + $invoice->amount_collection : $remaining_payment =0;

                    $invoice->update([
                        'status_id' => $request['status'],
                        'payment_date' => $request['payment_date']
                    ]);
                    InvoiceDetails::query()->create([
                        'status_id' => $request['status'],
                        'payment_date' => $request['payment_date'],
                        'total_amount'  => $detail->total_amount,
                        'invoice_id' => $request->id,
                        'user_id' => Auth::user()->id,
                        'payment_note' => $request['payment_note'],
                        'remaining_payment' => $detail->remaining_payment?? 0,
                    ]);
                }
            }

            return redirect()->route('invoices.print' , $invoice->id)->with('success', 'تم تحديث حالة الفاتورة بنجاح');
        }catch (Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'خطأ في تحديث حالة الفاتورة');
        }
    }
    public function filter(Request $request){
      $invoices =   Invoices::query()->with('section' , 'product')->where('status_id' ,  $request->id)->get();
      $statuses = Status::all();
      return view('invoices.index' , compact('invoices' , 'statuses'));
    }
    public function archive(){
        $invoices = Invoices::query()->latest()->with('product', 'section')->onlyTrashed()->get();
        $statuses = Status::all();
        return view('invoices.archive', compact('invoices', 'statuses'));
    }
    public function archiveFilter(Request $request){
        $invoices =   Invoices::query()->with('section' , 'product')->where('status_id' ,  $request->id)->onlyTrashed()->get();
        $statuses = Status::all();
        return view('invoices.archive' , compact('invoices' , 'statuses'));
    }
    public function restore(Request $request)
    {
        try {

            $invoice = Invoices::withTrashed()->where('id', $request->id)->first();
            $invoice->restore();
            return redirect()->route('invoices.archive')->with('success', 'تم استعادة الفاتورة بنجاح');
        }catch (\Exception $e) {
            return redirect()->route('invoices.archive')->with('error', 'خطأ في استعادة الفاتورة');
        }


    }
    public function delete(Request $request)
    {
        $invoice = Invoices::onlyTrashed()->where('id', $request->id)->firstOrFail();

        try {

            $directory = public_path('img/' . $invoice->invoice_number);

            // Check if the directory exists before trying to delete it
            if (File::exists($directory) && File::isDirectory($directory)) {
                File::deleteDirectory($directory);
            }

            $invoice->forceDelete();

            return redirect()->route('invoices.archive')->with('success', 'تم حذف الفاتورة بشكل نهائي بنجاح');
        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Error deleting invoice: ' . $e->getMessage());

            return redirect()->route('invoices.archive')->with('error', 'خطأ في حذف الفاتورة');
        }
    }
    public function print(Invoices $invoices)
    {
        $details = InvoiceDetails::query()->latest()->where('invoice_id' , $invoices->id)->first();
        return view('invoices.print' , compact('invoices' ,'details'));
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'invoices.xlsx');
    }

//    public function store(Request $request)
//    {
//        $checkInvoices = new Invoices();
//        $request->validate($checkInvoices->validateInvoice(), $checkInvoices->validationMessages());
//
//        try {
//
//            $total_win = $checkInvoices->getTotal($request['amount_commission'], $request['discount'], $request['rate_vat']);
//            $total = $checkInvoices->total($total_win , $request['amount_collection']);
//            $vat = $checkInvoices->get_vat($request['amount_commission'], $request['discount'], $request['rate_vat']);
//            if ($total <=  0){
//                return redirect()->back()->withErrors('error', 'خطأ في حسابات الفاتورة');
//            }
//            if ($checkInvoices->error) {
//                return redirect()->back()->withErrors('error', $checkInvoices->error);
//            }
//            $created = Invoices::query()->create([
//                'invoice_number' => $request->input('invoice_number'),
//                'invoice_date' => $request->input('invoice_date', date('Y-m-d H:i:s')),
//                'due_date' => $request['due_date'],
//                'section_id' => $request['section'],
//                'product_id' => $request['product'],
//                'amount_collection' => $request['amount_collection'],
//                'amount_commission' => $request['amount_commission'],
//                'discount' => $request['discount'],
//                'rate_vat' => $request['rate_vat'],
//                'value_vat' => $vat,
//                'total' => $total_win,
//                'note' => $request->input('note', 'default'),
//                'status_id' => 1
//            ]);
//
//            if (!$created) {
//                return redirect()->back()->withErrors('error', 'error in can not create ');
//            }
//
//            $Invoice_id = Invoices::query()->latest('created_at')->pluck('id')->first();
//            $createDetails = InvoiceDetails::query()->create([
//                'invoice_id' => $Invoice_id,
//                'remaining_payment' => $total,
//                'total_amount'  => $total,
//                'total_payment' => 0,
//                'status_id' => 1,
//                'user_id' => Auth::user()->id,
//                'payment_note' => $request->input('note', null),
//            ]);
//
//            if (!$createDetails) {
//                return redirect()->back()->withErrors('error', 'error in can not create details ');
//            }
//
//            if ($request->hasFile('img')) {
//
//                $request->validate([
//                    'img' => 'mimes:jpg,bmp,png,pdf,jpeg,gif'
//                ]);
//                $file = $request->file('img');
//                $path = public_path('img/' . $request['invoice_number']);
//                $file_name = $request['invoice_number'] . '/' . time() . rand() . '.' . $file->getClientOriginalExtension();
//                if($file->move($path , $file_name)) {
//
//                    $createAttachments = Attachment_invoice::query()->create([
//                        'invoice_id' => $Invoice_id,
//                        'attachment' => $file_name,
//                        'name' => $file_name,
//                        'type' => $request->file('img')->getClientOriginalExtension(),
//                        'user_id' => Auth::user()->id,
//                    ]);
//                }
//                if (!isset($createAttachments) || !$createAttachments) {
//                    return redirect()->back()->withErrors('error', 'error in can not create attachments ');
//                }
//            }
//
//
//            return redirect()->back()->with('success', 'تم انشاء الفاتورة بنجاح');
//        }catch (Exception $e){
////                Log::error('Error updating invoice: '. $e->getMessage());
//
//            return redirect()->back()->with('error', 'خطأ في تسجيل الفاتورة');
//        }
//    }


}




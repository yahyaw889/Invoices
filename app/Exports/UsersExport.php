<?php

namespace App\Exports;

use App\Models\Invoices;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
//        return User::all();
        return Invoices::query()->with('section', 'product', 'status')->get();
    }
}

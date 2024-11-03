<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request): false|string
    {

        $search = [
            1 => Invoices::query()->where('invoice_number' , 'like' , '%' . $request['search'] . '%')->first(),
            2 => Section::query()->where('title' , 'like' , '%' . $request['search'] . '%')->first(),
            3 => Product::query()->where('name' , 'like' , '%' . $request['search'] . '%')->first(),
            4 => User::query()->where('name' , 'like' , '%' . $request['search'] . '%')->first(),
            5 => User::query()->where('email' , 'like' , '%' . $request['search'] . '%')->first(),
        ];

        return json_encode($search);

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Status;
use App\Models\System;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $data = Cache::remember('dashboard_data', 60*60, function () {
            return [
                
                'created_all_invoices' => Invoices::withTrashed()->sum(DB::raw('amount_collection + total')),

                'count_invoices'            => Invoices::query()->count(),

                'count_unpaid'              => Invoices::query()->where('status_id', '1')->count(),
                'count_paid'                => Invoices::query()->where('status_id', '2')->count(),
                'count_paid_part'           => Invoices::query()->where('status_id', '4')->count(),
                'sum_total'                 => Invoices::query()->where('status_id', '!=', '5')->sum(DB::raw('amount_collection + total')),

                'sum_unpaid'                => Invoices::query()->where('status_id', '1')->sum(DB::raw ('amount_collection + total')),

                'sum_paid' => Invoices::query()->where('status_id', '2')->sum(DB::raw('amount_collection + total')),

                'sum_paid_part' => Invoices::query()->where('status_id', '4')->sum(DB::raw('amount_collection + total')),

                'total_count_months'            => $this->getInvoiceCountsByMonth(),
                'total_unpaid_count_months'     => $this->getInvoiceCountsByMonth(1),
                'total_paid_count_months'       => $this->getInvoiceCountsByMonth(2),
                'total_paid_part_count_months'  => $this->getInvoiceCountsByMonth(4),

                'total_wins'                    => Invoices::query()->where('status_id', '2')->sum('total'),

                'total_wins_month'              => Invoices::query()->where('status_id', '2')->whereMonth('created_at', Carbon::now()->month)->sum('total'),

                'new_users'                     => User::query()->whereMonth('created_at', Carbon::now()->month)->where('status' , '1')->count(),
                'all_users'                     => User::query()->count(),
                'app_views'                     => System::query()->pluck('views')->first(),
            ];
        });

        return view('dashboard.index' , [
                'sum_total'             => $data['sum_total'],
                'sum_unpaid'            => $data['sum_unpaid'] ,
                'sum_paid_part'         => $data['sum_paid_part'],
                'sum_paid'              => $data['sum_paid'] ,
                'created_all_invoices'  => $data['created_all_invoices'] ,
                'count_invoices'        => $data['count_invoices'],
                'count_unpaid'          => $data['count_unpaid'] ,
                'count_paid'            => $data['count_paid'],
                'count_paid_part'       => $data['count_paid_part'],
                'total_count_months'    => $data['total_count_months'],
                'total_unpaid_count_months'      => $data['total_unpaid_count_months'],
                'total_paid_count_months'        => $data['total_paid_count_months'],
                'total_paid_part_count_months'   => $data['total_paid_part_count_months'],
                'total_wins'            => $data['total_wins'],
                'total_wins_month'      => $data['total_wins_month'],
                'new_users'             => $data['new_users'],
                'all_users'             => $data['all_users'],
                'app_views'             => $data['app_views'],

            ]);
    }
    protected function getInvoiceCountsByMonth($statusId = 1): array
    {
        $counts = [];
        for ($month = 1; $month <= 12; $month++) {
            $query = Invoices::query()->whereMonth('created_at', $month);
            if ($statusId) {
                $query->where('status_id', $statusId);
            }
            $counts[] = $query->count();
        }
        return $counts;
    }

}


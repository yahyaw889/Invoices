@extends('layouts.master')
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">اهلا. مرحبا بك مجددا</h2>
                <p class="mg-b-0">هذا البرامج لإدارة الفواتير.</p>
            </div>
        </div>
        <div class="main-dashboard-header-right">
            <div>
                <label class="tx-13">Customer Ratings</label>
                <div class="main-star">
                    <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i> <span>(14,873)</span>
                </div>
            </div>

        </div>
    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">إجمالي الفواتير</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{formatNumberShort($sum_total , 2)}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">{{round(($sum_total / $created_all_invoices) * 100 , 2) }}%</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7"> +{{$count_invoices}}</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">{{ implode(',', $total_count_months) }}</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">إجمالي الفواتير الغير مدفوعة</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{formatNumberShort($sum_unpaid , 2)}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">{{round(($sum_unpaid / $created_all_invoices) * 100 , 2) }}%</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7"> -{{$count_unpaid}}</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">{{ implode(',', $total_unpaid_count_months) }}</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">إجمالي الفواتير المدفوعة</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{formatNumberShort($sum_paid , 2)}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">{{round(($sum_paid / $created_all_invoices) * 100 , 2) }}%</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7"> +{{$count_paid}}</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">{{ implode(',', $total_paid_count_months) }}</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">الفواتير المدفوعة جزئيا</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{formatNumberShort($sum_paid_part , 2)}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">{{round(($sum_paid_part / $created_all_invoices) * 100 , 2) }}%</p>
                            </div>
                            <span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7"> +{{$count_paid_part}}</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">{{ implode(',', $total_paid_part_count_months) }}</span>
            </div>
        </div>
    </div>
    <!-- row closed -->
    <div class="row row-sm">
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="">App Views</div>
                            <div class="h3 mt-2 mb-2"><b>{{formatNumberShort($app_views)}}</b><span class="text-success tx-13 ml-2">(+25%)</span></div>
                        </div>
                        <div class="col-auto align-self-center ">
                            <div class="feature mt-0 mb-0">
                                <i class="fe fe-eye project bg-primary-transparent text-primary "></i>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="mb-1">عدد المشاهدين الابلكيشن</p>
                        <div class="progress progress-sm h-1 mb-1">
                            <div class="progress-bar bg-primary wd-80 " role="progressbar"></div>
                        </div>
                        <small class="mb-0 text-muted">شهريا<span class="float-left text-muted">60%</span></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="">المستخدمين الجدد</div>
                            <div class="h3 mt-2 mb-2"><b>{{formatNumberShort($new_users)}}</b><span class="text-success tx-13 ml-2">(+15%)</span></div>
                        </div>
                        <div class="col-auto align-self-center ">
                            <div class="feature mt-0 mb-0">
                                <i class="fe fe-users project bg-pink-transparent text-pink "></i>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="mb-1">المستخدمين لاخر شهر فقط</p>
                        <div class="progress progress-sm h-1 mb-1">
                            <div class="progress-bar bg-secondary wd-50 " role="progressbar"></div>
                        </div>
                        <small class="mb-0 text-muted">شهريا<span class="float-left text-muted">50%</span></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="">عدد المستخدمين الكلي</div>
                            <div class="h3 mt-2 mb-2"><b>{{formatNumberShort($all_users)}}</b><span class="text-success tx-13 ml-2">(+08%)</span></div>
                        </div>
                        <div class="col-auto align-self-center ">
                            <div class="feature mt-0 mb-0">
                                <i class="ti-pulse project bg-warning-transparent text-warning "></i>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="mb-1">عدد التفاعلات اليومية</p>
                        <div class="progress progress-sm h-1 mb-1">
                            <div class="progress-bar bg-danger wd-30 " role="progressbar"></div>
                        </div>
                        <small class="mb-0 text-muted">يوميا<span class="float-left text-muted">30%</span></small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="">صافي الربح</div>
                            <div class="h3 mt-2 mb-2"><b>${{formatNumberShort($total_wins)}}</b><span class="text-success tx-13 ml-2">(+35%)</span></div>
                        </div>
                        <div class="col-auto align-self-center ">
                            <div class="feature mt-0 mb-0">
                                <i class="ti-bar-chart-alt project bg-success-transparent text-success "></i>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="mb-1">الربح لاخر شهر فقط</p>
                        <div class="progress progress-sm h-2 mb-1">
                            <div class="progress-bar bg-success wd-{{round(($total_wins_month / $total_wins) * 100) > 50 ? 50 : 25 }} " role="progressbar"></div>
                        </div>
                        <small class="mb-0 text-muted">شهريا<span class="float-right text-muted">{{ round(($total_wins_month / $total_wins) * 100 , 2) }}%</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">حالات الفواتير</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 text-muted mb-0">عدد الفواتير الصالحة في كل شهور السنة  </p>
                </div>
                <div class="card-body">
                    <div class="total-revenue">
                        <div>
                            <h4 class="text-center">{{formatNumberShort($count_invoices , 2)}}</h4>
                            <label><span class="bg-primary"></span>فواتير كلها</label>
                        </div>
                        <div>
                            <h4 class="text-center">{{formatNumberShort($count_unpaid , 2)}}</h4>
                            <label><span class="bg-danger"></span>فواتير غير المدفوعة</label>
                        </div>
                        <div>
                            <h4 class="text-center">{{formatNumberShort($count_paid , 2)}}</h4>
                            <label><span class="bg-success"></span>فواتير المدفوعة</label>
                        </div>
                        <div>
                            <h4 class="text-center">{{formatNumberShort($count_paid_part , 2)}}</h4>
                            <label><span class="bg-warning"></span>فواتير المدفوعة جزئيا</label>
                        </div>
                    </div>
                    <div id="bar_testing" class="sales-bar mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- row closed -->
    <!-- row opened -->
    <!-- row close -->

    <!-- row opened -->
    <!-- /row -->
    </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
    <!-- Moment js -->
    <script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
    <!--Internal  Flot js-->
    <script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
    <script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
    <script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
    <!--Internal Apexchart js-->
    <script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
    <!-- Internal Map -->
{{--    <script src="{{URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>--}}
{{--    <script src="{{URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>--}}
    <script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
    <!--Internal  index js -->
    <script src="{{URL::asset('assets/js/index.js')}}"></script>
    <script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>

    <script>
        var optionsBar = {
            chart: {
                height: 249,
                type: 'bar',
                toolbar: {
                    show: false,
                },
                fontFamily: 'Nunito, sans-serif',
                // dropShadow: {
                //   enabled: true,
                //   top: 1,
                //   left: 1,
                //   blur: 2,
                //   opacity: 0.2,
                // }
            },
            colors: ["#036fe7", '#f93a5a', '#f7a556' , 'green'],
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: false
                    },
                    columnWidth: '42%',
                    endingShape: 'rounded',
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                endingShape: 'rounded',
                colors: ['transparent'],
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    stroke: {
                        show: true,
                        width: 1,
                        endingShape: 'rounded',
                        colors: ['transparent'],
                    },
                },


            }],

            series: [{
                name: 'all_invoices',
                data: @json($total_count_months)
            }, {
                name: 'unpaid',
                data: @json($total_unpaid_count_months)
            }, {
                name: 'paid_part',
                data: @json($total_paid_part_count_months)
            },
                {
                name: 'paid',
                data: @json($total_paid_count_months)
            }
            ],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            fill: {
                opacity: 1
            },
            legend: {
                show: false,
                floating: true,
                position: 'top',
                horizontalAlign: 'left',
                // offsetY: -36

            },
            // title: {
            //   text: 'Financial Information',
            //   align: 'left',
            // },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        }

        new ApexCharts(document.querySelector('#bar_testing'), optionsBar).render();

    </script>
@endsection

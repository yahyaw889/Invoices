<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item">

    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/brand/logo.png') }}"
                        class="logo-1" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/brand/logo-white.png') }}"
                        class="dark-logo-1" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}"
                        class="logo-2" alt="logo"></a>
                <a href="{{ url('/' . ($page = 'index')) }}"><img src="{{ URL::asset('assets/img/brand/favicon.png') }}"
                        class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block">
                <input class="form-control" placeholder="Search for anything..." id="search" type="search">
                <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
                <ul id="searchResults" class="list-group" style="display: none; position: absolute; z-index: 1000;"></ul> <!-- Dropdown for suggestions -->
            </div>


        </div>
        <div class="main-header-right">
            <ul class="nav">
                <li class="">
                    <div class="dropdown  nav-itemd-none d-md-flex">
                        <a href="#" class="d-flex  nav-item nav-link pl-0 country-flag1" data-toggle="dropdown"
                            aria-expanded="false">
                            <span class="avatar country-Flag mr-0 align-self-center bg-transparent"><img
                                    src="{{ URL::asset('assets/img/flags/egypt_flag.jpg') }}" alt="img"></span>
                            <div class="my-auto">
                                <strong class="mr-2 ml-2 my-auto">English</strong>
                            </div>
                        </a>
                    </div>
                </li>
            </ul>
            <div class="nav nav-item  navbar-nav-right ml-auto">

                <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default nav-link resp-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="dropdown nav-item main-header-notification">
                    <a class="new nav-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg><span class=" pulse"></span></a>
                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-right">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">الإشعارات</h6>
                                <a href="{{url('/read-notifications')}}">
                                <span class="badge badge-pill badge-warning mr-auto my-auto float-left">اضغط لقراءة
                                    الكل</span></a>
                            </div>
                            @if (Auth::user()->unreadNotifications->count() == 0)
                                <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12">لا يوجد اشعارات
                                    غير مقرئة</p>
                            @else
                                <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12">
                                    عدد الإشعارات الغير مقرئة: {{ Auth::user()->unreadNotifications->count() }}
                                </p>
                            @endif
                        </div>

                        <div class="main-notification-list Notification-scroll">
                            @foreach (Auth::user()->unreadNotifications as $notification)
                                <a class="d-flex p-3 border-bottom"
                                    href="{{ url('invoices/' . $notification->data['invoice']) }}">
                                    <div class="notifyimg bg-primary">
                                        <i class="la la-check-circle text-white"></i>
                                    </div>
                                    <div class="mr-3">
                                        <h5 class="notification-label mb-1"> {{ $notification->data['message'] }} </h5>
                                        <div class="notification-subtext">
                                            {{ $notification->created_at->format('Y-m-d') }}</div>
                                    </div>
                                    <div class="mr-auto">
                                        <i class="las la-angle-left text-left text-muted"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>


                    </div>
                </div>
                <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                            class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                            </path>
                        </svg></a>
                </div>
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href=""><img alt=""
                            src="{{ URL::asset('assets/img/faces/6.jpg') }}"></a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user"><img alt=""
                                        src="{{ URL::asset('assets/img/faces/6.jpg') }}" class=""></div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ Auth::user()->name }}</h6><span>{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href=""><i class="bx bx-user-circle"></i>Profile</a>
                        <a class="dropdown-item" href=""><i class="bx bx-cog"></i> Edit Profile</a>
                        <a class="dropdown-item" href=""><i class="bx bxs-inbox"></i>Inbox</a>
                        <a class="dropdown-item" href=""><i class="bx bx-envelope"></i>Messages</a>
                        <a class="dropdown-item" href=""><i class="bx bx-slider-alt"></i> Account Settings</a>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="bx bx-log-out"></i> تسجيل خروج</a>
                    </div>
                </div>
                <div class="dropdown main-header-message right-toggle">
                    <a class="nav-link pr-0" data-toggle="sidebar-left" data-target=".sidebar-left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="/logout" method="POST" id="logout-form">
    @csrf
</form>
<div class="search" id="div_search" style="">
    <select name="search" id="searchResult">
                <option value="">yahya</option>
    </select>
</div>


@section('js')
<script>
    $(document).ready(function() {
    $('#search').on('input', function() {
        var query = $(this).val();

        // Only send a request if the input is not empty
        if (query.length > 0) {
            $.ajax({
                url: '{{ route('search') }}', // Replace with your route
                type: 'GET',
                data: { search: query }, // Send input query
                success: function(response) {
                    console.log(response);

                    response = JSON.parse(response);
                    $('#searchResults').empty();

                    if (response.length > 0) {

                        $.each(response, function(index, item) {
                            $('#searchResults').append(
                                $('<li>', {
                                    class: 'list-group-item',
                                    text: item.name,x
                                    'data-id': item.id // If you want to store the ID
                                })
                            );
                        });
                        $('#searchResults').show(); // Show the dropdown
                    } else {
                        $('#searchResults').hide(); // Hide if no results
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText); // Handle errors
                }
            });
        } else {
            $('#searchResults').hide(); // Hide dropdown if input is empty
        }
    });

    // Handle selection from suggestions
    $('#searchResults').on('click', 'li', function() {
        $('#search').val($(this).text()); // Set input value to selected
        $('#searchResults').hide(); // Hide dropdown after selection
    });

    // Hide results when clicking outside
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.main-header-center').length) {
            $('#searchResults').hide(); // Hide if clicked outside
        }
    });
});

</script>
@endsection


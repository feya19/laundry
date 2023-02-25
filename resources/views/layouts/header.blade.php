<!-- BEGIN Header -->
@php
    $outlet = session('outlets_id') ? App\Models\Outlet::find(session('outlets_id')) : '';
@endphp
<div class="header">
    <!-- BEGIN Header Holder -->
    <div class="header-holder header-holder-desktop sticky-header" id="sticky-header-desktop">
        <div class="header-container container-fluid">
            <div class="header-wrap">
                <!-- BEGIN Nav -->
                @if(isset($sidebar))
                    @if(!$sidebar)
                    <a href="{{ route('home') }}" class="mr-3">
                        <img src="{{ asset('assets/images/logo_dark.png') }}" alt="Logo" class="img-fluid" id="logo-dark" style="max-height: 4rem">
                        <img src="{{ asset('assets/images/logo_light.png') }}" alt="Logo" class="img-fluid" id="logo-light" style="max-height: 4rem">
                    </a>
                    @endif
                @endif
                <strong>{{ App\Library\Locale::humanDateDisplay(now()); }}</strong>
                <!-- END Nav -->
            </div>
            <div class="ml-auto header-wrap">
                <div id="outlet" style="cursor: pointer;" onclick="window.location.href='{{route('selectOutlet')}}'">
                    <i class="fa fa-store"></i><strong class="mx-2">{{$outlet ? $outlet->nama : 'Pilih Outlet'}}</strong>
                </div>
                <div class="dropdown ml-2">
                    <button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                        <div class="widget13-text"> Hi <strong>{{auth()->user()->name}}</strong>
                        </div>
                        <!-- BEGIN Avatar -->
                        @php
                        $alt_img = '<div class="avatar bg-purple widget13-avatar">
                                        <div class="avatar-display">
                                            <i class="fa fa-user-alt"></i>
                                        </div>
                                    </div>';
                        if(isset(auth()->user()->photo)){
                            $path = "upload/profile/".auth()->user()->photo;
                            if(file_exists(public_path($path))){
                                echo '<img src="'.asset($path).'" alt="" style="max-height: 30px;">';
                            }else{
                                echo $alt_img;
                            }
                        }else{
                            echo $alt_img;
                        }
                        @endphp
                        <!-- END Avatar -->
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                        <!-- BEGIN Portlet -->
                        <div class="portlet border-0">
                            <div class="portlet-body rounded-0 p-0">
                                <!-- BEGIN Rich List Item -->
                                <div class="rich-list-item w-20 p-0">
                                    <div class="rich-list-content">
                                        <div class="settings">
                                            <a href="{{ route('settings') }}" tabindex="0" target="_blank" role="menuitem" class="dropdown-item">
                                                <span class="dropdown-icon"><i data-feather="settings"></i></span>
                                                <span class="dropdown-content">Pengaturan</span>
                                            </a>
                                        </div>
                                        <div class="logout">
                                            {!! Form::open(['route' => 'logout', 'method' => 'POST']) !!}
                                            <a href="#"  onclick="$(this).closest('form').submit()" tabindex="0" role="menuitem" class="dropdown-item">
                                                <span class="dropdown-icon"><i data-feather="log-out"></i></span>
                                                <span class="dropdown-content">Keluar</span>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Dropdown -->
            </div>
        </div>
    </div>
    <!-- END Header Holder -->
    <!-- BEGIN Header Holder -->
    <div class="header-holder header-holder-mobile sticky-header" id="sticky-header-mobile">
        <div class="header-container container-fluid">
            <div class="header-wrap">
                <button class="btn btn-flat-primary btn-icon" data-toggle="aside">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <div class="header-wrap header-wrap-block justify-content-start px-3">
                <div id="outlet" style="cursor: pointer;" onclick="window.location.href='{{route('selectOutlet')}}'">
                    <p style="width: 200px !important;" class="mb-0 text-truncate"><i class="fa fa-store"></i><span class="mx-2">{{$outlet ? $outlet->nama : 'Pilih Outlet'}}</span></p>
                </div>
            </div>
            <div class="header-wrap">
                <!-- BEGIN Dropdown -->
                <div class="dropdown ml-2">
                    <button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                        <div class="widget13-text"> Hi <strong>{{auth()->user()->name}}</strong>
                        </div>
                        <!-- BEGIN Avatar -->
                        @php
                        $alt_img = '<div class="avatar bg-purple widget13-avatar">
                                        <div class="avatar-display">
                                            <i class="fa fa-user-alt"></i>
                                        </div>
                                    </div>';
                        if(isset(auth()->user()->photo)){
                            $path = "upload/profile/".auth()->user()->photo;
                            if(file_exists(public_path($path))){
                                echo '<img src="'.asset($path).'" alt="" style="max-height: 30px;">';
                            }else{
                                echo $alt_img;
                            }
                        }else{
                            echo $alt_img;
                        }
                        @endphp
                        <!-- END Avatar -->
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                        <!-- BEGIN Portlet -->
                        <div class="portlet border-0">
                            <div class="portlet-body rounded-0 p-0">
                                <!-- BEGIN Rich List Item -->
                                <div class="rich-list-item w-20 p-0">
                                    <div class="rich-list-content">
                                        <div class="settings">
                                            <a href="{{ route('settings') }}" tabindex="0" target="_blank" role="menuitem" class="dropdown-item">
                                                <span class="dropdown-icon"><i data-feather="settings"></i></span>
                                                <span class="dropdown-content">Pengaturan</span>
                                            </a>
                                        </div>
                                        <div class="logout">
                                            {!! Form::open(['route' => 'logout', 'method' => 'POST']) !!}
                                            <a href="#"  onclick="$(this).closest('form').submit()" tabindex="0" role="menuitem" class="dropdown-item">
                                                <span class="dropdown-icon"><i data-feather="log-out"></i></span>
                                                <span class="dropdown-content">Keluar</span>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Header Holder -->
    <!-- BEGIN Header Holder -->
    <div class="header-holder header-holder-mobile">
        <div class="header-container container-fluid">
            <div class="header-wrap header-wrap-block justify-content-start w-100">
                <!-- BEGIN Breadcrumb -->
                <div class="breadcrumb">
                    <a href="{{route('home')}}" class="breadcrumb-item">
                        <div class="breadcrumb-icon">
                            <i data-feather="home"></i>
                        </div>
                        <span class="breadcrumb-text">Dashboard</span>
                    </a>
                </div>
                <!-- END Breadcrumb -->
            </div>
        </div>
    </div>
    <!-- END Header Holder -->
</div>
<!-- END Header -->
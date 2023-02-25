@php 
    $role = auth()->user()->role;
    $rolePermission = config('permission.'.$role);
@endphp
<div class="aside">
    <div class="aside-header" style="min-height: 5rem;max-height: 5rem">
        <a href="{{ route('home') }}" class="aside-title">
            <img src="{{ asset('assets/images/logo_dark.png') }}" alt="Logo" class="img-fluid" id="logo-dark" style="max-height: 4rem">
            <img src="{{ asset('assets/images/logo_light.png') }}" alt="Logo" class="img-fluid" id="logo-light" style="max-height: 4rem">
        </a>
        <div class="aside-addon">
            <button class="btn btn-label-primary btn-icon btn-lg" data-toggle="aside">
                <i class="fa fa-times aside-icon-minimize"></i>
                <i class="fa fa-thumbtack aside-icon-maximize"></i>
            </button>
        </div>
    </div>
    <div class="aside-body" data-simplebar="data-simplebar">
        <!-- BEGIN Menu -->
        <div class="menu" id="sidebar">
            <div class="menu-item">
                <a href="{{route('home')}}" class="menu-item-link">
                    <div class="menu-item-icon">
                        <i class="fa fa-desktop"></i>
                    </div>
                    <span class="menu-item-text">Dashboard</span>
                </a>
            </div>
            @if($master = $rolePermission['master'] ?? false)
            <div class="menu-item">
                <button class="menu-item-link menu-item-toggle">
                    <div class="menu-item-icon">
                        <i class="fa fa-database"></i>
                    </div>
                    <span class="menu-item-text">Master</span>
                    <div class="menu-item-addon">
                        <i class="menu-item-caret caret"></i>
                    </div>
                </button>
                <div class="menu-submenu">
                    @if(in_array('outlet', $master))
                    <div class="menu-item">
                        <a href="{{route('master.outlet.index')}}" class="menu-item-link">
                            <i class="menu-item-bullet"></i>
                            <span class="menu-item-text">Outlet</span>
                        </a>
                    </div>
                    @endif
                    @if(in_array('jenis_produk', $master))
                    <div class="menu-item">
                        <a href="{{route('master.jenis_produk.index')}}" class="menu-item-link">
                            <i class="menu-item-bullet"></i>
                            <span class="menu-item-text">Jenis Produk</span>
                        </a>
                    </div>
                    @endif
                    @if(in_array('produk', $master))
                    <div class="menu-item">
                        <a href="{{route('master.produk.index')}}" class="menu-item-link">
                            <i class="menu-item-bullet"></i>
                            <span class="menu-item-text">Produk</span>
                        </a>
                    </div>
                    @endif
                    @if(in_array('users', $master))
                    <div class="menu-item">
                        <a href="{{route('master.users.index')}}" class="menu-item-link">
                            <i class="menu-item-bullet"></i>
                            <span class="menu-item-text">Users</span>
                        </a>
                    </div>
                    @endif
                    @if(in_array('pelanggan', $master))
                    <div class="menu-item">
                        <a href="{{route('master.pelanggan.index')}}" class="menu-item-link">
                            <i class="menu-item-bullet"></i>
                            <span class="menu-item-text">Pelanggan</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @if($feature = $rolePermission['feature'] ?? false)
                @if(in_array('transaksi', $feature))
                <div class="menu-item">
                    <a href="{{route('transaksi.index')}}" class="menu-item-link">
                        <div class="menu-item-icon">
                            <i class="fa fa-cart-plus"></i>
                        </div>
                        <span class="menu-item-text">Transaksi</span>
                    </a>
                </div>
                @endif
                @if(in_array('report', $feature))
                <div class="menu-item">
                    <a href="{{route('report.index')}}" class="menu-item-link">
                        <div class="menu-item-icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <span class="menu-item-text">Laporan</span>
                    </a>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Quản lý người dùng</h3>
                    </div>

                    <div class="title_right">
                        <div class="col-md-5 col-sm-5  form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">Go!</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="x_panel">
                    <div class="x_content row">
                        @foreach ($users as $user)
                            <div class="col-md-4 col-sm-4  profile_details">
                                <div class="well profile_view">
                                    <div class="col-sm-12">
                                        <h4 class="brief text-uppercase">
                                            <i>@if ($user->role_id == 2)
                                                Staff
                                            @elseif ($user->role_id == 3)
                                                    User
                                                @else
                                                    Không xác định
                                                @endif</i>
                                        </h4>
                                        <div class="left col-md-7 col-sm-7">
                                            <h2>{{ $user->fullName }}</h2>
                                            <p><strong>About: </strong> {{ $user->username }} </p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-building"></i> Address: {{ $user->address }}</li>
                                                <li><i class="fa fa-phone"></i> Phone #: {{ $user->phoneNumber }}</li>
                                            </ul>
                                        </div>
                                        <div class="right col-md-5 col-sm-5 text-center">
                                            @php
                                                $avatar = (!empty($user->avatar) && file_exists(public_path('storage/' . $user->avatar)))
                                                    ? asset('storage/' . $user->avatar)
                                                    : asset('admin/assets/images/user-profile/user_avatar.jpg');
                                            @endphp

                                            <img src="{{ $avatar }}" class="img-circle img-fluid"
                                                style="width:330px;height:230px;object-fit:cover">
                                        </div>
                                    </div>
                                    <div class=" profile-bottom text-center">
                                        <div class=" col-sm-12 emphasis" style="display: flex">
                                            @if ($user->role_id == 3)
                                                <button type="button" class="btn btn-primary btn-sm btn-active upgradeStaff"
                                                    data-attr='{"id":"{{ $user->id }}","action":"{{ route('admin.active-user') }}"}'>
                                                    <i class="fa fa-user"></i> Nhân viên
                                                </button>
                                            @endif
                                            {{-- ACTIVE --}}


                                            {{-- BAN --}}
                                            <button type="button" class="btn btn-warning btn-ban"
                                                data-attr='{"id":"{{ $user->id }}","action":"{{ route('admin.status-user') }}","status":"b"}'
                                                style="{{ $user->status === 'b' ? 'display:none' : '' }}">
                                                <i class="fa fa-ban"></i> Chặn
                                            </button>

                                            {{-- UNBAN --}}
                                            <button type="button" class="btn btn-warning btn-unban"
                                                data-attr='{"id":"{{ $user->id }}","action":"{{ route('admin.status-user') }}","status":"u"}'
                                                style="{{ $user->status !== 'b' ? 'display:none' : '' }}">
                                                <i class="fa fa-ban"></i> Bỏ chặn
                                            </button>

                                            {{-- DELETE --}}
                                            <button type="button" class="btn btn-danger btn-delete"
                                                data-attr='{"id":"{{ $user->id }}","action":"{{ route('admin.status-user') }}","status":"d"}'
                                                style="{{ $user->status === 'd' ? 'display:none' : '' }}">
                                                <i class="fa fa-close"></i> Xóa
                                            </button>

                                            {{-- RESTORE --}}
                                            <button type="button" class="btn btn-danger btn-restore"
                                                data-attr='{"id":"{{ $user->id }}","action":"{{ route('admin.status-user') }}","status":"r"}'
                                                style="{{ $user->status !== 'd' ? 'display:none' : '' }}">
                                                <i class="fa fa-close"></i> Khôi phục
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>
@include('admin.blocks.footer')
@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('プロファイル') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-4 order-lg-2">

            <div class="card shadow mb-4">
                <div class="card-profile-image mt-4">
                    <form id="file-upload" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="profile_path">
                            <figure class="rounded-circle avatar avatar font-weight-bold"
                                    style="font-size: 60px; height: 180px; width: 180px;" data-initial="Photo">

                                @if (Auth::user()->profile_url != null)
                                    <img id="uploaded_url" src="{{ url(Auth::user()->profile_url)}}">
                                @else
                                    <img id="uploaded_url" src="" style="display: none;">
                                @endif
                            </figure>
                        </label>
                        <input type="file" id="profile_path" style="display: none">
                    </form>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h5 class="font-weight-bold">{{  Auth::user()->fullName }}</h5>
                                @if(Auth::user()->isAdmin())
                                    <p>管理者</p>
                                @else
                                    <p>ユーザ</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">アカウント</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="profile_url" id="profile_url">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <h6 class="heading-small text-muted mb-4">ユーザ情報</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">ユーザ名<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name" placeholder="ユーザ名"
                                               value="{{ old('name', Auth::user()->name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="full_name">名前</label>
                                        <input type="text" id="full_name" class="form-control" name="full_name"
                                               placeholder="名前"
                                               value="{{ old('full_name', Auth::user()->full_name) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">
                                            メールアドレス
                                            <span class="small text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" class="form-control" name="email"
                                               placeholder="example@example.com"
                                               value="{{ old('email', Auth::user()->email) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="current_password">現在のパスワード</label>
                                        <input type="password" id="current_password" class="form-control"
                                               name="current_password" placeholder="現在のパスワード">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="new_password">新しいパスワード</label>
                                        <input type="password" id="new_password" class="form-control"
                                               name="new_password" placeholder="新しいパスワード">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="confirm_password">パスワードの確認</label>
                                        <input type="password" id="confirm_password" class="form-control"
                                               name="password_confirmation" placeholder="パスワードの確認">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">保存</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#profile_path').change(function (e) {
                var formData = new FormData();
                let file_data = $(this).prop('files')[0];
                formData.append('profile_path', file_data);
                let user_id = $('input[name=user_id]').val();
                formData.append('user_id', user_id);
                if (file_data) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{url('admin/user/upload')}}",
                        data: formData,
                        type: 'post',
                        async: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: (data) => {
                            $('#profile_url').val(data.profile_url);
                            $('#uploaded_url').attr('src', `.${data.profile_url}`);
                            $('#uploaded_url').css('display', 'block');
                        }
                    })
                }
            });
        });
    </script>
@endsection

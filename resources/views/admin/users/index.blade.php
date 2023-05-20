@extends('layouts.admin', ['title' => 'ユーザー'])
@section('main-content')
    <div>
        {{--        <div class="action-container">--}}
        {{--            <a class="btn btn-primary" href="{{ route('admin.user.add') }}">新規追加</a>--}}
        {{--        </div>--}}
        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th>ユーザ名</th>
                    <th>メールアドレス</th>
                    <th>名前</th>
                    <th>役割</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <a href="{{ route('admin.user.edit',['id'=>$user->id]) }}"><i class="fa fa-edit"></i></a>
                            <a class="text-danger" href="{{ route('admin.user.delete',['id'=>$user->id]) }}"><i
                                    class="fa fa-times"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

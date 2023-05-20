@extends('layouts.admin', ['title' => 'ユーザー質問'])
@section('main-content')
    <div>
        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th>ユーザ名</th>
                    <th>メールアドレス</th>
                    <th>名前</th>
                    <th>郵便番号</th>
                    <th>住所</th>
                    <th>電話番号</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->zip_code }}</td>
                        <td>{{ $client->address }}</td>
                        <td>{{ $client->phone_number }}</td>
                        <td><a href="{{ route('admin.client.show', ['id'=> $client->id]) }}"><i
                                    class="fa fa-eye"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $clients->links() }}
        </div>
    </div>
@endsection

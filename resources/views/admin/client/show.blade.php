@extends('layouts.admin', ['title' => 'ユーザー質問'])
@section('main-content')
    <div>
        <h2>ユーザ設問回答</h2>
    </div>

    <div class="row">
        <div class="col-md-2">ユーザ名</div>
        <div class="col-md-10">{{ $client->name }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">メールアドレス</div>
        <div class="col-md-10">{{ $client->email }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">名前</div>
        <div class="col-md-10">{{ $client->full_name }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">郵便番号</div>
        <div class="col-md-10">{{ $client->zip_code }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">住所</div>
        <div class="col-md-10">{{ $client->address }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">電話番号</div>
        <div class="col-md-10">{{ $client->phone_number }}</div>
    </div>
    <div class="row mt-1">
        <div class="col-md-2">設問</div>
        <div class="col-md-10">{{ $client->survey != null ? $client->survey->title : '' }}</div>
    </div>
    <div class="mt-1">
        <table class="table">
            <thead>
                <tr>
                    <th>質問</th>
                    <th>回答</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($answers as $answer)
                <tr>
                    <td>{{ $answer->answer->question != null ? $answer->answer->question->title : ''}}</td>
                    <td>{{ $answer->answer != null ? $answer->answer->title : ''}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

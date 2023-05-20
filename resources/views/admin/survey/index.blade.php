@extends('layouts.admin', ['title' => 'アンケート'])
@section('js')
    <script src="{{ asset('vendor/clipboard/clipboard.min.js') }}"></script>
    <script>
        var btns = document.querySelectorAll('.clipboard');
        var clipboard = new ClipboardJS(btns);

        clipboard.on('success', function (e) {
            $('.clip-toast').addClass('open');
            setTimeout(function() {
                $('.clip-toast').removeClass('open');
            }, 1000);
        });

        clipboard.on('error', function (e) {
            console.log(e);
        });
    </script>
@endsection
@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.survey.add') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>タイトル</th>
                <th>ステータス</th>
                <th>背景色</th>
                <th>文字色</th>
                <th>枠カラ色</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($surveys as $survey)
                <tr>
                    <td>{{ $survey->id }}</td>
                    <td>{{ $survey->title }}</td>
                    <td>{{ $survey->statuses->name }}</td>
                    <td><span style="background-color: {{$survey->background_color}}; width: 40px; display: block">&nbsp;</span>
                    </td>
                    <td><span
                            style="background-color: {{$survey->char_color}}; width: 40px; display: block">&nbsp;</span>
                    </td>
                    <td><span
                            style="background-color: {{$survey->border_color}}; width: 40px; display: block">&nbsp;</span>
                    </td>
                    <td>
                    <a class="clipboard" style="cursor: pointer;" data-clipboard-text="https://styleboard.xbiz.jp/client/?id={{ $survey->token }}" title="https://styleboard.xbiz.jp/client/?id={{ $survey->token }}"><i
                                    class="fa fa-clipboard mr-1"></i></a>
                        <a href="{{ route('admin.survey.edit',['id'=>$survey->id]) }}"><i class="fa fa-edit"></i></a>
                        <a class="text-danger" href="{{ route('admin.survey.delete',['id'=>$survey->id]) }}"><i
                                class="fa fa-times"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $surveys->links() }}
    </div>
    <div class="clip-toast">
        URLをコピーしました。
    </div>
    <style>
        .clip-toast {
            position: fixed;
            font-size: 12px;
            background: #00000088;
            width: fit-content;
            padding: 8px 16px;
            border-radius: 4px;
            right: 50px;
            bottom: 50px;
            opacity: 0;
            visibility: hidden;
            transition: all 300ms cubic-bezier(0.335, 0.01, 0.03, 1.36);
            color: white;
        }
        .open {
            opacity: 1;
            visibility: visible;
        }
    </style>
@endsection

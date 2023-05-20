@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('設定') }}</h1>

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

        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.setting.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="profile_url" id="profile_url">
                        <input type="hidden" name="_method" value="PUT">
                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" name="flag" value="{{$creditCardPaymentFlg ? 0 : 1}}" class="btn btn-primary btn-lg {{ !$creditCardPaymentFlg ? 'disabled' : ''}}">EC<br/>
                                        (クレジットカード決済)</button>
                                </div>
                                <div class="col text-center" >
                                    <button type="submit" name="flag" value="{{!$creditCardPaymentFlg ? 1 : 0}}" class="btn btn-primary btn-lg  {{ $creditCardPaymentFlg ? 'disabled' : ''}}" style="height: 70px;">診断クイズ<br/></button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $creditCardPaymentFlg = Setting::where('code', 'credit_card_payment')->first()->flag;
        return view('admin/setting/index', compact('creditCardPaymentFlg'));
    }

    public function update(Request $request)
    {
        Setting::where('code', 'credit_card_payment')->update(['flag'=> $request->get("flag")]);
        return redirect()->route('admin.setting')->withSuccess('İ’è updated successfully.');
    }
}

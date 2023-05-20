<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        if(Auth::user()->isAdmin()) {
            $clients = Client::simplePaginate(20);
        } else {
            $clients = Client::where('email',Auth::user()->email)
                ->simplePaginate(20);
        }
        return view('admin/client/index', ['clients' => $clients]);
    }

    public function show(Request $request, $id){
        $answers = ClientAnswer::where('client_id', $id)->get();
        $client = Client::find($id);

        return view('admin/client/show', ['client' => $client, 'answers' => $answers]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\XmlToCollection;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        $xmlObjetClient = new XmlToCollection('client.xml','ObjectClient');
        $clients = $xmlObjetClient->getData();
        return view("welcome")->with(compact('clients'));
    }

    public function calculator(Request $request)
    {
        return response()->json($request->all());
    }
}

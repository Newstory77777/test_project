<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MainController extends Controller
{

    public function index()
    {
        $companies = json_decode(file_get_contents(storage_path() . "/companies.json"), true);
        $symbols = array_column($companies, 'Symbol');
        return view('index', compact('symbols'));
    }

    public function show(Request $request)
    {
        $companies = json_decode(file_get_contents(storage_path() . "/companies.json"), true);
        $symbols = array_column($companies, 'Symbol');
        $data = request()->validate([
            'symbol' => ['required',
                Rule::in($symbols),
            ],
            'start_date' => ['required', 'date_format:m/d/Y', 'before_or_equal:end_date', 'before_or_equal:today'],
            'end_date' => ['required', 'date_format:m/d/Y', 'after_or_equal:end_date', 'before_or_equal:today'],
            'email' => 'required|email',
        ],
            [
                'symbol.in' => 'country symbol value is not valid'
            ]);
        //We get data here
        //1)Send request and show table
        //2)Build chart
        //3)Send letter
        dd($data);
    }
}

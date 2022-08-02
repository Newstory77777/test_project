<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;


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
        ///!!!!!!!!!!!()
        /// Why we do not use date info into next request?
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
        $rapidApiUrl = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';
        $rapidApiHeaders = [
            'X-RapidAPI-Key' => '',
            'X-RapidAPI-Host' => ''
        ];
        $pendingRequest = Http::withHeaders($rapidApiHeaders);
        $response = $pendingRequest->get($rapidApiUrl, ['symbol' => $data['symbol']]);

        //if ($response->successful())
        $result = $response->json();
        $prices = $result['prices'] ?? array();

        $prices = array_map(function ($price) {
            $price['format_date'] = date('Y-m-d H:i', $price['date']);
            return $price;
        }, $prices);

        //2)Build chart
        //3)Send letter

        return view('show', compact('prices'));

    }
}

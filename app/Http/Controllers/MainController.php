<?php

namespace App\Http\Controllers;

use App\Mail\CompanyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $symbolsAndCompanies = array_column($companies, 'Company Name', 'Symbol');
        $symbols = array_keys($symbolsAndCompanies);

        $data = $request->validate([
            'symbol' => ['required',
                Rule::in($symbols),
            ],
            'start_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_date', 'before_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:end_date', 'before_or_equal:today'],
            'email' => 'required|email',
        ],
            [
                'symbol.in' => 'country symbol value is not valid'
            ]);

        //We get data here
        //1)Send request and show table
        $rapidApiUrl = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';
        $rapidApiHeaders = [
            'X-RapidAPI-Key' => env('X_RAPIDAPI_KEY'),
            'X-RapidAPI-Host' => env('X_RAPIDAPI_HOST')
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
        try {
            Mail::to($data['email'])
                ->send(new CompanyMail(
                        [
                            'start_date' => $data['start_date'],
                            'end_date' => $data['end_date'],
                            'company' => $symbolsAndCompanies[$data['symbol']]
                        ]
                    )
                );
            $mailMessage = 'Mail to ' . $data['email'] . ' was sent. It can be checked at https://mailtrap.io';
        } catch (Exception $e) {
            $mailMessage = '';
        }

        return view('show', compact('prices', 'mailMessage'));

    }
}

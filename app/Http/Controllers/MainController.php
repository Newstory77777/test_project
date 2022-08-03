<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MainController extends BaseController
{
    public function index()
    {
        $symbolsAndCompanies = $this->service->getCompaniesArr();
        $symbols = array_keys($symbolsAndCompanies);
        return view('index', compact('symbols'));
    }

    public function show(Request $request)
    {
        //Get Company Symbols for validation
        $symbolsAndCompanies = $this->service->getCompaniesArr();

        $data = $request->validate([
            'symbol' => ['required',
                Rule::in(array_keys($symbolsAndCompanies)),
            ],
            'start_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_date', 'before_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:end_date', 'before_or_equal:today'],
            'email' => 'required|email',
        ],
            [
                'symbol.in' => 'country symbol value is not valid'
            ]);

        //Get data to display table and chart
        $prices = $this->service->getPrices(['symbol' => $data['symbol']]);

        //Sending email
        $mailMessage = $this->service->sendMail($data, $symbolsAndCompanies);

        return view('show', compact('prices', 'mailMessage'));

    }
}

<?php

namespace App\Services\Main;

use App\Mail\CompanyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class Service
{
    public array $companies = array();
    public string $companiesPath;
    public array $connectParams;

    public function __construct()
    {
        $this->companiesPath = storage_path() . '/' . env('COMPANIES_FILE');
        $this->connectParams = array(
            'headers' => [
                'X-RapidAPI-Key' => env('X_RAPIDAPI_KEY'),
                'X-RapidAPI-Host' => env('X_RAPIDAPI_HOST'),
            ],
            'url' => env('X_RAPIDAPI_URL')
        );
    }

    /**
     * @param string $filename
     * @return mixed|void
     */
    private function getJsonFromFile(string $filename)
    {
        try {
            $result = json_decode(file_get_contents($filename), true);
        } catch (Throwable $e) {
            abort(404, 'File of companies was not found');
        }
        return $result;
    }

    public function getCompaniesArr()
    {
        if (!$this->companies) {
            $companies = $this->getJsonFromFile($this->companiesPath);
            $this->companies = array_column($companies, 'Company Name', 'Symbol');
        }
        return $this->companies;
    }

    private function getDataFrom3dPartyService(array $data)
    {
        $pendingRequest = Http::withHeaders($this->connectParams['headers']);
        $response = $pendingRequest->get($this->connectParams['url'], $data);
        if ($response->successful()) {
            return $response;
        } else {
            abort(404, 'Service ' . $this->connectParams['url'] . ' does not response.');
        }
    }

    private function getPricesFromResponse($response)
    {
        $result = $response->json();
        $prices = $result['prices'] ?? array();
        return array_map(function ($price) {
            $price['format_date'] = date('Y-m-d H:i', $price['date']);
            return $price;
        }, $prices);
    }

    public function getPrices($data)
    {
        $response = $this->getDataFrom3dPartyService($data);
        return $this->getPricesFromResponse($response);
    }

    public function sendMail($data, $symbolsAndCompanies)
    {
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
            return 'Mail to ' . $data['email'] . ' was sent. It can be checked at https://mailtrap.io';
        } catch (Exception $e) {
            return '';
        }
    }
}

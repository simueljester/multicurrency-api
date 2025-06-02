<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CompanyRepository;
use App\Http\Repositories\InvoiceRepository;
use App\Http\Requests\GetInvoiceRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private $exchangeRateService;

    protected $invoiceRepository;

    protected $companyRepository;

    /**
     * construct is upon loading the controller
     */
    public function __construct()
    {
        $this->exchangeRateService = new ExchangeRateService;

        $this->invoiceRepository = app(InvoiceRepository::class);
        $this->companyRepository = app(CompanyRepository::class);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetInvoiceRequest $request)
    {
        $perPage = $request->input('per_page', 20);

        $keyword = $request->input('keyword') ?? null;

        $sort_by = $request->input('sort_by') ?? null;

        $sort_type = $request->input('sort_type') ?? null;

        $includes = ApiHelper::parseIncludes();

        $invoices = $this->invoiceRepository->query()
            ->select(
                'id',
                'title',
                'amount',
                'currency_code',
                'base_currency',
                'exchange_rate',
                'exchange_rate_timestamp',
                'amount_in_base_currency',
                'created_at',
                'updated_at',
            )
            ->when($keyword, function ($query, $keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            })
            ->when($sort_by && $sort_type, function ($query) use ($sort_by, $sort_type) {
                $query->orderBy($sort_by, $sort_type);
            })
            ->when($includes->contains('company'), function ($query) {
                $query->addSelect(['company_id'])->with('company');
            })
            ->when($includes->contains('exchange_rate'), function ($query) {
                $query->addSelect(['exchange_rate_id'])->with('exchangeRate');
            })
            ->paginate($perPage);

        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();
        $company = $this->companyRepository->find($validated['company_id']);
        $systemBaseCurrency = config('app.base_currency');
        $companyCurrency = $company->base_currency;

        try {
            $rateData = $this->exchangeRateService->getLatestRate($systemBaseCurrency, $companyCurrency);

            $amountInBase = $this->exchangeRateService->convertToBaseCurrency(
                $validated['amount'],
                $companyCurrency,
                $systemBaseCurrency,
                $rateData['from_currency'],
                $rateData['rate']
            );

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $invoiceData = [
            'company_id' => $company->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'currency_code' => $companyCurrency,
            'base_currency' => $systemBaseCurrency,
            'exchange_rate_id' => $rateData['id'],
            'exchange_rate' => $rateData['rate'],
            'exchange_rate_timestamp' => $rateData['timestamp'],
            'amount_in_base_currency' => $amountInBase,
        ];

        $invoice = $this->invoiceRepository->save($invoiceData);

        return new InvoiceResource($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $invoiceId)
    {
        $includes = ApiHelper::parseIncludes();

        $invoice = $this->invoiceRepository->query()
            ->whereId($invoiceId)
            ->select(
                'id',
                'title',
                'amount',
                'currency_code',
                'base_currency',
                'exchange_rate',
                'exchange_rate_timestamp',
                'amount_in_base_currency',
                'created_at',
                'updated_at'
            )
            ->when($includes->contains('company'), function ($query) {
                $query->addSelect(['company_id'])->with('company');
            })
            ->when($includes->contains('exchange_rate'), function ($query) {
                $query->addSelect(['exchange_rate_id'])->with('exchangeRate');
            })
            ->first();

        if (! $invoice) {
            return response()->json(['error' => 'Invoice ID is invalid or does not exist.'], 404);
        }

        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request,$invoiceId)
    {
        $validated = $request->validated();
        $company = $this->companyRepository->find($validated['company_id']);
        $systemBaseCurrency = config('app.base_currency');
        $companyCurrency = $company->base_currency;

        try {
            $rateData = $this->exchangeRateService->getLatestRate($systemBaseCurrency, $companyCurrency);

            $amountInBase = $this->exchangeRateService->convertToBaseCurrency(
                $validated['amount'],
                $companyCurrency,
                $systemBaseCurrency,
                $rateData['from_currency'],
                $rateData['rate']
            );

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $invoiceData = [
            'company_id' => $company->id,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'currency_code' => $companyCurrency,
            'base_currency' => $systemBaseCurrency,
            'exchange_rate_id' => $rateData['id'],
            'exchange_rate' => $rateData['rate'],
            'exchange_rate_timestamp' => $rateData['timestamp'],
            'amount_in_base_currency' => $amountInBase,
        ];

        $invoice = $this->invoiceRepository->update($invoiceId,$invoiceData);

        return new InvoiceResource($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

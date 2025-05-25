<?php

namespace App\Http\Controllers\API;

use App\Enums\ExchangeRateSourceEnum;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ExchangeRateRepository;
use App\Http\Requests\GetExchangeRequest;
use App\Http\Requests\StoreExchangeRequest;
use App\Http\Requests\UpdateExchangeRequest;
use App\Http\Resources\ExchangeRateResource;

class ExchangeRateController extends Controller
{
    protected $exchangeRateRepository;

    /**
     * construct is upon loading the controller
     */
    public function __construct()
    {
        $this->exchangeRateRepository = app(ExchangeRateRepository::class);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetExchangeRequest $request)
    {
        $perPage = $request->input('per_page', 20);

        $sort_by = $request->input('sort_by') ?? null;

        $sort_type = $request->input('sort_type') ?? null;

        $from_currency = $request->input('from_currency') ?? null;

        $to_currency = $request->input('to_currency') ?? null;

        $exchangeRates = $this->exchangeRateRepository->query()
            ->when($sort_by && $sort_type, function ($query) use ($sort_by, $sort_type) {
                $query->orderBy($sort_by, $sort_type);
            })
            ->when($from_currency && $to_currency, function ($query) use ($from_currency, $to_currency) {
                $query->where('from_currency', $from_currency)
                    ->where('to_currency', $to_currency);
            })
            ->paginate($perPage);

        return ExchangeRateResource::collection($exchangeRates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExchangeRequest $request)
    {
        $validated = $request->validated();

        $fromCurrency = $validated['from_currency'];
        $toCurrency = $validated['to_currency'];
        $rate = $validated['rate'];
        $source = ExchangeRateSourceEnum::Manual;

        $exchangeData = [
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'rate' => $rate,
            'fetched_at' => now(),
            'source' => $source->value,
        ];

        $exchangeRate = $this->exchangeRateRepository->save($exchangeData);

        return response()->json([
            'message' => 'Rate has been successfully created',
            'data' => new ExchangeRateResource($exchangeRate),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExchangeRequest $request, $exchangeRateId)
    {
        $validated = $request->validated();

        $exchangeRate = $this->exchangeRateRepository->find($exchangeRateId);

        if (! $exchangeRate) {
            return response()->json(['error' => 'Exchange Rate ID is invalid or does not exist.'], 404);
        }

        if ($exchangeRate->invoices()->exists()) {
            return response()->json(['message' => 'Cannot update exchange rate. It is already associated with one or more invoices. To maintain historical accuracy and ensure the integrity of past financial records, exchange rates linked to recorded invoices cannot be modified. Alternatively, Insert a new exchange rate for future transactions.'], 422);
        }

        $exchangeRate->from_currency = $validated['from_currency'];
        $exchangeRate->to_currency = $validated['to_currency'];
        $exchangeRate->rate = $validated['rate'];
        $exchangeRate->fetched_at = now();
        $exchangeRate->save();

        return response()->json([
            'message' => 'Exchange Rate has been successfully updated',
            'data' => new ExchangeRateResource($exchangeRate),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CompanyRepository;
use App\Http\Requests\GetCompanyRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct()
    {
        $this->companyRepository = app(CompanyRepository::class);
    }

    /**
     * Display a listing of the resource.
     * Adding resource for flexibility and control of request
     */
    public function index(GetCompanyRequest $request)
    {
        $perPage = $request->input('per_page', 20);

        $keyword = $request->input('keyword') ?? null;

        $sort_by = $request->input('sort_by') ?? null;

        $sort_type = $request->input('sort_type') ?? null;

        $includes = ApiHelper::parseIncludes();

        $companies = $this->companyRepository->query()
            ->select('id', 'name', 'base_currency', 'created_at', 'updated_at')
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->when($sort_by && $sort_type, function ($query) use ($sort_by, $sort_type) {
                $query->orderBy($sort_by, $sort_type);
            })
            ->paginate($perPage);

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     * Used repository pattern for query
     */
    public function store(StoreCompanyRequest $request)
    {

        $company = $this->companyRepository->save($request->validated());

        return response()->json([
            'message' => 'Company has been successfully created',
            'data' => new CompanyResource($company),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $companyId)
    {
        $includes = ApiHelper::parseIncludes();

        $company = $this->companyRepository->query()
            ->whereId($companyId)
            ->select('id', 'name', 'base_currency', 'created_at', 'updated_at')
            ->first();

        if (! $company) {
            return response()->json(['error' => 'Company ID is invalid or does not exist.'], 404);
        }

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, $companyId)
    {
        $validated = $request->validated();

        $company = $this->companyRepository->find($companyId);
        if (! $company) {
            return response()->json(['error' => 'Company ID is invalid or does not exist.'], 404);
        }

        $company->name = $validated['name'];
        $company->base_currency = $validated['base_currency'];
        $company->save();

        return response()->json([
            'message' => 'Company has been successfully updated',
            'data' => new CompanyResource($company),
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

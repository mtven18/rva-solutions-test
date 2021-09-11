<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    /**
     * @var TransactionService
     */
    private $service;

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $service
     *
     * @return void
     */
    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display resources list.
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return TransactionResource::collection(
            $this->service->list($request->user())
        );
    }

    /**
     * Make new transaction.
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     * @throws ForbiddenException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->service->send($request->username, $request->sum, $request->user());
        return response()->json([
            'message' => __('app.transaction_sent')
        ], JsonResponse::HTTP_CREATED);
    }
}

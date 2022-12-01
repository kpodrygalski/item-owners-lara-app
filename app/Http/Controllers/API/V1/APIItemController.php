<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Items\PatchNameRequest;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use Symfony\Component\HttpFoundation\Response;

class APIItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // $items = Item::with(['user:id,name,email'])->get(['id', 'name', 'quantity', 'user_id']);
        $items = Item::query();

        // SELECT * FROM items WHERE ID = 2
        if ($request->has('userId')) {
            $userId = $request->get('userId');
            $items->where('user_id', $userId);
        }

        if ($request->has('date')) {
            $date = Carbon::createFromFormat('Y-m-d', $request->get('date'));
            $items->whereYear('created_at', $date->year);
        }

        if ($request->has('from') && $request->has('to')) {
            $from = $request->get('from');
            $to = $request->get('to');

            $range = [$from, $to];

            $items->whereBetween('created_at', $range);
        }

        if ($request->has('userName')) {
            $userName = $request->get('userName');

            $items->with(['user' => function ($query) use ($userName) {
                return $query->where('name', 'LIKE', '%' . $userName . '%');
            }]);
        }

        return response()->json([
            'items' => $items->get(['id', 'name', 'quantity', 'user_id'])
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreItemRequest $request
     * @return JsonResponse
     */
    public function store(StoreItemRequest $request): JsonResponse
    {
        $userId = auth()->user()->id;

        $name = $request->name;
        $quantity = $request->quantity;

        $item = Item::create([
            'name' => $name,
            'quantity' => $quantity,
            'user_id' => $userId
        ]);

        return response()->json([
            'item' => $item
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Item $item
     * @return JsonResponse
     */
    public function show(Request $request, Item $item): JsonResponse
    {
        if ($request->has('showUser') && $request->get('showUser') === '1') {
            if (!$item->relationLoaded('user')) {
                $item->load('user:id,name,email');
            }
        }

        return response()->json([
            'item' => $item
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemRequest $request
     * @param Item $item
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request, Item $item): JsonResponse
    {
        $name = $request->name;
        $quantity = $request->quantity;

        $wasItemUpdated = $item->update([
            'name' => $name,
            'quantity' => $quantity
        ]);

        return response()->json([
            'item' => $item,
            'wasItemUpdated' => $wasItemUpdated
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item): JsonResponse
    {
        $id = $item->id;
        $wasItemDeleted = $item->delete();
        // $wasItemDeleted = $item->forceDelete();

        return response()->json([
            'message' => 'Item with ID = ' . $id . ' was deleted!'
        ], Response::HTTP_OK);
    }

    /**
     * Patch item name.
     *
     * @param PatchNameRequest $request
     * @param Item $item
     * @return JsonResponse
     */
    public function patchItemName(PatchNameRequest $request, Item $item): JsonResponse
    {
        $name = $request->name;

        $wasPatched = $item->update([
            'name' => $name
        ]);

        return response()->json([
            'item' => $item
        ], Response::HTTP_OK);
    }
}

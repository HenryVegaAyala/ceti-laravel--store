<?php

namespace App\Http\Api\Product;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductShowResource;
use App\Modules\Base\Controllers\ApiController;
use App\Modules\Product\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct(["index"]);
    }

    public function index()
    {
        $result = Product::query()
            ->select([
                "products.id",
                "products.name",
                "products.description",
                "products.price",
                "categories.id as category_id",
                "categories.name as category_name"
            ])
            ->join("categories", "categories.id", "=", "products.category_id")
            ->get();

        return $this->successResponse([
            'response' => ProductResource::collection($result),
        ]);
    }

    public function create(ProductCreateRequest $request)
    {
        $product = Product::query()
            ->create($request->all());

        return $this->successResponse(new ProductShowResource($product), 201);
    }

    public function show($id)
    {
        $response = Product::query()->findOrFail($id);

        return $this->successResponse(new ProductShowResource($response));
    }

    public function update(Request $request, $id)
    {
        $attributes = $request->all();

        $product = Product::query()->findOrFail($id);
        $product->update($attributes);

        return $this->successResponse(new ProductShowResource($product));
    }

    public function destroy($id)
    {
        Product::query()->findOrFail($id)->delete();

        return $this->successResponse([
            'message' => 'Product deleted successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Resources\Tenant\ProductResource;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductsApiController extends ApiController
{
    protected function permission(): string
    {
        return 'products.list,products.show,products.create,products.update,products.delete';
    }

    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand', 'unit'])
            ->filter([
                'search' => $request->query('search'),
                'category_id' => $request->query('category_id'),
                'brand_id' => $request->query('brand_id'),
            ])
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return $this->paginated($products, ProductResource::class);
    }

    public function show(int $id)
    {
        $product = Product::with(['category', 'brand', 'unit'])->find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        return $this->success(new ProductResource($product));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:255|unique:products,sku',
            'code' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'weight' => 'nullable|numeric',
            'alert_qty' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $product = Product::create($validated);

        return $this->success(new ProductResource($product->load(['category', 'brand', 'unit'])), 201);
    }

    public function update(Request $request, int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'sku' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'code' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'weight' => 'nullable|numeric',
            'alert_qty' => 'nullable|integer',
            'active' => 'nullable|boolean',
        ]);

        $product->update($validated);

        return $this->success(new ProductResource($product->load(['category', 'brand', 'unit'])));
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Not Found', 404);
        }

        $product->delete();

        return $this->success(null);
    }
}

<?php 

namespace App\Services;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    public function create($data)
    {
        $validatedData = $data->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $imageName = time().'-'.$data->name.'.'.$data->image->extension();  
        $data->image->move(public_path('images'), $imageName);

        $this->productRepository->create($data->all(), $imageName);

        return response()->json(['success' => 'Product has been created successfully.'], 201);
    }
}
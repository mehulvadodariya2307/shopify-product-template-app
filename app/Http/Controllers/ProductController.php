<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    
    public function getAllProducts(Request $request): JsonResponse
    {
        $user = Auth::user();
        $query = Product::where('user_id', $user->id);
        $searchString = "";
        if (isset($request->search) && !empty($request->search)) {
            $searchString = $request->search;
            $query->where(function ($q) use ($searchString) {
                $q->where('title', 'LIKE', "%$searchString%")
                    ->orWhere('body_html', 'LIKE', "%$searchString%")
                    ->orWhere('price', 'LIKE', "%$searchString%");
            });
        }
        $totalQuery = $query->count();
        if ($request->limit) {
            $query->limit($request->limit)->offset($request->offset);
        }
        
        $data = $query->get();
      
        return response()->json(['success' => true, 'data' => $data, 'total' => $totalQuery]);
    }
    
    public function add(Request $request)
    {

        $user = Auth::user();
        $data = $request->json()->all();

        $validator = Validator::make($data, [
            'title' => 'required',
            'body_html' => 'required',
            'price' => 'required|numeric|gt:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false]);
        }

        $productData = ['product' => ['title' => $request->title, 'body_html' => $request->body_html, "variant" => ["price" => $request->price],"image"=>["src"=>"https://placehold.co/200x200@2x.png"]]];

        $productAPI = $user
            ->api()
            ->rest(
                'POST',
                '/admin/api/' .
                config('shopify-app.api_version') .
                '/products.json',
                $productData
            );

        if ($productAPI['status'] == 201) {
            // product added
            $body = json_decode( json_encode($productAPI['body']),true);
            $productResponse = isset($body['product']) ? $body['product'] : [];

            if(count($productResponse) == 0){
                return response()->json(['message' => "API response is null please try again", 'success' => false]);
            }
            
            // image api start
            $productImageData = ["image"=>["src"=>"https://placehold.co/500x500?text=$request->title"]];
            $productImageAPI = $user
                ->api()
                ->rest(
                    'POST',
                    '/admin/api/' .
                    config('shopify-app.api_version') .
                    '/products/'.$productResponse['id'].'/images.json',
                    $productImageData
                );

            $product = new Product;
            $product->product_id = $productResponse['id'];
            $product->user_id = $user->id;
            $product->product_handle = $productResponse['handle'];
            $product->title = $productResponse['title'];
            $product->body_html = $productResponse['body_html'];
            $product->price = $request->price;
            $product->product_json = json_encode($productResponse);
            $product->save();

            if($productImageAPI['status'] == 200){
                // image uploaded
                return response()->json(['message' => "product added successfully", 'success' => true]);
            }else{
                // image upload failed
                return response()->json(['message' => "product added without Image", 'success' => true]);
            }
            // image api end
                
        }else{
            return response()->json(['message' => "something wrong please try again after sometime", 'success' => false]);
        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Banner;

class IndexController extends Controller
{
    public function index(){

        // in ascending order
        //$productsAll = Product::get();

        // in descending order
        //$productsAll = Product::orderBy('id','DESC')->get();

        // in random order
        $productsAll = Product::inRandomOrder()->where('status','1')->paginate(6);

        // get all categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        //$categories = json_decode(json_encode($categories));
        
        
        $banners = Banner::where('status', '1')->get();
        // dd($banners);
        return view('index')->with(compact('productsAll', 'categories', 'banners'));
    }
}

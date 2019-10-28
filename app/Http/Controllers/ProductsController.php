<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Auth;
use Session;
use Image;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\ProductsImage;
use App\User;
use App\Country;
use App\DeliveryAdresses;
use DB;
use App\Order;
use App\OrdersProduct;



class ProductsController extends Controller
{
    public function addProduct(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            
            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            if(!empty($data['description'])){
                $product->description = $data['description'];
            }else{
                $product->description = '';
            }

            if (!empty($data['care'])) {
                $product->care = $data['care'];
            } else {
                $product->care = '';
            }
            $product->price = $data['price'];


            // Upload Image
            if($request->hasFile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/' . $filename;
                    $small_image_path = 'images/backend_images/products/small/' . $filename;
                    //Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                    //Store image name in products table
                    $product->image = $filename;
                }
            }

            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }

            $product->status = $status;
            $product->save();
            return redirect('/admin/view-products')->with('flash_message_success','Product Added Successfully');
        }
        

        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id=null){

        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111, 99999) . '.' . $extension;
                    $large_image_path = 'images/backend_images/products/large/' . $filename;
                    $medium_image_path = 'images/backend_images/products/medium/' . $filename;
                    $small_image_path = 'images/backend_images/products/small/' . $filename;
                    //Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300, 300)->save($small_image_path);

                }
            }else{
                $filename = $data['current_image'];
            }

            if(empty($data['description'])){
                $data['description'] = '';
            }
            if (empty($data['care'])) {
                $data['care'] = '';
            }

            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }

            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'], 'product_name' => $data['product_name'], 'product_code' => $data['product_code'], 'description' => $data['description'], 'care' => $data['care'], 'price' => $data['price'],'image'=>$filename,'status'=>$status]);
            return redirect()->back()->with('flash_message_success', 'Product updated successfully');
        }

        $productDetails = Product::where(['id'=>$id])->first();

        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach ($categories as $cat) {
            if($cat->id==$productDetails->category_id){
                $selected = "selected";
            }else{
                $selected = "";
            }
            $categories_dropdown .= "<option value='" . $cat->id . "' ".$selected.">" . $cat->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value = '" . $sub_cat->id . "'" . $selected . ">&nbsp;--&nbsp;" . $sub_cat->name . "</option>";
            }
        }

        return view('admin.products.edit_product')->with(compact('productDetails', 'categories_dropdown'));
    }

    public function viewProducts(){
        $products = Product::orderBy('id','DESC')->get();
        foreach($products as $key => $val){
            $category_name = Category::where(['id' => $val -> category_id])->first();
            $products[$key] -> category_name = $category_name->name;
        }
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProduct($id=null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Product deleted successfully');
    }

    public function deleteProductImage($id = null){

        //Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();
        //echo $productImage->image; die;
        //Get Product image path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';
        //delete large image if it doesn't exist in folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }
        //delete medium image if it doesn't exist in folder
        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }
        //delete small image if it doesn't exist in folder
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }
        //Delete image from products table
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('flash_message_success','Image deleted successfully');
    }

    public function deleteAltImage($id = null){

        //Get Product Image Name
        $productImage = ProductsImage::where(['id' => $id])->first();
        //echo $productImage->image; die;
        //Get Product image path
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';
        //delete large image if it doesn't exist in folder
        if (file_exists($large_image_path . $productImage->image)) {
            unlink($large_image_path . $productImage->image);
        }
        //delete medium image if it doesn't exist in folder
        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }
        //delete small image if it doesn't exist in folder
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }
        //Delete image from products table
        ProductsImage::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product Alternate Image(s) deleted successfully');
    }

    public function addAttributes(Request $request, $id=null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    //SKU check
                    $attrCountSKU = ProductsAttribute::where('sku', $val)->count();
                    if($attrCountSKU > 0){
                        return redirect('admin/add-attributes/' . $id)->with('flash_message_error', 'SKU already exists !');
                    }

                    //Size check
                    $attrCountSizes = ProductsAttribute::where(['product_id'=>$id, 'size'=>$data['size'][$key]])->count();
                    if($attrCountSizes > 0){
                        return redirect('admin/add-attributes/' . $id)->with('flash_message_error', '"'.$data['size'][$key].'" Size already exists !');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }
            return redirect('admin/add-attributes/'.$id)->with('flash_message_success','Product Attributes has been added successfully');
        }
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function editAttributes(Request $request, $id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach($data['idAttr'] as $key => $attr){
                ProductsAttribute::where(['id'=>$data['idAttr'][$key]])->update(['price'=>$data['price'][$key], 'stock'=>$data['stock'][$key]]);
            }

            return redirect()->back()->with('flash_message_success', 'Attributes successfully updated');
        }
    }

    public function addImages(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        if ($request->isMethod('post')) {
            $data = $request->all();
            if($request->hasFile('image')){
                $files = $request->file('image');
                foreach($files as $file){
                    //echo "<pre>"; print_r($data); die;
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $filename = rand(111, 99999) . '.' . $extension;
                    $large_image_path = 'images/backend_images/products/large/' . $filename;
                    $medium_image_path = 'images/backend_images/products/medium/' . $filename;
                    $small_image_path = 'images/backend_images/products/small/' . $filename;
                    Image::make($file)->save($large_image_path);
                    image::make($file)->resize(600, 600)->save($medium_image_path);
                    image::make($file)->resize(300, 300)->save($small_image_path);
                    $image->image = $filename;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }
            }
            return redirect('admin/add-images/'.$id)->with('flash_message_success','Images have been added successfully');
        }

        $productsImages = ProductsImage::where(['product_id'=>$id])->get();

        return view('admin.products.add_images')->with(compact('productDetails', 'productsImages'));
    }

    public function deleteAttribute($id = null){
        ProductsAttribute::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success','Atrribute has been deleted successfully');
    }

    public function products($url = null){

        // Show 404 page ij category doesn't exist
        $countCategory = Category::where(['url'=> $url,'status'=>1])->count();
        if($countCategory==0){
            abort(404);
        }

        $categoryDetails = Category::where(['name' => $url])->first();
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        
        if($categoryDetails->parent_id==0){
            //if name is main category
            $subCategories = Category::where(['parent_id'=> $categoryDetails->id])->get();

            foreach($subCategories as $subcat){
                $cat_ids[] = $subcat->id;
            }
            $productsAll = Product::whereIn('category_id', $cat_ids)->where('status',1)->get();
            $productsAll = json_decode(json_encode($productsAll));


            /*$cat_ids = $categoryDetails->id . ",";
            foreach($subCategories as $key =>$subcat){
                if($key==1) $cat_ids.= ",";
                $cat_ids .= $subcat->id;
            }
            //echo $cat_ids; die;
            $cat_ids = explode(",", $cat_ids);
            $productsAll = Product::whereIn('category_id', $cat_ids)->get();
            // dd($productsAll);
            //$productsAll = json_decode(json_encode($productsAll));
            //echo "<pre>"; print_r($productsAll); die;*/
        }else{
            //if name is sub category 
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->where('status', 1)->get();
        }
        
        return view('products.listing')->with(compact('categories', 'categoryDetails','productsAll'));
    }

    public function product($id = null){
        //Show only enabled products
        $productsCount = Product::where(['id'=>$id, 'status'=>1])->count();
        if($productsCount == 0){
            abort(404);
        }

        //Get product details
        $productDetails = Product::with('attributes')->where('id',$id)->first();
        $productDetails = json_decode(json_encode($productDetails));
        //echo "<pre>"; print_r($productDetails); die;

        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id'=>$productDetails->category_id])->get();
        // $relatedProducts = json_decode(json_encode($relatedProducts));
        // echo"<pre>"; print_r($relatedProducts); die;

        //Get all cat and subcat
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        //Get product alternate images
        $productAltImages = ProductsImage::where('product_id',$id)->get();
        // $productAltImages = json_decode(json_encode($productAltImages));
        // echo "<pre>"; print_r($productAltImages); die;

        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');

        return view('products.detail')->with(compact('productDetails', 'categories', 'productAltImages', 'total_stock', 'relatedProducts'));
    }

    public function getProductPrice(Request $request){
        $data = $request->all();
        $proArr = explode("-",$data['idSize']);
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        echo $proAttr->price;
    }

    public function addtocart(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;

        if(empty($data['user_email'])){
            $data['user_email'] = '';
        }
        
        $session_id = Session::get('session_id');
        if(empty($session_id)){
            $session_id = str_random(40);
            Session::put('session_id', $session_id);
        }
        
        $sizeArr = explode("-", $data['size']);

        $countProducts = DB::table('cart')->where(['product_id'=>$data['product_id'], 'size'=>$sizeArr[1] , 'session_id'=>$session_id])->count();

        if($countProducts > 0){
            return redirect()->back()->with('flash_message_error','Product already exists in Cart');
        }else{
            $getSKU = ProductsAttribute::select('sku')->where(['product_id'=> $data['product_id'], 'size' => $sizeArr[1]])->first();

            DB::table('cart')->insert(['product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_code' => $getSKU->sku, 'price' => $data['price'], 'size' => $sizeArr[1], 'quantity' => $data['quantity'], 'user_email' => $data['user_email'], 'session_id' => $session_id]);
        }
        
        return redirect('cart')->with('flash_message_success','Product has been added in Cart!');
    }

    public function cart(){
        if(Auth::check()){
            $user_email = Auth::User()->email;
            $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
        }else{
            $session_id = Session::get('session_id');
            $userCart = DB::table('cart')->where(['session_id' => $session_id])->get();
        }
        foreach($userCart as $key => $product){
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // echo "<pre>";print_r($userCart);
        return view('products.cart')->with(compact('userCart'));
    }

    public function deleteCartProduct($id = null){
        DB::table('cart')->where('id', $id)->delete();
        return redirect('cart')->with('flash_message_success','Product removed from Cart');
    }

    public function updateCartQuantity($id=null, $quantity=null){
        $getCartDetails = DB::table('cart')->where('id',$id)->first();
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();
        $updated_quantity = $getCartDetails->quantity+$quantity;
        if($getAttributeStock->stock >= $updated_quantity){
            DB::table('cart')->where('id', $id)->increment('quantity', $quantity);
            return redirect('cart')->with('flash_message_success', 'Quantity updated successfully');
        }else{
            return redirect('cart')->with('flash_message_error', 'Maximum stock reached !');
        }

        
    }

    public function checkout(Request $request){
        $user_id = Auth::User()->id;
        $user_email = Auth::User()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();
        $shippingDetails = DeliveryAdresses::where('user_id', $user_id)->first();
        $shippingCount = DeliveryAdresses::where('user_id', $user_id)->count();

        //Checking if shipping adress exist

        if ((DeliveryAdresses::where('user_id', $user_id)->count())>0){

            $shippingCount = DeliveryAdresses::where('user_id', $user_id)->count();
            if ($shippingCount > 0) {
                $shippingDetails = DeliveryAdresses::where('user_id', $user_id)->first();
            }
        }
        elseif($request->isMethod('get')){
            return view('products.checkout',compact('userDetails','countries', 'shippingDetails'));
            }


        


        //update cart tabel with user email
        $session_id = Session::get('session_id');
        DB::table('cart')->where(['session_id'=>$session_id])->update(['user_email'=>$user_email]);
        //Return to checkout page if any field is empty
        if($request->isMethod('post')){
            $data = $request->all();
            // dd($data);
            if(empty($data['billing_name']) || empty($data['billing_adress']) || empty($data['billing_city']) ||empty($data['billing_state']) ||empty($data['billing_country']) ||empty($data['billing_pincode']) ||empty($data['billing_mobile']) ||empty($data['shipping_name']) ||empty($data['shipping_adress']) ||empty($data['shipping_city']) ||empty($data['shipping_state']) ||empty($data['shipping_country']) ||empty($data['shipping_pincode']) ||empty($data['shipping_mobile'])){
                return redirect()->back()->with('flash_message_error', 'All fields are required !');
            }
        

            //Update user details
            User::where('id', $user_id)->update(['name'=>$data['billing_name'], 'Adress'=>$data['billing_adress'], 'City'=>$data['billing_city'], 'State'=>$data['billing_state'], 'Country'=>$data['billing_country'], 'Pincode'=>$data['billing_pincode'], 'mobile'=>$data['billing_mobile']]);

            if($shippingCount > 0){
                //Update shipping adress
                DeliveryAdresses::where('user_id', $user_id)->update(['name' => $data['shipping_name'], 'Adress' => $data['shipping_adress'], 'City' => $data['shipping_city'], 'State' => $data['shipping_state'], 'Country' => $data['shipping_country'], 'Pincode' => $data['shipping_pincode'], 'mobile' => $data['shipping_mobile']]);
            }else{
                //Add new shipping adress
                $shipping = new DeliveryAdresses();
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping_name = $data['shipping_name'];
                $shipping_adress = $data['shipping_adress'];
                $shipping_city = $data['shipping_city'];
                $shipping_state = $data['shipping_state'];
                $shipping_country = $data['shipping_country'];
                $shipping_pincode = $data['shipping_pincode'];
                $shipping_mobile = $data['shipping_mobile'];
                $shipping->save();
            }

            return redirect()->action('ProductsController@orderReview');

        }
        // return $userDetails->name;

        // return view('products.checkout')->with(compact('userDetails', 'countries', 'shippingDetails'));
        return view('products.checkout',compact('userDetails', 'countries', 'shippingDetails'));
    }

    public function orderReview(){
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id', $user_id)->first();
        $shippingDetails = DeliveryAdresses::where('user_id', $user_id)->first();
        $shippingDetails = json_decode(json_encode($shippingDetails));
        // dd($shippingDetails);
        $userCart = DB::table('cart')->where(['user_email' => $user_email])->get();
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        // dd($userCart);
        return view('products.order_review')->with(compact('shippingDetails', 'userDetails', 'userCart'));
    }

    public function placeOrder (Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;
            //Getting shipping adress
            $shippingDetails = DeliveryAdresses::where(['user_email'=>$user_email])->first();
            
            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->adress = $shippingDetails->adress;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->pincode = $shippingDetails->pincode;
            $order->country = $shippingDetails->country;
            $order->mobile = $shippingDetails->mobile;
            // $order->payment_method = $data['payment_method'];
            $order->total_amount = $data['total_amount'];
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();
            $cartProducts = DB::table('cart')->where(['user_email'=>$user_email])->get();
            foreach($cartProducts as $pro){
                $cartPro = new OrdersProduct();
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_size = $pro->size;
                $cartPro->product_price = $pro->price;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();
            }

            Session::put('order_id', $order_id);
            Session::put('total_amount', $data['total_amount']);

            return redirect('/thanks');
        }
    }

    public function thanks (Request $request){
        $user_email = Auth::user()->email;
        DB::table('cart')->where('user_email', $user_email)->delete();
        return view('products.thanks');
    }

    public function userOrders(Request $request){
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id', $user_id)->orderBy('created_at','DESC')->get();
        return view('products.user_orders')->with(compact('orders'));
    }

    public function userOrderDetails($order_id){
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        
        $orderDetails = json_decode(json_encode($orderDetails));
        return view('products.user_order_details')->with(compact('orderDetails'));
    }

    public function ViewOrders(){
        $orders = Order::with('orders')->orderBy('id', 'DESC')->get();
        $orders = json_decode(json_encode($orders));
        // dd($orders);
        return view('admin.orders.view_orders')->with(compact('orders'));
    }

    public function ViewOrderDetails($order_id){
        $orderDetails = Order::with('order')->where('id', $order_id)->first();
        $orderDetails = json_decode(json_encode($orderDetails));
        return view('admin.orders.order_details')->with(compact('$orderDetails'));
    }
}
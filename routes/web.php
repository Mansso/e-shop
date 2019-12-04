<?php
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::match(['get', 'post'], '/admin', 'AdminController@login');

Auth::routes(['verify'=>true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

//index page
Route::get('/', 'IndexController@index');

//Category listing Page
Route::get('/products/{url}', 'ProductsController@products');

//Product Detail Page
Route::get('product/{id}','ProductsController@product');

//Get Product Attribute Price
Route::get('/get-product-price', 'ProductsController@getProductPrice');

//Add to cart page
Route::match(['get', 'post'], '/add-cart', 'ProductsController@addtocart');

//Cart page
Route::match(['get', 'post'], '/cart', 'ProductsController@cart');

//Deleting products from cart page
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct');

//Update product quantity in cart
Route::get('/cart/update-quantity/{id}/{quantity}', 'ProductsController@updateCartQuantity');

//Register/login
Route::get('/login-register', 'UsersController@userLoginRegister');

//Users register form submit
Route::post('/user-register', 'UsersController@register');

//Users login form submit
Route::post('/user-login', 'UsersController@login');

//Users logout
Route::get('/user-logout', 'UsersController@logout');

//Ater login
Route::group(['middleware' => ['frontlogin']], function(){
    //User Account
    Route::match(['get', 'post'], '/account', 'UsersController@account');

    //Check user current password
    Route::post('/check-user-pwd', 'UsersController@chkUserPassword');

    //Update user password
    Route::post('update-user-pwd', 'UsersController@updatePassword');

    //Checkout page
    Route::match(['get', 'post'], '/checkout', 'ProductsController@checkout');

    //Order review page
    Route::match(['get', 'post'], '/order-review', 'ProductsController@orderReview');

    //place Order
    Route::match(['get', 'post'], '/place-order', 'ProductsController@placeOrder');

    //Thanks page
    Route::get('/thanks', 'ProductsController@thanks');

    //User order page
    Route::get('/orders', 'ProductsController@userOrders');

    //User order page
    Route::get('/orders/{id}', 'ProductsController@userOrderDetails');
});

//Apply Coupon
Route::post('/cart/apply-coupon','ProductsController@applyCoupon');

//Check if user already exists
Route::match(['get', 'post'], '/check-email', 'UsersController@checkEmail');

Route::group(['middleware' => ['auth']],function(){
    Route::get('/admin/dashboard', 'AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check_pwd','AdminController@chkPassword');
    Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');

    // Categories Routes (Admin)
    Route::match(['get','post'],'/admin/add-category','CategoryController@addCategory');
    Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
    Route::get('/admin/view-category','CategoryController@viewCategories');

    // Products Routes
    Route::match(['get', 'post'], '/admin/add-product', 'ProductsController@addProduct');
    Route::match(['get','post'],'/admin/edit-product/{id}','ProductsController@editProduct');
    Route::get('/admin/view-products','ProductsController@viewProducts');
    Route::get('/admin/delete-product/{id}','ProductsController@deleteProduct');
    Route::get('/admin/delete-product-image/{id}','ProductsController@deleteProductImage');
    Route::get('/admin/delete-alt-image/{id}', 'ProductsController@deleteAltImage');

    //Products Attributes Routes
    Route::match(['get','post'],'/admin/add-attributes/{id}','ProductsController@addAttributes');
    Route::match(['get','post'],'/admin/edit-attributes/{id}','ProductsController@editAttributes');
    Route::match(['get', 'post'], '/admin/add-images/{id}', 'ProductsController@addImages');
    Route::get('/admin/delete-attribute/{id}','ProductsController@deleteAttribute');

    //Coupons Route
    Route::match(['get','post'],'admin/add-coupon','CouponsController@addCoupon');
    Route::match(['get','post'], '/admin/edit-coupon/{id}','CouponsController@editCoupon');
    Route::get('/admin/view-coupons','CouponsController@viewCoupon');
    Route::get('/admin/delete-coupon/{id}', 'CouponsController@deleteCoupon');

    //Admin Banners Routes
    Route::match(['get','post'], '/admin/add-banner', 'BannersController@addBanner');
    Route::match(['get','post'],'/admin/edit-banner/{id}','BannersController@editBanner');
    Route::get('admin/view-banners', 'BannersController@viewBanners');
    Route::get('admin/delete-banner/{id}', 'BannersController@deleteBanner');

    //Add orders routes
    Route::get('/admin/view-orders', 'ProductsController@ViewOrders');

    //Add orders Details routes
    Route::get('/admin/view-order/{id}', 'ProductsController@ViewOrderDetails');
});



Route::get('/logout', 'AdminController@logout');
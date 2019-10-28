@extends('layouts.frontLayout.front_design')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Order Review</li>
            </ol>
        </div>
        <div class="shopper-informations">
            <div class="row">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="login-form">
                    <h2>Billing Details</h2>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->name }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->Adress }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->City }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->State }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{$userDetails->Country}}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->Pincode }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $userDetails->mobile }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="signup-form">
                    <h2>Shipping Details</h2>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->name }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->adress }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->city }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->state }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->country }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->pincode }}</div>
                    </div>
                    <div class="form-group">
                        <div class="form-control">{{ $shippingDetails->mobile }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="review-payment">
            <h2>Review & Payment</h2>
        </div>
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description"></td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $total_amount = 0; ?>
                    @foreach($userCart as $cart)
                    <tr>
                        <td class="cart_product">
                            <a href=""><img style="width:150px" src={{ asset('images/backend_images/products/small/'.$cart->image) }} alt=""></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="">{{ $cart->product_name }}</a></h4>
                            <p>Code : {{ $cart->product_code }}</p>
                        </td>
                        <td class="cart_price">
                            <p>$ {{ $cart->price }}</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                {{ $cart->quantity }}
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">$ {{ $cart->price*$cart->quantity }}</p>
                        </td>
                    </tr>
                    <?php $total_amount = $total_amount + ($cart->price * $cart->quantity); ?>
                    @endforeach


                    <tr>
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">
                            <table class="table table-condensed total-result">
                                <tr>
                                    <td>Total</td>
                                    <td><span>$ {{ $total_amount }}</span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <form name="paymentForm" id="paymentForm" action="{{ url('/place-order') }}" method="post">{{ csrf_field() }}
            <input type="hidden" name="total_amount" value="{{ $total_amount }}">
            <span style="float:right"><button class="btn btn-success" type="submit">Order</button></span>
        </form>
        <!-- <div class="payment-options">
            <span>
                <label><input type="checkbox"> Direct Bank Transfer</label>
            </span>
            <span>
                <label><input type="checkbox"> Check Payment</label>
            </span>
            <span>
                <label><input type="checkbox"> Paypal</label>
            </span>
        </div> -->
    </div>
</section>
<!--/#cart_items-->

@endsection
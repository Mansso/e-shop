@extends('layouts.frontLayout.front_design')
@section('content')

<section id="form" style="margin-top:60px">
    <!--form-->
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Check out</li>
            </ol>
        </div>
        @if(Session::has('flash_message_error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{!! session('flash_message_error') !!}</strong>
            </div>
        @endif
        <form action="{{ url('/checkout') }}" method="post">{{ csrf_field() }}
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <!--login form-->
                        <h2>Bill to</h2>
                        <div class="form-group">
                            <input name="billing_name" id="billing_name" value="{{ $userDetails->name }}" type="text" placeholder="Name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_adress" id="billing_adress" value="{{ $userDetails->Adress }}" type="text" placeholder="Adress" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_city" id="billing_city" value="{{ $userDetails->City }}" type="text" placeholder="City" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_state" id="billing_state" value="{{ $userDetails->State }}" type="text" placeholder="State" class="form-control" />
                        </div>
                        <div class="form-group">
                            <select id="billing_country" name="billing_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_name }}" @if($country->country_name == $userDetails->Country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="billing_pincode" id="billing_pincode" value="{{ $userDetails->Pincode }}" type="text" placeholder="Pincode" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="billing_mobile" id="billing_mobile" value="{{ $userDetails->mobile }}" type="text" placeholder="Mobile" class="form-control" />
                        </div>
                        <div class="form-check">
                            <input value="{{ $userDetails->name }}" type="checkbox" class="form-check-input" id="billtoship">
                            <label for="billtoship" class="form-check-label">Billing adress is the same as the shipping adress</label>
                        </div>

                        <!--/login form-->
                    </div>
                </div>

                <div class="col-sm-1">
                    <h2></h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <!--sign up form-->
                        <h2>Ship to</h2>
                        <div class="form-group">
                            <input name="shipping_name" id="shipping_name" value="{{ $shippingDetails->name }}" type="text" placeholder="Name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="shipping_adress" id="shipping_adress" value="{{ $shippingDetails->adress }}" type="text" placeholder="Adress" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="shipping_city" id="shipping_city" value="{{ $shippingDetails->city }}" type="text" placeholder="City" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="shipping_state" id="shipping_state" value="{{ $shippingDetails->state }}" type="text" placeholder="State" class="form-control" />
                        </div>
                        <div class="form-group">
                            <select id="shipping_country" name="shipping_country" class="form-control">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_name }}"@if($country->country_name == $userDetails->Country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="shipping_pincode" id="shipping_pincode" value="{{ $shippingDetails->pincode }}" type="text" placeholder="Pincode" class="form-control" />
                        </div>
                        <div class="form-group">
                            <input name="shipping_mobile" id="shipping_mobile" value="{{ $shippingDetails->mobile }}" type="text" placeholder="Mobile" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-success">Checkout</button>
                    </div>
                    <!--/sign up form-->
                </div>
            </div>
        </form>
    </div>
</section>
<!--/form-->

@endsection
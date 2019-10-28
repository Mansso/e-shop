@extends('layouts.frontLayout.front_design')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Thanks</li>
            </ol>
        </div>
    </div>
</section>


<section id="do_action">
    <div class="container">
        <div class="heading" align="center">
            <h3>Your order has been placed successfully !</h3>
            <p>Order number: {{ Session::get('order_id') }} and total payable about: ${{ Session::get('total_amount') }}</p>
        </div>
    </div>
</section>


@endsection

<?php
Session::forget('total_amount');
Session::forget('order_id');
?>
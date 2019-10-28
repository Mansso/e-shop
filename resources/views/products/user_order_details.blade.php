@extends('layouts.frontLayout.front_design')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="{{ url('/orders') }}">Orders</a></li>
                <li class="active">{{ $orderDetails->id }}</li>
            </ol>
        </div>
    </div>
</section>


<section id="do_action">
    <div class="container">
        <div class="heading" align="center">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Product code</th>
                        <th>Product name</th>
                        <th>Product size</th>
                        <th>Product price</th>
                        <th>Product quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderDetails->orders as $pro)
                    <tr>
                        <td>{{ $pro->product_code }}</td>
                        <td>{{ $pro->product_name }}</td>
                        <td>{{ $pro->product_size }}</td>
                        <td>{{ $pro->product_price }}</td>
                        <td>{{ $pro->product_qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>


@endsection
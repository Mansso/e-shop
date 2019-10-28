@extends('layouts.frontLayout.front_design')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Orders</li>
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
                        <th>Order id</th>
                        <th>Ordered products</th>
                        <th>Total amount</th>
                        <th>Created on</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                @foreach($order->orders as $pro)
                                    <a href="{{ url('/orders/'.$order->id) }}">{{ $pro->product_code }}</a></br>
                                @endforeach
                            </td>
                            <td>{{ $order->total_amount }}</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>


@endsection
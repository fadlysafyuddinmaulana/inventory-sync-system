@extends('layouts.app')

@section('page_title', 'Product Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Product ID:</dt>
                        <dd class="col-sm-9">{{ $product->id }}</dd>

                        <dt class="col-sm-3">Name:</dt>
                        <dd class="col-sm-9"><strong>{{ $product->name }}</strong></dd>

                        <dt class="col-sm-3">SKU:</dt>
                        <dd class="col-sm-9">{{ $product->default_code ?? 'N/A' }}</dd>

                        <dt class="col-sm-3">Price:</dt>
                        <dd class="col-sm-9">Rp {{ number_format($product->list_price, 0, ',', '.') }}</dd>

                        <dt class="col-sm-3">Quantity:</dt>
                        <dd class="col-sm-9">
                            <span class="badge badge-lg badge-success">{{ number_format($product->qty_on_hand, 0) }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('products') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

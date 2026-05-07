@extends('layouts.app')

@section('page_title', 'Product Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-box-open"></i> Product Information
                        </h3>
                        <a href="{{ route('products') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Product Image -->
                    <div class="product-image-container mb-4 text-center">
                        @if (!empty($product['image_url_large']))
                            <img src="{{ $product['image_url_large'] }}" alt="{{ $product['name'] }}"
                                onerror="this.src='{{ asset(config('odoo.image.fallback', 'images/no-image.png')) }}';"
                                class="img-fluid"
                                style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-height: 400px;">
                        @else
                            <div class="img-placeholder"
                                style="width:100%;height:400px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;border:1px solid #ddd;border-radius:8px;color:#999;">
                                <div style="text-align:center;">
                                    <div style="font-size:48px;margin-bottom:10px;">📷</div>
                                    <div>No image available</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Product Details -->
                    <dl class="row">
                        <dt class="col-sm-4">Product ID:</dt>
                        <dd class="col-sm-8">
                            <code>{{ $product['id'] }}</code>
                        </dd>

                        <dt class="col-sm-4">Product Name:</dt>
                        <dd class="col-sm-8">
                            <strong>{{ $product['name'] }}</strong>
                        </dd>

                        <dt class="col-sm-4">SKU / Code:</dt>
                        <dd class="col-sm-8">
                            <code class="bg-light p-2 rounded">{{ $product['default_code'] ?? 'N/A' }}</code>
                        </dd>

                        <dt class="col-sm-4">Price:</dt>
                        <dd class="col-sm-8">
                            <strong class="text-success">Rp
                                {{ number_format($product['list_price'] ?? 0, 0, ',', '.') }}</strong>
                        </dd>

                        <dt class="col-sm-4">Quantity On Hand:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem;">
                                {{ number_format($product['qty_on_hand'] ?? 0, 0) }} units
                            </span>
                        </dd>

                        <dt class="col-sm-4">Product Type:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-secondary">
                                {{ ucfirst($product['type'] ?? 'product') }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Category:</dt>
                        <dd class="col-sm-8">
                            @if ($product['categ_id'] && is_array($product['categ_id']))
                                {{ $product['categ_id'][1] ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </dd>

                        @if (!empty($product['description']))
                            <dt class="col-sm-4">Description:</dt>
                            <dd class="col-sm-8">
                                <p class="text-muted">{{ Str::limit($product['description'], 200) }}</p>
                            </dd>
                        @endif

                        <dt class="col-sm-4">Created:</dt>
                        <dd class="col-sm-8">
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($product['create_date'])->format('d M Y, H:i') }}
                            </small>
                        </dd>

                        <dt class="col-sm-4">Last Updated:</dt>
                        <dd class="col-sm-8">
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($product['write_date'])->format('d M Y, H:i') }}
                            </small>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Actions Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('products') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-list"></i> Product List
                    </a>
                    <button class="btn btn-info btn-block" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Information
                    </h5>
                </div>
                <div class="card-body small text-muted">
                    <p><strong>Data Source:</strong> Odoo 19</p>
                    <p><strong>Last Sync:</strong> {{ now()->format('d M Y, H:i') }}</p>
                    <p>This product information is synchronized from your Odoo system.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn-block,
            .card-header .btn,
            .btn {
                display: none;
            }

            .product-image-container {
                margin: 20px 0;
            }
        }
    </style>
@endsection

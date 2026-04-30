@extends('layouts.app')

@section('title', 'Pergerakan Barang')
@section('page-title', 'Pergerakan Barang')

@section('breadcrumb')
    <nav class="text-sm text-gray-600 dark:text-gray-400 mt-2">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-900 dark:hover:text-gray-300">Dashboard</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 dark:text-gray-300">Pergerakan Barang</span>
    </nav>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Masuk Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">45</p>
            <p class="text-blue-600 text-sm font-medium mt-4">Total barang masuk</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Keluar Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">28</p>
            <p class="text-green-600 text-sm font-medium mt-4">Total barang keluar</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Retur Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">3</p>
            <p class="text-purple-600 text-sm font-medium mt-4">Total retur barang</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pergerakan Barang</h3>
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                + Catat Pergerakan
            </button>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex gap-4">
            <input type="text" placeholder="Cari produk..."
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option>Semua Tipe</option>
                <option>Masuk</option>
                <option>Keluar</option>
                <option>Retur</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Produk</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Tipe</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Qty</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Dari/Ke</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">29 Apr 2026</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Laptop Dell XPS</td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs font-medium">Masuk</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">5</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Supplier A</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Penerimaan barang</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">29 Apr 2026</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Monitor LG 27"</td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-full text-xs font-medium">Keluar</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">2</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Toko Retail</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Pengiriman</td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">28 Apr 2026</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Keyboard Mechanical</td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full text-xs font-medium">Retur</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">1</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Pelanggan</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Barang rusak</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

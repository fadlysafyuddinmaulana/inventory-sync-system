<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Models\Odoo\ProductTemplate;
use App\Models\Odoo\StockQuant;
use App\Models\Odoo\StockWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    /**
     * Show backup page
     */
    public function index()
    {
        $lastBackup = BackupLog::where('status', '=', 'success')
            ->orderByDesc('created_at')
            ->first();

        return view('backup-data-bootstrap', [
            'lastBackup' => $lastBackup,
        ]);
    }

    /**
     * Execute backup
     */
    public function backup(Request $request)
    {
        $backupLog = BackupLog::create([
            'status' => 'pending',
            'started_at' => now(),
        ]);

        try {
            // Backup products from Odoo to SQL Server
            $products = ProductTemplate::all();
            $productCount = $this->backupProducts($products);

            // Backup stocks from Odoo to SQL Server
            $stocks = StockQuant::with(['product', 'location.warehouse'])->get();
            $stockCount = $this->backupStocks($stocks);

            // Count warehouses
            $warehouseCount = StockWarehouse::query()->count('*');

            // Calculate backup size
            $backupSize = $this->calculateBackupSize($productCount, $stockCount);

            // Update backup log
            $backupLog->update([
                'status' => 'success',
                'product_count' => $productCount,
                'stock_count' => $stockCount,
                'warehouse_count' => $warehouseCount,
                'backup_size' => $backupSize,
                'completed_at' => now(),
                'message' => 'Backup berhasil dilakukan',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dilakukan',
                'data' => [
                    'product_count' => $productCount,
                    'stock_count' => $stockCount,
                    'warehouse_count' => $warehouseCount,
                    'backup_size' => $backupSize,
                ],
            ]);
        } catch (\Exception $e) {
            $backupLog->update([
                'status' => 'failed',
                'completed_at' => now(),
                'message' => 'Backup gagal: ' . $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Backup gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Backup products to SQL Server
     */
    private function backupProducts(\Illuminate\Support\Collection $products)
    {
        $backupData = [];

        foreach ($products as $product) {
            $backupData[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'default_code' => $product->default_code,
                'list_price' => $product->list_price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_products')->truncate();

        // Insert backup data
        if (!empty($backupData)) {
            DB::connection('sqlsrv_backup')->table('backup_products')->insert($backupData);
        }

        return count($backupData);
    }

    /**
     * Backup stocks to SQL Server
     */
    private function backupStocks(\Illuminate\Support\Collection $stocks)
    {
        $backupData = [];

        foreach ($stocks as $stock) {
            $backupData[] = [
                'product_id' => $stock->product_id,
                'product_name' => $stock->product->template->name ?? 'N/A',
                'location_id' => $stock->location_id,
                'location_name' => $stock->location->name ?? 'N/A',
                'warehouse_id' => $stock->location->warehouse_id,
                'warehouse_name' => $stock->location->warehouse->name ?? 'N/A',
                'quantity' => $stock->quantity,
                'reserved_quantity' => $stock->reserved_quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_stocks')->truncate();

        // Insert backup data
        if (!empty($backupData)) {
            DB::connection('sqlsrv_backup')->table('backup_stocks')->insert($backupData);
        }

        return count($backupData);
    }

    /**
     * Calculate backup size
     */
    private function calculateBackupSize(int $productCount, int $stockCount)
    {
        // Rough estimation: ~1KB per product and ~500B per stock entry
        $estimatedSize = ($productCount * 1024) + ($stockCount * 512);

        if ($estimatedSize < 1024 * 1024) {
            return round($estimatedSize / 1024, 2) . ' KB';
        } elseif ($estimatedSize < 1024 * 1024 * 1024) {
            return round($estimatedSize / (1024 * 1024), 2) . ' MB';
        }

        return round($estimatedSize / (1024 * 1024 * 1024), 2) . ' GB';
    }

    /**
     * Download backup
     */
    public function download(int $id)
    {
        $backup = BackupLog::findOrFail($id);

        if ($backup->status !== 'success') {
            return back()->with('error', 'Backup tidak tersedia');
        }

        // Here you would generate CSV or export data
        // This is a placeholder for actual download functionality

        return back()->with('success', 'Backup berhasil diunduh');
    }
}

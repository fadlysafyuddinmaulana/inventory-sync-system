<?php

namespace App\Http\Controllers;

use App\Models\BackupLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\OdooService;

class BackupController extends Controller
{
    private OdooService $odooService;

    public function __construct(OdooService $odooService)
    {
        $this->odooService = $odooService;
    }

    /**
     * Show backup page
     */
    public function index()
    {
        $lastBackup = BackupLog::where('status', 'success')
            ->orderByDesc('created_at')
            ->first();

        return view('backup.index', [
            'lastBackup' => $lastBackup,
        ]);
    }

    /**
     * Execute backup
     */
    public function backup(Request $request)
    {
        $backupLog = new BackupLog();
        $backupLog->status = 'pending';
        $backupLog->started_at = Carbon::now();
        $backupLog->save();

        try {
            // Backup products from Odoo via API to SQL Server
            $productIds = $this->odooService->execute('product.template', 'search', [[]]);

            $products = [];
            if (!empty($productIds) && is_array($productIds)) {
                // include image_1920 (Odoo 14+), avoid requesting non-existing 'image' field
                $products = $this->odooService->execute('product.template', 'read', [$productIds, ['id', 'name', 'default_code', 'list_price', 'image_1920']]);
            }

            $productCount = $this->backupProducts($products);

            // Backup stocks from Odoo via API to SQL Server
            $stocks = $this->odooService->execute('stock.quant', 'search_read', [[], ['id', 'product_id', 'quantity', 'reserved_quantity', 'location_id'], 0, 0]);

            $stockCount = $this->backupStocks($stocks);

            // Get warehouse count via Odoo API
            $warehouseIds = $this->odooService->execute('stock.warehouse', 'search', [[]]);
            $warehouseCount = is_array($warehouseIds) ? count($warehouseIds) : 0;

            // Update backup log
            $backupLog->update([
                'status' => 'success',
                'product_count' => $productCount,
                'stock_count' => $stockCount,
                'warehouse_count' => $warehouseCount,
                'total_data' => $productCount + $stockCount,
                'backup_size' => null,
                'completed_at' => Carbon::now(),
                'message' => 'Backup berhasil dilakukan',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dilakukan',
                'data' => [
                    'product_count' => $productCount,
                    'stock_count' => $stockCount,
                    'warehouse_count' => $warehouseCount,
                ],
            ]);
        } catch (\Exception $e) {
            $backupLog->update([
                'status' => 'failed',
                'product_count' => 0,
                'stock_count' => 0,
                'warehouse_count' => 0,
                'total_data' => 0,
                'completed_at' => Carbon::now(),
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
    private function backupProducts(array $products): int
    {
        $backupData = [];

        foreach ($products as $product) {
            // image may be in image_1920 or image field; store as-is (base64 or url)
            $image = data_get($product, 'image_1920') ?: data_get($product, 'image');

            $backupData[] = [
                'product_id' => data_get($product, 'id'),
                'name' => data_get($product, 'name'),
                'code' => (string) data_get($product, 'default_code', ''),
                'price' => data_get($product, 'list_price'),
                'image' => $image,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_products')->truncate();

        // Insert backup data in chunks
        if (!empty($backupData)) {
            foreach (array_chunk($backupData, 100) as $chunk) {
                DB::connection('sqlsrv_backup')->table('backup_products')->insert($chunk);
            }
        }

        return count($backupData);
    }

    /**
     * Backup stocks to SQL Server
     */
    private function backupStocks(array $stocks): int
    {
        $backupData = [];

        foreach ($stocks as $stock) {
            // product_id and location_id from Odoo API often come as [id, display_name]
            $productField = data_get($stock, 'product_id');
            $locationField = data_get($stock, 'location_id');

            $productId = is_array($productField) ? ($productField[0] ?? null) : data_get($stock, 'product_id');
            $productName = is_array($productField) ? ($productField[1] ?? null) : data_get($stock, 'product_name');

            $locationId = is_array($locationField) ? ($locationField[0] ?? null) : data_get($stock, 'location_id');
            $locationName = is_array($locationField) ? ($locationField[1] ?? null) : data_get($stock, 'location_name');

            $backupData[] = [
                'product_id' => $productId,
                'product_name' => $productName,
                'location_id' => $locationId,
                'location_name' => $locationName,
                'warehouse_id' => null,
                'warehouse_name' => null,
                'quantity' => data_get($stock, 'quantity', 0),
                'reserved_quantity' => data_get($stock, 'reserved_quantity', 0),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Clear existing backup data
        DB::connection('sqlsrv_backup')->table('backup_stocks')->truncate();

        // Insert backup data in chunks
        if (!empty($backupData)) {
            foreach (array_chunk($backupData, 100) as $chunk) {
                DB::connection('sqlsrv_backup')->table('backup_stocks')->insert($chunk);
            }
        }

        return count($backupData);
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
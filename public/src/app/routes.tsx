import { createBrowserRouter, Navigate } from "react-router";
import { LoginPage } from "./pages/LoginPage";
import { DashboardLayout } from "./components/DashboardLayout";
import { DashboardPage } from "./pages/DashboardPage";
import { ProductsPage } from "./pages/ProductsPage";
import { WarehouseStockPage } from "./pages/WarehouseStockPage";
import { StockMovementsPage } from "./pages/StockMovementsPage";
import { BackupDataPage } from "./pages/BackupDataPage";
import { BackupLogsPage } from "./pages/BackupLogsPage";

export const router = createBrowserRouter([
  {
    path: "/login",
    Component: LoginPage,
  },
  {
    path: "/",
    Component: DashboardLayout,
    children: [
      { index: true, element: <Navigate to="/dashboard" replace /> },
      { path: "dashboard", Component: DashboardPage },
      { path: "products", Component: ProductsPage },
      { path: "warehouse-stock", Component: WarehouseStockPage },
      { path: "stock-movements", Component: StockMovementsPage },
      { path: "backup-data", Component: BackupDataPage },
      { path: "backup-logs", Component: BackupLogsPage },
    ],
  },
]);

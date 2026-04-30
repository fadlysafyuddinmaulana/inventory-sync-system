import { useEffect } from "react";
import { Outlet, useNavigate, useLocation, Link } from "react-router";
import { useAuth } from "../context/AuthContext";
import {
  LayoutDashboard,
  Package,
  Warehouse,
  ArrowLeftRight,
  Database,
  FileText,
  LogOut,
} from "lucide-react";

const menuItems = [
  { path: "/dashboard", label: "Dashboard", icon: LayoutDashboard },
  { path: "/products", label: "Products", icon: Package },
  { path: "/warehouse-stock", label: "Warehouse Stock", icon: Warehouse },
  { path: "/stock-movements", label: "Stock Movements", icon: ArrowLeftRight },
  { path: "/backup-data", label: "Backup Data", icon: Database },
  { path: "/backup-logs", label: "Backup Logs", icon: FileText },
];

export function DashboardLayout() {
  const { isAuthenticated, username, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    if (!isAuthenticated) {
      navigate("/login");
    }
  }, [isAuthenticated, navigate]);

  if (!isAuthenticated) {
    return null;
  }

  const handleLogout = () => {
    logout();
    navigate("/login");
  };

  const currentPage = menuItems.find((item) => item.path === location.pathname);
  const pageTitle = currentPage?.label || "Dashboard";

  return (
    <div className="flex h-screen bg-gray-50">
      {/* Sidebar */}
      <aside className="w-64 bg-white border-r border-gray-200 flex flex-col">
        <div className="p-6 border-b border-gray-200">
          <h1 className="text-xl text-gray-900">Warehouse System</h1>
          <p className="text-xs text-gray-500 mt-1">Inventory Management</p>
        </div>

        <nav className="flex-1 p-4 space-y-1">
          {menuItems.map((item) => {
            const Icon = item.icon;
            const isActive = location.pathname === item.path;

            return (
              <Link
                key={item.path}
                to={item.path}
                className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-colors ${
                  isActive
                    ? "bg-blue-50 text-blue-700"
                    : "text-gray-700 hover:bg-gray-50"
                }`}
              >
                <Icon className="w-5 h-5" />
                <span className="text-sm">{item.label}</span>
              </Link>
            );
          })}
        </nav>

        <div className="p-4 border-t border-gray-200">
          <div className="text-xs text-gray-500">Databases Connected</div>
          <div className="mt-2 space-y-1 text-xs">
            <div className="flex items-center gap-2">
              <div className="w-2 h-2 bg-green-500 rounded-full"></div>
              <span className="text-gray-700">Odoo (PostgreSQL)</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-2 h-2 bg-green-500 rounded-full"></div>
              <span className="text-gray-700">MySQL (Auth)</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-2 h-2 bg-green-500 rounded-full"></div>
              <span className="text-gray-700">SQL Server (Backup)</span>
            </div>
          </div>
        </div>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Topbar */}
        <header className="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
          <h2 className="text-xl text-gray-900">{pageTitle}</h2>

          <div className="flex items-center gap-4">
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                <span className="text-white text-sm">
                  {username?.charAt(0).toUpperCase()}
                </span>
              </div>
              <span className="text-sm text-gray-700">{username}</span>
            </div>

            <button
              onClick={handleLogout}
              className="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <LogOut className="w-4 h-4" />
              Logout
            </button>
          </div>
        </header>

        {/* Page Content */}
        <main className="flex-1 overflow-auto p-8">
          <Outlet />
        </main>
      </div>
    </div>
  );
}

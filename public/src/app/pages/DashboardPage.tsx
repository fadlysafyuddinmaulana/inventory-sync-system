import { Package, Warehouse, TrendingUp, ArrowLeftRight } from "lucide-react";
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from "recharts";

const summaryData = [
  { label: "Total Products", value: "1,234", icon: Package, color: "bg-blue-600" },
  { label: "Total Stock", value: "45,678", icon: Warehouse, color: "bg-green-600" },
  { label: "Total Warehouses", value: "12", icon: TrendingUp, color: "bg-purple-600" },
  { label: "Total Movements", value: "3,456", icon: ArrowLeftRight, color: "bg-orange-600" },
];

const chartData = [
  { name: "WH-A", stock: 8500 },
  { name: "WH-B", stock: 6200 },
  { name: "WH-C", stock: 7800 },
  { name: "WH-D", stock: 5400 },
  { name: "WH-E", stock: 9100 },
  { name: "WH-F", stock: 4900 },
];

export function DashboardPage() {
  return (
    <div className="space-y-8">
      {/* Summary Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {summaryData.map((item) => {
          const Icon = item.icon;
          return (
            <div key={item.label} className="bg-white p-6 rounded-xl border border-gray-200">
              <div className="flex items-center justify-between">
                <div>
                  <p className="text-sm text-gray-600">{item.label}</p>
                  <p className="text-3xl text-gray-900 mt-2">{item.value}</p>
                </div>
                <div className={`${item.color} p-3 rounded-lg`}>
                  <Icon className="w-6 h-6 text-white" />
                </div>
              </div>
            </div>
          );
        })}
      </div>

      {/* Chart */}
      <div className="bg-white p-6 rounded-xl border border-gray-200">
        <h3 className="text-lg text-gray-900 mb-6">Stock by Warehouse</h3>
        <ResponsiveContainer width="100%" height={300}>
          <BarChart data={chartData}>
            <CartesianGrid strokeDasharray="3 3" stroke="#e5e7eb" />
            <XAxis dataKey="name" stroke="#6b7280" />
            <YAxis stroke="#6b7280" />
            <Tooltip
              contentStyle={{
                backgroundColor: "#fff",
                border: "1px solid #e5e7eb",
                borderRadius: "8px",
              }}
            />
            <Bar dataKey="stock" fill="#2563eb" radius={[8, 8, 0, 0]} />
          </BarChart>
        </ResponsiveContainer>
      </div>

      {/* Recent Activity */}
      <div className="bg-white p-6 rounded-xl border border-gray-200">
        <h3 className="text-lg text-gray-900 mb-4">Recent Activity</h3>
        <div className="space-y-3">
          <div className="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
              <p className="text-sm text-gray-900">Stock movement completed</p>
              <p className="text-xs text-gray-500 mt-1">Product A → Warehouse B</p>
            </div>
            <span className="text-xs text-gray-500">2 hours ago</span>
          </div>
          <div className="flex items-center justify-between py-3 border-b border-gray-100">
            <div>
              <p className="text-sm text-gray-900">New product added</p>
              <p className="text-xs text-gray-500 mt-1">Product XYZ-2024</p>
            </div>
            <span className="text-xs text-gray-500">5 hours ago</span>
          </div>
          <div className="flex items-center justify-between py-3">
            <div>
              <p className="text-sm text-gray-900">Backup completed successfully</p>
              <p className="text-xs text-gray-500 mt-1">All databases backed up</p>
            </div>
            <span className="text-xs text-gray-500">1 day ago</span>
          </div>
        </div>
      </div>
    </div>
  );
}

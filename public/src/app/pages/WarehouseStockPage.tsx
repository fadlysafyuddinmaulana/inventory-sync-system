const mockStock = [
  { product: "Steel Beam A-Series", warehouse: "Warehouse A", quantity: 450 },
  { product: "Concrete Mix Premium", warehouse: "Warehouse B", quantity: 1200 },
  { product: "Wooden Plank Oak", warehouse: "Warehouse A", quantity: 850 },
  { product: "Paint Latex White", warehouse: "Warehouse C", quantity: 320 },
  { product: "Copper Wire 12AWG", warehouse: "Warehouse B", quantity: 675 },
  { product: "PVC Pipe 2-inch", warehouse: "Warehouse D", quantity: 920 },
  { product: "Ceramic Tiles Premium", warehouse: "Warehouse A", quantity: 540 },
  { product: "Glass Panel Tempered", warehouse: "Warehouse C", quantity: 185 },
  { product: "Aluminum Sheet 4x8", warehouse: "Warehouse B", quantity: 410 },
  { product: "Brick Standard Red", warehouse: "Warehouse D", quantity: 5600 },
  { product: "Cement Bags Portland", warehouse: "Warehouse A", quantity: 780 },
  { product: "Insulation Foam Board", warehouse: "Warehouse C", quantity: 265 },
];

const warehouseColors: Record<string, string> = {
  "Warehouse A": "bg-blue-100 text-blue-700 border-blue-200",
  "Warehouse B": "bg-green-100 text-green-700 border-green-200",
  "Warehouse C": "bg-purple-100 text-purple-700 border-purple-200",
  "Warehouse D": "bg-orange-100 text-orange-700 border-orange-200",
};

export function WarehouseStockPage() {
  return (
    <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Product Name
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Warehouse / Location
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Quantity
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {mockStock.map((item, index) => (
              <tr key={index} className="hover:bg-gray-50 transition-colors">
                <td className="px-6 py-4 text-sm text-gray-900">{item.product}</td>
                <td className="px-6 py-4">
                  <span
                    className={`inline-flex items-center px-3 py-1 rounded-full text-xs border ${
                      warehouseColors[item.warehouse]
                    }`}
                  >
                    {item.warehouse}
                  </span>
                </td>
                <td className="px-6 py-4 text-sm text-gray-900">{item.quantity.toLocaleString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

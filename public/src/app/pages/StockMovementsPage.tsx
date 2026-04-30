const mockMovements = [
  {
    product: "Steel Beam A-Series",
    quantity: 150,
    source: "Warehouse A",
    destination: "Warehouse B",
    status: "Done",
  },
  {
    product: "Concrete Mix Premium",
    quantity: 300,
    source: "Warehouse C",
    destination: "Warehouse D",
    status: "Pending",
  },
  {
    product: "Wooden Plank Oak",
    quantity: 225,
    source: "Warehouse B",
    destination: "Warehouse A",
    status: "Done",
  },
  {
    product: "Paint Latex White",
    quantity: 80,
    source: "Warehouse A",
    destination: "Warehouse C",
    status: "In Transit",
  },
  {
    product: "Copper Wire 12AWG",
    quantity: 120,
    source: "Warehouse D",
    destination: "Warehouse B",
    status: "Done",
  },
  {
    product: "PVC Pipe 2-inch",
    quantity: 450,
    source: "Warehouse B",
    destination: "Warehouse D",
    status: "Pending",
  },
  {
    product: "Ceramic Tiles Premium",
    quantity: 175,
    source: "Warehouse C",
    destination: "Warehouse A",
    status: "Done",
  },
  {
    product: "Glass Panel Tempered",
    quantity: 60,
    source: "Warehouse A",
    destination: "Warehouse C",
    status: "In Transit",
  },
];

const statusStyles: Record<string, string> = {
  Done: "bg-green-100 text-green-700 border-green-200",
  Pending: "bg-yellow-100 text-yellow-700 border-yellow-200",
  "In Transit": "bg-blue-100 text-blue-700 border-blue-200",
};

export function StockMovementsPage() {
  return (
    <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Product
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Quantity
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Source
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Destination
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Status
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {mockMovements.map((movement, index) => (
              <tr key={index} className="hover:bg-gray-50 transition-colors">
                <td className="px-6 py-4 text-sm text-gray-900">{movement.product}</td>
                <td className="px-6 py-4 text-sm text-gray-900">{movement.quantity}</td>
                <td className="px-6 py-4 text-sm text-gray-700">{movement.source}</td>
                <td className="px-6 py-4 text-sm text-gray-700">{movement.destination}</td>
                <td className="px-6 py-4">
                  <span
                    className={`inline-flex items-center px-3 py-1 rounded-full text-xs border ${
                      statusStyles[movement.status]
                    }`}
                  >
                    {movement.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

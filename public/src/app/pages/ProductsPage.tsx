import { useState } from "react";
import { Search } from "lucide-react";

const mockProducts = [
  { id: "PRD-001", name: "Steel Beam A-Series", price: "$1,250.00" },
  { id: "PRD-002", name: "Concrete Mix Premium", price: "$89.50" },
  { id: "PRD-003", name: "Wooden Plank Oak", price: "$45.00" },
  { id: "PRD-004", name: "Paint Latex White", price: "$32.99" },
  { id: "PRD-005", name: "Copper Wire 12AWG", price: "$156.00" },
  { id: "PRD-006", name: "PVC Pipe 2-inch", price: "$12.50" },
  { id: "PRD-007", name: "Ceramic Tiles Premium", price: "$78.00" },
  { id: "PRD-008", name: "Glass Panel Tempered", price: "$245.00" },
  { id: "PRD-009", name: "Aluminum Sheet 4x8", price: "$189.00" },
  { id: "PRD-010", name: "Brick Standard Red", price: "$0.85" },
  { id: "PRD-011", name: "Cement Bags Portland", price: "$15.50" },
  { id: "PRD-012", name: "Insulation Foam Board", price: "$42.00" },
];

const ITEMS_PER_PAGE = 10;

export function ProductsPage() {
  const [searchQuery, setSearchQuery] = useState("");
  const [currentPage, setCurrentPage] = useState(1);

  const filteredProducts = mockProducts.filter(
    (product) =>
      product.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
      product.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const totalPages = Math.ceil(filteredProducts.length / ITEMS_PER_PAGE);
  const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
  const paginatedProducts = filteredProducts.slice(startIndex, startIndex + ITEMS_PER_PAGE);

  return (
    <div className="space-y-6">
      {/* Search Bar */}
      <div className="bg-white p-6 rounded-xl border border-gray-200">
        <div className="relative">
          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input
            type="text"
            placeholder="Search products by ID or name..."
            value={searchQuery}
            onChange={(e) => {
              setSearchQuery(e.target.value);
              setCurrentPage(1);
            }}
            className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
          />
        </div>
      </div>

      {/* Data Table */}
      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50 border-b border-gray-200">
              <tr>
                <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                  Product ID
                </th>
                <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                  Product Name
                </th>
                <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                  Price
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {paginatedProducts.map((product) => (
                <tr key={product.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-6 py-4 text-sm text-gray-900">{product.id}</td>
                  <td className="px-6 py-4 text-sm text-gray-900">{product.name}</td>
                  <td className="px-6 py-4 text-sm text-gray-900">{product.price}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
          <div className="text-sm text-gray-600">
            Showing {startIndex + 1} to {Math.min(startIndex + ITEMS_PER_PAGE, filteredProducts.length)} of{" "}
            {filteredProducts.length} results
          </div>

          <div className="flex gap-2">
            <button
              onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
              disabled={currentPage === 1}
              className="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Previous
            </button>
            <button
              onClick={() => setCurrentPage((p) => Math.min(totalPages, p + 1))}
              disabled={currentPage === totalPages}
              className="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

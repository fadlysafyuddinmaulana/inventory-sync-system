const mockLogs = [
  {
    date: "2026-04-30 14:30:00",
    totalData: "2.4 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
  {
    date: "2026-04-29 14:30:00",
    totalData: "2.3 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
  {
    date: "2026-04-28 14:30:00",
    totalData: "2.2 GB",
    status: "Failed",
    message: "Connection timeout to SQL Server",
  },
  {
    date: "2026-04-27 14:30:00",
    totalData: "2.1 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
  {
    date: "2026-04-26 14:30:00",
    totalData: "2.0 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
  {
    date: "2026-04-25 14:30:00",
    totalData: "1.9 GB",
    status: "Partial",
    message: "MySQL backup completed, PostgreSQL failed",
  },
  {
    date: "2026-04-24 14:30:00",
    totalData: "1.8 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
  {
    date: "2026-04-23 14:30:00",
    totalData: "1.7 GB",
    status: "Success",
    message: "All databases backed up successfully",
  },
];

const statusStyles: Record<string, string> = {
  Success: "bg-green-100 text-green-700 border-green-200",
  Failed: "bg-red-100 text-red-700 border-red-200",
  Partial: "bg-yellow-100 text-yellow-700 border-yellow-200",
};

export function BackupLogsPage() {
  return (
    <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50 border-b border-gray-200">
            <tr>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Date
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Total Data
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Status
              </th>
              <th className="px-6 py-3 text-left text-xs text-gray-600 uppercase tracking-wider">
                Message
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {mockLogs.map((log, index) => (
              <tr key={index} className="hover:bg-gray-50 transition-colors">
                <td className="px-6 py-4 text-sm text-gray-900">{log.date}</td>
                <td className="px-6 py-4 text-sm text-gray-900">{log.totalData}</td>
                <td className="px-6 py-4">
                  <span
                    className={`inline-flex items-center px-3 py-1 rounded-full text-xs border ${
                      statusStyles[log.status]
                    }`}
                  >
                    {log.status}
                  </span>
                </td>
                <td className="px-6 py-4 text-sm text-gray-700">{log.message}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

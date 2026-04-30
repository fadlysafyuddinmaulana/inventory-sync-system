import { useState } from "react";
import { Database, CheckCircle, XCircle, Loader2 } from "lucide-react";

export function BackupDataPage() {
  const [isBackingUp, setIsBackingUp] = useState(false);
  const [notification, setNotification] = useState<{
    type: "success" | "error" | null;
    message: string;
  }>({ type: null, message: "" });

  const handleBackup = async () => {
    setIsBackingUp(true);
    setNotification({ type: null, message: "" });

    await new Promise((resolve) => setTimeout(resolve, 2000));

    const isSuccess = Math.random() > 0.2;

    if (isSuccess) {
      setNotification({
        type: "success",
        message: "Backup completed successfully! All databases have been backed up to SQL Server.",
      });
    } else {
      setNotification({
        type: "error",
        message: "Backup failed! Unable to connect to SQL Server. Please check your connection and try again.",
      });
    }

    setIsBackingUp(false);

    setTimeout(() => {
      setNotification({ type: null, message: "" });
    }, 5000);
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Main Card */}
      <div className="bg-white p-8 rounded-xl border border-gray-200 text-center">
        <div className="flex justify-center mb-6">
          <div className="bg-blue-100 p-4 rounded-full">
            <Database className="w-12 h-12 text-blue-600" />
          </div>
        </div>

        <h2 className="text-2xl text-gray-900 mb-2">Database Backup</h2>
        <p className="text-gray-600 mb-8">
          Backup all data from Odoo (PostgreSQL) and MySQL to SQL Server
        </p>

        <button
          onClick={handleBackup}
          disabled={isBackingUp}
          className="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 mx-auto"
        >
          {isBackingUp ? (
            <>
              <Loader2 className="w-5 h-5 animate-spin" />
              Backing up...
            </>
          ) : (
            "Backup Now"
          )}
        </button>
      </div>

      {/* Notification */}
      {notification.type && (
        <div
          className={`p-4 rounded-xl border flex items-start gap-3 ${
            notification.type === "success"
              ? "bg-green-50 border-green-200"
              : "bg-red-50 border-red-200"
          }`}
        >
          {notification.type === "success" ? (
            <CheckCircle className="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" />
          ) : (
            <XCircle className="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
          )}
          <div className="flex-1">
            <h4
              className={`text-sm ${
                notification.type === "success" ? "text-green-800" : "text-red-800"
              }`}
            >
              {notification.type === "success" ? "Success" : "Error"}
            </h4>
            <p
              className={`text-sm mt-1 ${
                notification.type === "success" ? "text-green-700" : "text-red-700"
              }`}
            >
              {notification.message}
            </p>
          </div>
        </div>
      )}

      {/* Info Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-white p-6 rounded-xl border border-gray-200">
          <h3 className="text-sm text-gray-600 mb-2">Source Database 1</h3>
          <p className="text-lg text-gray-900">Odoo (PostgreSQL)</p>
          <div className="flex items-center gap-2 mt-2">
            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
            <span className="text-xs text-gray-600">Connected</span>
          </div>
        </div>

        <div className="bg-white p-6 rounded-xl border border-gray-200">
          <h3 className="text-sm text-gray-600 mb-2">Source Database 2</h3>
          <p className="text-lg text-gray-900">MySQL (Auth)</p>
          <div className="flex items-center gap-2 mt-2">
            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
            <span className="text-xs text-gray-600">Connected</span>
          </div>
        </div>

        <div className="bg-white p-6 rounded-xl border border-gray-200">
          <h3 className="text-sm text-gray-600 mb-2">Backup Destination</h3>
          <p className="text-lg text-gray-900">SQL Server</p>
          <div className="flex items-center gap-2 mt-2">
            <div className="w-2 h-2 bg-green-500 rounded-full"></div>
            <span className="text-xs text-gray-600">Connected</span>
          </div>
        </div>
      </div>
    </div>
  );
}

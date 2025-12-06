import { PovertyStandard } from "@/types"

// Helper function to format numbers as Indonesian Rupiah with thousand separators
const formatRupiah = (value: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

export function StandardTables({ povertyStandards }: { povertyStandards: PovertyStandard[] }) {
  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      {/* Hasil Perhitungan Model CIBEST */}
      <div className="bg-white rounded-lg shadow-sm p-6">
        <h3 className="text-lg font-semibold text-gray-800 mb-4">Hasil Perhitungan Model CIBEST</h3>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-teal-500 text-white">
                <th className="px-4 py-2 text-left">No.</th>
                <th className="px-4 py-2 text-left">Standar Kemiskinan</th>
                <th className="px-4 py-2 text-center">Index Kesejahteraan CIBEST</th>
              </tr>
            </thead>
            <tbody>
              {povertyStandards.map((item, idx) => (
                <tr key={item.id} className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-2">{idx + 1}</td>
                  <td className="px-4 py-2">{item.name}</td>
                  <td className="px-4 py-2 text-center">{item.index_kesejahteraan_cibest !== null ? item.index_kesejahteraan_cibest.toFixed(2) : "-"}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Standar Kemiskinan */}
      <div className="bg-white rounded-lg shadow-sm p-6">
        <h3 className="text-lg font-semibold text-gray-800 mb-4">Standar Kemiskinan</h3>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="bg-teal-500 text-white">
                <th className="px-4 py-2 text-left">No.</th>
                <th className="px-4 py-2 text-left">Standar Kemiskinan</th>
                <th className="px-4 py-2 text-center">Pendapatan Keluarga Per Bulan</th>
                <th className="px-4 py-2 text-center">Pendapatan Keluarga Per Tahun</th>
              </tr>
            </thead>
            <tbody>
              {povertyStandards.map((item, idx) => (
                <tr key={item.id} className="border-b border-gray-200 hover:bg-gray-50">
                  <td className="px-4 py-2">{idx + 1}</td>
                  <td className="px-4 py-2">{item.name}</td>
                  <td className="px-4 py-2 text-center">{item.nilai_keluarga !== null ? formatRupiah(item.nilai_keluarga) : "-"}</td>
                  <td className="px-4 py-2 text-center">{item.nilai_per_tahun !== null ? formatRupiah(item.nilai_per_tahun) : "-"}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}

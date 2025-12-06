import { PovertyIndicator } from "@/types"

interface IndicatorsTableProps {
  povertyIndicators: PovertyIndicator[];
}

export function IndicatorsTable({ povertyIndicators }: IndicatorsTableProps) {
  return (
    <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
      <h2 className="text-xl font-semibold text-gray-800 mb-4">Indikator Kemiskinan Umum</h2>
      <div className="overflow-x-auto">
        <table className="w-full text-sm">
          <thead>
            <tr className="bg-teal-500 text-white">
              <th className="px-4 py-3 text-left">Indikator</th>
              <th className="px-4 py-3 text-center">Before</th>
              <th className="px-4 py-3 text-center">After</th>
              <th className="px-4 py-3 text-center">Perubahan</th>
            </tr>
          </thead>
          <tbody>
            {povertyIndicators.map((item, idx) => (
              <tr key={idx} className="border-b border-gray-200 hover:bg-gray-50">
                <td className="px-4 py-3">{item.indicator}</td>
                <td className="px-4 py-3 text-center">{item.before}</td>
                <td className="px-4 py-3 text-center">{item.after}</td>
                <td className="px-4 py-3 text-center">
                  <span className={item.change < 0 ? "text-green-600 font-semibold" : "text-red-600"}>
                    {item.change > 0 ? "+" : ""}
                    {item.change}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  )
}

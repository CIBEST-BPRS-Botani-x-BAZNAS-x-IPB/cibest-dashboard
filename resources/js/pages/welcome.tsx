import { QuadrantDistribution } from "@/components/quadrant-distribution"
import { IndonesiaMap } from "@/components/indonesia-map"
import { ProvinceTable } from "@/components/province-table"
import { StandardTables } from "@/components/standard-tables"
import { IndicatorsTable } from "@/components/indicators-table"
import { DashboardFooter } from "@/components/dashboard-footer"
import { Link, usePage } from "@inertiajs/react"
import { QuadrantData, SharedData, PovertyStandard, AllProvincesByStandard, PovertyIndicator, Province } from "@/types"
import { dashboard, login, register } from "@/routes"

export default function Welcome({
  canRegister = true,
  respondentCount,
  quadrantDistribution,
  povertyStandards,
  povertyIndicators,
  provinces,
  allProvincesByStandard
}: {
  canRegister?: boolean;
  respondentCount: number;
  quadrantDistribution: QuadrantData[];
  povertyStandards: PovertyStandard[];
  povertyIndicators: PovertyIndicator[];
  provinces: Province[];
  allProvincesByStandard: AllProvincesByStandard;
}) {
  const { auth } = usePage<SharedData>().props;

  return (
    <main className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div className="max-w-7xl mx-auto px-4 py-6">
          <div className="flex justify-between items-center">
            <div>
              <h1 className="text-4xl font-bold text-teal-600">Dashboard CIBEST</h1>
              <p className="text-gray-600 mt-1">Kesejahteraan Holistik UKM dan Pemberdayaan Dunia dan Akhirat</p>
            </div>
            <div className="text-right">
              <p className="text-sm text-gray-600">Jumlah Responden</p>
              <p className="text-3xl font-bold text-teal-600">
                {respondentCount}
              </p>
            </div>
            <nav className="flex items-center justify-end gap-4">
              {auth.user ? (
                <Link
                  href={dashboard()}
                  className="inline-block rounded-sm border border-teal-500 bg-teal-500 px-5 py-1.5 text-sm leading-normal text-white hover:bg-teal-600 hover:border-teal-600"
                >
                  Dashboard
                </Link>
              ) : (
                <>
                  <Link
                    href={login()}
                    className="inline-block rounded-sm border border-yellow-500 bg-yellow-500 px-5 py-1.5 text-sm leading-normal text-white hover:bg-yellow-600 hover:border-yellow-600"
                  >
                    Log in
                  </Link>
                  {canRegister && (
                    <Link
                      href={register()}
                      className="inline-block rounded-sm border border-teal-500 bg-teal-500 px-5 py-1.5 text-sm leading-normal text-white hover:bg-teal-600 hover:border-teal-600"
                    >
                      Register
                    </Link>
                  )}
                </>
              )}
            </nav>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <QuadrantDistribution quadrantData={quadrantDistribution} />
        <IndonesiaMap
          provinces={allProvincesByStandard[0] || []}
          povertyStandardId={povertyStandards[0]?.id || 0}
        />
        <div className="mt-6">
          <ProvinceTable
            provinces={provinces}
            povertyStandards={povertyStandards}
            allProvincesByStandard={allProvincesByStandard}
          />
        </div>
        <StandardTables povertyStandards={povertyStandards} />
        <IndicatorsTable povertyIndicators={povertyIndicators} />
      </div>

      <DashboardFooter />
    </main>
  )
}

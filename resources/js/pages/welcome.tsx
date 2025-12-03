import { QuadrantDistribution } from "@/components/quadrant-distribution"
import { IndonesiaMap } from "@/components/indonesia-map"
import { ProvinceTable } from "@/components/province-table"
import { StandardTables } from "@/components/standard-tables"
import { IndicatorsTable } from "@/components/indicators-table"
import { DashboardFooter } from "@/components/dashboard-footer"
import { Link, usePage } from "@inertiajs/react"
import { QuadrantData, SharedData } from "@/types"
import { dashboard, login, register } from "@/routes"

export default function Welcome({
  canRegister = true,
  respondentCount,
  quadrantDistribution
}: {
  canRegister?: boolean;
  respondentCount: number;
  quadrantDistribution: QuadrantData[];
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
                  className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                  Dashboard
                </Link>
              ) : (
                <>
                  <Link
                    href={login()}
                    className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                  >
                    Log in
                  </Link>
                  {canRegister && (
                    <Link
                      href={register()}
                      className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
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
        <IndonesiaMap />
        <div className="mt-6">
          <ProvinceTable />
        </div>
        <StandardTables />
        <IndicatorsTable />
      </div>

      <DashboardFooter />
    </main>
  )
}

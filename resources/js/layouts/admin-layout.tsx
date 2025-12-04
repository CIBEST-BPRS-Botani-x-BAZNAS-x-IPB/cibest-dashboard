import { Link, usePage } from "@inertiajs/react"
import { dashboard, login, logout } from "@/routes"
import { SharedData } from "@/types"

interface AdminLayoutProps {
    title: string;
    children: React.ReactNode;
}

export default function AdminLayout({ 
    title, 
    children 
}: AdminLayoutProps) {
    const { auth } = usePage<SharedData>().props;

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Header */}
            <div className="bg-white border-b border-gray-200 sticky top-0 z-10">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="flex justify-between items-center">
                        <div>
                            <h1 className="text-4xl font-bold text-teal-600">Dashboard CIBEST</h1>
                            <p className="text-gray-600 mt-1">Kesejahteraan Holistik UKM dan Pemberdayaan Dunia dan Akhirat</p>
                        </div>
                        <nav className="flex items-center justify-end gap-4">
                            <Link
                                href={dashboard()}
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Dashboard
                            </Link>
                            <button 
                                onClick={() => document.getElementById('logout-form')?.click()}
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Log Out
                            </button>
                            <form id="logout-form" action={logout()} method="post" className="hidden">
                                <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''} />
                            </form>
                        </nav>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-7xl mx-auto px-4 py-8">
                <h1 className="text-3xl font-bold text-teal-600 mb-8">{title}</h1>
                <div className="bg-white p-6 rounded-lg shadow-md">
                    {children}
                </div>
            </div>
        </div>
    )
}
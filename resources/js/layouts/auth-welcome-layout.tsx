import { Link } from "@inertiajs/react"
import { dashboard, login, register } from "@/routes"
import { usePage } from "@inertiajs/react"
import { SharedData } from "@/types"

interface AuthWelcomeLayoutProps {
    title: string;
    children: React.ReactNode;
    description?: string;
}

export default function AuthWelcomeLayout({
    title,
    description,
    children
}: AuthWelcomeLayoutProps) {
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
                                    <Link
                                        href={register()}
                                        className="inline-block rounded-sm border border-teal-500 bg-teal-500 px-5 py-1.5 text-sm leading-normal text-white hover:bg-teal-600 hover:border-teal-600"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </nav>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-6xl mx-auto px-4 py-12 flex justify-center items-center gap-12 min-h-[70vh]">
                {/* Form Section - wider column */}
                <div className="w-full md:w-2/3 lg:w-1/2">
                    <div className="bg-white p-8 rounded-lg shadow-md">
                        <div className="text-center mb-6">
                            <h1 className="text-2xl font-bold text-teal-600">{title}</h1>
                            {description && <p className="text-gray-600 mt-2">{description}</p>}
                        </div>
                        <div className="space-y-6">
                            {children}
                        </div>
                    </div>
                </div>

                {/* Logo Section - vertically centered */}
                <div className="hidden md:block w-1/3">
                    <Link href="/" className="block">
                        <img
                            src="/logo.png"
                            alt="CIBEST Logo"
                            className="w-full h-auto object-contain rounded-lg shadow-sm cursor-pointer"
                        />
                    </Link>
                </div>
            </div>
        </div>
    )
}
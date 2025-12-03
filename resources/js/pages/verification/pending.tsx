import { Head, Link, router } from '@inertiajs/react';
import { logout } from '@/routes';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';

interface Props {
    status?: string;
}

export default function PendingVerification({ status }: Props) {
    const cleanup = useMobileNavigation();

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <Head title="Verification Pending" />
            <div className="max-w-md w-full space-y-8 bg-white p-10 rounded-lg shadow">
                <div>
                    <h2 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Menunggu Verifikasi Akun
                    </h2>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        Akun Anda saat ini sedang dalam proses verifikasi oleh administrator.
                    </p>
                </div>
                
                {status && (
                    <div className="p-4 bg-blue-100 text-blue-800 rounded">
                        {status}
                    </div>
                )}
                
                <div className="mt-8 text-center">
                    <p className="text-sm text-gray-600 mb-6">
                        Mohon tunggu hingga administrator meninjau dan memverifikasi akun Anda.
                        Anda akan menerima email pemberitahuan setelah akun Anda disetujui.
                    </p>
                    
                    <Link 
                        href="/"
                        className="font-medium text-blue-600 hover:text-blue-500"
                    >
                        Kembali ke Home
                    </Link>
                    <Link
                        className="block w-full"
                        href={logout()}
                        as="button"
                        onClick={handleLogout}
                        data-test="logout-button"
                    >
                        Log out
                    </Link>
                </div>
            </div>
        </div>
    );
}
import { Head, Link, usePage } from '@inertiajs/react';
import { SharedData } from '@/types';
import AdminLayout from '@/layouts/admin-layout';

interface User {
    id: number;
    name: string;
    email: string;
    user_role: string;
    admin_verification_status: string;
    admin_verified_at?: string;
    admin_verified_by?: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    user: User;
}

export default function ViewUser({ user }: Props) {
    // Check if user has admin role (this would be passed from backend)
    const { auth } = usePage<SharedData>().props;
    const currentUserRole = auth.user.user_role;

    if (currentUserRole !== 'admin') {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="max-w-md w-full bg-white p-8 rounded-lg shadow">
                    <h2 className="text-2xl font-bold text-center text-red-600">Access Denied</h2>
                    <p className="text-center mt-4 text-gray-600">You do not have permission to access this page.</p>
                    <Link href="/" className="block mt-6 text-center text-blue-600 hover:text-blue-800">
                        Return to Home
                    </Link>
                </div>
            </div>
        );
    }

    return (
        <AdminLayout title={`User Details - ${user.name}`}>
            <Head title={`User Details - ${user.name}`} />

            <div className="bg-white shadow-md rounded-lg p-6 max-w-3xl">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 className="text-lg font-semibold text-teal-600 mb-2">Personal Information</h3>
                        <div className="space-y-2">
                            <p><span className="font-medium">Name:</span> {user.name}</p>
                            <p><span className="font-medium">Email:</span> {user.email}</p>
                            <p><span className="font-medium">Role:</span> {user.user_role}</p>
                        </div>
                    </div>

                    <div>
                        <h3 className="text-lg font-semibold text-teal-600 mb-2">Verification Status</h3>
                        <div className="space-y-2">
                            <p>
                                <span className="font-medium">Status:</span>
                                <span className={`ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                    user.admin_verification_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                    user.admin_verification_status === 'verified' ? 'bg-green-100 text-green-800' :
                                    'bg-red-100 text-red-800'
                                }`}>
                                    {user.admin_verification_status}
                                </span>
                            </p>

                            {user.admin_verified_at && (
                                <p><span className="font-medium">Verified at:</span> {new Date(user.admin_verified_at).toLocaleString()}</p>
                            )}

                            {user.admin_verified_by && (
                                <p><span className="font-medium">Verified by:</span> Admin ID {user.admin_verified_by}</p>
                            )}

                            <p><span className="font-medium">Registered:</span> {new Date(user.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                </div>

                <div className="mt-8">
                    <Link
                        href="/admin/users"
                        className="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded"
                    >
                        Back to Users
                    </Link>
                </div>
            </div>
        </AdminLayout>
    );
}
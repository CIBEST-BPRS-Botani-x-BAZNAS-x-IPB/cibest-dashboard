import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { SharedData } from '@/types';
import AdminLayout from '@/layouts/admin-layout';

interface User {
    id: number;
    name: string;
    email: string;
    admin_verification_status: string;
    admin_verified_at?: string;
    created_at: string;
}

interface Props {
    pendingUsers: User[];
    verifiedUsers: User[];
    rejectedUsers: User[];
}

export default function Users({ pendingUsers, verifiedUsers, rejectedUsers }: Props) {
    const [status, setStatus] = useState<string | null>(null);
    const { auth } = usePage<SharedData>().props;

    const approveUser = (userId: number) => {
        router.post(`/admin/users/${userId}/approve`, {}, {
            onSuccess: () => {
                setStatus('User approved successfully.');
                // Reload the page or update the state to reflect the change
                window.location.reload();
            },
            onError: (errors) => {
                console.error('Error approving user:', errors);
            }
        });
    };

    const rejectUser = (userId: number) => {
        router.post(`/admin/users/${userId}/reject`, {}, {
            onSuccess: () => {
                setStatus('User rejected successfully.');
                // Reload the page or update the state to reflect the change
                window.location.reload();
            },
            onError: (errors) => {
                console.error('Error rejecting user:', errors);
            }
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'verified':
                return 'bg-green-100 text-green-800';
            case 'rejected':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    // Check if user has admin role (this would be passed from backend)
    const userRole =  auth.user.user_role;

    if (userRole !== 'admin') {
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
        <AdminLayout title="User Management">
            <Head title="User Management" />

            {status && (
                <div className="mb-6 p-4 bg-green-100 text-green-800 rounded">
                    {status}
                </div>
            )}

            {/* Pending Users Section */}
            <div className="mb-12">
                <h2 className="text-2xl font-semibold text-teal-600 mb-4">Pending Verification</h2>
                {pendingUsers.length > 0 ? (
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {pendingUsers.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.email}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {new Date(user.created_at).toLocaleDateString()}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(user.admin_verification_status)}`}>
                                                {user.admin_verification_status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                onClick={() => approveUser(user.id)}
                                                className="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mr-2"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                onClick={() => rejectUser(user.id)}
                                                className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded mr-2"
                                            >
                                                Reject
                                            </button>
                                            <Link href={`/admin/users/${user.id}`} className="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded">
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                ) : (
                    <div className="text-center py-4 text-gray-500">No users pending verification</div>
                )}
            </div>

            {/* Verified Users Section */}
            <div className="mb-12">
                <h2 className="text-2xl font-semibold text-teal-600 mb-4">Verified Users</h2>
                {verifiedUsers.length > 0 ? (
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {verifiedUsers.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.email}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {user.admin_verified_at ? new Date(user.admin_verified_at).toLocaleDateString() : 'N/A'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(user.admin_verification_status)}`}>
                                                {user.admin_verification_status}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                ) : (
                    <div className="text-center py-4 text-gray-500">No verified users</div>
                )}
            </div>

            {/* Rejected Users Section */}
            <div>
                <h2 className="text-2xl font-semibold text-teal-600 mb-4">Rejected Users</h2>
                {rejectedUsers.length > 0 ? (
                    <div className="bg-white shadow-md rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {rejectedUsers.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.name}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">{user.email}</td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            {user.admin_verified_at ? new Date(user.admin_verified_at).toLocaleDateString() : 'N/A'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(user.admin_verification_status)}`}>
                                                {user.admin_verification_status}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                ) : (
                    <div className="text-center py-4 text-gray-500">No rejected users</div>
                )}
            </div>
        </AdminLayout>
    );
}
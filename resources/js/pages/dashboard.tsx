import AppLayout from '@/layouts/app-layout';
import { baznas, cibest, dashboard, povertyStandards } from '@/routes';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { HandCoins, HandHeart, Scale } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 bg-gray-50">
                <h1 className='font-bold text-2xl text-teal-600'>Halo {auth.user.user_role} {auth.user.name}! </h1>
                <div className="flex flex-row gap-6 mt-8">
                    <Link href={cibest.url()} className='w-64 h-32'>
                        <div className="w-full h-full bg-yellow-500 hover:bg-yellow-600 rounded-lg shadow-md flex flex-col items-center justify-center text-white transition-all duration-200 transform hover:scale-105">
                            <HandHeart className="h-10 w-10 mb-2" />
                            <span className="font-semibold text-lg">Survei BPRS</span>
                        </div>
                    </Link>
                    <Link href={baznas.url()} className='w-64 h-32'>
                        <div className="w-full h-full bg-teal-500 hover:bg-teal-600 rounded-lg shadow-md flex flex-col items-center justify-center text-white transition-all duration-200 transform hover:scale-105">
                            <HandCoins className="h-10 w-10 mb-2" />
                            <span className="font-semibold text-lg">Survei BAZNAS</span>
                        </div>
                    </Link>
                    <Link href={povertyStandards.url()} className='w-64 h-32'>
                        <div className="w-full h-full bg-purple-500 hover:bg-purple-600 rounded-lg shadow-md flex flex-col items-center justify-center text-white transition-all duration-200 transform hover:scale-105">
                            <Scale className="h-10 w-10 mb-2" />
                            <span className="font-semibold text-lg">Standar Kemiskinan</span>
                        </div>
                    </Link>
                </div>
            </div>
        </AppLayout>
    );
}

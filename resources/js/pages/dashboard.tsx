import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { baznas, cibest, dashboard } from '@/routes';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { HandCoins, HandHeart } from 'lucide-react';

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
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className='font-bold'>Halo {auth.user.user_role} {auth.user.name}! </h1>
                <div className="flex flex-row gap-4">
                    <Link href={cibest.url()} className='w-fit h-fit'>
                        <Button className='w-fit h-fit flex-col justify-center items-center' variant={'outline'}>
                            <HandHeart /> Survei CIBEST
                        </Button>
                    </Link>
                    <Link href={baznas.url()} className='w-fit h-fit'>
                        <Button className='w-fit h-fit flex-col justify-center items-center' variant={'outline'}>
                            <HandCoins /> Survei BAZNAS
                        </Button>
                    </Link>
                </div>
            </div>
        </AppLayout>
    );
}

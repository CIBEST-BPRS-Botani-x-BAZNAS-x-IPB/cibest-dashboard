import AppLayout from '@/layouts/app-layout';
import { baznas } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'BAZNAS',
        href: baznas().url,
    },
];

export default function Baznas() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="BAZNAS" />
            <div className="flex h-full flex-1   gap-4 overflow-x-auto rounded-xl p-4">
                
            </div>
        </AppLayout>
    );
}

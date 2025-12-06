import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { baznas, cibest, dashboard, home, povertyStandards } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { HandCoins, HandHeart, LayoutGrid, Scale } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard.url(),
        icon: LayoutGrid,
    },
    {
        title: 'BPRS',
        href: cibest.url(),
        icon: HandHeart,
    },
    {
        title: 'BAZNAS',
        href: baznas.url(),
        icon: HandCoins,
    },
    {
        title: 'Standar Kemiskinan',
        href: povertyStandards.url(),
        icon: Scale,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton className='h-35' asChild>
                            <Link href={home().url} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}

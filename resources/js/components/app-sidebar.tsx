import { Link, usePage } from '@inertiajs/react';
import {
    BookOpenCheck,
    BriefcaseBusiness,
    CircleDollarSign,
    ClipboardList,
    History,
    LayoutDashboard,
    ShieldCheck,
    Users,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
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
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Visão geral',
        href: '/dashboard',
        icon: LayoutDashboard,
    },
    {
        title: 'Treinamentos',
        href: '/trainings',
        icon: ClipboardList,
    },
    {
        title: 'Colaboradores',
        href: '/employees',
        icon: Users,
    },
    {
        title: 'Cursos',
        href: '/courses',
        icon: BookOpenCheck,
    },
    {
        title: 'Orçamentos',
        href: '/budgets',
        icon: CircleDollarSign,
    },
    {
        title: 'Cadastros auxiliares',
        href: '/references',
        icon: BriefcaseBusiness,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Ambiente protegido',
        href: '/settings/security',
        icon: ShieldCheck,
    },
];

export function AppSidebar() {
    const { auth } = usePage().props;
    const items = auth.permissions.admin
        ? [
              ...mainNavItems,
              { title: 'Auditoria', href: '/audit', icon: History },
          ]
        : mainNavItems;

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={items} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}

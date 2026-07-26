<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BadgeCheck,
    Building2,
    ClipboardCheck,
    FilePlus2,
    LayoutDashboard,
    LogIn,
    Search,
    ShieldCheck,
    UserPlus,
    Users,
} from '@lucide/vue';
import { computed } from 'vue';

import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
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

type SharedUser = {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    requested_role: 'buyer' | 'seller' | 'both' | null;
    roles: string[];
    permissions: string[];
    company: null | {
        id: number;
        legal_name: string;
        trading_name: string | null;
        activity_type: string;
        verification_status: string;
    };
};

const page = usePage();
const user = computed<SharedUser | null>(() => {
    const auth = page.props.auth as { user?: SharedUser | null } | undefined;
    return auth?.user ?? null;
});
const roles = computed(() => user.value?.roles ?? []);
const permissions = computed(() => user.value?.permissions ?? []);
const company = computed(() => user.value?.company ?? null);

const isAdmin = computed(
    () => roles.value.includes('super-admin') || roles.value.includes('administrator'),
);

const canCreatePosts = computed(() => permissions.value.includes('create trade posts'));

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Marketplace',
            href: '/trade-posts',
            icon: Search,
        },
    ];

    if (!user.value) return items;

    items.unshift({
        title: 'My dashboard',
        href: '/dashboard',
        icon: LayoutDashboard,
    });

    if (!user.value.email_verified_at) {
        items.push({
            title: 'Verify email',
            href: '/email/verify',
            icon: BadgeCheck,
        });

        return items;
    }

    if (!company.value || company.value.verification_status === 'rejected') {
        items.push({
            title: company.value ? 'Correct company details' : 'Verify my company',
            href: '/verification/apply',
            icon: Building2,
        });
    } else {
        items.push({
            title: 'Company status',
            href: '/verification/status',
            icon: BadgeCheck,
        });
    }

    if (company.value?.verification_status === 'approved' && canCreatePosts.value) {
        items.push(
            {
                title: 'Create trade post',
                href: '/trade-posts/create',
                icon: FilePlus2,
            },
            {
                title: 'My posts',
                href: '/my-posts',
                icon: ClipboardCheck,
            },
        );
    }

    if (isAdmin.value) {
        items.push(
            {
                title: 'Company approvals',
                href: '/admin/verifications',
                icon: ShieldCheck,
            },
            {
                title: 'User management',
                href: '/admin/users',
                icon: Users,
            },
            {
                title: 'Post moderation',
                href: '/admin/trade-posts',
                icon: ClipboardCheck,
            },
        );
    }

    return items;
});

const guestNavItems = computed<NavItem[]>(() => {
    if (user.value) return [];

    return [
        {
            title: 'Log in',
            href: '/login',
            icon: LogIn,
        },
        {
            title: 'Create account',
            href: '/register',
            icon: UserPlus,
        },
    ];
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/trade-posts">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter v-if="guestNavItems.length" :items="guestNavItems" />
            <NavUser v-if="user" />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>

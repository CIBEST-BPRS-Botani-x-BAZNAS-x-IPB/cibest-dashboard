import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    user_role: 'enumerator' | 'admin';
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

interface QuadrantData {
  id: number;
  name: string;
  before: {
    [key: number]: number;
  };
  after: {
    [key: number]: number;
  };
}

export interface PovertyStandard {
  id: number;
  name: string;
  index_kesejahteraan_cibest: number;
  nilai_keluarga: number;
  nilai_per_tahun: number;
  log_natural: number;
}

export interface PovertyIndicator {
  indicator: string;
  before: number;
  after: number;
  change: number;
}

export interface Province {
  id?: number;
  name: string;
  Q1: number;
  Q2: number;
  Q3: number;
  Q4: number;
  total: number;
  dominant: string;
}

export interface AllProvincesByStandard {
  [key: number]: Province[];
}

export interface RespondentBreakdown {
  bprs: number;
  baznas: number;
}
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function getAppUrl(path: string = ''): string {
    const meta = typeof document !== 'undefined' ? document.querySelector<HTMLMetaElement>('meta[name="app-url"]') : null;
    const baseUrl = meta?.content ? meta.content.replace(/\/$/, '') : '';
    const cleanPath = path.replace(/^\//, '');
    return baseUrl ? `${baseUrl}/${cleanPath}` : `/${cleanPath}`;
}

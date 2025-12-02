import AppLogo from '@/components/app-logo';
import { home } from '@/routes';
import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

interface AuthLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: PropsWithChildren<AuthLayoutProps>) {
    return (
        <div className="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
                <div className="w-full flex flex-row items-center justify-center gap-16 px-32">
                    <Link
                        href={home()}
                        className="max-w-md flex flex-col items-center gap-2 font-medium"
                    >
                        <div className="mb-1 flex items-center justify-center rounded-md">
                            {/* <AppLogoIcon className="size-9 fill-current text-[var(--foreground)] dark:text-white" /> */}
                            <AppLogo />
                        </div>
                        <span className="sr-only">{title}</span>
                    </Link>
                    <div className="max-w-md flex flex-col gap-8 w-full items-start">
                        <div className="space-y-2">
                            <h1 className="text-xl font-medium">{title}</h1>
                            <p className="text-center text-sm text-muted-foreground">
                                {description}
                            </p>
                        </div>
                        <div className="min-w-xs">
                            {children}
                        </div>
                    </div>
                </div>
            </div>
    );
}

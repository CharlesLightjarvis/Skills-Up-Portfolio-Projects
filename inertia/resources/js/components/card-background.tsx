import { cn } from '@/lib/utils';
import type { ComponentProps, ReactNode } from 'react';

type CardBackgroundProps = ComponentProps<'div'> & {
    children: ReactNode;
    innerClassName?: string;
};

export function CardBackground({
    children,
    className,
    innerClassName,
    ...props
}: CardBackgroundProps) {
    return (
        <div
            className={cn(
                'relative overflow-hidden rounded-3xl border bg-muted p-2 shadow-lg',
                className,
            )}
            {...props}
        >
            <div
                className={cn(
                    'flex h-full w-full flex-col overflow-hidden rounded-2xl bg-background shadow-lg',
                    innerClassName,
                )}
            >
                {children}
            </div>
        </div>
    );
}

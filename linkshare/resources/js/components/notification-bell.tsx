// resources/js/components/notification-bell.tsx
import { useState, useRef, useEffect } from 'react';
import { router, usePage } from '@inertiajs/react';
import { NotificationsProps } from '@/types/notification';
import {
    markAllRead,
    markRead,
} from '@/actions/App/Http/Controllers/NotificationController';

type PageProps = {
    notifications: NotificationsProps | null;
};

export function NotificationBell() {
    const { notifications } = usePage<PageProps>().props;
    const [open, setOpen] = useState(false);
    const ref = useRef<HTMLDivElement>(null);

    const items = notifications?.items ?? [];
    const unreadCount = notifications?.unread_count ?? 0;

    useEffect(() => {
        const handler = (e: MouseEvent) => {
            if (ref.current && !ref.current.contains(e.target as Node)) {
                setOpen(false);
            }
        };
        document.addEventListener('mousedown', handler);
        return () => document.removeEventListener('mousedown', handler);
    }, []);

    function handleMarkRead(id: string, url?: string | null) {
        router.patch(
            markRead(id),
            {},
            {
                preserveScroll: true,
                only: ['notifications'],
                onSuccess: () => {
                    if (url) {
                        router.visit(url);
                        setOpen(false);
                    }
                },
            },
        );
    }

    function handleMarkAllRead() {
        router.patch(
            markAllRead(), // wayfinder : /notifications/read-all
            {},
            { preserveScroll: true, only: ['notifications'] },
        );
    }

    return (
        <div className="relative" ref={ref}>
            <button
                onClick={() => setOpen((o) => !o)}
                className="relative rounded-full p-2 transition hover:bg-muted"
                aria-label="Notifications"
            >
                <svg
                    className="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>
                {unreadCount > 0 && (
                    <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                        {unreadCount > 9 ? '9+' : unreadCount}
                    </span>
                )}
            </button>

            {open && (
                <div className="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-xl border bg-background shadow-lg">
                    <div className="flex items-center justify-between border-b px-4 py-3">
                        <span className="font-semibold">Notifications</span>
                        {unreadCount > 0 && (
                            <button
                                onClick={handleMarkAllRead}
                                className="text-xs text-blue-500 hover:underline"
                            >
                                Tout marquer comme lu
                            </button>
                        )}
                    </div>

                    <ul className="max-h-80 divide-y overflow-y-auto">
                        {items.length === 0 ? (
                            <li className="px-4 py-6 text-center text-sm text-muted-foreground">
                                Aucune notification
                            </li>
                        ) : (
                            items.map((notif) => (
                                <li
                                    key={notif.id}
                                    onClick={() => {
                                        if (!notif.read) {
                                            handleMarkRead(notif.id, notif.data.url);
                                        } else if (notif.data.url) {
                                            router.visit(notif.data.url);
                                            setOpen(false);
                                        }
                                    }}
                                    className={`cursor-pointer px-4 py-3 transition hover:bg-muted ${!notif.read ? 'bg-blue-50 dark:bg-blue-950/20' : ''}`}
                                >
                                    <p className="text-sm">
                                        <span className="font-medium">
                                            {notif.data.owner_name}
                                        </span>{' '}
                                        a partagé{' '}
                                        {notif.data.shareable_type ===
                                        'collection'
                                            ? 'la collection'
                                            : 'le lien'}{' '}
                                        <span className="font-medium">
                                            "{notif.data.shareable_name}"
                                        </span>{' '}
                                        avec vous.
                                    </p>
                                    <span className="mt-0.5 block text-xs text-muted-foreground">
                                        {notif.created_at}
                                    </span>
                                    {!notif.read && (
                                        <span className="mt-1 inline-block h-2 w-2 rounded-full bg-blue-500" />
                                    )}
                                </li>
                            ))
                        )}
                    </ul>
                </div>
            )}
        </div>
    );
}

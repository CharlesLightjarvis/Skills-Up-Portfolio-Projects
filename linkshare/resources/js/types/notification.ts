import { ShareableType } from './share';

export type NotificationData = {
    share_id: number;
    owner_id: number;
    owner_name: string;
    shareable_id: number;
    shareable_type: ShareableType;
    shareable_name: string;
    url: string | null;
};

export type AppNotification = {
    id: string;
    data: NotificationData;
    read: boolean;
    created_at: string;
};

export type NotificationsProps = {
    unread_count: number;
    items: AppNotification[];
};

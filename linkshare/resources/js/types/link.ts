import type { Collection } from './collection';

export type Link = {
    id: number;
    collection_id: number;
    url: string;
    title: string | null;
    description: string | null;
    image_url: string | null;
    site_name: string | null;
    domain: string | null;
    note: string | null;
    status: string;
    is_favorite: boolean;
    created_at: string;
    updated_at: string;
    collection?: Collection;
};

// resources/js/components/share-modal.tsx
import { useEffect, useRef, useState } from 'react';
import { router, useForm } from '@inertiajs/react';
import { useDebouncedCallback } from 'use-debounce';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import { Share2 } from 'lucide-react';
import type { Shareable, ShareableType } from '@/types/share';
import { User } from '@/types';

type Props = {
    shareable: Shareable;
    shareableType: ShareableType;
    open: boolean;
    onClose: () => void;
    userSearchResults: User[];
};

export function ShareModal({
    shareable,
    shareableType,
    open,
    onClose,
    userSearchResults,
}: Props) {
    const [query, setQuery] = useState('');
    const [selected, setSelected] = useState<User | null>(null);
    const [searching, setSearching] = useState(false);

    const { post, processing, errors, setData, reset } = useForm({
        recipient_id: 0,
        shareable_type: shareableType,
        shareable_id: shareable.id,
    });

    // Sync shareable quand la target change
    useEffect(() => {
        setData({
            recipient_id: selected?.id ?? 0,
            shareable_type: shareableType,
            shareable_id: shareable.id,
        });
    }, [shareable.id, shareableType, selected]);

    // Reset complet à la fermeture
    useEffect(() => {
        if (!open) {
            setQuery('');
            setSelected(null);
            reset();
            // Vide les résultats de recherche
            router.get(
                window.location.pathname,
                {},
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['userSearchResults'],
                },
            );
        }
    }, [open]);

    const debouncedSearch = useDebouncedCallback(
        (value: string) => {
            setSearching(false);
            router.get(
                window.location.pathname,
                { q: value || undefined },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['userSearchResults'],
                },
            );
        },
        300,
        { maxWait: 1000 },
    );

    function handleSearch(e: React.ChangeEvent<HTMLInputElement>) {
        const value = e.target.value;
        setQuery(value);
        setSelected(null);

        if (value.length >= 2) {
            setSearching(true);
            debouncedSearch(value);
        } else {
            debouncedSearch.cancel();
            setSearching(false);
            router.get(
                window.location.pathname,
                {},
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                    only: ['userSearchResults'],
                },
            );
        }
    }

    function selectUser(user: User) {
        setSelected(user);
        setQuery(user.email);
    }

    function submit() {
        if (!selected || processing) return;
        post('/shares', {
            onSuccess: onClose,
        });
    }

    const shareableName = shareable.name ?? shareable.title ?? '';

    return (
        <Dialog open={open} onOpenChange={(o) => !o && onClose()}>
            <DialogContent className="max-w-md">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        <Share2 className="h-4 w-4" />
                        Partager
                    </DialogTitle>
                    <DialogDescription>
                        {shareableType === 'collection' ? 'Collection' : 'Lien'}{' '}
                        :{' '}
                        <span className="font-medium text-foreground">
                            {shareableName}
                        </span>
                    </DialogDescription>
                </DialogHeader>

                {/* Champ de recherche */}
                <div className="relative">
                    <Input
                        type="text"
                        placeholder="Nom ou email..."
                        value={query}
                        onChange={handleSearch}
                        autoFocus
                        autoComplete="off"
                    />
                    {searching && (
                        <div className="absolute top-2.5 right-3">
                            <Spinner />
                        </div>
                    )}
                </div>

                {/* Résultats — masqués si un user est déjà sélectionné */}
                {!selected &&
                    query.length >= 2 &&
                    !searching &&
                    (userSearchResults.length > 0 ? (
                        <ul className="divide-y rounded-lg border">
                            {userSearchResults.map((user) => (
                                <li
                                    key={user.id}
                                    onClick={() => selectUser(user)}
                                    className="flex cursor-pointer items-center gap-3 px-4 py-2.5 transition hover:bg-muted"
                                >
                                    <div className="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                        {user.name[0].toUpperCase()}
                                    </div>
                                    <div className="min-w-0">
                                        <p className="truncate text-sm font-medium">
                                            {user.name}
                                        </p>
                                        <p className="truncate text-xs text-muted-foreground">
                                            {user.email}
                                        </p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="py-3 text-center text-sm text-muted-foreground">
                            Aucun utilisateur trouvé pour "
                            <span className="font-medium text-foreground">
                                {query}
                            </span>
                            ".
                        </p>
                    ))}

                {/* Utilisateur sélectionné */}
                {selected && (
                    <div className="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 dark:border-green-900 dark:bg-green-950/20">
                        <div className="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">
                            {selected.name[0].toUpperCase()}
                        </div>
                        <div className="min-w-0">
                            <p className="text-sm font-medium">
                                {selected.name}
                            </p>
                            <p className="text-xs text-muted-foreground">
                                {selected.email}
                            </p>
                        </div>
                        <button
                            onClick={() => {
                                setSelected(null);
                                setQuery('');
                            }}
                            className="ml-auto text-muted-foreground hover:text-destructive"
                            aria-label="Retirer la sélection"
                        >
                            ✕
                        </button>
                    </div>
                )}

                {errors.recipient_id && (
                    <p className="text-sm text-destructive">
                        {errors.recipient_id}
                    </p>
                )}

                <div className="flex justify-end gap-2 pt-2">
                    <Button variant="outline" onClick={onClose}>
                        Annuler
                    </Button>
                    <Button onClick={submit} disabled={!selected || processing}>
                        {processing && <Spinner />}
                        Partager
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    );
}

import { useState } from 'react';
import LinkController from '@/actions/App/Http/Controllers/LinkController';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

export type FetchedLink = {
    url: string;
    title: string;
    description: string | null;
    image_url: string | null;
    site_name: string | null;
    domain: string | null;
};

function getXsrfToken(): string {
    return decodeURIComponent(
        document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? '',
    );
}

interface LinkUrlFetcherProps {
    onAdd: (link: FetchedLink) => void;
}

export function LinkUrlFetcher({ onAdd }: LinkUrlFetcherProps) {
    const [url, setUrl] = useState('');
    const [preview, setPreview] = useState<FetchedLink | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [fetched, setFetched] = useState(false);

    function handleUrlChange(value: string) {
        setUrl(value);
        setFetched(false);
        setPreview(null);
        setError(null);
    }

    async function fetchPreview() {
        if (!url) return;

        setLoading(true);
        setError(null);

        try {
            const res = await fetch(LinkController.preview.url(), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-XSRF-TOKEN': getXsrfToken(),
                },
                body: JSON.stringify({ url }),
            });

            const data = await res.json();

            if (!res.ok) {
                setError(data.message ?? 'Impossible de récupérer cette page.');
                return;
            }

            setPreview({
                url: data.url ?? url,
                title: data.title ?? url,
                description: data.description ?? null,
                image_url: data.image ?? null,
                site_name: data.site_name ?? null,
                domain: data.domain ?? null,
            });
            setFetched(true);
        } catch {
            setError('Une erreur réseau est survenue.');
        } finally {
            setLoading(false);
        }
    }

    function handleAdd() {
        if (!preview) return;
        onAdd(preview);
        setUrl('');
        setPreview(null);
        setFetched(false);
        setError(null);
    }

    return (
        <div className="space-y-3">
            <div className="grid gap-2">
                <Label>URL du lien</Label>
                <div className="flex gap-2">
                    <Input
                        type="url"
                        placeholder="https://example.com"
                        value={url}
                        onChange={(e) => handleUrlChange(e.target.value)}
                        onKeyDown={(e) => e.key === 'Enter' && e.preventDefault()}
                    />
                    <Button
                        type="button"
                        variant="secondary"
                        onClick={fetchPreview}
                        disabled={!url || loading}
                        className="shrink-0"
                    >
                        {loading ? <Spinner /> : 'Vérifier'}
                    </Button>
                </div>
                {error && <p className="text-sm text-destructive">{error}</p>}
            </div>

            {preview && (
                <div className="flex items-start gap-3 rounded-md border p-3">
                    {preview.image_url && (
                        <img
                            src={preview.image_url}
                            alt=""
                            className="h-12 w-20 shrink-0 rounded object-cover"
                        />
                    )}
                    <div className="min-w-0 flex-1">
                        <p className="truncate text-sm font-medium">
                            {preview.title}
                        </p>
                        <p className="truncate text-xs text-muted-foreground">
                            {preview.domain}
                        </p>
                    </div>
                    <Button
                        type="button"
                        size="sm"
                        onClick={handleAdd}
                    >
                        Ajouter
                    </Button>
                </div>
            )}
        </div>
    );
}

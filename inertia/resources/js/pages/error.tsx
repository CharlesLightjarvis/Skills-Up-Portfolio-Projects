import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

type ErrorPageProps = {
    status: number;
};

const errors: Record<
    number,
    {
        title: string;
        description: string;
    }
> = {
    403: {
        title: 'Forbidden',
        description: "You don't have permission to access this page.",
    },
    404: {
        title: 'Page Not Found',
        description:
            'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.',
    },
    500: {
        title: 'Server Error',
        description: 'Something went wrong on the server.',
    },
    503: {
        title: 'Service Unavailable',
        description: 'The server is temporarily unable to handle the request.',
    },
};

export default function ErrorPage({ status }: ErrorPageProps) {
    const error = errors[status] ?? {
        title: 'Something went wrong',
        description: 'An unexpected error occurred.',
    };

    return (
        <div className="flex min-h-screen items-center justify-center p-4">
            <div className="flex flex-col items-center gap-4">
                <h1 className="text-fd-muted-foreground text-6xl font-bold">
                    {status}
                </h1>

                <h2 className="text-2xl font-semibold">{error.title}</h2>

                <p className="text-fd-muted-foreground max-w-md">
                    {error.description}
                </p>

                <Button asChild variant="outline">
                    <Link
                        href="/"
                        className="bg-fd-primary text-fd-primary-foreground mt-4 rounded-lg px-4 py-2 text-sm font-medium transition-opacity hover:opacity-90"
                    >
                        Back to Home
                    </Link>
                </Button>
            </div>
        </div>
    );
}

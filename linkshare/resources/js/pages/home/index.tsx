import { Head } from '@inertiajs/react';
import { Header } from './partials/header';
import { Footer } from './partials/footer';
import { Hero } from './partials/hero';
import { Process } from './partials/process';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <Header />
            <div>
                <Hero />
                <Process />
            </div>
            <Footer />
        </>
    );
}

import { Button } from '@/components/ui/button'
import { WorldMap } from '../world-map'
import { Link } from '@tanstack/react-router'

export function Hero() {
  return (
    <section className="relative min-h-screen w-full overflow-hidden">
      {/* Background Map with overlay */}
      <div className="absolute inset-0 z-0">
        <div className="absolute inset-0 bg-linear-to-b from-background/60 via-background/80 to-background z-10" />
        <div className="scale-110 ">
          <WorldMap
            dots={[
              {
                start: { lat: 64.2008, lng: -149.4937, label: 'Fairbanks' },
                end: { lat: 34.0522, lng: -118.2437, label: 'Los Angeles' },
              },
              {
                start: { lat: 64.2008, lng: -149.4937, label: 'Fairbanks' },
                end: { lat: -15.7975, lng: -47.8919, label: 'Brasília' },
              },
              {
                start: { lat: -15.7975, lng: -47.8919, label: 'Brasília' },
                end: { lat: 38.7223, lng: -9.1393, label: 'Lisbon' },
              },
              {
                start: { lat: 51.5074, lng: -0.1278, label: 'London' },
                end: { lat: 28.6139, lng: 77.209, label: 'New Delhi' },
              },
              {
                start: { lat: 28.6139, lng: 77.209, label: 'New Delhi' },
                end: { lat: 43.1332, lng: 131.9113, label: 'Vladivostok' },
              },
              {
                start: { lat: 28.6139, lng: 77.209, label: 'New Delhi' },
                end: { lat: -1.2921, lng: 36.8219, label: 'Nairobi' },
              },
              {
                start: { lat: 3.139, lng: 101.6869, label: 'Kuala Lumpur' },
                end: { lat: 6.5244, lng: 3.3792, label: 'Lagos' },
              },
              {
                start: { lat: 48.8566, lng: 2.3522, label: 'Paris' },
                end: { lat: 35.6762, lng: 139.6503, label: 'Tokyo' },
              },
            ]}
          />
        </div>
      </div>

      {/* Hero Content */}
      <div className="relative z-20 flex min-h-screen flex-col items-center justify-center px-4 py-20 text-center">
        <div className="mx-auto max-w-5xl space-y-8">
          {/* Main Heading */}
          <h1 className="text-5xl font-bold tracking-tight sm:text-6xl md:text-7xl lg:text-8xl">
            Bridge people.
            <br />
            <span className="bg-linear-to-r from-primary to-primary/60 bg-clip-text text-transparent">
              Ship something.
            </span>
          </h1>

          {/* Subheading */}
          <p className="mx-auto max-w-3xl text-lg text-muted-foreground sm:text-xl md:text-2xl">
            Connecte-toi avec des développeurs et designers de ton niveau. Pas
            d’argent, seulement des compétences. Construis de vrais projets,
            gagne en réputation et fais toi découvrir.
          </p>

          {/* Value Props */}
          <div className="mx-auto flex max-w-2xl flex-wrap items-center justify-center gap-4 text-sm text-muted-foreground sm:text-base md:gap-8">
            <div className="flex items-center gap-2">
              <div className="h-2 w-2 rounded-full bg-green-500" />
              <span>Time for Time</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="h-2 w-2 rounded-full bg-blue-500" />
              <span>30-Day Projects</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="h-2 w-2 rounded-full bg-purple-500" />
              <span>Build Your Portfolio</span>
            </div>
          </div>

          {/* CTAs */}
          <div className="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
            <Button
              asChild
              size="lg"
              className="h-12 px-8 text-lg sm:h-14 sm:px-10"
            >
              <Link to="/projects">Voir les projets</Link>
            </Button>
          </div>

          {/* Social Proof */}
          <div className="pt-8 text-sm text-muted-foreground">
            Join 500+ developers and designers shipping real projects
          </div>
        </div>
      </div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 z-20 -translate-x-1/2 animate-bounce">
        <div className="flex flex-col items-center gap-2">
          <span className="text-xs text-muted-foreground">
            Scroll to explore
          </span>
          <svg
            className="h-6 w-6 text-muted-foreground"
            fill="none"
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="2"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
          </svg>
        </div>
      </div>
    </section>
  )
}

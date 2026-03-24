import { createFileRoute } from '@tanstack/react-router'
import { Hero } from '../-root-components/ui/hero'
import { Features } from '../-root-components/ui/features'
import { HowItWorks } from '../-root-components/ui/how-it-works'
import { Stats } from '../-root-components/ui/stats'
import { FinalCTA } from '../-root-components/ui/final-cta'

export const Route = createFileRoute('/_layout/')({
  component: App,
})

function App() {
  return (
    <div className="min-h-screen">
      <Hero />
      <Features />
      <HowItWorks />
      <Stats />
      <FinalCTA />
    </div>
  )
}

import { motion } from 'framer-motion'

export function Features() {
  const features = [
    {
      icon: (
        <svg
          className="h-8 w-8"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      ),
      title: 'Time for Time',
      description:
        'No freelance, no money. Exchange skills equally. You code my backend, I design your UI. Fair collaboration.',
      gradient: 'from-green-500 to-emerald-600',
    },
    {
      icon: (
        <svg
          className="h-8 w-8"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      ),
      title: '30-Day Projects',
      description:
        'Every project has a deadline. No "we\'ll see later". Ship a real MVP or demo in 30 days. Commitment or exit.',
      gradient: 'from-blue-500 to-cyan-600',
    },

    {
      icon: (
        <svg
          className="h-8 w-8"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
      ),
      title: 'Bridge Score',
      description:
        'Your reputation is everything. Complete projects → gain score. Ghost or abandon → lose it. No second chances.',
      gradient: 'from-purple-500 to-pink-600',
    },
  ]

  return (
    <section className="border-t bg-background py-24 md:py-32">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="mx-auto mb-16 max-w-3xl text-center">
          <h2 className="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
            How Bridge Works
          </h2>
          <p className="text-lg text-muted-foreground md:text-xl">
            Three non-negotiable rules that make collaboration actually work
          </p>
        </div>

        {/* Features Grid */}
        <div className="mx-auto grid max-w-6xl gap-8 md:grid-cols-3">
          {features.map((feature, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className="group relative"
            >
              {/* Card */}
              <div className="relative h-full rounded-2xl border bg-card p-8 transition-all duration-300 hover:shadow-xl">
                {/* Gradient Background on Hover */}
                <div
                  className={`absolute inset-0 rounded-2xl bg-linear-to-br ${feature.gradient} opacity-0 transition-opacity duration-300 group-hover:opacity-5`}
                />

                {/* Content */}
                <div className="relative space-y-4">
                  {/* Icon */}
                  <div
                    className={`inline-flex h-16 w-16 items-center justify-center rounded-xl bg-linear-to-br ${feature.gradient} text-white`}
                  >
                    {feature.icon}
                  </div>

                  {/* Title */}
                  <h3 className="text-2xl font-bold">{feature.title}</h3>

                  {/* Description */}
                  <p className="text-muted-foreground">{feature.description}</p>
                </div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Bottom CTA */}
        <div className="mx-auto mt-16 max-w-2xl rounded-2xl border bg-muted/30 p-8 text-center backdrop-blur-sm">
          <p className="text-lg font-medium">
            Not for everyone. Only for those who actually want to{' '}
            <span className="bg-linear-to-r from-primary to-primary/60 bg-clip-text font-bold text-transparent">
              ship something real
            </span>
            .
          </p>
        </div>
      </div>
    </section>
  )
}

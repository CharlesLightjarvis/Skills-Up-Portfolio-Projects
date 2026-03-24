import { motion } from 'framer-motion'

export function HowItWorks() {
  const steps = [
    {
      number: '01',
      title: 'Post Your Project',
      description:
        'Define what you need, what you offer, and your 30-day deadline. No vague ideas allowed.',
      details: [
        'Clear deliverable (MVP, landing page, demo)',
        'Stack and skills needed',
        'What you bring to the table',
        'Time commitment (e.g., 5h/week)',
      ],
    },
    {
      number: '02',
      title: 'Find Your Match',
      description:
        'Browse projects or get applications. Everyone must show real work (GitHub, portfolio, previous Bridge projects).',
      details: [
        'Filter by skills and stack',
        'Check Bridge Score reputation',
        'Review past completed projects',
        'No anonymous profiles',
      ],
    },
    {
      number: '03',
      title: 'Collaborate',
      description:
        'Work together for 30 days. Both parties commit. No ghosting, no excuses. Ship or lose reputation.',
      details: [
        'Regular check-ins',
        'Public project timeline',
        'Mutual accountability',
        'Option to renew for another 30 days',
      ],
    },
    {
      number: '04',
      title: 'Ship & Earn',
      description:
        'Publish your deliverable. Gain +20 Bridge Score. Build your portfolio. Move to the next project.',
      details: [
        'Completed project badge',
        'Portfolio showcase',
        'Partner feedback',
        'Higher visibility for next projects',
      ],
    },
  ]

  return (
    <section className="bg-muted/30 py-24 md:py-32">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="mx-auto mb-20 max-w-3xl text-center">
          <h2 className="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
            From Idea to Shipped
          </h2>
          <p className="text-lg text-muted-foreground md:text-xl">
            A straightforward process. No bullshit, no "let's see how it goes"
          </p>
        </div>

        {/* Steps */}
        <div className="mx-auto max-w-5xl space-y-16">
          {steps.map((step, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, x: index % 2 === 0 ? -50 : 50 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: index * 0.1 }}
              className="group relative"
            >
              <div className="grid gap-8 md:grid-cols-2 md:gap-12">
                {/* Left Side - Number & Title */}
                <div
                  className={`flex flex-col justify-center ${index % 2 === 0 ? 'md:order-1' : 'md:order-2'}`}
                >
                  <div className="space-y-4">
                    {/* Step Number */}
                    <div className="text-7xl font-bold text-primary/20 transition-colors duration-300 group-hover:text-primary/40 md:text-8xl">
                      {step.number}
                    </div>

                    {/* Title */}
                    <h3 className="text-3xl font-bold md:text-4xl">
                      {step.title}
                    </h3>

                    {/* Description */}
                    <p className="text-lg text-muted-foreground">
                      {step.description}
                    </p>
                  </div>
                </div>

                {/* Right Side - Details */}
                <div
                  className={`flex flex-col justify-center ${index % 2 === 0 ? 'md:order-2' : 'md:order-1'}`}
                >
                  <div className="rounded-2xl border bg-card p-6 shadow-sm transition-shadow duration-300 hover:shadow-lg md:p-8">
                    <ul className="space-y-3">
                      {step.details.map((detail, i) => (
                        <motion.li
                          key={i}
                          initial={{ opacity: 0, x: -20 }}
                          whileInView={{ opacity: 1, x: 0 }}
                          viewport={{ once: true }}
                          transition={{ duration: 0.3, delay: i * 0.1 }}
                          className="flex items-start gap-3"
                        >
                          <div className="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10">
                            <svg
                              className="h-3 w-3 text-primary"
                              fill="none"
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="3"
                              viewBox="0 0 24 24"
                              stroke="currentColor"
                            >
                              <path d="M5 13l4 4L19 7"></path>
                            </svg>
                          </div>
                          <span className="text-sm text-muted-foreground md:text-base">
                            {detail}
                          </span>
                        </motion.li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>

              {/* Connector Line (except last item) */}
              {index < steps.length - 1 && (
                <div className="mt-8 flex justify-center md:mt-12">
                  <div className="h-12 w-px bg-linear-to-b from-border to-transparent md:h-16" />
                </div>
              )}
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  )
}

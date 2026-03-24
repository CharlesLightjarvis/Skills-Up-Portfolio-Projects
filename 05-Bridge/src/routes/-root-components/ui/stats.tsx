import { motion } from 'framer-motion'

export function Stats() {
  const stats = [
    {
      value: '500+',
      label: 'Active Builders',
      description: 'Developers and designers shipping together',
    },
    {
      value: '120+',
      label: 'Projects Shipped',
      description: 'Real MVPs, demos, and live products',
    },
    {
      value: '87%',
      label: 'Completion Rate',
      description: 'Projects that reach their 30-day goal',
    },
    {
      value: '4.8/5',
      label: 'Average Score',
      description: 'Partner satisfaction rating',
    },
  ]

  const bridgeScoreExamples = [
    {
      action: 'Project Shipped',
      score: '+20',
      color: 'text-green-500',
      icon: (
        <svg
          className="h-5 w-5"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      ),
    },
    {
      action: 'Positive Feedback',
      score: '+5',
      color: 'text-blue-500',
      icon: (
        <svg
          className="h-5 w-5"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
        </svg>
      ),
    },
    {
      action: 'Project Abandoned',
      score: '-20',
      color: 'text-orange-500',
      icon: (
        <svg
          className="h-5 w-5"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      ),
    },
    {
      action: 'Ghosting Confirmed',
      score: '-40',
      color: 'text-red-500',
      icon: (
        <svg
          className="h-5 w-5"
          fill="none"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      ),
    },
  ]

  return (
    <section className="border-t bg-background py-24 md:py-32">
      <div className="container mx-auto px-4">
        {/* Stats Grid */}
        <div className="mx-auto mb-20 grid max-w-6xl gap-8 sm:grid-cols-2 lg:grid-cols-4">
          {stats.map((stat, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: index * 0.1 }}
              className="text-center"
            >
              <div className="mb-2 text-4xl font-bold md:text-5xl">
                {stat.value}
              </div>
              <div className="mb-1 text-lg font-medium">{stat.label}</div>
              <div className="text-sm text-muted-foreground">
                {stat.description}
              </div>
            </motion.div>
          ))}
        </div>

        {/* Bridge Score Section */}
        <div className="mx-auto max-w-4xl">
          {/* Header */}
          <div className="mb-12 text-center">
            <h2 className="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
              Your Bridge Score Matters
            </h2>
            <p className="text-lg text-muted-foreground">
              Every action impacts your reputation. Build it slowly, lose it
              fast.
            </p>
          </div>

          {/* Score Examples */}
          <div className="rounded-2xl border bg-card p-6 md:p-8">
            <div className="space-y-4">
              {bridgeScoreExamples.map((item, index) => (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, x: -20 }}
                  whileInView={{ opacity: 1, x: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.3, delay: index * 0.1 }}
                  className="flex items-center justify-between rounded-lg border bg-background p-4 transition-all hover:shadow-md"
                >
                  <div className="flex items-center gap-3">
                    <div className={`${item.color}`}>{item.icon}</div>
                    <span className="font-medium">{item.action}</span>
                  </div>
                  <div
                    className={`text-2xl font-bold ${item.color} ${item.score.startsWith('+') ? '' : ''}`}
                  >
                    {item.score}
                  </div>
                </motion.div>
              ))}
            </div>

            {/* Warning Box */}
            <div className="mt-6 rounded-lg border-2 border-destructive/20 bg-destructive/5 p-4">
              <div className="flex items-start gap-3">
                <svg
                  className="mt-0.5 h-5 w-5 flex-shrink-0 text-destructive"
                  fill="none"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div className="text-sm">
                  <p className="font-semibold text-destructive">
                    Score below -50 = Account Banned
                  </p>
                  <p className="mt-1 text-muted-foreground">
                    Your projects become invisible, reputation frozen. Only way
                    back: 30-day wait + complete a redemption project.
                  </p>
                </div>
              </div>
            </div>
          </div>

          {/* Bottom Message */}
          <div className="mt-8 text-center">
            <p className="text-muted-foreground">
              This isn't a game. Your Bridge Score is your{' '}
              <span className="font-bold text-foreground">
                professional identity
              </span>{' '}
              on the platform.
            </p>
          </div>
        </div>
      </div>
    </section>
  )
}

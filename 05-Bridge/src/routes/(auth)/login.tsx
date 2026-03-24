import { createFileRoute } from '@tanstack/react-router'
import { LoginForm } from './-components/login-form'
import { useEffect } from 'react'
import { toast } from 'sonner'

export const Route = createFileRoute('/(auth)/login')({
  component: RouteComponent,
  validateSearch: (search: Record<string, unknown>): { verified?: string } => {
    return {
      verified: search.verified as string | undefined,
    }
  },
})

function RouteComponent() {
  const { verified } = Route.useSearch()

  useEffect(() => {
    if (verified === 'success') {
      toast.success('Email vérifié avec succès! Vous pouvez maintenant vous connecter.')
    } else if (verified === 'already') {
      toast.info('Votre email est déjà vérifié.')
    } else if (verified === 'error') {
      toast.error('Lien de vérification invalide ou expiré.')
    }
  }, [verified])

  return <LoginForm />
}

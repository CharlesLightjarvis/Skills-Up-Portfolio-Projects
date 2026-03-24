import { createFileRoute, useNavigate } from '@tanstack/react-router'
import { useEffect, useState } from 'react'
import { Loader2, CheckCircle, XCircle } from 'lucide-react'
import { authService } from '@/services/auth-service'

export const Route = createFileRoute('/(auth)/verify-email')({
  component: RouteComponent,
  validateSearch: (search: Record<string, unknown>) => {
    return {
      id: search.id as string,
      hash: search.hash as string,
      expires: search.expires as string,
      signature: search.signature as string,
    }
  },
})

function RouteComponent() {
  const navigate = useNavigate()
  const { id, hash, expires, signature } = Route.useSearch()
  const [status, setStatus] = useState<'loading' | 'success' | 'error'>(
    'loading',
  )
  const [message, setMessage] = useState('')

  useEffect(() => {
    const verifyEmail = async () => {
      if (!id || !hash || !expires || !signature) {
        setStatus('error')
        setMessage('Lien de vérification invalide.')
        setTimeout(() => navigate({ to: '/login' }), 3000)
        return
      }

      try {
        const result = await authService.verifyEmail(
          id,
          hash,
          expires,
          signature,
        )

        if (result.success) {
          setStatus('success')
          setMessage(result.message)
          // Rediriger vers login après 2 secondes
          setTimeout(() => {
            navigate({ to: '/login', search: { verified: 'success' } })
          }, 2000)
        } else {
          setStatus('error')
          setMessage(result.message)
          setTimeout(() => navigate({ to: '/login' }), 3000)
        }
      } catch (error) {
        setStatus('error')
        setMessage('Une erreur est survenue lors de la vérification.')
        setTimeout(() => navigate({ to: '/login' }), 3000)
      }
    }

    verifyEmail()
  }, [id, hash, expires, signature, navigate])

  return (
    <div className="flex flex-col items-center justify-center gap-6 text-center">
      <div className="flex flex-col items-center gap-4">
        {status === 'loading' && (
          <>
            <Loader2 className="h-12 w-12 animate-spin text-primary" />
            <h1 className="text-2xl font-bold">Vérification en cours...</h1>
            <p className="text-muted-foreground">
              Nous vérifions votre adresse email. Veuillez patienter.
            </p>
          </>
        )}

        {status === 'success' && (
          <>
            <CheckCircle className="h-12 w-12 text-green-500" />
            <h1 className="text-2xl font-bold text-green-600">
              Email vérifié!
            </h1>
            <p className="text-muted-foreground">{message}</p>
            <p className="text-sm text-muted-foreground">
              Redirection vers la page de connexion...
            </p>
          </>
        )}

        {status === 'error' && (
          <>
            <XCircle className="h-12 w-12 text-red-500" />
            <h1 className="text-2xl font-bold text-red-600">
              Erreur de vérification
            </h1>
            <p className="text-muted-foreground">{message}</p>
            <p className="text-sm text-muted-foreground">
              Redirection vers la page de connexion...
            </p>
          </>
        )}
      </div>
    </div>
  )
}

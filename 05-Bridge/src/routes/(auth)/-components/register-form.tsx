import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'
import {
  Field,
  FieldDescription,
  FieldError,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from '@/components/ui/field'
import { Input } from '@/components/ui/input'
import { Controller, useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import {
  registerSchema,
  registerDefaultValues,
  type RegisterFormData,
} from '@/schemas/auth-schema'
import { useAuthStore } from '@/stores/auth-store'
import { Link, useNavigate } from '@tanstack/react-router'
import { toast } from 'sonner'
import { useState } from 'react'

export function RegisterForm({
  className,
  ...props
}: React.ComponentProps<'form'>) {
  const { register } = useAuthStore()
  const navigate = useNavigate()
  const [isLoading, setIsLoading] = useState(false)

  const form = useForm<RegisterFormData>({
    resolver: zodResolver(registerSchema),
    defaultValues: registerDefaultValues,
    mode: 'onChange',
  })

  const onSubmit = async (data: RegisterFormData) => {
    setIsLoading(true)
    console.log('📝 Submitting registration form:', {
      email: data.email,
      name: `${data.first_name} ${data.last_name}`,
    })

    try {
      const result = await register(data)

      console.log('📦 Backend response:', JSON.stringify(result, null, 2))

      if (result.success) {
        toast.success('Inscription réussie! Vérifiez votre email pour activer votre compte.')
        navigate({ to: '/login' })
      } else {
        console.error('❌ Registration failed:', result)
        toast.error(result.message || "Erreur lors de l'inscription")

        if (result.errors) {
          Object.entries(result.errors).forEach(([field, messages]) => {
            const errorMessages = messages as string[]
            // Gérer les erreurs imbriquées pour social_links
            if (field.startsWith('social_links.')) {
              const socialField = field.replace('social_links.', '')
              form.setError(`social_links.${socialField}` as any, {
                type: 'manual',
                message: errorMessages[0],
              })
            } else {
              form.setError(field as keyof RegisterFormData, {
                type: 'manual',
                message: errorMessages[0],
              })
            }
          })
        }
      }
    } catch (error) {
      console.error('❌ Registration error:', error)
      toast.error('Une erreur est survenue')
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <form
      className={cn('flex flex-col gap-6', className)}
      onSubmit={form.handleSubmit(onSubmit)}
      {...props}
    >
      <FieldGroup>
        <div className="flex flex-col items-center gap-1 text-center">
          <h1 className="text-2xl font-bold">Créer un compte</h1>
          <p className="text-muted-foreground text-sm text-balance">
            Remplissez les informations ci-dessous pour créer votre compte
          </p>
        </div>

        <div className="grid grid-cols-2 gap-4">
          <Controller
            name="first_name"
            control={form.control}
            render={({ field, fieldState }) => (
              <Field data-invalid={fieldState.invalid}>
                <FieldLabel htmlFor="first_name">Prénom</FieldLabel>
                <Input
                  {...field}
                  id="first_name"
                  type="text"
                  placeholder="John"
                  aria-invalid={fieldState.invalid}
                  disabled={isLoading}
                />
                {fieldState.invalid && (
                  <FieldError errors={[fieldState.error]} />
                )}
              </Field>
            )}
          />

          <Controller
            name="last_name"
            control={form.control}
            render={({ field, fieldState }) => (
              <Field data-invalid={fieldState.invalid}>
                <FieldLabel htmlFor="last_name">Nom</FieldLabel>
                <Input
                  {...field}
                  id="last_name"
                  type="text"
                  placeholder="Doe"
                  aria-invalid={fieldState.invalid}
                  disabled={isLoading}
                />
                {fieldState.invalid && (
                  <FieldError errors={[fieldState.error]} />
                )}
              </Field>
            )}
          />
        </div>

        <Controller
          name="email"
          control={form.control}
          render={({ field, fieldState }) => (
            <Field data-invalid={fieldState.invalid}>
              <FieldLabel htmlFor="email">Email</FieldLabel>
              <Input
                {...field}
                id="email"
                type="email"
                placeholder="john.doe@example.com"
                aria-invalid={fieldState.invalid}
                disabled={isLoading}
              />
              {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
            </Field>
          )}
        />

        <div className="grid grid-cols-2 gap-4">
          <Controller
            name="password"
            control={form.control}
            render={({ field, fieldState }) => (
              <Field data-invalid={fieldState.invalid}>
                <FieldLabel htmlFor="password">Mot de passe</FieldLabel>
                <Input
                  {...field}
                  id="password"
                  type="password"
                  aria-invalid={fieldState.invalid}
                  disabled={isLoading}
                />
                {fieldState.invalid && (
                  <FieldError errors={[fieldState.error]} />
                )}
              </Field>
            )}
          />

          <Controller
            name="password_confirmation"
            control={form.control}
            render={({ field, fieldState }) => (
              <Field data-invalid={fieldState.invalid}>
                <FieldLabel htmlFor="password_confirmation">
                  Confirmer
                </FieldLabel>
                <Input
                  {...field}
                  id="password_confirmation"
                  type="password"
                  aria-invalid={fieldState.invalid}
                  disabled={isLoading}
                />
                {fieldState.invalid && (
                  <FieldError errors={[fieldState.error]} />
                )}
              </Field>
            )}
          />
        </div>

        <FieldSeparator>Liens sociaux</FieldSeparator>

        <Controller
          name="social_links.github"
          control={form.control}
          render={({ field, fieldState }) => (
            <Field data-invalid={fieldState.invalid}>
              <FieldLabel htmlFor="github">
                GitHub <span className="text-red-500">*</span>
              </FieldLabel>
              <Input
                {...field}
                id="github"
                type="url"
                placeholder="https://github.com/username"
                aria-invalid={fieldState.invalid}
                disabled={isLoading}
              />
              {fieldState.invalid && <FieldError errors={[fieldState.error]} />}
            </Field>
          )}
        />

        <Field>
          <Button type="submit" disabled={isLoading} className="w-full">
            {isLoading ? 'Inscription en cours...' : "S'inscrire"}
          </Button>
        </Field>

        <FieldDescription className="text-center">
          Vous avez déjà un compte?{' '}
          <Link to="/login" className="underline underline-offset-4">
            Se connecter
          </Link>
        </FieldDescription>
      </FieldGroup>
    </form>
  )
}

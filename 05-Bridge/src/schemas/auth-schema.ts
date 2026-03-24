import { z } from 'zod'

export const loginSchema = z.object({
  email: z.string().min(1, 'Email requis').email('Email invalide'),
  password: z
    .string()
    .min(1, 'Mot de passe requis')
    .min(6, 'Le mot de passe doit contenir au moins 6 caractères'),
})

export type LoginFormData = z.infer<typeof loginSchema>

export const loginDefaultValues: LoginFormData = {
  email: '',
  password: '',
}

export const registerSchema = z
  .object({
    first_name: z
      .string()
      .min(1, 'Prénom requis')
      .max(255, 'Le prénom ne peut pas dépasser 255 caractères'),
    last_name: z
      .string()
      .min(1, 'Nom requis')
      .max(255, 'Le nom ne peut pas dépasser 255 caractères'),
    email: z
      .string()
      .min(1, 'Email requis')
      .email('Email invalide')
      .max(255, "L'email ne peut pas dépasser 255 caractères"),
    password: z
      .string()
      .min(1, 'Mot de passe requis')
      .min(8, 'Le mot de passe doit contenir au moins 8 caractères')
      .regex(/[a-z]/, 'Le mot de passe doit contenir au moins une lettre minuscule')
      .regex(/[A-Z]/, 'Le mot de passe doit contenir au moins une lettre majuscule')
      .regex(/[0-9]/, 'Le mot de passe doit contenir au moins un chiffre'),
    password_confirmation: z.string().min(1, 'Confirmation requise'),
    social_links: z.object({
      github: z.string().min(1, 'Lien GitHub requis').url('URL invalide'),
      linkedin: z.string().url('URL invalide').optional().or(z.literal('')),
      twitter: z.string().url('URL invalide').optional().or(z.literal('')),
      portfolio: z.string().url('URL invalide').optional().or(z.literal('')),
    }),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: 'Les mots de passe ne correspondent pas',
    path: ['password_confirmation'],
  })

export type RegisterFormData = z.infer<typeof registerSchema>

export const registerDefaultValues: RegisterFormData = {
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  password_confirmation: '',
  social_links: {
    github: '',
    linkedin: '',
    twitter: '',
    portfolio: '',
  },
}

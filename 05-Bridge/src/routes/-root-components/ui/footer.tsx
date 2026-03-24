import React from 'react'
import { FaFacebook, FaInstagram, FaLinkedin, FaTwitter } from 'react-icons/fa'
import bridgeImage1 from '../../../assets/bridge1.png' // chemin vers ton PNG

interface FooterProps {
  logo?: {
    url: string
    src: string
    alt: string
    title: string
  }
  sections?: Array<{
    title: string
    links: Array<{ name: string; href: string }>
  }>
  description?: string
  socialLinks?: Array<{
    icon: React.ReactElement
    href: string
    label: string
  }>
  copyright?: string
  legalLinks?: Array<{
    name: string
    href: string
  }>
}

const defaultSections = [
  {
    title: 'Product',
    links: [
      { name: 'Overview', href: '#' },
      { name: 'Pricing', href: '#' },
      { name: 'Marketplace', href: '#' },
      { name: 'Features', href: '#' },
    ],
  },
  {
    title: 'Company',
    links: [
      { name: 'About', href: '#' },
      { name: 'Team', href: '#' },
      { name: 'Blog', href: '#' },
      { name: 'Careers', href: '#' },
    ],
  },
  {
    title: 'Resources',
    links: [
      { name: 'Help', href: '#' },
      { name: 'Sales', href: '#' },
      { name: 'Advertise', href: '#' },
      { name: 'Privacy', href: '#' },
    ],
  },
]

const defaultSocialLinks = [
  { icon: <FaInstagram className="size-5" />, href: '#', label: 'Instagram' },
  { icon: <FaFacebook className="size-5" />, href: '#', label: 'Facebook' },
  { icon: <FaTwitter className="size-5" />, href: '#', label: 'Twitter' },
  { icon: <FaLinkedin className="size-5" />, href: '#', label: 'LinkedIn' },
]

const defaultLegalLinks = [
  { name: 'Terms and Conditions', href: '#' },
  { name: 'Privacy Policy', href: '#' },
]

export const Footer = ({
  logo = {
    url: 'https://www.shadcnblocks.com',
    src: bridgeImage1,
    alt: 'Bridge logo',
    title: '',
  },
  sections = defaultSections,
  description = 'A collection of components for your startup business or side project.',
  socialLinks = defaultSocialLinks,
  copyright = '© 2025 Bridge. All rights reserved.',
  legalLinks = defaultLegalLinks,
}: FooterProps) => {
  return (
    <section className="py-16 sm:py-24">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 gap-10 lg:grid-cols-3 lg:gap-8">
          {/* Logo + description + social */}
          <div className="flex flex-col ">
            <div className="flex items-center">
              <a href={logo.url}>
                <img
                  src={logo.src}
                  alt={logo.alt}
                  title={logo.title}
                  className="h-20 w-auto"
                />
              </a>
              <h2 className="text-lg sm:text-xl font-semibold">{logo.title}</h2>
            </div>
            <p className="text-sm text-muted-foreground max-w-full mt-2">
              {description}
            </p>
            <ul className="flex items-center space-x-4 mt-4">
              {socialLinks.map((social, idx) => (
                <li key={idx}>
                  <a
                    href={social.href}
                    aria-label={social.label}
                    className="text-muted-foreground hover:text-primary "
                  >
                    {social.icon}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Sections */}
          <div className="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:col-span-2 lg:grid-cols-3">
            {sections.map((section, idx) => (
              <div key={idx}>
                <h3 className="mb-4 font-bold">{section.title}</h3>
                <ul className="space-y-2 text-sm text-muted-foreground">
                  {section.links.map((link, linkIdx) => (
                    <li
                      key={linkIdx}
                      className="hover:text-primary font-medium"
                    >
                      <a href={link.href}>{link.name}</a>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        </div>

        {/* Copyright + Legal */}
        <div className="mt-8 border-t pt-6 flex flex-col gap-4 text-xs text-muted-foreground font-medium sm:flex-row sm:justify-between sm:items-center">
          <p>{copyright}</p>
          <ul className="flex flex-col gap-2 sm:flex-row sm:gap-4">
            {legalLinks.map((link, idx) => (
              <li key={idx} className="hover:text-primary">
                <a href={link.href}>{link.name}</a>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </section>
  )
}

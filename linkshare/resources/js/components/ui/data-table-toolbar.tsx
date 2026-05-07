'use client'

import { type Table } from '@tanstack/react-table'
import { Link } from '@inertiajs/react'
import { X } from 'lucide-react'
import type React from 'react'

import { DataTableFacetedFilter } from './data-table-faceted-filter'
import { Input } from './input'
import { Button } from './button'
import { DataTableViewOptions } from './data-table-view-options'

export interface FacetedFilterConfig {
  columnId: string
  title: string
  options: readonly {
    readonly label: string
    readonly value: string
    readonly icon?: React.ComponentType<{ className?: string }>
  }[]
}

export interface SearchFilterConfig {
  columnIds: string[]
  placeholder: string
}

export interface ActionButtonConfig {
  label: string
  href?: string
  onClick?: () => void
}

interface DataTableToolbarProps<TData> {
  table: Table<TData>
  searchFilter?: SearchFilterConfig
  facetedFilters?: FacetedFilterConfig[]
  actionButton?: ActionButtonConfig
}

export function DataTableToolbar<TData>({
  table,
  searchFilter,
  facetedFilters = [],
  actionButton,
}: DataTableToolbarProps<TData>) {
  const globalFilter = (table.getState().globalFilter as string) ?? ''
  const isFiltered = table.getState().columnFilters.length > 0 || globalFilter !== ''

  return (
    <div className="flex items-center justify-between">
      <div className="flex flex-1 items-center gap-2">
        {searchFilter && (
          <Input
            placeholder={searchFilter.placeholder}
            value={globalFilter}
            onChange={(e) => table.setGlobalFilter(e.target.value)}
            className="h-8 w-[150px] lg:w-[250px]"
          />
        )}
        {facetedFilters.map((filter) => {
          const column = table.getColumn(filter.columnId)
          if (!column) return null

          return (
            <DataTableFacetedFilter
              key={filter.columnId}
              column={column}
              title={filter.title}
              options={filter.options}
            />
          )
        })}
        {isFiltered && (
          <Button
            variant="ghost"
            size="sm"
            onClick={() => {
              table.resetColumnFilters()
              table.setGlobalFilter('')
            }}
          >
            Reset
            <X />
          </Button>
        )}
      </div>
      <div className="flex items-center gap-2">
        <DataTableViewOptions table={table} />
        {actionButton && (
          actionButton.href ? (
            <Button size="sm" asChild>
              <Link href={actionButton.href}>{actionButton.label}</Link>
            </Button>
          ) : (
            <Button size="sm" onClick={actionButton.onClick}>
              {actionButton.label}
            </Button>
          )
        )}
      </div>
    </div>
  )
}

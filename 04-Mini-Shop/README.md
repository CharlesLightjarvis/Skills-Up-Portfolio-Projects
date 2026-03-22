# 🛍️ React Native E-Commerce App

A fully-featured mobile shopping app built with Expo and React Native, consuming a real REST API. iOS-first — best experienced on iOS, Android support not guaranteed.

---

## Features

**Product Catalog**

- Browse products in a responsive 2-column grid
- Filter by category with an animated tag selector
- Skeleton loading states for a polished UX
- Error handling with retry on all screens

**Product Detail**

- Horizontal image carousel with dot indicators
- Quantity selector
- Add to cart with live feedback

**Shopping Cart**

- Persistent cart across app restarts
- Add, remove, and update item quantities
- Real-time total price calculation
- Clear cart in one tap

**Order Flow**

- Order confirmation screen with total summary
- Auto-clears cart on confirmation
- Returns to home with a clean navigation stack

**Search**

- Native iOS search bar integrated in the header
- Filter products by title and description in real time
- Debounced input to avoid unnecessary renders
- Recent searches saved persistently across sessions
- Recent searches store the selected product (title + price), not the raw query
- Tap a recent search to go directly to the product
- Empty state with icon and instructions when no searches yet
- No results state with contextual message

---

## Tech Stack

- **Expo Router** — file-based navigation with native stack & modals
- **TanStack Query** — server state management, caching & auto-refetch
- **Zustand** — lightweight client state for the cart and recent searches
- **expo-secure-store** — persistent storage for cart and recent searches
- **HeroUI Native** — component library (Button, TagGroup, SkeletonGroup, Card...)
- **NativeWind** — Tailwind CSS utility classes for React Native
- **Axios** — HTTP client with response interceptor for API error handling
- **SF Symbols** — native iOS icons via `expo-symbols`

---

## Architecture

Feature-based folder structure with a clear separation between services, hooks, components and screens. Each feature owns its types, API calls, React Query hooks and UI — making the codebase easy to navigate and scale.

```
├── 📁 app
│   ├── 📁 (tabs)
│   │   ├── 📁 (home)
│   │   │   ├── 📄 _layout.tsx
│   │   │   └── 📄 index.tsx
│   │   ├── 📁 search
│   │   │   ├── 📄 _layout.tsx
│   │   │   └── 📄 index.tsx
│   │   └── 📄 _layout.tsx
│   ├── 📁 cart
│   │   ├── 📄 _layout.tsx
│   │   └── 📄 index.tsx
│   ├── 📁 order
│   │   ├── 📄 _layout.tsx
│   │   └── 📄 confirmation.tsx
│   ├── 📁 product
│   │   └── 📄 [id].tsx
│   └── 📄 _layout.tsx
├── 📁 assets
│   └── 📁 images
│       ├── 🖼️ favicon.png
│       ├── 🖼️ icon.png
│
├── 📁 components
│   ├── 📁 ui
│   │   ├── 📄 icon-symbol.ios.tsx
│   │   └── 📄 icon-symbol.tsx
│   ├── 📄 external-link.tsx
│   └── 📄 haptic-tab.tsx
├── 📁 features
│   ├── 📁 cart
│   │   ├── 📁 components
│   │   │   └── 📄 cart-button.tsx
│   │   ├── 📁 hooks
│   │   │   └── 📄 use-cart.ts
│   │   └── 📁 store
│   │       └── 📄 cart-store.ts
│   ├── 📁 category
│   │   ├── 📁 components
│   │   │   └── 📄 category-filter.tsx
│   │   ├── 📁 hooks
│   │   │   └── 📄 use-categories.ts
│   │   ├── 📁 services
│   │   │   └── 📄 category-service.ts
│   │   └── 📁 types
│   │       └── 📄 category.ts
│   ├── 📁 product
│   │   ├── 📁 components
│   │   │   ├── 📄 product-card.tsx
│   │   │   ├── 📄 product-detail-error.tsx
│   │   │   ├── 📄 product-detail-skeleton.tsx
│   │   │   ├── 📄 product-error.tsx
│   │   │   └── 📄 product-skeleton.tsx
│   │   ├── 📁 data
│   │   │   ├── 📄 mock-categories.ts
│   │   │   └── 📄 mock-products.ts
│   │   ├── 📁 hooks
│   │   │   └── 📄 use-products.ts
│   │   ├── 📁 services
│   │   │   └── 📄 product-service.ts
│   │   └── 📁 types
│   │       └── 📄 product.ts
│   └── 📁 search
│       ├── 📁 components
│       │   └── 📄 recent-searches.tsx
│       └── 📁 store
│           └── 📄 search-store.ts
├── 📁 shared
│   ├── 📁 config
│   │   ├── 📄 api.ts
│   │   ├── 📄 query-client.ts
│   │   └── 📄 query-keys.ts
│   └── 📁 hook
│       ├── 📄 use-debounce.ts
│       └── 📄 use-search.ts
├── ⚙️ .gitignore
├── 📝 README.md
├── ⚙️ app.json
├── 📄 bun.lock
├── 📄 eslint.config.js
├── 📝 filetree.md
├── 🎨 global.css
├── 📄 metro.config.js
├── ⚙️ package-lock.json
├── ⚙️ package.json
├── 📄 packages.txt
├── ⚙️ skills-lock.json
├── ⚙️ tsconfig.json
└── 📄 uniwind-types.d.ts
```

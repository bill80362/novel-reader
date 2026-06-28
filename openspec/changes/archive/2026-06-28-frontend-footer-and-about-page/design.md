## Context

The `novel-reader` platform is a small public Laravel site that displays AI-generated novels. Its current shared layout (`resources/views/layouts/app.blade.php`) contains a footer with only a copyright line. There is no disclaimer about AI-generated content, no introduction of the site owner, and no static "About" page. The layout already loads Alpine.js via CDN, so client-side modals are available without adding dependencies.

## Goals / Non-Goals

**Goals:**
- Make the AI-content disclaimer reachable from every page on the site.
- Provide a static `/about` page that introduces the site owner and links to an external resume.
- Keep the change purely presentational: no database, no admin panel, no new runtime dependencies.

**Non-Goals:**
- Editing disclaimer/about content from an admin UI (Filament or otherwise).
- Internationalization beyond the existing `zh-TW` locale used across the site.
- Refactoring the existing layout, navbar, or styling system.

## Decisions

- **Disclaimer surfaced as Alpine.js modal, not a separate page.** A modal keeps the footer compact and avoids a redundant route. Alpine.js is already loaded by the layout (`<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>`), so no extra build steps or new dependencies are required. The modal listens for a custom `open-disclaimer` window event so it can also be triggered from the `/about` page.
- **About page is a Blade view extending the existing layout.** This keeps visual consistency with the rest of the site for free and avoids new layout scaffolding. Tailwind classes (`prose prose-invert`) are used for readable content styling.
- **New route handled by a closure on `NovelController@about`.** Keeps request handling colocated with the rest of the public site logic without creating a new controller class for a single static view. Aliased as `about`.
- **Footer links use the existing dark Tailwind palette (`bg-slate-800`, `text-slate-400`, hover `text-slate-200`).** Matches the current footer and avoids introducing new theme tokens.
- **Resume link opens in a new tab** (`target="_blank" rel="noopener"`) since it points to an external domain.

## Risks / Trade-offs

- [Alpine.js modal is purely client-side] → Modal content is inlined in the layout, so it is visible in the page source. Acceptable because the disclaimer is non-sensitive.
- [Hardcoded Chinese-only disclaimer and About content] → If the site later needs multi-language support, these strings must be extracted to translation files. Out of scope for this change.
- [Closure route on `NovelController@about`] → Adds a method to a controller that is currently focused on novel browsing. Acceptable for a one-method addition; would be revisited if more static pages appear.

## Migration Plan

No data migration. Deployment is a standard pull + asset build:

1. Deploy the three changed files.
2. Run `npm run build` (or `composer run dev`) so Vite picks up any new Tailwind classes used.
3. Smoke-test `/` and `/about` to confirm the footer and About page render correctly.

Rollback: revert the three files; no schema or cache changes.

## Open Questions

_None._

## Why

The platform currently shows only a generic copyright line in the footer (`© {year} {app_name}. All rights reserved.`) and provides no way to disclose that all novels are AI-generated or to introduce the site owner. Visitors have no transparency about content origin and no way to learn who runs the platform.

## What Changes

- Update the shared layout footer to show two clickable items: **免責聲明** (Disclaimer) and **關於本站** (About).
- Add an AI-generated-content disclaimer accessible from the footer as a modal (Alpine.js), so the disclosure is one click away on every page without cluttering the layout.
- Add a new public page at `/about` that introduces the platform owner with a link to their external resume (`http://resume.0921515408.com/`) and references the same disclaimer.

## Capabilities

### New Capabilities

- `site-footer`: shared layout footer with copyright, disclaimer trigger, and link to About page.
- `about-page`: static public `/about` page with platform-owner introduction and disclaimer reference.
- `ai-content-disclaimer`: AI-generated content disclaimer surfaced from the footer.

### Modified Capabilities

_None._

## Impact

- `resources/views/layouts/app.blade.php` — replace the existing footer block and add a disclaimer modal markup.
- `resources/views/about.blade.php` — new Blade view extending the existing layout.
- `routes/web.php` — add one named route `about` pointing at `NovelController@about` (or a closure).

No backend changes: no model, no migration, no Filament resource, no new JS dependency (Alpine.js is already loaded by the layout).

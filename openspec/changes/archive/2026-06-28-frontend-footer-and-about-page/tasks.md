## 1. Routes

- [x] 1.1 Add the `about` named route in `routes/web.php` pointing to `NovelController@about`
- [x] 1.2 Add an `about` method to `app/Http/Controllers/NovelController.php` that returns the `about` view

## 2. Layout Footer and Disclaimer Modal

- [x] 2.1 Replace the footer block in `resources/views/layouts/app.blade.php` with a copyright line plus the 「免責聲明」 and 「關於本站」 controls
- [x] 2.2 Add the disclaimer modal markup to `resources/views/layouts/app.blade.php` using Alpine.js, listening for the `open-disclaimer` window event and supporting close on `Escape` / outside click / close button

## 3. About Page

- [x] 3.1 Create `resources/views/about.blade.php` extending `layouts.app` with sections: title, platform description, owner introduction (Bill Chen), external resume link, AI-content notice, and a 「免責聲明」 button that dispatches the `open-disclaimer` event

## 4. Verification

- [x] 4.1 Run `php artisan test --compact` (or filter to any existing layout/feature tests) to confirm nothing regressed
- [x] 4.2 Run `vendor/bin/pint --dirty --format agent` on changed PHP files
- [x] 4.3 Build frontend assets with `npm run build` so Tailwind picks up any new utility classes used in the footer / modal / about page
- [x] 4.4 Manually verify in a browser: home page shows the updated footer, clicking 「免責聲明」 opens the modal, the modal closes on `Escape` / outside click / close button, and `/about` renders correctly with a working resume link

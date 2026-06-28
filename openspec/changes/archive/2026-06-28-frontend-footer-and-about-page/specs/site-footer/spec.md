## ADDED Requirements

### Requirement: Footer renders copyright and links
The shared site layout MUST render a footer on every page that extends it, showing the current year, the configured application name, and two clickable navigation items.

#### Scenario: Default footer on home page
- **WHEN** a visitor opens the home page (`/`)
- **THEN** the footer displays the copyright line `© {current_year} {app_name}. All rights reserved.` together with a 「免責聲明」 control and a 「關於本站」 link

#### Scenario: About link routes to about page
- **WHEN** a visitor clicks the 「關於本站」 link in the footer
- **THEN** the browser navigates to the `about` named route (path `/about`)

#### Scenario: Disclaimer control opens the modal
- **WHEN** a visitor clicks the 「免責聲明」 control in the footer
- **THEN** the AI-content disclaimer modal becomes visible

### Requirement: Footer uses existing dark theme styling
The footer MUST reuse the existing dark Tailwind palette (`bg-slate-800`, `text-slate-400`) and MUST NOT introduce new global style tokens.

#### Scenario: Footer styling consistency
- **WHEN** a visitor views any page that extends the shared layout
- **THEN** the footer matches the existing dark slate palette and remains visually subordinate to the main content

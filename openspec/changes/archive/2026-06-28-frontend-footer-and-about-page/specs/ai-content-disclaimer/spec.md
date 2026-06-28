## ADDED Requirements

### Requirement: Disclaimer modal opens from any page
The shared layout MUST include an AI-content disclaimer that can be opened via a 「免責聲明」 control in the footer and via a custom event dispatched from any page.

#### Scenario: Opening the disclaimer from the footer
- **WHEN** a visitor clicks the 「免責聲明」 control in the footer
- **THEN** the disclaimer modal becomes visible and the rest of the page is dimmed by a backdrop

#### Scenario: Closing the disclaimer
- **WHEN** the modal is open and the visitor presses `Escape`, clicks the close button, or clicks outside the modal content
- **THEN** the modal closes

#### Scenario: Triggering the disclaimer from another page
- **WHEN** any page dispatches the `open-disclaimer` event on the `window`
- **THEN** the disclaimer modal opens without requiring a full page reload

### Requirement: Disclaimer content discloses AI origin
The disclaimer modal MUST contain Chinese-language text that clearly states the novels are AI-generated, are for entertainment only, and disclaims liability for accuracy or commercial use.

#### Scenario: Required disclosure statements
- **WHEN** a visitor opens the disclaimer modal
- **THEN** the visible text includes a statement that the novels are AI-generated, a statement that content is fictional and for entertainment, and a liability disclaimer

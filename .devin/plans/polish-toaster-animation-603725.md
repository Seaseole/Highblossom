# Polish Toaster Animation

This plan outlines the steps to polish the toast notifications in Highblossom, applying design engineering principles (Emil Kowalski style) to make them feel more premium and responsive.

## Proposed Changes

### 1. Identify Component Structure
- The `flux:toast` component has been published to `@/resources/views/flux/toast/index.blade.php`.
- It uses a custom element `<ui-toast>` which likely handles the core animation logic via the Flux JS library.
- We will focus on styling and entry/exit transitions that can be controlled via CSS and the component's structure.

### 2. Apply Design Engineering Principles
Based on the `emil-design-eng` skill, I will:
- **Refine Easings**: Replace default transitions with high-performance `cubic-bezier` curves.
- **Improve Spatial Consistency**: Ensure toasts enter and exit with a sense of physics (e.g., slight scale and opacity ramp).
- **Perceived Performance**: Use a faster `ease-out` for the entry to make the system feel snappier.
- **Visual Polish**: Adjust shadows, borders, and spacing to match the premium/brutalist aesthetic of the application.

### 3. Implementation Steps

#### Step 1: CSS Overrides in `app.css`
Add custom properties for specialized easings and toast-specific transitions.
- Define `--ease-out-quint: cubic-bezier(0.23, 1, 0.32, 1)`.
- Add scoped styles for `[data-flux-toast-dialog]` to handle the "pop" effect.

#### Step 2: Component Refinement in `index.blade.php`
- Add Tailwind transition classes to the toast container.
- Refine the internal layout (spacing, icon alignment) for better visual balance.
- Ensure the `:active` state of the close button provides tactile feedback.

## Questions for the User
1. Do you have a specific preference for where toasts should appear (e.g., top-right vs bottom-right)? Currently, it defaults to `bottom end`.
2. Would you like to add a progress bar for the auto-dismiss feature, or keep it minimal?
3. Should I apply the "Brutalist" style (sharp corners, thick borders) already present in `app.css` to the toasts, or keep them with the "Premium/Glass" feel?

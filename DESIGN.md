---
name: Highblossom
description: Premium architectural glass and aluminum solutions.
register: product
---

# Highblossom Design System

## Overview
Highblossom is a premium architectural glass and aluminum provider. The design system reflects the precision, durability, and high-end nature of the products. It balances industrial brutalist utility with a premium dark aesthetic.

**Creative North Star:** "Architectural Precision as a Digital Interface"

**Key Characteristics:**
- High contrast and deep levels of black
- Glass-morphism and subtle transparencies
- Brutalist grid with strong borders
- High-performance motion and interactions
- Authoritative Swiss typography

## Colors

### Palette
The palette is dominated by deep neutrals with high-energy red accents to indicate brand identity and interaction.

- **Admin Background** (`oklch(14.47% 0.015 285.82)`): #0A0A0F. The deep base for all administrative surfaces.
- **Admin Surface** (`oklch(22.14% 0.015 285.82)`): #16161D. Used for cards and secondary panels.
- **Admin Accent** (`oklch(53.24% 0.225 25.12)`): #DC2626. High-energy red for primary actions and highlights.
- **Admin Text** (`oklch(98.11% 0.003 285.82)`): #FAFAFA. Off-white for maximum readability.
- **Admin Text Muted** (`oklch(69.59% 0.009 258.31)`): #A1A1AA. Secondary labels and body text.

### Named Rules
- **The Contrast Rule.** Avoid middle-gray values. Every surface must be either a deep black or a high-contrast white/accent to maintain the premium feel.
- **The State Rule.** Every interactive element must have a visible transition to the accent color on hover or focus.

## Typography

### Hierarchy
- **Headline Font:** Cabinet Grotesk. A bold, architectural sans-serif used for high-impact titles and navigation.
- **Body Font:** Geist. A clean, mono-influenced sans-serif optimized for data density and readability.

- **H1 (Large Display)** (Cabinet Grotesk, weight 700, size 2.25rem): Primary page titles.
- **H2 (Section Header)** (Cabinet Grotesk, weight 700, size 1.875rem): Major content groupings.
- **Body (Standard)** (Geist, weight 400, size 0.875rem): Standard UI text and data.
- **Label (Small)** (Geist, weight 600, size 0.75rem, uppercase): Metadata, badges, and table headers.

## Elevation

### Shadows
- **Accent Glow** (`box-shadow: 0 0 30px rgba(220, 38, 38, 0.4)`): Used on primary buttons and active states to create a "laser-cut" glow effect.
- **Surface Focus** (`box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1)`): Used for focus rings on inputs and interactive elements.

## Components

### Glass Cards
Premium containers mimicking the physical glass products.
- **Background:** `var(--color-admin-surface)` with `backdrop-filter: blur(20px)`
- **Border:** `1px solid var(--color-admin-border-subtle)`
- **Interaction:** Shifts to `var(--color-admin-surface-alt)` on hover.

### Premium Buttons
- **Shape:** `rounded-full` (9999px)
- **Background:** #DC2626 (Red)
- **Shadow:** Accent Glow on hover.
- **Motion:** `translateY(-2px)` on hover.

### Admin Table
- **Radius:** `rounded-2xl` (1.5rem)
- **Header:** 10% accent tint background.
- **Interaction:** Row hover adds 5% accent tint.

## Do's and Don'ts

### Do
- Use high-quality architectural photography.
- Maintain consistent 1px borders for grid structure.
- Use Geist Mono for numerical data or codes.
- Apply `backdrop-blur` to all floating panels.

### Don't
- Use soft, generic shadows on cards.
- Apply gradients to text or headings.
- Use more than one accent color per view.
- Use fluid typography that breaks architectural alignment.

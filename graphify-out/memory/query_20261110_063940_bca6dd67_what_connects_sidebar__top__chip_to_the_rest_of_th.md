---
type: "query"
date: "2026-11-10T06:39:40.596024+00:00"
question: "What connects Sidebar, Top, Chip to the rest of the system?"
contributor: "graphify"
source_nodes: ["AdminNavLayout", "BookingDamageType", "Sidebar", "Top", "Chip"]
---

# Q: What connects Sidebar, Top, Chip to the rest of the system?

## Answer

Expanded via vocab: sidebar top chip layout enum. False lead: Sidebar and Top are cases of app/Enums/AdminNavLayout (lines 21 and 22) and Chip is a case of app/Enums/BookingDamageType (line 16). Each has degree 1 because an enum case links only to its own class; callers reference the class, never the case node. No documentation gap. Full leaf census at degree 1 or less: 2031 nodes = 829 blade view nodes + 711 function or method leaves + 173 other + 167 migration up/down closures + 103 config key leaves from composer.json package.json docker-compose.yml + 48 enum cases. Blade dominates, matching the build warning that 773 code files yielded no symbols. So the graph is missing the view layer rather than missing docs; the fix is extraction coverage (treat x-component and wire references in blade as edges), not more markdown.

## Source Nodes

- AdminNavLayout
- BookingDamageType
- Sidebar
- Top
- Chip
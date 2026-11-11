---
type: "query"
date: "2026-11-10T06:39:40.172098+00:00"
question: "Should Admin JS Behaviors be split into smaller more focused modules?"
contributor: "graphify"
source_nodes: ["admin.js", "bootDayDesk", "trapWithin", "moveBlockTo"]
---

# Q: Should Admin JS Behaviors be split into smaller more focused modules?

## Answer

Expanded via vocab: admin js dialog block theme desk. Cohesion 0.031 is partly an artifact: the community is a single file node admin.js with a contains star (internal degree 69, every other member 3 or less) and no edges from blade or livewire templates that call these functions. Real evidence is the file: resources/js/admin.js is 3363 lines with 70 extracted symbols sorting into about nine clusters, dialog focus trap (26-197), command palette and selection (197-627), forms and slug (691-929), media picker (1095-1547), block builder (1559-1727), preview (1858), theme (2158-2337), nav layout (2254-2271), overflow probes (2631-3064), day desk (3156-3353). Recommend splitting along those lines into resources/js/admin/, starting with the three that already own state (openDialogs, deskTeardown, PALETTE_RECENT_KEY). Caveats: only 20 declarations are column 0, the rest are nested in closure scope so the boot wiring must be untangled first, and window.generateSecurePassword (1873) plus window.initAdminNav (3076) are contracts called from blade templates the graph cannot see; admin shell uses wire:navigate and a persist island so modules need to stay re-entrant.

## Source Nodes

- admin.js
- bootDayDesk
- trapWithin
- moveBlockTo
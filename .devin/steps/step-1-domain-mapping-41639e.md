# Step 1: Requirements Review & Domain Mapping

## Domain Model (Text-Based Diagram)

```text
[Public User]
      |
      +--- [Pages] (Dynamic Routing)
      +--- [Services] (Service Catalog)
      +--- [Blog/Posts] (Dynamic Content)
      +--- [Leads] (Contact/Quote Forms)
      +--- [Bookings] (Multi-step flow)
              |
              +--- [Inspections] (Availability-based scheduling)
              +--- [Staff Absences] (Blocks booking slots)

[Admin/Staff Portal]
      |
      +--- [Content Management] (CRUD for Pages, Blocks, Services, Posts, Media)
      +--- [Booking Management] (Approval, Rescheduling, Cancellation)
      +--- [Availability Management] (Setting work hours, blocking dates)
      +--- [Lead Management] (Viewing and processing quotes)
```

## Discrete Single-Purpose Actions

| Domain | Action | Description | Rule Applied |
|--------|--------|-------------|--------------|
| **Content** | `PublishPageAction` | Handles page state transitions and SEO data. | Rule 1 (Single Purpose) |
| **Content** | `SyncMediaToResourceAction` | Associates media files with domain entities. | Rule 1 |
| **Booking** | `CreateBookingAction` | Validates multi-step input and creates booking. | Rule 1, Rule 9 (Queue) |
| **Booking** | `ScheduleInspectionAction` | Assigns inspection slots based on availability. | Rule 1, Rule 2 (Subqueries) |
| **Booking** | `CancelBookingAction` | Handles cancellation logic and notifications. | Rule 1, Rule 9 |
| **Booking** | `RecordStaffAbsenceAction` | Blocks slots for specific staff/dates. | Rule 1 |
| **Leads** | `ProcessLeadCaptureAction` | Sanitizes form data and triggers alerts. | Rule 1, Rule 6 (Security) |
| **Leads** | `ConvertLeadToBookingAction` | Transitions a validated lead to a booking flow. | Rule 1 |

## Rule Application Strategy (Rule 1)
By extracting business logic into these `Action` classes, we decouple it from Controllers and Models. This ensures:
- **Testability:** Actions can be unit-tested in isolation without HTTP requests.
- **Reusability:** The same logic (e.g., `CreateBookingAction`) can be used by web routes, API endpoints, or even CLI commands.
- **Maintenance:** Constructors force us to define dependencies explicitly, making the system's "wiring" transparent.

---
**Status:** Requirements reviewed and domain mapped. Ready for folder structure definition.

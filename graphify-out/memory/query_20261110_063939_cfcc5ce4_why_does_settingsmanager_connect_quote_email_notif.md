---
type: "query"
date: "2026-11-10T06:39:39.696730+00:00"
question: "Why does SettingsManager connect Quote Email Notifications to 22 service communities?"
contributor: "graphify"
source_nodes: ["SettingsManager", "CompanySettingService", "CompanySetting", "AvailabilityService"]
---

# Q: Why does SettingsManager connect Quote Email Notifications to 22 service communities?

## Answer

Expanded via vocab: setting company cache quote notification email. Note first: SettingsManager sits in a community labelled Quote Email Notifications, the label names its heaviest consumer, not the utility. Degree 49: 17 imports + 28 references. It is the single memoised read path for company settings (all() caches per request and maps getDefaults() through CompanySetting::get). Bypass check found real divergence: CompanySettingService::getDefaultSettings lines 456-501 keeps its own inline defaults table and 6 keys disagree with SettingsManager::getDefaults - address, primary_phone, whatsapp_number_default, date_format (d/M/Y vs d/m/Y), time_format_display (12 vs 24), facebook/instagram/linkedin_url. It already delegates working_hours (544) and homepage keys (514) with comments saying the two copies must not drift, so the fix was applied to some keys only. Effect: on a missing row the admin settings form and the public site render different company details. Three more readers skip the manager entirely with no SettingsManager reference: HeroVariantsCommand 37 and 43, EnsureEmailIsVerifiedWhenRequired 28, EnsureRegistrationIsEnabled 33; the middlewares each re-apply filter_var boolean coercion the manager already provides and lose the request memo.

## Source Nodes

- SettingsManager
- CompanySettingService
- CompanySetting
- AvailabilityService
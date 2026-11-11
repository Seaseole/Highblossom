---
type: "query"
date: "2026-11-10T06:39:39.261493+00:00"
question: "Why does User connect Sign-in Auth Services to 33 other communities?"
contributor: "graphify"
source_nodes: ["User", "SettingsManager", "initials", "Username"]
---

# Q: Why does User connect Sign-in Auth Services to 33 other communities?

## Answer

Expanded via vocab: user auth session role permission booking post avatar. BFS depth1 from app_models_user_user (deg 106, community Sign-in Auth Services): 39 imports + 45 references + 10 method + 6 mixes_in + 2 implements + 1 inherits, reaching 29 communities. Bridge is structural, three causes: trait/contract surface (HasApiTokens HasFactory HasRoles Notifiable PasskeyAuthenticatable TwoFactorAuthenticatable, MustVerifyEmail, PasskeyUser) pulls in Fortify/passkeys/sanctum/spatie; seven hasMany relations keyed on user_id (posts, bookings, bookingEvents, inspectionNotes, sessions, revokedSessions) pull in booking/blog/session/inspection; config/auth.php plus migrations pull in auth and schema. Verdict: legitimate aggregate root, not a god model; treat any User change as 29-community blast radius. Bypass check: initials() has zero reimplementation (12 blade call sites all use the accessor, but blade yields no symbols so true fan-in is undercounted); the username cast comment at User.php 59-62 says normalising in the model avoids doing it in each form, yet all four forms still call Username::normalize (CreateNewUser 81, ProfileUpdateRequest 37, UserRequest 90, BackfillUsernames 97); UserResource 30 reimplements getAvatarUrlAttribute with media_url() which is a thin alias of MediaUrl::asset so identical today.

## Source Nodes

- User
- SettingsManager
- initials
- Username
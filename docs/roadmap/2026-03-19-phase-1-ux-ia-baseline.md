# Roadmap Phase 1: UX and IA Baseline (Older-Adult Friendly)

Date: 2026-03-19
Issue: LEF-4
Project: Artricenter web

## 1. Current-State Audit (What exists now)

Current public pages:
- `quienes-somos.html`
- `especialidades.html`
- `tratamiento-medico-integral.html`
- `club-vida-y-salud.html`
- `contactanos.html`

Observed UX/IA gaps:
- No clear homepage/landing page; brand logo sends users to `quienes-somos.html`.
- Navigation labels are specialist-oriented and long for older users under stress.
- Contact flow is fragmented: contact details/form are not explicit, and primary conversion path depends on floating CTAs.
- Content structure is mostly text blocks without scannable summaries or action-oriented next steps.
- Potential trust signals (doctor credentials, clinic location, hours, payment options) are not surfaced as first-class IA nodes.
- WhatsApp link currently points to `web.whatsapp.com`, which is desktop-biased and not mobile-first.

## 2. Simplified Information Architecture (Target)

Top-level IA for older users (simple language, task-based):
- Inicio
- Enfermedades que tratamos
- Tratamiento y seguimiento
- Conoce al equipo
- Recursos para pacientes
- Contacto y cita

Navigation mapping from current pages:
- `quienes-somos.html` -> `Conoce al equipo`
- `especialidades.html` -> `Enfermedades que tratamos`
- `tratamiento-medico-integral.html` -> `Tratamiento y seguimiento`
- `club-vida-y-salud.html` -> `Recursos para pacientes`
- `contactanos.html` -> `Contacto y cita`

Required new page in Phase 2+:
- `index.html` (Inicio) with direct paths to symptoms, treatment steps, and booking.

## 3. UX Baseline by Page (Phase 1)

### A. Conoce al equipo (`quienes-somos.html`)
Purpose:
- Build trust quickly and explain who will guide the patient.

Core sections (order):
- Hero: who Artricenter helps + one clear CTA.
- Equipo médico (short bios + credentials).
- Enfoque de atención (what to expect in first visit).
- Misión y valores (brief, plain language).

Acceptance criteria:
- First screen contains 1 primary CTA (`Agendar valoración`) visible without scrolling on mobile.
- Content reading level avoids jargon; every section heading is action or reassurance oriented.
- At least one trust block visible before midpoint (credentials/years/patient volume).

### B. Enfermedades que tratamos (`especialidades.html`)
Purpose:
- Help users identify their condition and route to care.

Core sections:
- Intro: "Si tienes dolor articular frecuente, te orientamos".
- Disease cards (short symptom-based summaries).
- "Cuándo consultar" checklist.
- CTA to contact/booking.

Acceptance criteria:
- Each disease block includes: symptoms, impact, and next step in <= 90 words.
- Section order prioritizes common user language before medical term.
- Page contains at least two CTA points (mid-page and final section).

### C. Tratamiento y seguimiento (`tratamiento-medico-integral.html`)
Purpose:
- Explain treatment path from diagnosis to follow-up.

Core sections:
- 3-step process (Valoración -> Plan -> Seguimiento).
- What PAIPER means in patient language.
- Expected timelines and follow-up frequency.
- CTA to schedule first consultation.

Acceptance criteria:
- 3-step treatment model appears as scannable blocks on mobile.
- "PAIPER" includes plain-language explanation in first sentence.
- User can identify next action within 5 seconds (single dominant CTA).

### D. Recursos para pacientes (`club-vida-y-salud.html`)
Purpose:
- Support adherence and reduce anxiety between visits.

Core sections:
- Educational resources by topic (dolor, movilidad, hábitos).
- Community/support activities.
- "Qué hacer en caso de brote" quick guide.

Acceptance criteria:
- Resources grouped in <= 4 categories, clearly labeled.
- Every category has one actionable recommendation.
- Includes a contact escalation path for urgent symptom changes.

### E. Contacto y cita (`contactanos.html`)
Purpose:
- Convert intent into booked consultation with minimum friction.

Core sections:
- Contact options (WhatsApp, phone, form).
- Hours and location.
- What to prepare for first visit.
- Testimonials (optional, below conversion content).

Acceptance criteria:
- Contact methods visible in first viewport on mobile.
- "Tiempo de respuesta" expectation is explicit.
- Primary CTA works on mobile (`wa.me` or app-safe deep link).

## 4. Cross-Page UX Rules (Baseline)

- Typography baseline: minimum 18px body size on mobile, line-height >= 1.5.
- CTA consistency: one primary CTA label used across all pages (`Agendar valoración`).
- Navigation: max 6 top-level items, plain language labels, no duplicate "Contáctanos" child entry.
- Accessibility baseline: visible focus states, keyboard-reachable nav, color contrast >= WCAG AA.
- Mobile first: critical actions and trust signals in first two scrolls.

## 5. Prioritized Backlog for Next Phases

P1 (must do next):
- Add `index.html` homepage and reroute brand logo there.
- Replace WhatsApp desktop URL with mobile-safe variant.
- Refactor nav labels to target IA vocabulary.

P2:
- Add structured schema hints for medical service pages.
- Add FAQ blocks by condition and first-visit flow.

P3:
- Add simple conversion analytics events on CTAs and contact steps.

## 6. Definition of Done for LEF-4

LEF-4 is considered complete when:
- Existing pages are audited and gap list is documented.
- Simplified IA aligned to older-user tasks is defined.
- Page-by-page UX baseline with acceptance criteria exists.
- Cross-page baseline rules and next-phase backlog are documented.

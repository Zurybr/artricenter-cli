# Phase 2: Content Engine - Context

**Gathered:** 2026-03-19
**Status:** Ready for planning

<domain>
## Phase Boundary

Enable content management through Custom Post Types for doctors, specialties, and locations with custom templates for content pages. Creates the content engine that allows non-technical staff to manage clinic information without developer intervention.

</domain>

<decisions>
## Implementation Decisions

### Page Creation Strategy
- **Static pages in WordPress admin**: All 5 content pages (homepage, especialidades, tratamiento, club-vida-y-salud, contacto) created as static pages
- **Auto-created pages on plugin activation**: Pages created programmatically via wp_insert_post() on activation, not manually by admin
- **Fixed template layout**: Homepage sections have fixed structure in template (not flexible Gutenberg blocks)
- **Template + editable content areas**: Template defines section structure, content comes from page editor (each section maps to editable area)

### CPT Field Management
- **Custom meta boxes with WordPress API**: Use add_meta_box() and native WordPress field types (no ACF dependency)
- **Native WordPress field types**: Text inputs, URL inputs, media uploader, textarea — simple and performant
- **Multiple grouped meta boxes**: Fields organized by context (e.g., "Basic Info", "Social Media", "Location" for Doctores)
- **Separate post meta keys**: Each field saved as separate post_meta entry (e.g., _doctor_name, _doctor_specialty) for simple queries

### Template Strategy
- **Unified template per CPT type**: One flexible template with conditional rendering for single entries (not separate single-doctores.php, single-especialidades.php)
- **Shortcode listings for archives**: CPT listings use shortcodes ([doctores_list], [especialidades_list]) instead of archive templates
- **Plugin-provided templates**: Templates live in plugin directory using page template attribute (theme doesn't need modification)
- **Shortcode integration for homepage**: Homepage sections use shortcodes ([artricenter_doctors_grid], [artricenter_mission_cards]) to pull CPT data

### Claude's Discretion
- Exact shortcode naming and parameter structure
- Template part organization and file structure within plugin
- Meta box field ordering and layout
- CPT rewrite slug format (e.g., doctor-artricenter vs doctores)

</decisions>

<code_context>
## Existing Code Insights

### Reusable Assets
- **PSR-4 autoloader pattern** from artricenter-structure plugin — reuse for CPT plugin namespace (Artricenter\Content\*)
- **Template tag pattern** from structure plugin — use for shortcodes and template functions
- **Custom hooks**: artricenter_before_content, artricenter_after_content — can inject CPT data into pages

### Established Patterns
- **Single plugin with multiple classes**: Structure plugin contains header, footer, nav, hooks — follow same pattern for CPT plugin
- **Class-based components**: Each CPT type (Doctores, Especialidades, Sucursales) gets its own class
- **Template integration**: Use template tags and shortcodes (not automatic injection) for flexibility

### Integration Points
- **Plugin activation hook**: Create CPTs and pages on activation
- **Template hierarchy**: Plugin provides templates via page template attribute
- **Shortcode API**: Register shortcodes for CPT listings and homepage sections
- **WordPress admin**: Add meta boxes to CPT edit screens

</code_context>

<specifics>
## Specific Ideas

From Phase 1 decisions:
- Follow `.artricenter-` CSS namespace for any new styles
- Use template tag pattern (artricenter_get_doctores(), artricenter_doctores_list())
- Maintain single structure plugin pattern extended to content engine plugin

No specific requirements — open to standard WordPress CPT and template approaches.

</specifics>

<deferred>
## Deferred Ideas

- Advanced CPT relationships (e.g., doctor belongs to multiple specialties) — Phase 3 or later
- CPT search/filtering functionality — separate phase
- CPT REST API endpoints — future phase if needed
- CPT export/import functionality — add to backlog

</deferred>

---

*Phase: 02-content-engine*
*Context gathered: 2026-03-19*

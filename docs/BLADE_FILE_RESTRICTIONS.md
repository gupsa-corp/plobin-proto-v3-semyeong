# Blade File Creation Restrictions

## ⚠️ FORBIDDEN PATHS

**NEVER create blade files in the following directories:**

- `views/livewire/*` - This path is STRICTLY PROHIBITED for blade file creation

## Reasoning

The `views/livewire/*` directory should not contain blade template files as it conflicts with Livewire component structure and may cause routing or rendering issues.

## Alternative Locations

When creating blade files, use these appropriate locations instead:
- `resources/views/` - Standard blade templates
- `resources/views/components/` - Blade components
- `resources/views/layouts/` - Layout templates
- Other standard Laravel view directories

---

**Remember: Always check this file before creating new blade templates!**
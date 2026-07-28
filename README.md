# Force Update Translations
Contributors: mayukojpn, nao, dartui, pedromendonca, casiepa, mekemoke, miyauchi, nekojonez, rocketmartue
Tags: translation
Requires at least: 4.7
Tested up to: 6.9
Requires PHP: 5.6
Stable tag: 0.6.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Apply WordPress.org theme and plugin translations to a site even if translations are not yet approved or language packs have not been released.

## Description

Apply WordPress.org theme and plugin translations to a site even if translations are not yet approved or language packs have not been released.

This plugin exports translations from [translate.wordpress.org](https://translate.wordpress.org) with **Current + Waiting (suggestions) + Fuzzy** statuses, writes them into `WP_LANG_DIR` in the same layout as official language packs, and generates Jed JSON / `.l10n.php` so PHP and JavaScript strings can both apply.

**Note about Translation Playground:**
The [Translation Playground](https://make.wordpress.org/polyglots/2023/04/19/wp-translation-playground/) is now available for quick translation testing. However, if you need to test translations on your actual site, this plugin may remain the practical solution.

<!-- only:github/ -->
## How it works

1. You trigger an update from the Plugins list (Stable or Development) or Appearance → Update translation (themes).
2. The plugin downloads `.po` / `.mo` from GlotPress for your chosen locale.
3. It generates:
   - Jed `.json` files for `@wordpress/i18n` / `wp_set_script_translations()`
   - `.l10n.php` on WordPress 6.5+ for PHP strings
4. WordPress loads those files normally—no custom gettext filters are required.
5. Forced translations are tracked so official language-pack updates do not silently overwrite them (configurable).

`readme.txt` for WordPress.org is generated from this `README.md` with [wp-readme](https://github.com/fumikito/wp-readme) (`deno task readme`).
<!-- /only:github -->

## Installation

1. Install the plugin from the WordPress.org plugin directory, or upload the plugin ZIP.
1. Activate **Force Update Translations**.
1. Set your user or site language to something other than English (United States).

## Plugin translation

To download the translation files for a plugin:

1. Visit **Plugins**.
1. Under a WordPress.org plugin, choose **Update translation: Stable** or **Development**.
1. The link marked `(current)` shows which source is installed locally.

## Theme translation

To download the translation files for a theme:

1. Activate the theme you want to update.
1. Visit **Appearance → Update translation**.
1. Click **Update translation**.

## Settings

Visit **Settings → Force Update Translations** to:

- Choose whether downloads use the **user language** or the **site language**
- Enable/disable protection against official language-pack overwrites
- Bulk-update translations for installed WordPress.org plugins

<!-- only:github/ -->
## WP-CLI

```bash
wp fut update plugin <slug> --branch=stable
wp fut update plugin <slug> --branch=dev
wp fut update theme <stylesheet>
wp fut list-forced
wp fut clear-forced [--type=plugin] [--slug=akismet]
```

## JavaScript translations

Yes—JS strings are covered when:

- the GlotPress PO includes source references to `.js` / `.jsx` / `.ts` / `.tsx` files, and
- the target plugin/theme registers script translations (`wp_set_script_translations` or equivalent).

## Development

```bash
deno task readme      # Generate readme.txt from README.md
deno task plugin-zip  # Build installable plugin ZIP
```
<!-- /only:github -->

## Notes

- Plugin projects can target **Stable** or **Development**. Themes use the single GlotPress theme project (no Stable/Dev split in this UI).
- Official language packs only ship approved strings; this plugin also pulls Waiting suggestions so you can preview them on a real site.

## Screenshots

1. "Update translation" link will be shown in your plugins list.

## Changelog

<!-- only:wp>
To read the changelog for the latest plugin release, please navigate to the <a href="https://github.com/mayukojpn/force-update-translations#changelog">GitHub</a>.
</only:wp -->

<!-- only:github/ -->
### 0.6.4 - 2026-07-28

* Feature: Settings screen for locale source (user vs site language)
* Feature: Protect forced translations from being overwritten by official language packs
* Feature: Bulk update for installed WordPress.org plugins
* Feature: WP-CLI commands (`wp fut update`, `list-forced`, `clear-forced`)
* Docs: Document Stable/Dev selection, JS JSON generation, and overwrite protection
* Docs: Generate `readme.txt` from `README.md` via wp-readme

### 0.6.3 - 2026-07-28

* Feature: Choose Stable or Development as the translation source when updating plugin translations
* Feature: Show whether installed plugin translations came from Stable or Development

### 0.6.0 - 2025-12-17

* Security: Fixed CSRF vulnerability (CVE-2025-58236)
* Security: Added nonce verification and permission checks for translation updates
* Security: Improved input validation and path traversal protection
* Improvement: PHP 8.2 compatibility enhancements
* Improvement: Code quality improvements (PHPDoc, visibility declarations)
* Update: Synchronized GlotPress locales library to latest upstream version
* Credits: Vulnerability discovered by @nblirwn (Patchstack Alliance), security patch implemented by @rocket-martue

### 0.5

* Child theme support. props @pedro-mendonca

### 0.4

* Bug fix for fresh installed WP. props @Dartui

### 0.3.2 & 0.3.3

* Update tested up to versions.

### 0.3.1

* Update locales.php and add WP.org variants support. props @pedro-mendonca

### 0.3.0

* Added theme translation support.

### 0.2.5

* Tested up to WP 5.5.
* Minor grammar correction. Props @ePascalC
* Added plugin icon. Props @mekemoke

### 0.2.4

* Tested up to WP 5.2.2 props @pedro-mendonca
* Check if if user Locale isn't 'en_US' props @pedro-mendonca

### 0.2.3

* Add Multisite support. props @pedro-mendonca

### 0.2.2

* Check if plugin exists in WordPress.org plugin directory. props @pedro-mendonca

### 0.2.1

* Make target locale switchable by user setting. Thanks for reporting @Dartui
* Improve escaping. Thanks for reporting @miya0001

### 0.2

* Export only Current/Waiting/Fuzzy translations. props @naokomc
* Capitalize plugin name.
<!-- /only:github -->

## Upgrade Notice

### 0.6.0

* Security fix for CVE-2025-58236. Update recommended.

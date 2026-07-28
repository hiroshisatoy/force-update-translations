=== Force Update Translations ===
Contributors: mayukojpn, nao, dartui, pedromendonca, casiepa, mekemoke, miyauchi, nekojonez, rocketmartue
Tags: translation
Requires at least: 4.7
Tested up to: 6.9
Requires PHP: 5.6
Stable tag: 0.6.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Apply WordPress.org theme and plugin translations to a site even if translations are not yet approved or language packs have not been released.

== Description ==

Apply WordPress.org theme and plugin translations to a site even if translations are not yet approved or language packs have not been released.

This plugin exports translations from [translate.wordpress.org](https://translate.wordpress.org) with **Current + Waiting (suggestions) + Fuzzy** statuses, writes them into `WP_LANG_DIR` in the same layout as official language packs, and generates Jed JSON / `.l10n.php` so PHP and JavaScript strings can both apply.

**Note about Translation Playground:**
The [Translation Playground](https://make.wordpress.org/polyglots/2023/04/19/wp-translation-playground/) is now available for quick translation testing. However, if you need to test translations on your actual site, this plugin may remain the practical solution.



== Installation ==

1. Install the plugin from the WordPress.org plugin directory, or upload the plugin ZIP.
1. Activate **Force Update Translations**.
1. Set your user or site language to something other than English (United States).

== Plugin translation ==

To download the translation files for a plugin:

1. Visit **Plugins**.
1. Under a WordPress.org plugin, choose **Update translation: Stable** or **Development**.
1. The link marked `(current)` shows which source is installed locally.

== Theme translation ==

To download the translation files for a theme:

1. Activate the theme you want to update.
1. Visit **Appearance → Update translation**.
1. Click **Update translation**.

== Settings ==

Visit **Settings → Force Update Translations** to:

- Choose whether downloads use the **user language** or the **site language**
- Enable/disable protection against official language-pack overwrites
- Bulk-update translations for installed WordPress.org plugins



== Notes ==

- Plugin projects can target **Stable** or **Development**. Themes use the single GlotPress theme project (no Stable/Dev split in this UI).
- Official language packs only ship approved strings; this plugin also pulls Waiting suggestions so you can preview them on a real site.

== Screenshots ==

1. "Update translation" link will be shown in your plugins list.

== Changelog ==

To read the changelog for the latest plugin release, please navigate to the <a href="https://github.com/mayukojpn/force-update-translations#changelog">GitHub</a>.



== Upgrade Notice ==

= 0.6.0 =

* Security fix for CVE-2025-58236. Update recommended.

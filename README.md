# Twenty Twenty-Five Printable

A child theme of **Twenty Twenty-Five** that holds the templates of Eric van der Vlist's travel blog, in their web and print versions. Until now these templates were edited in the site editor and lived in the database of each site. As files in this repository they are versioned, reviewed with `diff`, and travel to production with the plugins, as part of a *rollout* (see [`wp-otherguise`](https://github.com/evlist/wp-otherguise), `docs/design/rollout.md`). Twenty Twenty-Five itself stays untouched, so its updates cannot overwrite anything.

## What it holds

Exported from the staging copy of the blog on 2026-10-12 (the templates and parts that the site editor had stored in the database for Twenty Twenty-Five), without change except that the `theme` attribute of the template parts was removed (rule 1 below):

| Kind | Files |
|---|---|
| Custom templates, web / print | `journee` / `journee-print`; `publication-randonnee` / `publication-randonnee-print`; `wp-custom-template-r-sum-mensuel-randonn-e` ("Résumé mensuel randonnée") / `wp-custom-template-r-sum-mensuel-randonn-e-print` |
| Customised standard templates | `archive`, `home`, `search`, `single` |
| Customised template parts | `header` (it only delegates to the part `header-large-title`), `footer` (**empty**: the blog has no footer) |

Not carried over: `save-category-autour-de-la-mediterranee-en-velo-2024`, an attempt that is no longer used (Eric, 2026-10-12), and the leftovers of Twenty Twenty-Four that the database still holds.

**Dependency on the database.** `archive`, `home` and `search` show their list of posts through a **reusable block** of the database, `<!-- wp:block {"ref":32919} /-->`. The number is the identifier of that post on the blog; it exists on every site as long as the site is a copy of production, and it must exist on production before the theme is activated there (`wp post get 32919 --field=post_type` says `wp_block`). Turning it into a pattern of the theme would remove the dependency; not done.

## What is where

- `style.css`: the header of the theme (`Template: twentytwentyfive` makes it a child theme) and the CSS of the blog: the galleries, the alignment of images, the maps and the drop cap, which the plugin Custom CSS & JS used to inject (snippet 33286, frontend, header). A block theme does not load the `style.css` of a child by itself (tried on WordPress 7.1.3): `functions.php` loads it after the parent's, on the site and in the editors.
- `functions.php`: that, and nothing else.

The classes of the print templates (`print-link`, `author-line`, `title-date-line`, `no-print`...) are **not** in this CSS: it does not define them; where they are styled, if they are, is still to find.
- `theme.json`: the custom templates (`customTemplates`: name, title, post types), so that they can be chosen in the post editor. **Every template file that is not a standard WordPress template needs an entry there.**
- `templates/<slug>.html` and `parts/<slug>.html`: one file per template and per template part. **The file name is the slug**, which is what the posts remember (the template chosen for a post is stored by slug), so a slug must not change once posts use it.

## Rules for the templates

1. **No `theme` attribute in `wp:template-part` blocks.** The site editor writes `"theme":"twentytwentyfive"`; in a child theme such a part renders as **nothing, silently** (tried on WordPress 7.1.3). Without the attribute the part is resolved in the active theme, which falls back to the parent: `<!-- wp:template-part {"slug":"header-large-title","area":"header"} /-->`.
2. **Edit the files, not the site editor, on staging and production.** A template saved in the site editor is stored in the database and **shadows the file** (tried on WordPress 7.1.3: a database copy attached to the child theme is used instead of the file). Check with `tools/staging/templates.sh check` of the plugin repository, which lists such copies, and removes them (after a backup) when asked.
3. **Templates of the database attached to the parent theme are not used by the child** (also tried): after switching to this theme, the customised templates of Twenty Twenty-Five of the site stop applying until their content is in `templates/`. Export them **before** switching.
4. **Styles of the site editor** (the "global styles" saved for Twenty Twenty-Five) are stored per theme and may not follow the switch. Not tried: compare the two sites on staging before and after.

## Installing

The **name of the directory is the slug of the theme**, and the identifiers that the plugin Otherguise keeps for the variants contain it (`wp-twenty-twenty-five-printable//publication-randonnee`), so it must be **the same on every site**. It is mounted like the plugins: a clone of this repository, under `wp-content/themes/wp-twenty-twenty-five-printable` (the name of the repository). `tools/staging/rollout.sh` of the plugin repository treats it as it treats the plugins (tag, push, apply).

After activating it on a site: the variants of the modes screen refer to the templates **with the theme in their identifier**, so they must be declared again for this theme.

## Verified

Tried on a scratch WordPress 7.1.3 (Twenty Twenty-Five 1.5): the ten templates and the two parts of this repository are found as theme files, the custom ones listed with their title and the post type `post`, the empty footer replaces the one of the parent, and every template renders without a PHP error (the reusable block is missing on that site, so the lists of posts are empty there); their template parts render with the rules above; a database copy shadows the file; a database copy attached to the parent theme is ignored. **Never tried on the blog itself**, nor with the site editor.

## License

GPL-3.0-or-later, see `LICENSES/`.

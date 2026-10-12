# Twenty Twenty-Five Printable

A child theme of **Twenty Twenty-Five** that holds the templates of Eric van der Vlist's travel blog, in their web and print versions. Until now these templates were edited in the site editor and lived in the database of each site. As files in this repository they are versioned, reviewed with `diff`, and travel to production with the plugins, as part of a *rollout* (see [`wp-otherguise`](https://github.com/evlist/wp-otherguise), `docs/design/rollout.md`). Twenty Twenty-Five itself stays untouched, so its updates cannot overwrite anything.

The pairs of templates it is meant to hold (web / print), as used with the modes of the plugin Otherguise:

| Web | Print |
|---|---|
| `journee` | `journee-print` |
| `publication-randonnee` | `publication-randonnee-print` |
| `wp-custom-template-r-sum-mensuel-randonn-e` ("Résumé mensuel randonnée") | `wp-custom-template-r-sum-mensuel-randonn-e-print` |

plus the other templates of the site (for example the one of a category). **Only `publication-randonnee` and its print version are here so far**, copied from `wp-otherguise/docs/examples/`; the others are exported from production with `tools/staging/templates.sh export` of the plugin repository (see below).

## What is where

- `style.css`: the header of the theme (`Template: twentytwentyfive` makes it a child theme).
- `theme.json`: the custom templates (`customTemplates`: name, title, post types), so that they can be chosen in the post editor. **Every template file that is not a standard WordPress template needs an entry there.**
- `templates/<slug>.html`: one file per template. **The file name is the slug**, which is what the posts remember (the template chosen for a post is stored by slug), so a slug must not change once posts use it.

## Rules for the templates

1. **No `theme` attribute in `wp:template-part` blocks.** The site editor writes `"theme":"twentytwentyfive"`; in a child theme such a part renders as **nothing, silently** (tried on WordPress 7.1.3). Without the attribute the part is resolved in the active theme, which falls back to the parent: `<!-- wp:template-part {"slug":"header-large-title","area":"header"} /-->`.
2. **Edit the files, not the site editor, on staging and production.** A template saved in the site editor is stored in the database and **shadows the file** (tried on WordPress 7.1.3: a database copy attached to the child theme is used instead of the file). Check with `tools/staging/templates.sh check` of the plugin repository, which lists such copies, and removes them (after a backup) when asked.
3. **Templates of the database attached to the parent theme are not used by the child** (also tried): after switching to this theme, the customised templates of Twenty Twenty-Five of the site stop applying until their content is in `templates/`. Export them **before** switching.
4. **Styles of the site editor** (the "global styles" saved for Twenty Twenty-Five) are stored per theme and may not follow the switch. Not tried: compare the two sites on staging before and after.

## Installing

The directory of the theme must be called `twentytwentyfive-printable` (the template parts of the templates do not depend on it, the identifiers the plugin Otherguise keeps for the variants do: `twentytwentyfive-printable//publication-randonnee`). It is mounted like the plugins: a clone of this repository under `wp-content/themes/twentytwentyfive-printable`. `tools/staging/rollout.sh` of the plugin repository treats it as it treats the plugins (tag, push, apply).

After activating it on a site: the variants of the modes screen refer to the templates **with the theme in their identifier**, so they must be declared again for this theme.

## Verified

Tried on a scratch WordPress 7.1.3 (Twenty Twenty-Five 1.5): the file templates are found and listed with their title and post type; their template parts render with the rules above; a database copy shadows the file; a database copy attached to the parent theme is ignored. **Never tried on the blog itself**, nor with the site editor.

## License

GPL-3.0-or-later, see `LICENSES/`.

# Twenty Twenty-Five Printable

A child theme of **Twenty Twenty-Five** that holds the templates of Eric van der Vlist's travel blog, in their web and print versions. Until now these templates were edited in the site editor and lived in the database of each site. As files in this repository they are versioned, reviewed with `diff`, and travel to production with the plugins, as part of a *rollout* (see [`shared-room`](https://github.com/evlist/shared-room), `docs/design/rollout.md`). Twenty Twenty-Five itself stays untouched, so its updates cannot overwrite anything.

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
- `functions.php`: that, and the declaration (filter `books_print_sizes`) of the image sizes of the print modes as names and fractions of the usable width of the page (`books-print-full` 0.9; `books-print-thumb` 0.36 cropped 16:9 for the gallery in the right column of `publication-randonnee-print`; `books-print-half` 0.5 cropped 16:9 for the full-width gallery of the two other print templates); the module Books of Otherguise computes the pixels (300 ppi) and registers them. The print templates use them (`sizeSlug` of the featured image, `size` of the gallery block). The fractions are estimated from the CSS and the default of 2 columns of the gallery block, a little above the estimate; not measured on a PDF yet; nothing was tried on a WordPress.

The print format is **not** under `@media print` (the page to print is meant to be seen in HTML in the browser in print mode): its CSS is `modes/print.css`, loaded by the Modes module of the plugin Otherguise only when the mode print is active (see below). The links between the web and the print mode are blocks `modes/link` of that plugin (the print icon in the web templates, in the print ones the QR code, made by the shortcode `[kaya_qrcode_dynamic]` of Kaya QR Code Generator around the shortcode `[modes_url mode="web"]` of Otherguise, is itself the link back to the web version); the sizes of the GPX maps of the print templates are attributes of the block `wp-attached-gpx`. The classes `author-line` and `title-part` are not styled anywhere (not a concern).
- `theme.json`: the fonts and the sizes of the blog (`settings.typography`), its typography (`styles`), and the custom templates (`customTemplates`: name, title, post types), so that they can be chosen in the post editor. **Every template file that is not a standard WordPress template needs an entry there.**
- `templates/<slug>.html` and `parts/<slug>.html`: one file per template and per template part. **The file name is the slug**, which is what the posts remember (the template chosen for a post is stored by slug), so a slug must not change once posts use it.

## A print page that follows the size of the paper

The day is composed for a page of **190 mm of usable width** (an A4 with margins of 10 mm: the global format of Otherguise Books). A **book project** of Books that prints on another page (CoolLibri in A5, for instance) gives the print pages the variable **`--books-k`**: the usable width of that page over the width the templates were composed for (0.674 for an A5 with margins of 10 mm, 1 for the design). The end of `modes/print.css` multiplies by it what does not follow the width of the page by itself: the heights that the map plugin writes in the HTML (`200px` and `150px`), the constrained widths of the layout, the spacing presets, the shares of the two columns (the narrower the page, the larger the column of the figures), the crop of the photo (as wide as the page, cropped a little wider on a smaller page: 16:9 in the design, about 2:1 in an A5), and the gallery (a grid of **2 columns, 3 when there are 7 photos or more on a page smaller than the design's**); the **text only down to 85 %** (`--books-t`, through the root font size), and the QR code down to 85 % so that it can be read. **The two columns of `publication-randonnee-print`** (the figures on the left, the text and the photos on the right) are a **float** and a block, not two flex columns: when the text and the photos are longer than a page, the next pages use the whole width instead of leaving the left third empty. The gap between the two columns is a **padding on the right of the column of the figures** (10 px, times `--books-k`, the former padding of the right column), not a margin: the constrained layout of WordPress gives the children of `#post-grid` `margin-right: auto !important`, which cancelled the first margin and made the columns touch. The QR code is in the column of the figures, so it stays on the first page (checked in Chromium on a day with its content tripled, in an A5; not with Gotenberg, not on the real publications). **Every `var( --books-k, 1 )` has a fallback of 1**: the theme does not need Books, and a page with no project (or a project on the page the templates were composed for) is what it was.

Tried (Chromium, 12 days of the testing site, 2026-10-27): in an A5 with margins of 10 mm, **9 of the 12 days are one page, as many as in an A4**; the other three have long texts or many photos and take two pages or more in both formats. The day that has the most text is the limit: the text is not reduced below 85 %. Nothing was tried yet on a PDF made by Gotenberg, nor with the real map tiles (the test page had none).

**The reference page.** `settings.custom.otherguise.reference` of `theme.json` states the page this theme is composed, optimised and tested for, in millimetres: `width` 210, `height` 297, `margin` 10 and `safety` 5 (an A4 with margins of 1 cm and a safety zone of 5 mm). Otherguise Books reads it to compute `--books-k` (it compares this page with the printer profile of a book project) and nothing else reads it; a theme without it keeps the global format of the settings of Books. WordPress also turns these keys into CSS variables (`--wp--custom--otherguise--reference--width`, etc.), which is harmless. Not tried yet: the reading of the key by the plugin.

## Rules for the templates

1. **No `theme` attribute in `wp:template-part` blocks.** The site editor writes `"theme":"twentytwentyfive"`; in a child theme such a part renders as **nothing, silently** (tried on WordPress 7.1.3). Without the attribute the part is resolved in the active theme, which falls back to the parent: `<!-- wp:template-part {"slug":"header-large-title","area":"header"} /-->`.
2. **Edit the files, not the site editor, on staging and production.** A template saved in the site editor is stored in the database and **shadows the file** (tried on WordPress 7.1.3: a database copy attached to the child theme is used instead of the file). Check with `tools/staging/templates.sh check` of the `shared-room` repository, which lists such copies, and removes them (after a backup) when asked.
3. **Templates of the database attached to the parent theme are not used by the child** (also tried): after switching to this theme, the customised templates of Twenty Twenty-Five of the site stop applying until their content is in `templates/`. Export them **before** switching.
4. **Styles of the site editor** (the "global styles": the fonts and the colours chosen there) are stored per theme and **do not follow the switch** (observed on the blog: Fira Sans and Literata became Manrope and Fira Code). What was chosen on the blog is now in `theme.json` (`settings.typography`: the families Literata and Fira Sans and the five sizes; `styles`: the typography of the body, the headings, the buttons, the site title...), exported from the post `wp-global-styles-twentytwentyfive` of 2026-10-12. The global stylesheet it gives was compared on a scratch WordPress with the one that Twenty Twenty-Five gives with that post: identical. To change the fonts, edit `theme.json` here, not the site editor (a choice saved in the site editor is stored for the child theme and goes on top of `theme.json`).

## Installing

The **name of the directory is the slug of the theme**, and the identifiers that the plugin Otherguise keeps for the variants contain it (`wp-twenty-twenty-five-printable//publication-randonnee`), so it must be **the same on every site**. It is mounted like the plugins: a clone of this repository, under `wp-content/themes/wp-twenty-twenty-five-printable` (the name of the repository). `tools/staging/rollout.sh` of the `shared-room` repository treats it as it treats the plugins (tag, push, apply).

After activating it on a site: the variants of the modes screen refer to the templates **with the theme in their identifier**, so they must be declared again for this theme.

## Verified

Tried on a scratch WordPress 7.1.3 (Twenty Twenty-Five 1.5): the ten templates and the two parts of this repository are found as theme files, the custom ones listed with their title and the post type `post`, the empty footer replaces the one of the parent, and every template renders without a PHP error (the reusable block is missing on that site, so the lists of posts are empty there); their template parts render with the rules above; a database copy shadows the file; a database copy attached to the parent theme is ignored. **Never tried on the blog itself**, nor with the site editor.

## License

GPL-3.0-or-later, see `LICENSES/`.

## Stylesheets of the modes

`modes/<mode>.css` is loaded by the Modes module of Otherguise **only when that mode is active** (not through `@media print`, since the print format is a mode of the site, not a media query). `modes/print.css` is the print format of the blog, taken from wp-pdf-helper. `style.css` stays the stylesheet of every mode.

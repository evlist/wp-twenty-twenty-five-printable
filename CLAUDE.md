# CLAUDE.md

Instructions for Claude Code sessions working in this repository (the child theme of Twenty Twenty-Five of Eric's travel blog). The context shared with Eric's other projects is in the repository `evlist/shared-room`; the rollouts and the scripts of the three sites are in `evlist/wp-otherguise` (`docs/design/rollout.md`, `tools/staging/`).

## Language

- Talk to Eric in **French**.
- Everything stored in the repository (templates, comments, documentation, commit messages) is in **English**; the titles of the templates are those of the site (French).

## Rules

- Read the "Rules for the templates" of `README.md` before touching a template: no `theme` attribute in template parts, one file per slug, an entry in `theme.json` for every custom template, never rename a slug that posts use.
- A template is changed **in this repository**, tried on testing, rehearsed on staging, then rolled out to production with a tag (`rollout-N`, see `wp-otherguise`). Never edit templates in the site editor of staging or production.
- Every file carries an SPDX header (GPL-3.0-or-later), or is covered by `REUSE.toml` (templates, which cannot carry a comment: a comment at the top of a template becomes a block). Keep `reuse lint` clean.
- Say what was only tried on a scratch WordPress and what was never tried on the blog.
- Never ask for, store or commit tokens or passwords. Do not create pull requests or push to another branch unless asked.

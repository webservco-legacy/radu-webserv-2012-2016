# radu-webserv-2012-2016

My personal site radu.webserv.co of 2012 to 2016 and the tools around it: one app on my WEb framework with its modules, a
Discogs API library with OAuth, VIBlog's Bash admin menu, a backup script and a one-page world map. A historical archive,
kept read-only: the PHP code of its time, not safe to deploy today, kept for its history.

## What is in it

- `src/RaduApp/`: the site (`app/` the controllers, modules, templates, configs and translations; `web/` the web root, the
  skins and the scripts). Modules: Car (my car log), Trades (my trade list), Music, Bct (my Discogs collection through the
  API), Items, Content, Users (accounts, Facebook login), Viblog, Comments, Cache, Sandbox; the Discogs library in
  `app/lib/Discogs/`, early Discogs API experiments in `web/discogs/`;
- `src/RaduAppOld/`: the first version of the site (2012);
- `src/RaduAdmin/`: a Bash `dialog` menu for VIBlog posts (image and video resizing, fades, encoding, metadata, upload);
- `src/wsBackup/`: the first version of my backup script (2013), before
  [ws-backup-2013-2017](https://github.com/webservco-legacy/ws-backup-2013-2017);
- `src/tam89/` (a copy in `src/RaduApp/web/tam89/`): TAM89 World Map, a Google GeoChart map of the countries whose bands a
  punk label released, with release details from the Discogs API.

The site runs on WEb, which is not in this repository: see
[web-2012-2017](https://github.com/webservco-legacy/web-2012-2017).

## Branches

| Branch | What |
|---|---|
| `main` | the history from Subversion, then the later copies, to 2016-10 |
| `working-copy-2014-08` | the files of a working copy of 2014-08 that Subversion did not have |
| `working-copy-2014-12` | the same for a working copy of 2014-12 |

## History

| Date | Commit | |
|---|---|---|
| 2012-03-20 | b01e2b8 | the first commits: VIBlog imported, RaduAdmin |
| 2012-07-22 | 4d9855d | the Car module |
| 2013-05-29 | 15787f6 | wsBackup |
| 2014-03-25 | 3a24b35 | TAM89 World Map |
| 2014-04-14 | a03a257 | the Trades module |
| 2014-04-27 | fde6f65 | the Facebook login |
| 2014-10-26 | 18f1117 | Discogs OAuth |
| 2015-02-25 | 2b660f8 | Bct, the last Subversion revision in the dump (r153) |
| 2016-10-17 | 82ba912 | the last revision (r167), from a copy |

## How it was built

From the dump of my Subversion repository (r1 to r153; the `git-svn-id` lines name them) and copies of trunk for the later
revisions (their log messages lost), turned into git in 2026 (121 commits on all branches), the dates kept. The
last commit before this README sets LF line endings and indents with spaces. Passwords and keys in the configs read
`REDACTED`; the mail addresses in the mail configs read `user@example.com`; the database and FTP logins in RaduAdmin's configs read `example`. The database dumps that Subversion held were
never part of the git history.

Left out of the whole history: the site's media (VIBlog photos and videos, record covers, trade pictures, two fanzine
scans, four audio recordings), the database models, VidEnc (its own repository,
[videnc](https://github.com/webservco-legacy/videnc), without the bundled encoders), client work (an import tool and an
LDAP page), a catalogue module made for a friend's record collection (its commit messages say "Catalogue", and so do the routes, layouts and scripts outside it that named it), TAM89's saved
release files and the label's own text (a short note stands in its place), a saved Discogs page, another author's OAuth
example, and three web fonts without a licence (the skins fall back to other fonts). Some commits of 2013 and 2014 became
empty this way and are not in the history.

## Third-party code

Bundled as it shipped, each under its own licence: OAuthSimple (BSD, with its `license.txt`, in two copies), the Facebook
PHP SDK 3.2.3 (Apache 2.0, with its CA bundle), PHP Simple HTML DOM Parser (MIT, in two copies), jQuery, PhotoSwipe, klass,
Magnific Popup, Modernizr and Respond.js (MIT), Google Code Prettify (Apache 2.0); the fonts Bitstream Vera Sans Mono
(Bitstream Vera licence) and Underwood Champion by Vic Fieger (freeware).

## Licence

New BSD (`LICENSE`), as the file headers say; the `LICENSE` file was added in 2026. The skins' graphics are mine.

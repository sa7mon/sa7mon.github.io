You have found the source code of https://danthesalmon.com/

## Hugo Configuration

The site has 2 taxonomies:

- tags
- link_tags

so that we can have a separate list of tags for posts and links.

## Links

When posting links, follow the format:

```sh
hugo new links/2025-06-01_title-of-post.md
```

then update the site and paste the link into the new file

## Development

After cloning the top-level project

1. `cd personal-site/danthesalmon.com/themes/pixyll`
1. `git submodule init`
1. `git submodule update`

Run local server `hugo server --bind 172.31.0.5 --port 9000 --baseUrl 10.0.1.174`

## Cover Image

Frees, H. W., photographer. (ca. 1914) The Entanglement. , ca. 1914. June 24. [Photograph] Retrieved from the Library of Congress, https://www.loc.gov/item/2013648272/.

## Licenses

- [Inter](https://fonts.google.com/specimen/Inter/license) font - SIL Open Font License Version 11
- [Roboto Mono](https://fonts.google.com/specimen/Roboto+Mono/license) font - SIL Open Font License Version 11

## To DO

- Add tag list to section pages: `/posts/`, `/links/`
- Change page title of `http://localhost:1313/links/tags/` from `Link_tags`
- Add link taxonomies
    - site
- decrease space after h1/2/3/4
- code highlighting font: menlo?
    - -webkit-font-smoothing: subpixel-antialiased;
- images in old posts (like fix-some-settings-...) are broken with `posts/images/` moved
- Add year count to post list
- Search without Javascript
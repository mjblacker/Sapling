# Sapling

Sapling is an opinionated WordPress theme based on Sapling using [Lando](https://lando.dev/), [Timber](https://www.upstatement.com/timber/), [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/), [Vite](https://vitejs.dev/), [Tailwind](https://tailwindcss.com/) and [Alpine.js](https://github.com/alpinejs/alpine).

Sapling uses the WordPress block editor to visually edit the site. This is made possible by the [ACF Blocks feature](https://www.advancedcustomfields.com/resources/blocks/). *ACF Pro license is required for this feature.*

Sapling runs on Lando (docker) to make development consistent between all developers. The install scripts will download WordPress into a `wp` directory and symlink the theme, this allows the theme to contain everything it needs to run for development.

Built and Maintained by [Aspire Web](https://aspireweb.com.au)

## Installation

1. Clone or download zip of repository to your development directory.
2. Run `lando start` in the theme directory.
3. Run `lando install` in the theme directory and follow the instructions to install WordPress.
4. Make sure you have installed [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)
5. (optional) Restore database/files to the `wp` directory in the folder containing the WordPress install.

## Development

Sapling builds your css and js files using Vite. This allows you to use the latest Javascript and CSS features.

To get started:
1. Run `lando build` to generate assets that can be used in the admin block editor. This only needs to be run as often as you want to see updated block previews in the admin.
2. Run `lando dev` to start the Vite dev server.
3. Run `lando dev-debug` to start the Vite dev server in debug mode.
4. Run `lando debug` to start viewing the WordPress debug logs.

If you need to restore the build process to defaults, run `lando clean` and this will remove the js modules, php vendor and wp install folders. This is typically only done when you need to run `lando destroy` to reset the theme build files.

### Live Reload

Live reload is enabled by default using Vite.

Vite is configured to run on port 3012 by default. This is a combination of the vite, lando and theme configuration to make this work. Changing it will require a small adjustment in each config for the new port and rebuilding with Lando.

### Versioning

To assist with long-term caching, file hashing (e.g. `main-e1457bfd.js`) is enabled by default. This is useful for cache-busting purposes.

## Production

When you're ready for production, run `lando bundle` from the theme directory. You can test production assets in development by setting the vite → environment property to "production" in config.json.
The bundle command will produce a zip that includes the theme as named in `package.json` that is ready to upload to the server or through WordPress theme upload feature.

If you're developing locally and moving files to your production environment, only the `theme` directory is needed. Ensure the `vendor` and `assets/dist` directories exists in the theme directory.

```
  sapling/
  ├── theme/ (this folder is copied and renamed)
      ├── vendor/
```

## Blocks

A block is a self-contained page section and includes its own template, script, style, functions and block files.

There are two ways to add blocks:
- JSON: Using the block.json file (see example-simple)
- PHP: Using the block.php file (see example-faq)

The PHP version is executed so can also include corresponding ACF field definitions. This is shown in the example-faq block.

The block and context file can also be prefixed with the blockname and dash. This can allow for finding files easier depending on your environment and personal preference.

Views are similar with the default name being index but there is also a prefixed render.twig version availble too. Check the `acfblocks.php` file for the specifics or to adjust these for your theme.

All *.js and *.css files in the block are automatically included in the build step so do not add these to the headers.

```
  example/
  ├── block.json
  ├── context.php
  ├── index.twig
  ├── script.js
  ├── style.css
```

To create a new block, create a directory in `theme/blocks`. Add your `index.twig` and `block.json` files and it's ready to be used with the WordPress block editor. You can optionally add style.css, script.js and context.php files. An example block is provided for reference. Add editable fields by creating a new ACF field group and setting the location rule to your new block. You can now use these fields with your block in the block editor.

### Accessing Fields

You access your block's fields in the index.twig file by using the `fields` variable. The example below shows how to display a block's field. We'll use "heading" as the example ACF field name, but it could be whatever name you give your field.

`{{ fields.heading }}`

Here's an example of how to loop through a repeater field where "features" is the ACF field name and the repeater field has a heading field.

```
{% for feature in fields.features %}
{{ feature.heading }}
{% endfor %}
```

## Directory Structure

`theme/` contains all of the WordPress core templates files.

`theme/acf-json/` contain all of your Advanced Custom Fields json files. These files are automatically created/updated using ACF's Local JSON feature.

`theme/assets/` contain all of your fonts, images, styles and scripts.

`theme/blocks/` contain all of your site's blocks. These blocks are available to use on any page via the block editor. Each block has its own template, script and style files.

`theme/patterns/` contains all of your sites's block patterns. Block Patterns are a collection of predefined blocks that you can insert into pages and posts and then customize with your own content.

`theme/views/` contains all of your Twig templates. These pretty much correspond 1 to 1 with the PHP files that respond to the WordPress template hierarchy. At the end of each PHP template, you'll notice a `Timber::render()` function whose first parameter is the Twig file where that data (or `$context`) will be used.

## License

MIT © Matt Blacker
MIT © Chris Earls

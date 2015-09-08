Newsletter WordPress Plugin
===========================

### HEADS UP
#### You MUST run `composer install` when you install the plugin.

The first version of this plugin aims to provide newsletter functionality
for the Julie Ann Wrigley Global Institute of Sustainability. Specifically
the first goal is to generate Board Letter emails from WordPress posts.

# How It Works

The plugin will pull WordPress posts from the blog that it is installed on,
dependent on tags, categories, and sort order passed to it.

The plugin will take the posts and display them in an email-friendly page.

Post Custom Attributes are used to store meta data, such as:

* `RedirectUrl` - Set the path of the link that is displayed on the newsletter for the post. Otherwise, defaults to the `sustainability.asu.edu` news post.

# Admin Panel

The admin panel will be used to generate emails based on different criteria:

* Any post that contains all of the given `tags`.
* And has the given `category`.
* And is in the given `date range`.
* Using the given `email template`.

Emails generated from these queries can be viewed or downloaded.

# 3rd Party Code

* Typeahead by Twitter: https://github.com/twitter/typeahead.js
* Pikaday Date Picker: https://github.com/dbushell/Pikaday

# Email Generation

When an email is generated, we use emogrifier to inline CSS and SCSS to transform SCSS to CSS:

* https://github.com/jjriv/emogrifier
* http://leafo.net/scssphp/docs

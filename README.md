Newsletter WordPress Plugin
===========================

Version 1

The first version of this plugin aims to provide newsletter functionality
for the Julie Ann Wrigley Global Institute of Sustainability. Specifically
the first goal is to generate Board Letter emails from WordPress posts.

# How It Works

The plugin will pull WordPress posts from the blog that it is installed on,
dependent on tags, categories, and sort order passed to it.

The plugin will take the posts and display them in an email-friendly page.

We are purposely tracking the vendor folder.

<center>
### HEADS UP
#### You MUST run `composer install` when you install the plugin.
</center>


# Admin Panel

The admin panel will be used to generate emails based on different criteria:

* Any post that contains all of the given `tags`.
* And has the given `category`.
* And is in the given `date range`.
* Using the given `email template`.

These queries can be saved for reuse. 

Emails generated from these queries can be viewed or downloaded.

* https://github.com/twitter/typeahead.js
* https://github.com/dbushell/Pikaday

# Email Generation

* https://github.com/jjriv/emogrifier
* http://leafo.net/scssphp/docs
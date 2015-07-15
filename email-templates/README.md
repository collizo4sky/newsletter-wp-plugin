Email Workflow
==============

When creating emails, it is important to remember that you are limited to HTML
tables, inline CSS, and having to test your emails on a wide variety of
clients and devices.

This pipeline helps with all of that work.

The pipeline will:

1. Compile SCSS to CSS
2. Build HTML and TXT email templates from Handlebars + Data
3. Inline the CSS into the template
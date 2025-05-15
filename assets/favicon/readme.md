# How to use the favicon
## Steps to use the favicon
 1. Download the favicon
 2. Place the favicon in this directory of the website dev folder
 3. Add the following code to the head of your HTML file
- It must be relative to the root of your website. For example, if your favicon files will be accessible at https://example.com/my-favicon/favicon.ico, enter /my-favicon/.
### Root Folder Path for favicons
```/var/www/dainedvorak.com/assets/favicon/```

```
<link rel="icon" type="image/png" href="/assets/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/assets/favicon/favicon.svg" />
<link rel="shortcut icon" href="/assets/favicon/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="Daine Dvorak Portfolio" />
<link rel="manifest" href="/assets/favicon/site.webmanifest" />

```
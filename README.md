# Social Booth Co theme

This is the custom WordPress theme for socialboothco.com. It's deployed with **Deployer for Git** from the `theme` branch of `enochwick/socialboothco`. The gallery plugin lives on `main`.

## What it covers

| Page | How it's built |
| --- | --- |
| Home | `front-page.php`. Hero with scrolling photo strip, package cards, upgrades, events, FAQ preview |
| /experience/ | `page-experience.php` |
| /faq/ | `page-faq.php`. Includes FAQ structured data for Google |
| /gallery/ | `page-gallery.php`. Uses the gallery plugin's `[sbc_gallery]` |
| Agreement, Thank you, any other page | `page.php`. Elementor-built pages keep rendering their own content |
| Check Availability, event forms | Elementor Canvas pages, unchanged |

All URLs stay the same, including `/#pricing`.

## Editing content

To edit content, go to **Appearance → Customize → Social Booth Co**. Changes show in the live preview before you publish.

- **Homepage: hero:** the tagline, headline, intro text, and the scrolling photos (a comma-separated list of Media Library IDs).
- **Packages and prices:** names, prices, descriptions, features, footer notes, "Most popular" and upgrades.
- **Event types**, **FAQ**, and **Contact, footer and booking**.

List fields take one item per line, with parts separated by `|`. Menus can be assigned under **Appearance → Menus** (Main menu and Footer menu). Until a menu is assigned, the theme shows the standard links.

## Booking

The theme turns two kinds of link into Amelia booking popups:

- `sbc-book`, used by every "Check your date" button. It opens Amelia category **1**.
- `sbc-consult`, used by every "Book a consultation" button. It opens Amelia category **4**.

Both category IDs can be changed in the Customizer. If Amelia is off, "Check your date" goes to `/check-availability/` and "Book a consultation" opens an email.

## Launch checklist

1. Go to Deployer for Git → **Install Theme**. Enter `https://github.com/enochwick/socialboothco`, with branch `theme`.
2. Go to Appearance → Themes, find **Social Booth Co** and click **Live Preview**. Check the pages before activating.
3. Click **Activate**.
4. Go to LiteSpeed Cache → **Purge All**.
5. Click "Check your date" and "Book a consultation" on the live site to confirm both Amelia popups open.

## Speed

The theme already does the following:

- On pages it draws itself (Home, Experience, FAQ, Gallery and non-Elementor pages), it doesn't load the CSS/JS from Elementor and its add-ons. That drops the homepage from 25 to 6 stylesheets and from 26 to 7 scripts. Elementor-built pages still load what they need.
- It serves Urbanist and Poppins from the theme, preloaded, instead of from Google Fonts.
- It removes the emoji script, and the block-editor CSS on templates that don't use it.
- Images lazy-load with responsive sizes, and the theme script is deferred.

## Cleanup in WordPress (do these in order)

The remaining builder pages (Check Availability, the event forms, Agreement and Thank You) use only core Elementor widgets. The add-on plugins are only used by the filler **Get a Quote** page and the old Astra design.

1. Go to Pages → **Get a Quote** and click Trash. Check nothing links to it first. Then go to Posts and trash the 5 template blog posts.
2. Deactivate these plugins, then check the site, then delete them:
   - The Plus Addons for Elementor
   - Premium Addons for Elementor
   - Ultimate Addons for Elementor (UAEL)
   - Sticky Header Effects for Elementor
   - Header Footer Elementor
3. Keep these: **Elementor** (the builder pages need it), **Amelia**, **Forminator**, **Yoast SEO**, **LiteSpeed Cache**, **Site Kit**, **Deployer for Git** and **Social Booth Co Gallery**.
4. Go to Amelia → Settings → Payments and turn off any payment gateways you don't use. Each active one loads its own script (PayPal, Stripe, Square).
5. In LiteSpeed Cache, turn on page cache, browser cache, CSS/JS minify and WebP images. Then Purge All.
6. After a couple of weeks with no issues, delete the **Astra** theme. It's your rollback until then.

## Rollback

To undo the launch, go to Appearance → Themes and activate **Astra**. Your Elementor pages were never changed, so the old site returns exactly as it was.

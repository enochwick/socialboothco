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

Every "Check your date" button has the class `sbc-book` and opens Amelia's booking popup for category **1** (changeable in the Customizer). If Amelia is off, it links to `/check-availability/`. There are no consultation buttons; the theme also strips any left in page content.

## Chat assistant

An "Ask us" button (bottom right, every page) opens a chat that answers questions from the site's own content: packages, upgrades, offers, FAQ, event types, the Experience steps, city pages and other published pages such as the Agreement. It never makes up prices or availability. For dates it points to "Check your date", which opens the Amelia popup from inside the chat. Questions it can't answer get handed to the team's email and phone.

**Turning it on.** Add your Anthropic API key to `wp-config.php` (above "That's all, stop editing!"). Never put it in the theme or in git:

```php
define( 'SBC_ANTHROPIC_API_KEY', 'sk-ant-...' );
```

Without the key, the button doesn't show. Optional settings in the same file: `SBC_CHAT_MODEL` (default `claude-haiku-5-5`, the cheapest model; use `claude-opus-5-5` for the strongest answers) and `SBC_CHAT_EMAIL` (default `memories@socialboothco.com`).

**Transcripts.** Every conversation is saved under **Chats** in the WordPress admin. Once a chat has had no new message for 5 minutes, the full transcript is emailed to memories@socialboothco.com. If the visitor comes back and keeps chatting, you get an "Updated website chat" email with the whole conversation.

**On-time emails.** WordPress only runs scheduled tasks when someone loads the site, and LiteSpeed serves most visits from cache, so emails can run late on quiet days. To fix that, add a server cron job in hPanel/cPanel (Advanced → Cron Jobs) that runs every minute:

```
wget -q -O - "https://socialboothco.com/wp-cron.php?doing_wp_cron" >/dev/null 2>&1
```

Emails go out through WordPress's normal mail (the same path Forminator and Amelia use). If they land in spam, set up an SMTP plugin.

**How answers are found.** The site content is split into small pieces and indexed. For each question, the best-matching pieces (plus the business basics) go to Claude with instructions to answer only from them. The index rebuilds automatically when you save the Customizer or any page. Content lives in `inc/chat.php`. To add synonyms (e.g. so "photo strip" finds prints), edit `sbc_chat_terms()`.

**Limits and cost.** Each visitor gets 20 messages per 10 minutes and 40 per conversation, and the whole site gets 500 messages a day. Each reply costs a fraction of a cent in API usage (about $1 per 1,000 replies).

## SEO

`inc/seo.php` works with Yoast SEO:

- **Titles and descriptions.** Pages still on Yoast's generic "Page - Social Booth Co" title get a written one (Experience, Gallery, FAQ, Agreement, Check Availability), and pages with no meta description get one. Anything you set in Yoast for a page wins, except the homepage description, which is replaced only when it's over 160 characters.
- **One H1 per page.** Agreement (Elementor, starts at an H2) has its first heading promoted to H1. Check Availability gets a visually hidden H1.
- **Local business data.** The homepage and city pages output `LocalBusiness` structured data (phone, email, price range, the cities in "Areas served", Thumbtack link). No star ratings: Google ignores self-published ones.
- **Leftover template content.** The demo blog posts, their category/tag/author/date archives and the Get a Quote page redirect (301) to the homepage and Check Availability, and are left out of Yoast's sitemaps. When a real blog starts, add `add_filter( 'sbc_blog_enabled', '__return_true' );` (e.g. with the Code Snippets plugin) to turn this off.

### City pages

Pages → Add New, title like **Photo Booth Rental in Frisco** (the city is the text after "in"), and under Template pick **City landing page**. In the content, write two or three sentences specific to that city (venues you've worked, neighborhoods, travel). They appear beside the "A photo booth experience" heading. The rest (packages, reviews, photos, FAQs) comes from the homepage.

The hero is themed per city: the city name as a giant outline behind the headline, a photo-strip print with the city on its caption, a spinning city stamp, and chips for local neighborhoods. Neighborhoods come from `sbc_city_spots()` in `inc/seo.php` (Frisco, Plano, McKinney, Dallas, Fort Worth, Arlington, Irving, Richardson, Denton, Allen, Prosper, Southlake, Carrollton, Garland); other cities show the rest of "Areas served". The print uses the page's featured image first, then homepage hero photos, rotated so each city gets a different set. Keep the content unique per city; copies of the same text don't rank.

## Launch checklist

1. Go to Deployer for Git → **Install Theme**. Enter `https://github.com/enochwick/socialboothco`, with branch `theme`.
2. Go to Appearance → Themes, find **Social Booth Co** and click **Live Preview**. Check the pages before activating.
3. Click **Activate**.
4. Go to LiteSpeed Cache → **Purge All**.
5. Click "Check your date" on the live site to confirm the Amelia popup opens.

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

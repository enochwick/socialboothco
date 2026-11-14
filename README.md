# Social Booth Co theme

This is the custom WordPress theme for socialboothco.com. It's deployed with **Deployer for Git** from the `theme` branch of `enochwick/socialboothco`. The gallery plugin lives on `main`.

## What it covers

| Page | How it's built |
| --- | --- |
| Home | `front-page.php`. Hero, packages comparison, upgrades, events, FAQ preview |
| /experience/ | `page-experience.php` |
| /faq/ | `page-faq.php`. Includes FAQ structured data for Google |
| /gallery/ | `page-gallery.php`. Uses the gallery plugin's `[sbc_gallery]` |
| Agreement, Thank you, any other page | `page.php`. Elementor-built pages keep rendering their own content |
| Check Availability, event forms | Elementor Canvas pages, unchanged |

All URLs stay the same, including `/#pricing`.

## Editing content

To edit content, go to **Appearance → Customize → Social Booth Co**. Changes show in the live preview before you publish.

- **Homepage: hero:** the headline and its two inline photos (`{1}` and `{2}` mark where they sit), the intro text and the photo row.
- **Packages and prices:** names, prices, "Most popular", the comparison rows and upgrades.
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

## Rollback

To undo the launch, go to Appearance → Themes and activate **Astra**. Your Elementor pages were never changed, so the old site returns exactly as it was.

# Social Booth Co Gallery

The `/gallery/` page on socialboothco.com is built by this plugin. It's a WordPress plugin, deployed with **Deployer for Git**.

It adds one shortcode, `[sbc_gallery]`, which renders:

- the hero, with the photo-strip collage and a "Check Your Date" button that opens the existing Amelia booking popup
- "The Moments": a filterable photo grid, a "View More Moments" button, and a full-screen lightbox (arrow keys, swipe, Esc)
- "Every booking includes"

Your Elementor header, footer, CTA section and Amelia popups are left exactly as they are.

## Adding or changing photos (no code needed)

1. Go to **Media → Library** and upload the photo.
2. Open the photo (**Edit more details**), tick one or more boxes under **Gallery Categories**, and add alt text.
3. Click **Update**. The photo appears in the gallery, ahead of the older ones.

To remove a photo from the gallery, untick all of its categories.
To add a new filter (for example "Graduations"), go to **Media → Gallery Categories**, add it, and then tag photos with it.

## Shortcode options

```
[sbc_gallery per_page="24" hero="yes" includes="yes" book_modal="c63d6a3" book_url="/#pricing" hero_ids="2312,2304,2314,2541,2315,2400"]
```

| Option | What it does |
| --- | --- |
| `per_page` | Number of photos shown before "View More Moments" |
| `hero` / `includes` | Set to `no` to hide that section |
| `book_modal` | ID of the Elementor modal that "Check Your Date" opens (the header's "Book Your Event" popup) |
| `book_url` | Where the button goes if that popup isn't on the page |
| `hero_ids` | Six Media Library IDs for the photo strips |

## Deploying

The plugin is deployed with Deployer for Git, connected to this GitHub repo (the plugin files are at the repo root). Each push to `main` is pulled in by WordPress, either by clicking **Update** in Deployer for Git or automatically through its "Push-to-Deploy" webhook.

On first activation, the plugin copies the categories from the old Premium Addons gallery onto the existing Media Library photos (attachment IDs are listed in `includes/seed.php`). This runs only once.

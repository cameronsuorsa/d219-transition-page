# District 219 Transition Page

WordPress plugin for the District 219 Toastmasters transition information page.

## Installation

1. Download `d219-transition-page.zip`
2. In WordPress admin: Plugins → Add New → Upload Plugin
3. Activate the plugin
4. Visit `yoursite.com/transition` or `yoursite.com/dlc`

If you get a 404, go to Settings → Permalinks and click Save.

## Pages

| URL | Purpose |
|-----|---------|
| `/transition` | Main transition overview page - timeline, maps, committees, FAQ |
| `/dlc` | District Leadership Committee - nominations, forms, how to apply |

**Note:** The old `/transition#nominations` anchor automatically redirects to `/dlc`.

## Asset Folder Structure

```
assets/
├── slides/                  ← Presentation slides (auto-detected)
│   ├── 01-overview.webp
│   ├── 02-timeline.webp
│   └── ...
│
├── headshots/               ← Committee member photos
│   ├── group-chair.webp
│   ├── alignment-chair.webp
│   ├── leadership-chair.webp
│   └── transition-chair.webp
│
├── maps/                    ← Territory maps
│   ├── national-overview.webp
│   └── group10-region.webp
│
├── forms/                   ← Nomination PDFs
│   ├── 450c-district-leader-nominating-form.pdf
│   ├── 450e-candidate-application.pdf
│   ├── 450h-district-officer-biographical-info.pdf
│   └── 450D-district-leader-agreement-statement-ff.pdf
│
└── transition-styles.css
```

## Adding Presentation Slides

**Just drop images in `/assets/slides/` and they appear automatically!**

The plugin detects numbers at the START or END of filenames:

| Filename | Order | Title |
|----------|-------|-------|
| `01-overview.webp` | 1 | "Overview" |
| `02-timeline.webp` | 2 | "Timeline" |
| `Slide1.png` | 1 | "Slide 1" |
| `Slide2.png` | 2 | "Slide 2" |
| `overview-01.webp` | 1 | "Overview" |
| `timeline_02.png` | 2 | "Timeline" |

**PowerPoint Export:** When you export as images, PowerPoint names them `Slide1.png`, `Slide2.png`, etc. - these work automatically!

**Supported formats:** `.webp`, `.jpg`, `.jpeg`, `.png`, `.gif`

**Gaps are OK:** Files numbered 01, 03, 07 will display in that order.

### To Add Your Slides:
1. In PowerPoint: File → Export → Change File Type → PNG or JPEG
2. Upload the exported images to `/assets/slides/`
3. Done! No code changes needed.

## Configuration

Edit `d219-transition-page.php` lines 18-19:

```php
define('D219_SHOW_BANNER', true);     // Show sitewide banner
define('D219_ZOOM_LINK', '');         // Zoom registration URL (when available)
```

## Updating Assets

### Committee Photos (`/assets/headshots/`)
| File | Person |
|------|--------|
| `group-chair.webp` | Rhonda Mauer |
| `alignment-chair.webp` | Dave Wiley |
| `leadership-chair.webp` | Melissa McGavick |
| `transition-chair.webp` | Jing Humphreys |

Recommended: 200x200px WebP, square crop.

### Maps (`/assets/maps/`)
| File | Description |
|------|-------------|
| `national-overview.webp` | US map showing all realigned districts |
| `group10-region.webp` | Group 10 regional map (D219 in burgundy) |

## GitHub Auto-Updates

The plugin checks for updates from:
`https://github.com/cameronsuorsa/d219-transition-page`

Create releases with version tags (e.g., `v1.0.1`) to push updates to both D10 and D13 sites.

## Version History

- **1.1.3** - Split nominations into separate `/dlc` page, added redirect from `/transition#nominations` to `/dlc`, updated banner with both links, auto-flush rewrite rules on version change
- **1.1.2** - Fixed email typo: district219lc1 → district219dlc1@gmail.com
- **1.1.1** - Fixed hero button hover (white bg, blue text/icon), changed Quick Interest Form icon to paper-plane, fixed PDF download icon toggle on hover
- **1.1.0** - Added Call for Nominations section with PDF forms, Quick Interest Form button, mobile-responsive positions grid, cache-busting version system
- **1.0.0** - Initial release

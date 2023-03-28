# Configuration Variables

## mainWork

Determines if a main story or route is promoted at the top of the homepage. Can be left blank, in which case all stories will be treated as equal. The value used for this parameter should be the ID (or semantic tag) of a work or a route. To summarize all four possibilities:

- If blank and only one story on site: that story is main by default.
- If not blank and only one story: same.
- If blank and many stories: no one story is promoted as the "main" story (like how M&L currently works).
- If not blank and many stories: main story is promoted as such.
    - This is similar to how WoH and the UHC currently work.
    - WoH promotes a main *route*, while UHC promotes a main *story*.

## additionalTiles

An array of tiles to be displayed beneath the main tile (or in the place of it). Each element in the array is an object with the following properties:

- `tile`. Takes the form `[tag:animation]`,`[tag:commercial]`,`[soundtracks]`,`[id/s]`,`[reference]`, or some variation thereof.
- `description`. A description to override any existing metadata on the tile. Takes the form `"Blah blah blah."`, where a blank string will tell Shin to use the existing metadata if it exists (and leave it blank otherwise).
- `image`. An image path which functions similarly to the above. If left blank, Shin will try to find an appropriate image (such as the first content entry of a given type, in the case of a type tile), or failing that, just leave it without one.
- `badge`. An image path to be layered over the top left corner of tiles. For example, you could add `"/img/badges/new.webp"` to put a "New!" badge over the top right tile.

## contentPath

The path to the main content of the library, in a form like `/content/[id/s]` or `/img/story/[tagtype:type]`. If something like the former is used, Shin will try to reason out what to do with the contents (in the case of a comic, for example, the images in the folder will be displayed in the order they're numbered). If the latter, Shin will try to find files with the relevant ID or semantic tag, and do the same (these can be numbered using an underscore, for example, `thecomingofthetoa_01.webp`). Table of contents images should go in `[path]/contents/`.

## wordCountEnabled

- `true`: Word counts will be displayed on link cards and media pages.
- `false`: Word counts will *not* be displayed on link cards or media pages.

## releaseDateEnabled

- `true`: Release dates will be displayed on link cards and media pages.
- `false`: Release dates will *not* be displayed on link cards or media pages.

## completedEnabled

- `true`: Completed status of content entries will be displayed on medium cards and pages.
- `false`: Completed status of content entries will *not* be displayed (useless for fully completed franchises).

## referenceEnabled

- `true`: Reference links will be generated on media pages, and a link to the reference homepage will be displayed in the main menu.
- `false`: Reference links will be *not* generated on media pages, and a link to the reference homepage will *not* be displayed in the main menu.

## referenceAlltoAll

- `true`: All content entries will (theoretically) be able to pull from all reference entries.
- `false`: Content entries (and their children) will only be able to pull from reference entries connected to them by the `reference_connections` table.
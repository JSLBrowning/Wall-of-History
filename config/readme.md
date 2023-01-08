# Configuration Variables

## readingOrder

- `tree`: Media entries are considered separate, discrete entities, with no overarching chronology. Leaf nodes will display navigation arrows to neighboring leaves with lower and higher chronology values. As such, chronology values can be repeated outside of sibling sets. The settings page will not have route options.
- `chronology`: Media entries are considered parts of a whole. Chronology values *cannot* be repeated, anywhere. The settings page will have route options.

## wordCount

- `true`: Word counts will be displayed on link cards and media pages.
- `false`: Word counts will *not* be displayed on link cards or media pages.

## publicationDate

- `true`: Publication dates will be displayed on link cards and media pages.
- `false`: Publication dates will *not* be displayed on link cards or media pages.

## rootName

String of admin’s choosing. Determines the display text of the backlink on root nodes ("Table of Contents" by default).

## referenceEnabled

- `true`: Reference links will be generated on media pages, and a link to the reference homepage will be displayed in the main menu.
- `false`: Reference links will be *not* generated on media pages, and a link to the reference homepage will *not* be displayed in the main menu.
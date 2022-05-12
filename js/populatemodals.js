function populateModalLinks() {
    const unsortedReferenceItems = localStorage.getItem("referenceTerms").split(",");
    const referenceItems = unsortedReferenceItems.sort((a,b) => b.length - a.length);

    // Here's an idea to ensure there's never a mixup:
    // 1. Sort all reference terms by length, longer to shorter (done).
    // 2. On page load, iterate over the list, only turning the *first* occurrence into a link.
    
    barriers = [".", ",", "!", "?", "…", " "];
    for (i = 0; i < barriers.length; i++) {
        for (j = 0; j < referenceItems.length; j++) {
            $("p:not(:has(>a:contains('" + referenceItems[j] + "'))):contains('" + referenceItems[j] + barriers[i] + "')").html(function(_, html) {
                combo = referenceItems[j] + barriers[i];
                return html.replace(combo, '<a data-reference="' + referenceItems[j] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[j] + '</a>' + barriers[i]);
            });

            // What does this DO?
            $(".contentButtonText p").find("a").contents().unwrap();
        }
    }
}

populateModalLinks();
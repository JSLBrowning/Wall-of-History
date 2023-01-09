let language_codes = [
    // ISO 639-1, ISO 639-2/T, ISO 639-2/B, ISO 639-3, English
    ["en", "eng", "eng", "eng", "English"],
    ["es", "spa", "spa", "spa", "Spanish"],
    ["fi", "fin", "fin", "fin", "Finnish"],
    ["fr", "fra", "fre", "fra", "French"],
    ["pt", "por", "por", "por", "Portuguese"],
    ["ko", "kor", "kor", "kor", "Korean"],
]


function update_iso_code(language) {
    for (let i = 0; i < language_codes.length; i++) {
        if (language == language_codes[i][0]) {
            return language_codes[i][3];
        }
    }
}


function update_languages() {
    console.log("Updating languages…");

    // Update language list.
    let languageList = localStorage.getItem("languageList").split(",");
    for (let i = 0; i < languageList.length; i++) {
        languageList[i] = update_iso_code(languageList[i]);
    }
    languageList = languageList.join(",");
    localStorage.setItem("languageList", languageList);

    // Update language preference.
    localStorage.setItem("languagePreference", update_iso_code(localStorage.getItem("languagePreference")));
}


if (parseFloat(localStorage.getItem("version")) < 1.1) {
    localStorage.clear();
    location.reload();
} else if (parseFloat(localStorage.getItem("version")) == 1.1) {
    console.log("Older version detected.");
    update_languages();
} else {
    console.log("Latest version detected.");
}
function update_iso_codes(languages) {
    languages = languages.replace("en", "eng");
    languages = languages.replace("es", "spa");
    languages = languages.replace("fi", "fin");
    languages = languages.replace("fr", "fra");
    languages = languages.replace("pt", "por");
    languages = languages.replace("ko", "kor");
    return languages;
}


if (parseFloat(localStorage.getItem("version")) < 1.1) {
    localStorage.clear();
} else if (parseFloat(localStorage.getItem("version")) == 1.1) {
    console.log("Updating language codes…");
    let languageList = update_iso_codes(localStorage.getItem("languageList"));
    localStorage.setItem("languageList", String(languageList));
    let languagePreference = update_iso_codes(localStorage.getItem("languagePreference"));
    localStorage.setItem("languagePreference", languagePreference);
}
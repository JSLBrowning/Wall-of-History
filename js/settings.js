function fixSettings() {
  let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
  let settingsList = document.getElementById("sortable");

  for (index = 0; index < WallofHistoryReadingOrder.length; ++index) {
    let object = document.getElementById(WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":"))).parentElement;
    let childObject = document.getElementById(WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")))
    settingsList.appendChild(object);

    if (WallofHistoryReadingOrder[index].substr(WallofHistoryReadingOrder[index].length - 1) == "1") {
      childObject.checked = true;
    } else {
      childObject.checked = false;
    }
  }
}

if (localStorage.getItem("WallofHistoryReadingOrder") != null){
  fixSettings();
}

$.noConflict();
jQuery(document).ready(function ($) {
  function initSortable() {
    $("#sortable").sortable();
    $("#sortable").disableSelection();
  }
  initSortable();

  let arrFields = new Array();

  function output(e) {
    if (e["currentTarget"]["lastElementChild"]["checked"]) {
      e["target"]["parentElement"]["classList"].add("checked");
    } else {
      e["target"]["parentElement"]["classList"].remove("checked");
    }

    let key = e["currentTarget"]["lastElementChild"]["defaultValue"];
    if (e["currentTarget"]["lastElementChild"]["checked"]) {
      arrFields[key] = true;
    } else {
      arrFields[key] = false;
    }
  }

  function initActions() {
    $(".ui-state-default").on("change", function (e) {
      output(e);
    });
    $(".ui-state-default input").on("click", function (e) {
      output(e);
    });
  }
  initActions();

  $("#submit").on("click", function () {
    let yesCount = 0;
    let values = $("#sortable input:checkbox").map(function () {
      if (this.checked) {
        yesCount++;
        return this.value + ":1";
      } else {
        return this.value + ":0";
      }
    }).get();
    if (yesCount === 0) {
      alert("Error: At least one item must be checked.");
    } else {
      localStorage.setItem("WallofHistoryReadingOrder", values);
      localStorage.setItem("WallofHistoryReadingOrderApplicationDate", "08102020");
      localStorage.setItem("WallofHistoryReadingOrderRecommendedStatus", "false");
      alert("Your reading order has been updated!");
    }
  });
});

function uncheckEverything(){
  let boxes = document.getElementsByTagName("input");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkEverything(){
  let boxes = document.getElementsByTagName("input");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckAnimations(){
  let boxes = document.getElementsByClassName("animation");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkAnimations(){
  let boxes = document.getElementsByClassName("animation");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckBlogs(){
  let boxes = document.getElementsByClassName("blog");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkBlogs(){
  let boxes = document.getElementsByClassName("blog");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckCards(){
  let boxes = document.getElementsByClassName("card");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkCards(){
  let boxes = document.getElementsByClassName("card");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckComics(){
  let boxes = document.getElementsByClassName("comic");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkComics(){
  let boxes = document.getElementsByClassName("comic");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckDiaries(){
  let boxes = document.getElementsByClassName("diary");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkDiaries(){
  let boxes = document.getElementsByClassName("diary");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckGames(){
  let boxes = document.getElementsByClassName("game");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkGames(){
  let boxes = document.getElementsByClassName("game");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckGrowing(){
  let boxes = document.getElementsByClassName("growing");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkGrowing(){
  let boxes = document.getElementsByClassName("growing");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckMovies(){
  let boxes = document.getElementsByClassName("movie");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkMovies(){
  let boxes = document.getElementsByClassName("movie");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckNovels(){
  let boxes = document.getElementsByClassName("novel");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkNovels(){
  let boxes = document.getElementsByClassName("novel");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckPodcasts(){
  let boxes = document.getElementsByClassName("podcast");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkPodcasts(){
  let boxes = document.getElementsByClassName("podcast");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckSerials(){
  let boxes = document.getElementsByClassName("serial");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkSerials(){
  let boxes = document.getElementsByClassName("serial");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckShorts(){
  let boxes = document.getElementsByClassName("short");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkShorts(){
  let boxes = document.getElementsByClassName("short");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}

function uncheckWeb(){
  let boxes = document.getElementsByClassName("web");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
}

function checkWeb(){
  let boxes = document.getElementsByClassName("web");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
}
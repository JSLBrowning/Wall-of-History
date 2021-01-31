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

if (localStorage.getItem("WallofHistoryReadingOrder") != null) {
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

function checkAll(sel) {
  let boxes = document.querySelectorAll("[data-tags*='" + sel.options[sel.selectedIndex].value + "']");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = true;
  }
  alert("All " + sel.options[sel.selectedIndex].value + " items have been selected.");
}

function uncheckAll(sel) {
  let boxes = document.querySelectorAll("[data-tags*='" + sel.options[sel.selectedIndex].value + "']");
  for (i = 0; i < boxes.length; i++){
    boxes[i].checked = false;
  }
  alert("All " + sel.options[sel.selectedIndex].value + " items have been unselected.");
}
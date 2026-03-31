//FIRST VISIT AJAX
var news_count = 0;
var regionsLinkStream = {
  0: "_polyline2906",
  1: "_path3672",
  2: "_path3500",
  3: "_path3480",
  4: "_path3504",
  5: "_path3758",
  6: "_path3490",
  7: "_path3502",
  8: "_path3538",
  9: "_path3680",
  10: "_path3506",
  11: "_path3496",
  12: "_path3680",
  13: "_path3540",
  14: "_path3705",
  15: "_path3486",
  16: "_path3528",
  17: "_path3452",
  18: "_path3748",
  19: "_path3472",
  20: "_path3438",
  21: "_path3414",
  22: "_path3424",
  23: "_polyline2933",
  24: "_polyline2931",
  25: "_polyline2906",
  26: "_path3414",
};

var streamUrl = "https://k.togirro.ru/welcome/ajax_r_stream/";

$(document).ready(function () {
  $("#map2").show();
  var once = false;
  $(window).scroll(function () {
    if (
      window.scrollY + $(window).height() / 2 >=
      $(".baloon_svg").offset().top
    ) {
      if (!once) {
        getNews(function (response) {
          news_count = response.length;
          make_cards();
          for (i = 0; i < response.length; i++) {
            var type = response[i].event_category;
            var color = "#95ecff";
            if (type === "teacher") {
              color = "#95ecff";
            } else {
              color = "#96eaae";
            }
            place_baloon(
              response[i],
              regionsLinkStream[response[i].location_id],
              response[i].event_name,
              color,
              response[i].event_url,
              response[i].datetime
            );
            // if (i <= 7) {
            make_panel(
              response[i],
              response[i].datetime,
              response[i].event_name,
              response[i].event_type,
              response[i].event_desc,
              color,
              response[i].event_url
            );
            // }
          }
          baloon_text_animation();
        });
        ajax_req.init();
      }
      once = true;
    }
  });
});

function getNews(cb) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", streamUrl);
  xhr.addEventListener("load", function () {
    var response = JSON.parse(xhr.responseText);
    cb(response);
  });
  xhr.addEventListener("error", function () {
    console.log("error");
  });
  xhr.send();
}

//GET COORDS OF SVG ELEMENT

function getCoords(elem) {
  var box = elem.getBoundingClientRect();

  return {
    top: box.top + pageYOffset,
    left: box.left + pageXOffset,
    bottom: box.bottom + pageYOffset,
    right: box.right + pageXOffset,
  };
}

//POSITIONING BALOON
var bal_count = 0;
function place_baloon(resp, svg_elemen_id, text, color, url, time) {
  bal_count++;
  var path = document.getElementById(svg_elemen_id);
  coords = getCoords(path);
  var x = ((coords.right - coords.left) / 2 + coords.left).toString() + "px";
  var y = ((coords.bottom - coords.top) / 2 + coords.top).toString() + "px";
  var baloonId = "bal" + resp.event_id;
  $("#map2").append(
    '<div id="' +
      baloonId +
      '" class="baloon" style="disply:none;left:' +
      x +
      "; top: " +
      y +
      '">' +
      '<div class="baloon-time">' +
      time +
      "</div>" +
      '<div class="baloon-desc" style="background-color: ' +
      color +
      '">' +
      '<span class="baloon-text" style="display:none">' +
      text +
      "</span>" +
      "</div>" +
      "</div>"
  );

  var delay = 2000 * bal_count;
  $("#" + baloonId)
    .delay(delay)
    .show(1000, function () {
      $("#" + baloonId + " .baloon-desc").css({ width: "0px" });
      $("#" + baloonId + " .baloon-text").css({ display: "none" });
      $("#" + baloonId + " .baloon-text")
        .delay(600)
        .fadeIn("fast");
      $("#" + baloonId + " .baloon-desc").animate({ width: "260px" }, 500);

      $("#" + baloonId + " .baloon-text")
        .delay(5000)
        .fadeOut("fast");

      $("#" + baloonId + " .baloon-desc")
        .delay(5500)
        .animate({ width: "toggle" }, 500);

      $("#" + baloonId)
        .delay(6500)
        .hide(500, function () {
          $(this).remove();
        });
    });
}
function baloon_text_animation() {
  setTimeout(function () {
    $(".baloon-desc").animate({ width: "toggle" }, 500);
  }, 1100);
  setTimeout(function () {
    $(".baloon-text").fadeIn("slow");
  }, 1600);
}

//NEWS PANEL
function make_panel(resp, time, title, type, text, color, url) {
  if (text === null) {
    text = "";
  }
  if (url === null) {
    url = "";
  } else {
    url = "<br>Источник: " + url;
  }
  var galId = "gallery_" + resp.event_id;
  var photo_1 = resp.photo_1;
  var img =
    '<div class="panel-image-card" style="display: flex; flex-wrap: nowrap; justify-content: flex-start;">';
  if (resp.photo_1 != undefined) {
    img +=
      '<a style="padding: 5px 5px 0 0;" data-fancybox="' +
      galId +
      '" href="assets/uploads/news/' +
      resp.photo_1 +
      '"><img src="assets/uploads/news/' +
      resp.photo_1_small +
      '"></a>';
  }
  if (resp.photo_2 != undefined) {
    img +=
      '<a style="padding: 5px 5px;" data-fancybox="' +
      galId +
      '" href="assets/uploads/news/' +
      resp.photo_2 +
      '"><img src="assets/uploads/news/' +
      resp.photo_2_small +
      '"></a>';
  }
  if (resp.photo_3 != undefined) {
    img +=
      '<a style="padding: 5px 0 0 5px;" data-fancybox="' +
      galId +
      '" href="assets/uploads/news/' +
      resp.photo_3 +
      '"><img src="assets/uploads/news/' +
      resp.photo_3_small +
      '"></a>';
  }
  img += "</div>";

  var contText = "Читать далее...";
  if (!text) {
    contText = "";
  }
  $(".news_panel").append(
    '<div class="card" style="display: none">' +
      '<div class="card-header" style="background-color:' +
      color +
      '">' +
      '<div class="card-time" title="' +
      time +
      '">' +
      time +
      "</div>" +
      //CLOSING CARD FUNCTIONAL
      // '<div class="card-close"></div>'+
      '<h6 class="card-title">' +
      title +
      img +
      "</h6>" +
      '<h6 class="card-title" style="text-align: right;">' +
      contText +
      "</h6>" +
      "</div>" +
      '<div class="card-body" style="display: none">' +
      '<div class="card-text">' +
      text +
      url +
      "</div>" +
      //'<a href="'+url+'" target="_blank" class="btn btn-primary" style="background-color:'+color+'">Read more</a>'+
      "</div>" +
      "</div>"
  );
}

async function showNews(start, end, delay, cb = () => {}) {
  const timer = (ms) => new Promise((res) => setTimeout(res, ms));
  for (i = start; i < end; i++) {
    $($("#map2 .card")[i]).toggle("fast");
    await timer(delay);
  }
  cb();
}

//CARDS OF NEWS
function make_cards() {
  setTimeout(async function () {
    let cardAmount = $("#map2 .card").length;
    if (cardAmount > 7) {
      showNews(0, 7, 300, () => {
        const showMore =
          '<button id="readMore" type="button" class="btn btn-primary">Показать/скрыть новости</button>';
        $(".news_panel").append(showMore);
        $("#readMore").click(async () => {
          showNews(7, cardAmount, 0);
        });
      });
    } else {
      $("#map2 .card")
        .first()
        .show("fast", function showNext() {
          $(this).next("#map2 .card").show("fast", showNext);
        });
    }

    // $("#map2 .card")
    //   .first()
    //   .show("fast", function showNext() {
    //     $(this).next("#map2 .card").show("fast", showNext);
    //   });

    $("#map2 .card-header").click(function (e) {
      if (
        ($(e.target).next().css("display") === "none" &&
          $(e.target).next().attr("class") === "card-body") ||
        ($(e.target).parent().next().css("display") === "none" &&
          $(e.target).parent().next().attr("class") === "card-body")
      ) {
        if ($(e.target).attr("class") === "card-header") {
          $(e.target).next().slideDown("slow");
        } else if ($(e.target).attr("class") === "card-title") {
          $(e.target).parent().next().slideDown("slow");
        } else if ($(e.target).attr("class") === "card-time") {
          $(e.target).parent().next().slideDown("slow");
        }
      } else if (
        ($(e.target).next().css("display") === "block" &&
          $(e.target).next().attr("class") === "card-body") ||
        ($(e.target).parent().next().css("display") === "block" &&
          $(e.target).parent().next().attr("class") === "card-body")
      ) {
        if ($(e.target).attr("class") === "card-header") {
          $(e.target).next().slideUp("slow");
        } else if ($(e.target).attr("class") === "card-title") {
          $(e.target).parent().next().slideUp("slow");
        } else if ($(e.target).attr("class") === "card-time") {
          $(e.target).parent().next().slideUp("slow");
        }
      }
    });
  }, 6500);

  //CLOSING CARD FUNCTIONAL
  // setTimeout(function(){
  // 	$('.card-close').click(function(e){
  // 		$(e.target).parent().parent().slideUp(400);
  // 		setTimeout(function(){
  // 			$(e.target).parent().parent().remove();
  // 		}, 400);
  // 	});
  // }, 1000);
}

//CHECK NEW RECORD
// function hideCards() {
//   let cardsAmount = $("#map2 .card").length;
//   if (cardsAmount > 7) {
//     for (i = 7; i < cardsAmount; i++) {
//       $($("#map2 .card")[i]).css("display", "none !important");
//     }
//   }
// }
// setTimeout(() => {
//   hideCards();
// }, 10000);
// setInterval(() => {
//   hideCards();
// }, 60000);
var ajax_req = {
  updInterval: 60000,
  url: streamUrl,
  init: function () {
    var self = ajax_req;
    setInterval($.proxy(ajax_req.requestData, self), self.updInterval);
  },
  requestData: function () {
    var self = ajax_req;
    var currentdate = new Date();
    var datetime =
      currentdate.getDate() +
      "-" +
      (currentdate.getMonth() + 1) +
      "-" +
      currentdate.getFullYear() +
      "--" +
      currentdate.getHours() +
      ":" +
      currentdate.getMinutes() +
      ":" +
      currentdate.getSeconds();
    $.ajax({
      url: self.url + "/" + datetime,
      type: "GET",
      dataType: "json",
      success: function (data) {
        self.update(data);
      },
      error: function (data) {
        self.error(data);
      },
    });
  },
  update: function (response) {
    var self = ajax_req;
    if (news_count >= response.length) {
      return;
    } else {
      $("#map2 .card-header").off("click");
      make_cards();
      for (i = news_count; i < response.length; i++) {
        var type = response[i].event_type;
        var color = "#f7f7f7";
        if (type === "1") {
          color = "#fc5920";
        } else if (type === "2") {
          color = "#f9bd05";
        } else if (type === "3") {
          color = "#1f96f2";
        }
        // resp, svg_elemen_id, text, color, url, time
        place_baloon(
          regionsLinkStream[response[i].location_id],
          response[i].event_name,
          color,
          response[i].event_url
        );
        make_panel(
          response[i].datetime,
          response[i].event_name,
          response[i].event_type,
          response[i].event_desc,
          color,
          response[i].event_url
        );
      }
      baloon_text_animation();
      news_count = response.length;
    }
  },
  error: function (response) {
    var self = ajax_req;
    console.log("Failed to get data");
  },
};

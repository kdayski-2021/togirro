var cscroller_f = false;
window.onload = function () {
  setTimeout(function () {
    $(".preloader").slideUp("slow", () => {
      $("html").css("overflow", "");
    });
  }, 1000);
};
$(document).ready(function () {
  // VOTE
  function vote(direction, obj) {
    var pid = obj.attr("pid");
    $.ajax({
      type: "POST",
      url: siteUrl + "welcome/ajax_r_vote/" + direction + "/" + pid,

      async: false,
      success: function (msg) {
        var data = msg !== "" ? JSON.parse(msg) : [];

        if (data.error != undefined && data.error != "") {
          alert(data.error);
        }
        if (data.success) {
          $("#total_bals").html(data.left);
          var text_bals = obj.parent().find(".vote-text");
          if (isNaN(text_bals.html())) text_bals.html(0);
          var res_val = Number(text_bals.html()) + direction;
          if (res_val == 0) {
            text_bals.html("Голосовать");
          } else {
            text_bals.html(res_val);
          }
        }
      },
      error: function () {},
    });
  }
  $(".vote-p").on("click", function (e) {
    vote(1, $(this));
    e.preventDefault();
  });
  $(".vote-m").on("click", function (e) {
    vote(-1, $(this));
    e.preventDefault();
  });
  // DESIGN
  $(".block-collapse").on("click", function (e) {
    $(this).find(".block-collapse-div").slideToggle();
    e.preventDefault();
  });
  // REGISTER
  $("#themeSelect").multiselect({
    enableClickableOptGroups: true,
    buttonWidth: "100%",
    nonSelectedText: "Выберите интересующие Вас темы",
  });
  $("#registerModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var point_id = button.data("point_id");
    $.ajax({
      type: "POST",
      url: siteUrl + "welcome/ajax_r_progam/" + point_id,

      async: false,
      success: function (msg) {
        var data = msg !== "" ? JSON.parse(msg) : [];

        if (data.error != undefined && data.error != "") {
          alert(data.error);
        }
        $("#themeSelect").html(data.success);

        $("#themeSelect").multiselect("destroy");
        $("#themeSelect").multiselect({
          enableClickableOptGroups: true,
          buttonWidth: "100%",
          nonSelectedText: "Выберите интересующие Вас темы",
          nSelectedText: " - выбрано",
        });
      },
      error: function () {},
    });

    var point_name = button.data("point_name");
    var modal = $(this);
    modal.find("#registerModalLabel").html("Регистарция: " + point_name);
    modal.find("#point_id").val(point_id);
  });
  $("#f-submit").on("click", function (e) {
    if ($("#user_name").val() == "") {
      alert("Заполните поле Ф.И.О.");
      return false;
    }
    if ($("#user_email").val() == "") {
      alert("Заполните поле Email");
      return false;
    }

    if ($("#district_id").val() === null) {
      alert("Заполните поле Район");
      return false;
    }
    if ($("#themeSelect").val() === null) {
      alert("Заполните поле Интересующие темы");
      return false;
    }

    $("#f-form").submit();
    $("#registerModal").modal("hide");
  });

  //ACCORDION

  var js_accordion = $(".js-accordion"),
    collapse_trigger = $(".btn-link");
  if (js_accordion.length > 0) {
    //   Open first accordion by default
    js_accordion
      .find(".card")
      .first()
      .find(".collapse")
      .css("display", "block");

    //   Add class expanded on first accordion by default

    //   click event for accordion
    $("body").on("click", ".btn-link", function () {
      var currentCollapse = $(this).closest(".card").find(".collapse"),
        remainingCollapse = $(this)
          .closest(js_accordion)
          .find(".collapse")
          .not(currentCollapse),
        remainingBtn = $(this)
          .closest(js_accordion)
          .find(".btn-link")
          .not(this);

      //     toggle the clicked accordion
      currentCollapse.slideToggle();
      $(this).toggleClass("expanded");

      //     close all other accordion if any.
      remainingCollapse.slideUp();
      remainingBtn.removeClass("expanded");
    });
  }

  //   $(".content1").hide();
  $(".content2").hide();
  $("#toggle-documentation").click(function (e) {
    e.preventDefault();
    $("#documentation").animate({ height: "toggle" }, 1000);
    $("#documentation").toggleClass("hidden");
    arrow = $("#toggle-documentation").children()[0];
    direction = arrow.classList[1];
    visibility = $("#toggle-documentation").css("display");
    if (direction == "fa-angle-double-down" && visibility == "block") {
      $(arrow).removeClass(direction);
      $(arrow).addClass("fa-angle-double-up");
    } else {
      $(arrow).removeClass(direction);
      $(arrow).addClass("fa-angle-double-down");
    }
  });
  $("#toogle-expert").click(function (e) {
    // $("#participant_add0").hide();
    // $("#participant_add1").hide();
    $([document.documentElement, document.body]).animate(
      {
        scrollTop: $("#expert-section").offset().top,
      },
      500
    );
    $("#experts").slideToggle("slow");
    updateScrollToggler();
    e.preventDefault();
  });
  $(".card-participant").click(function (e) {
    if (e.target.nodeName == "A") {
      window.open($(e.target).attr("href"));
    }
  });
  $(".expert-category").click(function (e) {
    $("#experts").slideDown("slow");
    var cat = $(this).val();
    if (cat == 0) {
      $("#participant_add1").hide();
      $("#participant_add0").show();
    } else if (cat == 1) {
      $("#participant_add0").hide();
      $("#participant_add1").show();
    } else {
      $("#participant_add0").hide();
      $("#participant_add1").hide();
    }
    $(".card-expert").each(function () {
      if (cat == -1) {
        $(this).show();
      } else {
        if (cat != $(this).attr("category")) {
          $(this).hide();
        } else {
          $(this).show();
        }
      }
    });
    if (!cscroller_f) updateScrollToggler();
    e.preventDefault();
  });
  // LOCATION
  $("#locationModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);

    var point_name = button.data("point_name");
    var address = button.data("address");
    var modal = $(this);
    modal.find("#locationModalLabel").html("Адрес: " + address);

    var addressUrl = address.replace(/ /g, "+");
    var requestURL =
      "https://maps.googleapis.com/maps/api/geocode/json?address=" +
      addressUrl +
      "&key=AIzaSyAmmo1EdBtz181RNlShZ2wAdLathXSTyAw";
    var request = new XMLHttpRequest();
    request.open("GET", requestURL);
    request.responseType = "json";
    request.send();
    function make_lat(geocode_data) {
      return geocode_data["results"][0]["geometry"]["location"]["lat"];
    }
    function make_lng(geocode_data) {
      return geocode_data["results"][0]["geometry"]["location"]["lng"];
    }
    function initialize(lat, lng, address) {
      var myLatlng = new google.maps.LatLng(lat, lng);
      var address = address;
      var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        //center: myLatlng – это координаты центра карты
        //zoom – это увеличение при инициализации
        //mapTypeId – тип (политическая, физическая, гибрид)
      };
      var map = new google.maps.Map(
        document.getElementById("map_canvas"),
        myOptions
      );

      var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: address,
        //position – собственно координаты метки
        //map – на какую карту метку поместить
        //title – при наведении мыши будет писать address.
      });
      marker.setMap(map);
    }
    request.onload = function () {
      var geocode_data = request.response;
      lat = make_lat(geocode_data);
      lng = make_lng(geocode_data);
      initialize(lat, lng, address);
    };
  });
  // CONTENT SWITCHER
  $("#register-2").click(function (e) {
    // window.location.href = "https://forum.togirro.ru/register";
    // window.location.href = window.location.href + "/register";
    window.location.href = siteUrl + "register";
  });
  $("#register-1").click(function (e) {
    // window.location.href = "https://forum.togirro.ru/register";
    // window.location.href = window.location.href + "/register";
    window.location.href = siteUrl + "register";
  });
  // $("#teacheroftheyear").click(function (e) {
  // 	changeContent(1)
  // 	e.preventDefault()
  // })
  //   $("#proffesional5day").click(function (e) {
  //     changeContent(2);
  //     e.preventDefault();
  //   });
  function updateConatainer() {
    $(".content-switcher").attr("style", "width:" + $(window).width() + "px");
  }
  updateConatainer();
  $(window).resize(function () {
    updateConatainer();
  });
  var animTime = 500;
  var activeSide = 0;
  var lastContent = 0;
  function changeContent(mode) {
    $(".content3").hide();
    $(".content" + lastContent).hide();
    $(".content" + mode).slideDown("slow");
    lastContent = mode;
  }
  // ROADMAP DATE FIXER
  trash_date = $(".t514__leftcol.t-col.t-col_2.t-prefix_1.t-align_right");
  if ($(window).width() <= 768) {
    for (var i = 0; i < trash_date.length; i++) {
      $(trash_date[i]).remove();
    }
  }
  $(".side-item").mouseover(function () {
    if (!activeSide) {
      if ($(this).hasClass("left-side")) {
        //LEFT SIDE
        $(".right-side").stop().animate(
          {
            left: "55%",
          },
          animTime
        );
        $(".left-side")
          .stop()
          .animate(
            {
              left: "-45%",
            },
            animTime - 100
          );
      } else {
        $(".right-side")
          .stop()
          .animate(
            {
              left: "45%",
            },
            animTime - 100
          );
        $(".left-side").stop().animate(
          {
            left: "-55%",
          },
          animTime
        );
      }
    }
  });
  $(".side-item").mouseout(function () {
    if (!activeSide) {
      $(".right-side")
        .stop()
        .animate(
          {
            left: "50%",
          },
          animTime * 2
        );
      $(".left-side")
        .stop()
        .animate(
          {
            left: "-50%",
          },
          animTime * 2
        );
    }
  });
  $(".side-item").click(function () {
    activeSide = 1;
    if ($(this).hasClass("left-side")) {
      //LEFT SIDE
      $(".right-side")
        .stop()
        .animate(
          {
            left: "90%",
          },
          animTime * 2
        );
      $(".left-side")
        .stop()
        .animate(
          {
            left: "-10%",
          },
          animTime * 2
        );
      changeContent(1);
    } else {
      $(".right-side")
        .stop()
        .animate(
          {
            left: "5%",
          },
          animTime * 2
        );
      $(".left-side")
        .stop()
        .animate(
          {
            left: "-95%",
          },
          animTime * 2
        );
      changeContent(2);
    }
  });
});
function updateScrollToggler() {
  cscroller_f = !cscroller_f;
  if (cscroller_f) {
    $("#toogle-expert span").addClass("fa-angle-double-up");
    $("#toogle-expert span").removeClass("fa-angle-double-down");
  } else {
    $("#toogle-expert span").addClass("fa-angle-double-down");
    $("#toogle-expert span").removeClass("fa-angle-double-up");
  }
}

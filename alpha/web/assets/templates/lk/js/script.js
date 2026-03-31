$(document).ready(() => {
  $(".slide-schedule").click((e) => {
    $(e.target).next().slideToggle("slow");
  });
});

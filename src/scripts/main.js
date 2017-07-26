import $ from "jquery";
import fullCalendar from "fullcalendar";
import "fullcalendar/dist/fullcalendar.css";

// Mobile menu
const mobileMenu = $("#mini-menu");
mobileMenu.on("click", (event) => {
  $(event.currentTarget).toggleClass("state-show-child");
});

// Events calendar
$("#calendar").fullCalendar();

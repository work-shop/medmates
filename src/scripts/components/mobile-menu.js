import $ from "jquery";

const mobileMenu = $("#mini-menu");

mobileMenu.on("click", (event) => {
  $(event.currentTarget).toggleClass("state-show-child");
});

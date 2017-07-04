const $ = jQuery;
const mobileMenu = $("#mini-menu");

mobileMenu.on("click", (event) => {
  event.preventDefault();
  $(event.currentTarget).toggleClass("state-show-child");
});

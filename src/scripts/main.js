const $ = jQuery;

console.log("Hello, world!");

const mobileMenu = $("#mini-menu");

mobileMenu.on("click", (event) => {
  event.preventDefault();
  $(event.currentTarget).toggleClass("state-show-child");
});

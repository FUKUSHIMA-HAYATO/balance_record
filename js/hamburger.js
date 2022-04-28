let x_mark1 = document.getElementsByClassName("x_mark1");
let x_mark2 = document.getElementsByClassName("x_mark2");
let x_mark3 = document.getElementsByClassName("x_mark3");
let hamburgerMenu = document.getElementsByClassName('hamburgerMenu');
let x_mark_button = document.getElementsByClassName('x_mark_button');
let hamburger_overlay = document.getElementsByClassName('hamburger_overlay');

function hamburger() {
  x_mark1[0].classList.toggle("open");
  x_mark2[0].classList.toggle("open");
  x_mark3[0].classList.toggle("open");
  hamburgerMenu[0].classList.toggle("open");
  x_mark_button[0].classList.toggle("open");
  hamburger_overlay[0].classList.toggle("open");
}
hamburger_overlay[0].addEventListener('click', function(e){
  x_mark1[0].classList.toggle("open");
  x_mark2[0].classList.toggle("open");
  x_mark3[0].classList.toggle("open");
  hamburgerMenu[0].classList.toggle("open");
  x_mark_button[0].classList.toggle("open");
  hamburger_overlay[0].classList.toggle("open");
});
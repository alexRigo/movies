import '../styles/header.scss';

let userPhoto = document.querySelector("#user-photo");
let userMenu = document.querySelector("#user-menu-wrap");

userPhoto.addEventListener('click', () => {
    userMenu.classList.toggle("show");
});
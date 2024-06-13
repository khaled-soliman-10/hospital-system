const sidebar = document.querySelector(".page .sidebar");
const toggleButton = document.querySelector(".page .sidebar .title i");
const logo = document.querySelector(".page .sidebar .title .logo");
const title = document.querySelector(".page .sidebar .title h2");
const spans = document.querySelectorAll(".page .sidebar span");
const tog = document.querySelectorAll(".sidebar .links .num a .tog");

// drobdown list
const drob = document.querySelectorAll(".sidebar .links .num");
const menu = document.querySelectorAll(".sidebar .links .list");

if (drob[0].classList.contains("action")) {
    menu[0].style.display = "block";
    tog[0].classList.replace("fa-angle-right", "fa-angle-down");
}
if (drob[1].classList.contains("action")) {
  menu[1].style.display = "block";
  tog[1].classList.replace("fa-angle-right", "fa-angle-down");
}
// minimizing sidebar 
toggleButton.onclick = () => {
    toggleButton.classList.toggle("toggle-btn");
    if (toggleButton.classList.contains("toggle-btn")) {
        sidebar.style.width = "50px";
        logo.style.display = "none";
        menu.forEach((el) => {
            el.style.display = "none";
        })
        title.style.display = "none";
        tog.forEach((el) => {
            el.style.display = 'none';
        })
        spans.forEach((el) => {
            el.style.display = "none";
        })
    } else {
        sidebar.style.width = "250px";
        logo.style.display = "block";
        title.style.display = "block";
        tog.forEach((el) => {
            el.style.display = "inline";
        });
        spans.forEach((el) => {
            el.style.display = "inline";
        });
    }
};

const sidebar = document.querySelector(".page .sidebar");
const toggleButton = document.querySelector(".page .sidebar .title i");
const logo = document.querySelector(".page .sidebar .title .logo");
const title = document.querySelector(".page .sidebar .title h2");
const spans = document.querySelectorAll(".page .sidebar span");
const tog = document.querySelector(".sidebar .links .num a .tog");

// drobdown list
const drob = document.querySelector(".sidebar .links .num");
const menu = document.querySelector(".sidebar .links .list");

if (drob.classList.contains("action")) {
    menu.style.display = "block";
    tog.classList.replace("fa-angle-right", "fa-angle-down");
}
// minimizing sidebar 
toggleButton.onclick = ()=> {
    toggleButton.classList.toggle("toggle-btn");
    if (toggleButton.classList.contains("toggle-btn")) {
        sidebar.style.width = "50px";
        logo.style.display = "none";
        title.style.display = "none";
        tog.style.display = "none";
        spans.forEach((el) => {
            el.style.display = "none";
        })
    } else {
        sidebar.style.width = "250px";
        logo.style.display = "block";
        title.style.display = "block";
        tog.style.display = "inline";
        spans.forEach((el) => {
          el.style.display = "inline";
        });
    }
}
// show & hide page
// expired medicine
const expired = document.querySelector(".content .show");

expired.onclick = () => {
    hidden.style.display = "block";
}


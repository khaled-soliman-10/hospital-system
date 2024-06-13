const sidebar1 = document.querySelector(".sidebar");
const toggleButton1 = document.querySelector(".sidebar .title i");
const logo1 = document.querySelector(".sidebar .title .logo");
const title1 = document.querySelector(".sidebar .title h2");
const spans1 = document.querySelectorAll(".sidebar span");
const tog1 = document.querySelector(".sidebar .links .num a .tog");

toggleButton1.onclick = ()=> {
    toggleButton1.classList.toggle("toggle-btn");
    if (toggleButton1.classList.contains("toggle-btn")) {
        sidebar1.style.width = "50px";
        logo1.style.display = "none";
        title1.style.display = "none";
        tog1.style.display = "none";
        spans1.forEach((el) => {
            el.style.display = "none";
        })
    } else {
        sidebar1.style.width = "250px";
        logo1.style.display = "block";
        title1.style.display = "block";
        tog1.style.display = "inline";
        spans1.forEach((el) => {
        el.style.display = "inline";
        });
    }
}
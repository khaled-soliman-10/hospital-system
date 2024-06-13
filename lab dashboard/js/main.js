let btnSide = document.querySelector("aside header button");
let Aside = document.querySelector("aside");
let asideLinks = document.querySelectorAll("aside .links .link");
let logo = document.querySelector("aside header .logo");
let headLinks = document.querySelectorAll("aside .links .link h1");
let logout = document.querySelector("aside form button h1");
divLinks = document.querySelector("aside .links")

btnSide.addEventListener("click",()=> {
    if (Aside.classList.contains("width")) {
        Aside.classList.remove("width");
        disable();
    }else {
        Aside.classList.add("width")
        show();
    }
})

function disable() {
    logo.style.display = "none";
    headLinks.forEach(headLink => {
        headLink.style.display = "none";
    })
    logout.style.display = "none";
    asideLinks.forEach(aLink=> {
        aLink.style.cssText = "align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; padding: 5px; font-size: 20px"
    })
    divLinks.style.cssText = "align-items: center; justify-content: center";
}
function show() {
    logo.style.display = "flex";
    headLinks.forEach(headLink => {
        headLink.style.display = "block";
    })
    logout.style.display = "block";
    asideLinks.forEach(aLink=> {
        aLink.style.cssText = ""
    })
    divLinks.style.cssText = "";
}
// ----------------------
let rosheta = document.getElementById("#rosheta");
let okbtn = document.getElementById("#ok");



okbtn.addEventListener("click",()=> {
    okbtn.style.backgroundColor="red"
})
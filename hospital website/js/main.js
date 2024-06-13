let buttonList = document.querySelector("header .mobile .list button");
let buttonMark = document.querySelector("header .mobile .list button i");
let List = document.querySelector("header .mobile .list ul");

buttonList.addEventListener("click",()=>{
    if (!List.classList.contains("width")) {
        List.classList.add("width");
        buttonMark.classList.replace("fa-bars-staggered","fa-xmark");
    }else {
        List.classList.remove("width");
        buttonMark.classList.replace("fa-xmark","fa-bars-staggered");
    }
})

let buttonTop = document.querySelector(".top");

window.onscroll = ()=> {
    if (window.scrollY >= 700) {
        buttonTop.classList.add("display")
    }else {
        buttonTop.classList.remove("display")
    }
}

buttonTop.onclick = () => {
    window.scrollTo({
        top:0,
        behavior:"smooth"
    })
}
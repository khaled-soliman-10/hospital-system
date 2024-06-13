let mode = document.querySelectorAll(".mode");  //name of div which contain icon (sun,moon)
let iMode = document.querySelectorAll(".mode i"); //icon (sun,moon)
let sun = `<i class="fa-solid fa-cloud-sun"></i>`;
let moon = `<i class="fa-solid fa-cloud-moon"></i>`;

let getMode = localStorage.getItem("mode"); //check in the start if website was in dark he countinue with dark and opposite is true

if (getMode == "dark") {
    document.body.classList.add("dark")
    iMode.forEach (i => {
        i.classList.replace("fa-cloud-sun","fa-cloud-moon")
        i.style.transform = "rotate(360deg)"
    })
} 
mode.forEach(mo => {
    mo.onclick = () => {
        document.body.classList.toggle("dark")
        if (document.body.classList.contains("dark")) {
            iMode.forEach (i => {
                i.classList.replace("fa-cloud-sun","fa-cloud-moon")
                i.style.transform = "rotate(360deg)"
            })
            localStorage.setItem("mode","dark");
        
        } else {
            iMode.forEach (i => {
                i.classList.replace("fa-cloud-moon","fa-cloud-sun")
                i.style.transform = "rotate(-360deg)"
            })
            localStorage.setItem("mode","light");
        }
    }
});

if (!document.body.classList.contains("dark")) {
    localStorage.setItem("mode","light");
} else {
    localStorage.setItem("mode","dark");
}
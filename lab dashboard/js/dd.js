let btn = document.querySelector(".content .send-r .report .submits")
let report = document.querySelector(".report-send")
let reportClose = document.querySelector(".report-send form i")

btn.addEventListener("click",()=>{
    report.style.display = "block";
})
reportClose.addEventListener("click",()=>{
    report.style.display = "none"
})
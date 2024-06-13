let x = document.querySelector(".content .add-patient .rosheta-k i")
let Rosheta = document.querySelector(".content .add-patient .rosheta-k")
let openr = document.querySelector("#ok")
let printed = document.querySelector(".printed")

openr.addEventListener("click",()=>{
    Rosheta.style.display = "flex"

})

x.addEventListener("click",()=>{
    Rosheta.style.display = "none"
})

printed.addEventListener("click",()=>{
    alert("تمت الطباعة")
})
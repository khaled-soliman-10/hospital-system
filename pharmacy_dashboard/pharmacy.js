
// show & hide
const hidden = document.querySelector(".show-hide");
const closeBtn = document.querySelector(".show-hide i");

closeBtn.onclick = () => {
  hidden.style.display = "none";
};
// reciet
const buy = document.querySelector(".content .parent .reciet form button");

buy.onclick = () => {
  hidden.style.display = "block";
};

let printbtn = document.querySelector("#print")

printbtn.addEventListener("click",()=>{
  alert("تمت الطباعة")
})
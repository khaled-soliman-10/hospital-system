// .show-hide
const income = document.querySelector(".rlp .first  button");
const closeShowHide = document.querySelector(".main_rpl .show-hide .close");
const showHide = document.querySelector(".main_rpl .show-hide")

income.onclick = () => {
    showHide.style.display = "block";
};
closeShowHide.onclick = () => {
    showHide.style.display = "none";
};
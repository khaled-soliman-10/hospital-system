const patientTable = document.querySelector(".patient_search .patient_table");
const search = document.querySelector(".patient_search form button");

search.onclick = () => {
    patientTable.style.display = "block";
}
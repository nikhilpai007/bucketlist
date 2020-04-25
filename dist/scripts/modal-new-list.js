var modal_new_list = document.getElementById("modal-new-list");
var modalButton_new_list = document.getElementById("modal-button-new-list");
var modalClose_new_list = document.getElementsByClassName("modal-close-new-list")[0];

modalButton_new_list.onclick = function(){
  modal_new_list.style.display = "block";
}

modalClose_new_list.onclick = function(){
  modal_new_list.style.display = "none";
}

window.onclick = function(event){
  if (event.target == modal_new_list){
    modal_new_list.style.display = "none";
  }
}
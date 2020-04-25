var modal_new_list_item = document.getElementById("modal-new-list-item");
var modalButton_new_list_item = document.getElementById("modal-button-new-list-item");
var modalClose_new_list_item = document.getElementsByClassName("modal-close-new-list-item")[0];

modalButton_new_list_item.onclick = function(){
  modal_new_list_item.style.display = "block";
}

modalClose_new_list_item.onclick = function(){
  modal_new_list_item.style.display = "none";
}

window.onclick = function(event){
  if (event.target == modal_new_list_item){
    modal_new_list_item.style.display = "none";
  }
}
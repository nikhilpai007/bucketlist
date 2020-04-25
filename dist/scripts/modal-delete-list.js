var modal_delete_list = document.getElementById("modal-delete-list");
var modalButton_delete_list = document.getElementById("modal-button-delete-list");
var modalClose_delete_list = document.getElementsByClassName("modal-close-delete-list")[0];

modalButton_delete_list.onclick = function(){
  modal_delete_list.style.display = "block";
}

modalClose_delete_list.onclick = function(){
  modal_delete_list.style.display = "none";
}

window.onclick = function(event){
  if (event.target == modal_delete_list){
    modal_delete_list.style.display = "none";
    }
}
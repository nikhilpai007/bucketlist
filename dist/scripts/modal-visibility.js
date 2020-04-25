var modal_visibility = document.getElementById("modal-visibility");
var modalButton_visibility = document.getElementById("modal-button-visibility");
var modalClose_visibility = document.getElementsByClassName("modal-close-visibility")[0];

modalButton_visibility.onclick = function(){
  modal_visibility.style.display = "block";
}

modalClose_visibility.onclick = function(){
  modal_visibility.style.display = "none";
}

window.onclick = function(event){
  if (event.target == modal_visibility){
    modal_visibility.style.display = "none";
  }
}
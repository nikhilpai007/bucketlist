// View instructions buttons
var modal_view_instructions = document.getElementById("modal-view-instructions");
var modalButton_view_instructions = document.getElementById("view-instructions-button");
var modalClose_view_instructions = document.getElementsByClassName("close-view-instructions");

// Attach a click event listener to the button
modalButton_view_instructions.addEventListener('click', function (i) {
    // Display the modal when clicked
    modal_view_instructions.style.display = "block";
}.bind(null, i));

modalClose_view_instructions[0].addEventListener('click', function (i) {
    modal_view_instructions.style.display = "none";
}.bind(null, i)); 
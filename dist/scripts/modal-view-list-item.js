// View List Item buttons
var modal_view_list_item = document.getElementsByClassName("modal-view-list-item");
var modalButton_view_list_item = document.getElementsByClassName("list-item-view-button");
var modalClose_view_list_item = document.getElementsByClassName("close-view-list-item");

// For each view details button
for (var i = 0; i < modalButton_view_list_item.length; i++) {

    // Attach a click event listener to the button
    modalButton_view_list_item[i].addEventListener('click', function (i) {
        // Display the modal when clicked
        modal_view_list_item[i].style.display = "block";
    }.bind(null, i));

    modalClose_view_list_item[i].addEventListener('click', function (i) {
        modal_view_list_item[i].style.display = "none";
    }.bind(null, i));
}

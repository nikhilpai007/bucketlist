function confirmDeleteList(id)
{
    var ok = confirm("Are you sure you would like to delete this list? This action is irreversible!");
    if (ok)
    {
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }

        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                 window.location = "delete-list.php";  // self page
            }
        }

        xmlhttp.open('GET', 'delete-list.php');
        xmlhttp.send();
    }
    }
/**
 * Created by alex on 18.09.17.
 */
$(document).ready(function() {
    var agreeButton = document.getElementById('agreeButton');
    agreeButton.addEventListener("click", socketRun);

});


function socketRun() {
    var data = document.getElementById('hiddenInput').value;
    var http = new XMLHttpRequest();
    http.onprogress = function(e) {
        // alert(e.currentTarget.responseText);
        var re = /\.+/g;
        var str = e.currentTarget.responseText;
        var newstr = str.replace(re, '');
        var div = document.getElementById('log');
        div.innerHTML = newstr;
        // alert(e.currentTarget.responseText);
    };
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText + "\n");
            // var p = document.createElement('p');
            // var text = document.createTextNode(this.responseText);
            // var div = document.getElementById('log');
            // p.appendChild(text);
            // div.appendChild(p);
        }
    };
    http.open('GET', '/ajax/'+data, true);
    http.send();
}
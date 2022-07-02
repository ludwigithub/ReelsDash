$(document).ready(function () {
    var tit = ["Text 1", "Text 2", "Text 3", "Text 4", "Text 5"];

    for (var i in tit) {
        var element = document.createElement("h2");
        element.innerHTML = tit[i];
        $("#tit").append(element);
    }
});

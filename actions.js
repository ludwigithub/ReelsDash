
$(document).ready(function () {
    var tit = ["Text 1", "Text 2", "Text 3", "Text 4", "Text 5"];

    for (var i in tit) {
        var element = document.createElement("th");
        element.innerHTML = tit[i];
        $("#greeting").append(element);
    }
});
window.onload = function(){


let tableHeaders = ["Global Ranking", "Username", "Score", "Time Alive [seconds]", "Accuracy [%]"]

let scoreboardTable = document.createElement('table') // Create the table itself
scoreboardTable.className = 'scoreboardTable'
let scoreboardTableHead = document.createElement('thead') // Creates the table header group element
scoreboardTableHead.className = 'scoreboardTableHead'
let scoreboardTableHeaderRow = document.createElement('tr') // Creates the row that will contain the headers
scoreboardTableHeaderRow.className = 'scoreboardTableHeaderRow'

let scoreHeader = document.createElement('th') // Creates the current header cell during a specific iteration
scoreHeader.innerText = 15
scoreboardTableHeaderRow.append(scoreHeader) // Appends the current header cell to the header row
}

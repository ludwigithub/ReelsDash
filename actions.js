
$(document).ready(function () {
    

var canvas = document.getElementById("Line");
let mountains = [
  {  Line: "Monte Falco", Shift: 1658, "Units Produced": "Parco Foreste Casentinesi", Uptime: 5, "Avg Speed": 5, "Speed(10min)": 5, "Data Integrity": 5, "Order Info": 5, Need: 5 }
];

function generateTableHead(table, data) {
  let thead = table.createTHead();
  let row = thead.insertRow();
  for (let key of data) {
    let th = document.createElement("th");
    let text = document.createTextNode(key);
    th.className = "dbTitle";
    th.appendChild(text);
    row.appendChild(th);
  }
}

function generateTable(table, data) {//accepts the table and all the data declared in mountains
  for (let element of data) { //for all elements in the data,
    let row = table.insertRow(); //insert a row
    for (key in element) { //for all data in the row
      let cell = row.insertCell(); //insert a cell
      switch(element[key]){
        case element["Line"]:
            cell.className = "colName";
            break;
      }
      let text = document.createTextNode(element[key]);
      cell.appendChild(text);
    }
    var line = document.createTextNode( element["Line"]);
    line.id = "The Line";
  }
}

let table = document.querySelector("table");
let data = Object.keys(mountains[0]);
generateTableHead(table, data);
generateTable(table, mountains);

});
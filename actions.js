var lines = new Object();
for(let i = 0; i < 10; i++){
lines[i] = "John" + i;
}

$(document).ready(function () {

let mountains = [
  {  Line: "Monte Falco", Shift: 1658, "Units Produced": "Parco Foreste Casentinesi", Uptime: 1, "Avg Speed": 2, "Speed(10min)": 3, "Data Integrity": 4, "Order Info": 5, Need: 6 },
  {  Line: "Monte Falco1", Shift: 1659, "Units Produced": "Parco Foreste Casentinesi1", Uptime: 1, "Avg Speed": 2, "Speed(10min)": 3, "Data Integrity": 4, "Order Info": 5, Need: 6 }
];
mountains["Line"].push("yes");


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
      switch(key){
        case "Line":
            cell.className = "colName";
            break;
        case "Shift":
            cell.className = "colShift";
            break;
        case "Units Produced":
            cell.className = "colUnits";
            break;
        case "Uptime":
            cell.className = "colUptime";
            break;
        case "Avg Speed":
            cell.className = "colSpd";
            break;
        case "Speed(10min)":
            cell.className = "colName"; 
            break;
        case "Data Integrity":
            cell.className = "colName";
            break;
        case "Order Info":
            cell.className = "colOrder";
            break;
        case "Need":
            cell.className = "colRemaining";
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


$(document).ready(function () {


let inputData = [ { }]; //initialize the data

for(let index = 0; index <= 13; index ++ ){ //dynamically insert the data into the dictionary
  inputData.push({"Line": "Monte Falco" + index, "Shift": 1659 + index, "Units Produced": "Parco Foreste Casentinesi" + index, "Uptime": 1 + index, "Avg Speed": 2+index, "Speed(10min)": 3+index, "Data Integrity": +index, "Order Info": +index, "Need": 6+index})
}

//generates the heads based on the titles
function generateTableHead(table, data) {
  let thead = table.createTHead();
  let row = thead.insertRow();
  for (let key of data) { //for each name in the list of titles
    let th = document.createElement("th"); //create a head element
    let text = document.createTextNode(key); //create a node
    th.className = "dbTitle"; //give it the class name dbTitle
    th.appendChild(text); //append the head
    row.appendChild(th); //append the th
  }
}

function queryData(){
  $.ajax({
    url: 'test.php',
    success: function(data) {
      $('.result').html(data);
    }
  });
}

function generateTable(table, data) {//accepts the table and all the data declared in inputData
  for (let element of data) { //for all elements in the data,
    let row = table.insertRow(); //insert a row
    for (key in element) { //for all data in the row
      let cell = row.insertCell(); //insert a cell
      switch(key){ //adds a classname based on switch line the data is added to
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
    //var line = document.createTextNode( element["Line"]);
    //line.id = "The Line";
  }
}

let table = document.querySelector("table");
let data = Object.keys(inputData[0]);

generateTableHead(table, data);
generateTable(table, inputData);
queryData();

});
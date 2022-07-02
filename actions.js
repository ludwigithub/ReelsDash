$(document).ready (function(){
const box = document.createElement("h2");

// ✅ Works
for(let i= 0; i < 5; i++){
    box.innerHTML = i;
    $("#tit").append(box);
}

}
);

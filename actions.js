window.onload = function(){
const box = document.createElement("p");

// ✅ Works
for(let i= 0; i < 5; i++){
    box.innerHTML = i;
    $("#tit").append(box);
}

}


function showYourRestaurant(){

    var tableString = '<table style="margin-left: 50vw;">',
    body = document.getElementsByTagName('body')[0],
    div = document.createElement('div');
    div.classList.add('tableBackground');
    div.setAttribute("id", "bigTable");

    for (row = 0; row < 10; row += 1) {

        tableString += "<tr >";

        for (col = 0; col < 10; col += 1) {
            
            tableString += "<button class='tableButton' id='"+row+" "+col+"' value='2' onclick='console.log(this.id[0],this.id[2],this.value); changeType(this.id)'>" + "</button>";
        }
        tableString += "<br>";
        tableString += "</tr>";
    }


    tableString += "</table>";
    div.innerHTML = tableString;
    body.appendChild(div);
}

function changeType(coordinates){

    let myDivObjBgColor = getComputedStyle( document.getElementById(coordinates), "").backgroundColor
    console.log(myDivObjBgColor);

        if(myDivObjBgColor == 'rgb(0, 255, 0)'){
            document.getElementById(coordinates).style.background = "rgb(255, 0, 0)";
        }

        else if(myDivObjBgColor == 'rgb(255, 0, 0)')
            document.getElementById(coordinates).style.background = "rgb(104, 104, 104)";
        
            else 
            document.getElementById(coordinates).style.background = "rgb(0, 255, 0)";
        
}

showYourRestaurant();

function showHideYourRestaurant(){
    
}

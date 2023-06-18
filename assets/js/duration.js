let runtime = document.querySelector(".runtime").innerHTML;

let hour = parseInt(runtime / 60);
let minutes = runtime % 60;
let finalRuntime = 0;

if(minutes == 0){
    if(hour > 1){
        finalRuntime = hour + "h00";
    } else{
        finalRuntime = hour + "h00";
    }
    
} else{
    if(minutes < 10){
        minutes = "0" + minutes
    } 
    
    finalRuntime = hour + "h" + minutes;       
}

document.querySelector(".runtime").innerHTML = finalRuntime;




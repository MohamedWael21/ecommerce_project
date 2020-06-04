$(document).ready(function(){

$('#drop').click(function(){ //dropdwon menu
  $('.navbar .dropdwon').toggleClass('active');

});

$('#hum').click(function(){ //humberger button
$('.navbar .right').toggleClass('open');

});



$('.fa-eye').click(function(){  //eye function 
    
  var mode = $(this).prev('input').attr('type');
   
 if( mode == 'password'){
  
  $(this).prev('input').attr('type','text');
   return;
 } 

 $(this).prev('input').attr('type', 'password');


});

$('#catlink').click(function (e) { 
  $(this).addClass('active');
  
});

var searchbar = document.getElementById("searchBar");
if(searchbar){
searchbar.addEventListener("keyup",function(){
 search();
});

searchbar.addEventListener('focusout',function(){
  var result =  document.getElementById("liveresult");
  if(result.textContent == "no result"){
    result.style.display="none";
  }

})
}


// function for search 
function search(){
  
  var searchbar = document.getElementById("searchBar");
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange= function(){
   
    if(xhr.readyState== 4 && xhr.status==200){
        showresult(xhr.response);
      
    }

  };
  
   var hintforsearch = searchbar.value;

  xhr.open("GET","search.php?s="+ hintforsearch,true);
  xhr.send();

}

// function show result for livesearch
function showresult(jsonobject){
  if(jsonobject.length){
   jsonobject = JSON.parse(jsonobject);
   var liveresult = document.getElementById('liveresult');
   liveresult.style.display="block";
   liveresult.innerHTML="";
  for(var ob in jsonobject){
    var div = document.createElement("div");
    div.className="result-text";
    liveresult.appendChild(div);
    var result = document.createElement("a");
    result.href="../pages/item.php?itd="+jsonobject[ob].id+"&itn="+jsonobject[ob].name+'"';
    var text = document.createTextNode(jsonobject[ob].name);
    result.appendChild(text);
    div.appendChild(result);
  }
  
  if(searchbar.value=="" || jsonobject.length==0){

    liveresult.innerHTML="no result";
    
  }

}

}



});




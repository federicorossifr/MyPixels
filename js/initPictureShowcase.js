//Inizializza la griglia di foto visibile nella pagina home
//e nella pagina explore. (condivisa)
function initShowcase(route,location,container,ifempty) {
	get("./php/userRouter.php?route=getSession",function(result) {
    	var dataObj = JSON.parse(result);
	    if(!dataObj.length) {
	    	return;
	    }
	    globals.loggedUser = dataObj.data;
	    loadPictures(container,ifempty);
  	});


  	function loadPictures(feedContainer,ifempty) {
		get("./php/picRouter.php?route="+route,function(result){
			picsLoaded(result,feedContainer,ifempty);
		});
  	}

  	if(document.getElementById("orderSelector"))
	  	document.getElementById("orderSelector").onchange = function(event) {
	  		this.blur();
	  		doSort(event.target.value,document.getElementById("picturesContainer"));
	  	}

  	get("./php/userRouter.php?route=getNotifies",function(result) {
		displayNotifies(result,document.getElementById("notifies"),document.getElementById("notifies-count"),document.getElementById("picturesContainer"));
	});

	makeActiveLink(location);
}
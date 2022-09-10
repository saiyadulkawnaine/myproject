function  createObject() {
	let http =false;
	if (window.XMLHttpRequest){ // if Mozilla, Safari etc
		http  = new XMLHttpRequest();
	}
	else if (window.ActiveXObject){ // if IE
		try {
			http  = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
				http  = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else{
		return false;
	}
	return http;
}
let http=createObject();
module.exports = http;	
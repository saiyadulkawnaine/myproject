export let PageLoader=require('./MsSysPageLoader');
export let qs = require('querystring');
export let uniqueArray = function(arrArg) {
  return arrArg.filter(function(elem, pos,arr) {
    return arr.indexOf(elem) == pos;
  });
};

export function colExp(id,reg){
	var p = $('#'+id).layout('panel',reg);
	if(p.panel('options').collapsed){
		$('#'+id).layout('expand', reg);
	}else{
		$('#'+id).layout('collapse', reg);
	}
}
export function get(formId){
	let formObj = {};
	let inputs = $('#'+formId).serializeArray();
	$.each(inputs, function (i, input) {
		formObj[trim(input.name)] = trim(input.value);
	});
	return formObj;
}

export function set(index,row,data){
	for(var key in data.dropDown){
		//$('#'+row.form_id+' #'+key ).html(data.dropDown[key]);
		setHtml(key,data.dropDown[key]);
	}
	for(var key in data.fromData){
		//$('#'+row.form_id+' #'+key ).val(data[key]);
		$('#'+row.formId+' [name='+key+']').val(data.fromData[key]);
		
	}
}
export function setHtml(elm,dropdown){
	$('#'+elm).html( dropdown.substr(1).slice(0, -1));
}

export function trim( stringToTrim ) {
	return stringToTrim.replace( /^\s+|\s+$/g, "" );
}

export function ltrim( stringToTrim ) {
	return stringToTrim.replace( /^\s+/, "" );
}

export function rtrim( stringToTrim ) {
	return stringToTrim.replace( /\s+$/, "" );
}
export function baseUrl(){
	return url;
}
export function showError(sms,key){
	$.messager.show({
			title:'Error Box!',
			msg:sms,
			showType:'fade',
			timeout:0,
			modal:true,
			width:500,
			height:200,
			style:{
				right:'',
				bottom:'',
			}
		});
		if(key){
			$("#"+key).focus();
		}
		$('.messager-body').css('background-color','red');
		$('.messager-body').css('text-align','center' );
		$('.messager-body').css('line-height','15px' );
		$('.messager-body').css('font-weight','bold');
		$('.messager-body').css('color','white');
}
export function showSuccess(sms){
	$.messager.show({
			title:'Success Box!',
			msg:sms,
			showType:'fade',
			timeout:1000,
			modal:true,
			style:{
				right:'',
				bottom:'',
				timeout:0,
			}
		});
		$('.messager-body').css('background-color','green');
		$('.messager-body').css('text-align','center' );
		$('.messager-body').css('line-height','15px' );
		$('.messager-body').css('font-weight','bold');
		$('.messager-body').css('color','white');
}
export function resetForm(form){
	$('#'+form).find('input:text, input:password,input:hidden,textarea').val('');
	$('#'+form).find('select').val('');
	$('#'+form).find('input:radio, input:checkbox').prop('checked', false);
}

export function moveToRight(e,from,to){
		$('.LeftRight').moveToListAndDelete('#'+from, '#'+to);
		//$(class).moveToListAndDelete(from, to);
        e.preventDefault();
}

export function moveAllToRight(e,from,to){
	$('.LeftRight').moveAllToListAndDelete('#'+from, '#'+to);
	//$(class).moveAllToListAndDelete(from, to);
	e.preventDefault();
}

export function moveToLeft(e,from,to){
	$('.LeftRight').moveToListAndDelete('#'+to, '#'+from);
	//$(class).moveToListAndDelete(to, from);
	e.preventDefault();
}

export function moveAllToLeft(e,from,to){
	$('.LeftRight').moveAllToListAndDelete('#'+to, '#'+from);
	//$(class).moveAllToListAndDelete(to, from);
	e.preventDefault();
}

export function getJson (url,dataaobj){
		return axios.get(this.baseUrl()+'/'+url,{params:dataaobj})
	
	}
export function getHtml (url,dataaobj){
		return axios.get(this.baseUrl()+'/'+url,{params:dataaobj})
	
	}
export function multiply (x,y){
		 let z =x*y;
		 return z
	
	}
	
export function addDays(startDate,numberOfDays)
{
	var returnDate = new Date(
		startDate.getFullYear(),
		startDate.getMonth(),
		startDate.getDate()+numberOfDays,
		startDate.getHours(),
		startDate.getMinutes(),
		startDate.getSeconds()
	);
	var yyyy = returnDate.getFullYear().toString();                                    
	var mm = (returnDate.getMonth()+1).toString();//getMonth() is zero-based         
	var dd  = returnDate.getDate().toString();             
	return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
}
export function subDays(startDate,numberOfDays)
{
	var returnDate = new Date(
		startDate.getFullYear(),
		startDate.getMonth(),
		startDate.getDate()-numberOfDays,
		startDate.getHours(),
		startDate.getMinutes(),
		startDate.getSeconds()
	);
	var yyyy = returnDate.getFullYear().toString();                                    
	var mm = (returnDate.getMonth()+1).toString();//getMonth() is zero-based         
	var dd  = returnDate.getDate().toString();             
	return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
}

export function dateDiffDays(fromDate,toDate)
{
	if (fromDate<toDate) {
		let days   = (toDate - fromDate)/1000/60/60/24;
		return days
	}
}
export function weekno(fromDate)
{
	var date = new Date();
	var onejan=new Date(date.getFullYear(),0,1);
	var now = new Date(
	fromDate.getFullYear(),
	fromDate.getMonth(),
	fromDate.getDate()
	);
	let week = Math.ceil( (((now - onejan) / 86400000) + onejan.getDay() + 1) / 7 );
	return week;
}

export function toExcel(table_id,file_name)
{
	if(table_id=='')
	{
		alert('Please give Table Id');
		return;
	}
	let file='';
	if(file_name){
		file=file_name;
	}else{
		file=table_id;
	}
	$('#'+table_id).datagrid('toExcel',file+'.xls')
}
export	let months = ["Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];



	
	
	
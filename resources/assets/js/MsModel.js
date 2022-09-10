var https = require('./http');
class MsModel {
	constructor() {
		this.http = https;
	}
	
	upload(action,method,data,callback){
		let self = this.http;
		let s=this;
		self.onreadystatechange = function(){
			if(self.readyState == 4){ 		
				var response = self.responseText;
					let d=JSON.parse(response);
					if (typeof d == 'object') {
						if (d.success == true) {
							msApp.showSuccess(d.message)
							callback(d);
						}
						else if (d.success == false) {
							msApp.showError(d.message);
						}else{
							let err=s.message(d);
							msApp.showError(err.message,err.key);
							
						}
					}
			}
		};
		self.open( method,action,true);
		//self.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		self.setRequestHeader("Accept","application/json");
		self.setRequestHeader('x-csrf-token', $('meta[name="csrf-token"]').attr('content'));
		self.send(data);
	}
	
	save(action,method,data,callback){
		let self = this.http;
		let s=this;
		self.onreadystatechange = function(){
			if(self.readyState == 4){ 		
				var response = self.responseText;
					let d=JSON.parse(response);
					if (typeof d == 'object') {
						if (d.success == true) {
							msApp.showSuccess(d.message)
							callback(d);
						}
						else if (d.success == false) {
							msApp.showError(d.message);
						}else{
							let err=s.message(d);
							msApp.showError(err.message,err.key);
							
						}
					}
					 $.unblockUI();
			}
		};
		self.open( method,action,true);
		self.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		self.setRequestHeader("Accept","application/json");
		self.setRequestHeader('x-csrf-token', $('meta[name="csrf-token"]').attr('content'));
		self.send(data);
	}
	
	saves(action,method,data,callback){
		let s=this;
		let model='';
		if(method=='post'){
		 model=axios.post(action, data);
		}
		if(method=='put'){
		 model=axios.put(action, data);
		}
		model.then(function (response) {
			let d=response.data;
			if (d.success == true) {
			msApp.showSuccess(d.message)
			//callback(d);
			}
			if (d.success == false) {
			msApp.showError(d.message)
			//callback(d);
			}
		})
		.catch(function (error) {
			let d=error.response.data;
		    if (d.success == false) {
				msApp.showError(d.message);
			}else{
				let err=s.message(d);
				msApp.showError(err.message,err.key);
			}
		});
		return model;
	}
	get(index,row){
		let data= axios.get(row.route+"/"+row.id+'/edit');
		data.then(function (response) {
			msApp.set(index,row,response.data)
		})
		.catch(function (error) {
			console.log(error);
		});
		return data;
	}
	getHtml(route,param,div){
		let self = this.http;
		self.onreadystatechange = function() {
			if( self.readyState == 4 && self.status == 200 ) {
				let data=self.responseText;
				msApp.setHtml(div,data)
			}
		};
		self.open("POST",route,true);
		self.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		self.setRequestHeader("X-Requested-With","XMLHttpRequest");
		self.setRequestHeader('x-csrf-token', $('meta[name="csrf-token"]').attr('content'));
		self.send(msApp.qs.stringify(param));
	}
	message(d){
		let err=d.errors;
		msgObj = {};
		for(let key in err){
			msgObj['key']=key;
			msgObj['message']=err[key];
			return msgObj;
		}
	}
}
module.exports = MsModel;
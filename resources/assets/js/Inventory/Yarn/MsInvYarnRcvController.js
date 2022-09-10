let MsInvYarnRcvModel = require('./MsInvYarnRcvModel');
require('./../../datagrid-filter.js');
class MsInvYarnRcvController {
	constructor(MsInvYarnRcvModel)
	{
		this.MsInvYarnRcvModel = MsInvYarnRcvModel;
		this.formId='invyarnrcvFrm';
		this.dataTable='#invyarnrcvTbl';
		this.route=msApp.baseUrl()+"/invyarnrcv"
	}

	submit()
	{
		$.blockUI({
			message: '<i class="icon-spinner4 spinner">Saving...</i>',
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 999999,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 9999999,
				backgroundColor: 'transparent'
			}
		});	
		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsInvYarnRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvYarnRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvYarnRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvYarnRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invyarnrcvTbl').datagrid('reload');
		msApp.resetForm('invyarnrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvYarnRcvModel.get(index,row);
		data.then(function(response){
			msApp.resetForm('invyarnrcvitemFrm');
			if(response.data.fromData.receive_basis_id==2 || response.data.fromData.receive_basis_id==3){
				//$('#invyarnrcvitemFrm  [name=composition]').removeAttr("disabled");
				$('#invyarnrcvitemFrm  [name=composition]').attr("disabled","disabled");
				//$('#invyarnrcvitemTbimport').removeAttr("disabled");
				$('#invyarnrcvitemTbimport').attr("onClick","MsInvYarnRcvItem.openyarnWindow()");
				$('#invyarnrcvitemFrm  [name=rate]').removeAttr("disabled");
				$('#invyarnrcvitemFrm  [name=rate]').removeAttr("readonly");
				$('#invyarnrcvitemFrm  [name=currency_code]').val("BDT");
				$('#invyarnrcvitemFrm  [name=exch_rate]').val(1);

			}else{
				$('#invyarnrcvitemFrm  [name=composition]').attr("disabled","disabled");
				$('#invyarnrcvitemTbimport').attr("onClick","MsInvYarnRcvItem.import()");
				$('#invyarnrcvitemFrm  [name=rate]').attr("readonly",'readonly');
				$('#invyarnrcvitemFrm  [name=currency_code]').val("");
				$('#invyarnrcvitemFrm  [name=exch_rate]').val('');
			}
			if(response.data.fromData.receive_against_id==9){
				$('#invyarnrcvitemFrm  [name=color_id]').attr("readonly",'readonly');
			}else{
				$('#invyarnrcvitemFrm  [name=color_id]').removeAttr("readonly");;
			}
		}).catch(function(error){
			console.log(error);
		})
	}

	showGrid()
	{
		let self=this;
		$(this.dataTable).datagrid({
			method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter');
	}

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsRcvYarn.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invyarnrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

	showStorePdf()
	{
		var id= $('#invyarnrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/storereport?id="+id);
	}

}
window.MsInvYarnRcv=new MsInvYarnRcvController(new MsInvYarnRcvModel());
MsInvYarnRcv.showGrid();

$('#invyarnrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_yarn_rcv_id = $('#invyarnrcvFrm [name=inv_yarn_rcv_id]').val();
        var data={};
		data.inv_yarn_rcv_id=inv_yarn_rcv_id;
        if(index==1){
			if(inv_yarn_rcv_id===''){
				$('#invyarnrcvtabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
			$('#invyarnrcvitemFrm  [name=inv_yarn_rcv_id]').val(inv_yarn_rcv_id);

			//MsInvYarnRcv.find(inv_yarn_rcv_id);
			MsInvYarnRcvItem.get(inv_yarn_rcv_id);
        }
    }
});


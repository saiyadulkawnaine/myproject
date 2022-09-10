let MsInvTrimRcvModel = require('./MsInvTrimRcvModel');
require('./../../datagrid-filter.js');
class MsInvTrimRcvController {
	constructor(MsInvTrimRcvModel)
	{
		this.MsInvTrimRcvModel = MsInvTrimRcvModel;
		this.formId='invtrimrcvFrm';
		this.dataTable='#invtrimrcvTbl';
		this.route=msApp.baseUrl()+"/invtrimrcv"
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
			this.MsInvTrimRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsInvTrimRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#invtrimrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsInvTrimRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsInvTrimRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#invtrimrcvTbl').datagrid('reload');
		msApp.resetForm('invtrimrcvFrm');
		$('#invtrimrcvFrm [id="supplier_id"]').combobox('setValue', '');

	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		let data=this.MsInvTrimRcvModel.get(index,row);
		data.then(function(response){
			$('#invtrimrcvFrm [id="supplier_id"]').combobox('setValue', response.data.fromData.supplier_id);
			msApp.resetForm('invtrimrcvitemFrm');
			if(response.data.fromData.receive_against_id==7){
				$('#invtrimrcvitemFrm  [name=rate]').attr("readonly",'readonly');
			}else{
				$('#invtrimrcvitemFrm  [name=rate]').removeAttr("readonly");
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
		return '<a href="javascript:void(0)"  onClick="MsInvTrimRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	showPdf()
	{
		var id= $('#invtrimrcvFrm  [name=id]').val();
		if(id==""){
			alert("Select a MRR");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}

}
window.MsInvTrimRcv=new MsInvTrimRcvController(new MsInvTrimRcvModel());
MsInvTrimRcv.showGrid();

$('#invtrimrcvtabs').tabs({
    onSelect:function(title,index){
        let inv_trim_rcv_id = $('#invtrimrcvFrm [name=inv_trim_rcv_id]').val();
        var data={};
		data.inv_trim_rcv_id=inv_trim_rcv_id;
        if(index==1){
			if(inv_trim_rcv_id===''){
				$('#invyarnrcvtabs').tabs('select',0);
				msApp.showError('Select Yarn Receive Entry First',0);
				return;
		    }
			$('#invtrimrcvitemFrm  [name=inv_trim_rcv_id]').val(inv_trim_rcv_id);
			MsInvTrimRcvItem.get(inv_trim_rcv_id);
        }
    }
});


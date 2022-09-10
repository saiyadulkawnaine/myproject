let MsSoKnitYarnRcvModel = require('./MsSoKnitYarnRcvModel');
require('./../../datagrid-filter.js');
class MsSoKnitYarnRcvController {
	constructor(MsSoKnitYarnRcvModel)
	{
		this.MsSoKnitYarnRcvModel = MsSoKnitYarnRcvModel;
		this.formId='soknityarnrcvFrm';
		this.dataTable='#soknityarnrcvTbl';
		this.route=msApp.baseUrl()+"/soknityarnrcv"
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
			this.MsSoKnitYarnRcvModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoKnitYarnRcvModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#soknityarnrcvFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoKnitYarnRcvModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoKnitYarnRcvModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#soknityarnrcvTbl').datagrid('reload');
		msApp.resetForm('soknityarnrcvFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoKnitYarnRcvModel.get(index,row);
		workReceive.then(function(response){
			//$('#soknityarnrcvFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
		}).catch(function(error){
			console.log(errors)
		});
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

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoKnitYarnRcv.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	

	soWindow(){
		$('#soknityarnrcvsoWindow').window('open');
	}
	soknityarnrcvsoGrid(data){
		let self = this;
		$('#soknityarnrcvsosearchTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#soknityarnrcvFrm [name=so_knit_id]').val(row.id);
				$('#soknityarnrcvFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#soknityarnrcvFrm [name=company_id]').val(row.company_id);
				$('#soknityarnrcvFrm [name=buyer_id]').val(row.buyer_id);
				$('#soknityarnrcvsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#soknityarnrcvsosearchFrm  [name=so_no]').val();
		//let buyer_id=$('#soknityarnrcvFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#soknityarnrcvsosearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoKnitYarnRcv=new MsSoKnitYarnRcvController(new MsSoKnitYarnRcvModel());
MsSoKnitYarnRcv.showGrid();
MsSoKnitYarnRcv.soknityarnrcvsoGrid([]);
 $('#soknityarnrcvtabs').tabs({
	onSelect:function(title,index){
	 let so_knit_yarn_rcv_id = $('#soknityarnrcvFrm  [name=id]').val();
	 var data={};
	 data.so_knit_yarn_rcv_id=so_knit_yarn_rcv_id;
	 if(index==1){
		 if(so_knit_yarn_rcv_id===''){
			 $('#soknityarnrcvtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		 $('#soknityarnrcvitemFrm  [name=so_knit_yarn_rcv_id]').val(so_knit_yarn_rcv_id);
		 //MsSoKnitYarnRcvItem.showGrid([]);
		 MsSoKnitYarnRcvItem.get(so_knit_yarn_rcv_id);
	 }
}
}); 

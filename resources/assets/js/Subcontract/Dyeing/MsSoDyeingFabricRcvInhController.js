let MsSoDyeingFabricRcvInhModel = require('./MsSoDyeingFabricRcvInhModel');
require('./../../datagrid-filter.js');
class MsSoDyeingFabricRcvInhController {
	constructor(MsSoDyeingFabricRcvInhModel)
	{
		this.MsSoDyeingFabricRcvInhModel = MsSoDyeingFabricRcvInhModel;
		this.formId='sodyeingfabricrcvinhFrm';
		this.dataTable='#sodyeingfabricrcvinhTbl';
		this.route=msApp.baseUrl()+"/sodyeingfabricrcvinh"
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
			this.MsSoDyeingFabricRcvInhModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}
		else{
			this.MsSoDyeingFabricRcvInhModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#sodyeingfabricrcvinhFrm [id="buyer_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingFabricRcvInhModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingFabricRcvInhModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#sodyeingfabricrcvinhTbl').datagrid('reload');
		msApp.resetForm('sodyeingfabricrcvinhFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		workReceive = this.MsSoDyeingFabricRcvInhModel.get(index,row);
		workReceive.then(function(response){
			//$('#sodyeingfabricrcvinhFrm [id="buyer_id"]').combobox('setValue', response.data.fromData.buyer_id);
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
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingFabricRcvInh.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	soWindow(){
		$('#sodyeingfabricrcvsoinhWindow').window('open');
	}
	
	sodyeingfabricrcvsoGrid(data){
		let self = this;
		$('#sodyeingfabricrcvsosearchinhTbl').datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row)
			{
				$('#sodyeingfabricrcvinhFrm [name=so_dyeing_id]').val(row.id);
				$('#sodyeingfabricrcvinhFrm [name=sales_order_no]').val(row.sales_order_no);
				$('#sodyeingfabricrcvinhFrm [name=company_id]').val(row.company_id);
				$('#sodyeingfabricrcvinhFrm [name=buyer_id]').val(row.buyer_id);
				$('#sodyeingfabricrcvsoWindow').window('close');
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}
	getsubconorder()
	{
		let so_no=$('#sodyeingfabricrcvsosearchinhFrm  [name=so_no]').val();
		//let buyer_id=$('#sodyeingfabricrcvinhFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getso?so_no="+so_no);
		data.then(function (response) {
			$('#sodyeingfabricrcvsosearchinhTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}
}
window.MsSoDyeingFabricRcvInh=new MsSoDyeingFabricRcvInhController(new MsSoDyeingFabricRcvInhModel());
MsSoDyeingFabricRcvInh.showGrid();
MsSoDyeingFabricRcvInh.sodyeingfabricrcvsoGrid([]);
 $('#sodyeingfabricrcvinhtabs').tabs({
	onSelect:function(title,index){
	 let so_dyeing_fabric_rcv_id = $('#sodyeingfabricrcvinhFrm  [name=id]').val();
	 let so_dyeing_fabric_rcv_item_id = $('#sodyeingfabricrcvinhitemFrm  [name=id]').val();
	 if(index==1){
		 if(so_dyeing_fabric_rcv_id===''){
			 $('#sodyeingfabricrcvinhtabs').tabs('select',0);
			 msApp.showError('Select a Start Up First',0);
			 return;
		  }
		  msApp.resetForm('sodyeingfabricrcvinhitemFrm')
		 $('#sodyeingfabricrcvinhitemFrm  [name=so_dyeing_fabric_rcv_id]').val(so_dyeing_fabric_rcv_id);
		 MsSoDyeingFabricRcvInhItem.get(so_dyeing_fabric_rcv_id);
	 }
	 if(index==2){
		 if(so_dyeing_fabric_rcv_item_id===''){
			 $('#sodyeingfabricrcvinhtabs').tabs('select',1);
			 msApp.showError('Select a Item First',1);
			 return;
		  }
		  //msApp.resetForm('sodyeingfabricrcvinhrolFrm')
		 //$('#sodyeingfabricrcvinhrolFrm  [name=so_dyeing_fabric_rcv_item_id]').val(so_dyeing_fabric_rcv_item_id);
		 MsSoDyeingFabricRcvInhRol.get(so_dyeing_fabric_rcv_item_id);
	 }
}
}); 

let MsPlDyeingItemModel = require('./MsPlDyeingItemModel');
//require('./../../datagrid-filter.js');
class MsPlDyeingItemController {
	constructor(MsPlDyeingItemModel)
	{
		this.MsPlDyeingItemModel = MsPlDyeingItemModel;
		this.formId='pldyeingitemFrm';
		this.dataTable='#pldyeingitemTbl';
		this.route=msApp.baseUrl()+"/pldyeingitem"
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
			this.MsPlDyeingItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlDyeingItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#pldyeingitemFrm [name=pl_dyeing_id]').val($('#pldyeingFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlDyeingItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlDyeingItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsPlDyeingItem.get($('#pldyeingFrm  [name=id]').val())
		//MsPlDyeingItem.resetForm();
		$('#pldyeingitemFrm  [name=id]').val('')
		$('#pldyeingitemFrm  [name=machine_id]').val('')
		$('#pldyeingitemFrm  [name=machine_no]').val('')
		$('#pldyeingitemFrm  [name=machine_gg]').val('')
		$('#pldyeingitemFrm  [name=no_of_feeder]').val('')
		$('#pldyeingitemFrm  [name=capacity]').val('')
		$('#pldyeingitemFrm  [name=qty]').val('')
		$('#pldyeingitemFrm  [name=pl_start_date]').val('')
		$('#pldyeingitemFrm  [name=pl_end_date]').val('')
		$('#pldyeingitemFrm  [name=remarks]').val('')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlDyeingItemModel.get(index,row);
	}

	get(pl_dyeing_id)
	{
		let params={};
		params.pl_dyeing_id=pl_dyeing_id;
		
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#pldyeingitemTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){

		let self=this;
		$(this.dataTable).datagrid({
			//method:'get',
			border:false,
			singleSelect:true,
			fit:true,
			fitColumns:true,
			//url:this.route,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlDyeingItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	pldyeingitemWindowOpen(){
		$('#pldyeingitemsearchTbl').datagrid('loadData',[]);
		$('#pldyeingitemWindow').window('open');
	}

	showpldyeingitemGrid(data){
		let self = this;
		$('#pldyeingitemsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				onClickRow: function(index,row){
					$('#pldyeingitemFrm [name=so_dyeing_ref_id]').val(row.id);
					$('#pldyeingitemFrm [name=fabrication]').val(row.fabrication);
					$('#pldyeingitemFrm [name=dia]').val(row.dia);
					$('#pldyeingitemFrm [name=fabric_shape_id]').val(row.fabric_shape_id);
					$('#pldyeingitemFrm [name=measurment]').val(row.measurment);
					$('#pldyeingitemFrm [name=gsm_weight]').val(row.gsm_weight);
					$('#pldyeingitemFrm [name=dyeing_sales_order]').val(row.dyeing_sales_order);
					$('#pldyeingitemFrm [name=fabric_color]').val(row.fabric_color);
					$('#pldyeingitemWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	} 
	searchItem()
	{
		let params={};
		params.sale_oreder_no=$('#pldyeingitemsearchFrm  [name=sale_oreder_no]').val();
		params.buyer_id=$('#pldyeingitemsearchFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getitem",{params});
		data.then(function (response) {
			$('#pldyeingitemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	

	formatPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlDyeingItem.pdf(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
	}

	pdf(event,id)
	 {
		if(id==""){
			alert("Select a Plan");
			return;
		}
		window.open(this.route+"/report?id="+id);
	}
	setPlEndDate()
	{
        var capacity=$('#capacity').val();
        var qty=$('#qty').val();
        var days=Math.ceil((qty*1)/(capacity*1))-1;
		
		let pl_start_date=new Date($('#pl_start_date').val());
		let pl_end_date= msApp.addDays(pl_start_date,days);
		if(pl_end_date){
			$('#pl_end_date').val(pl_end_date);
		}
		else{
			$('#pl_end_date').val('');
		}
	}
}
window.MsPlDyeingItem=new MsPlDyeingItemController(new MsPlDyeingItemModel());
MsPlDyeingItem.showGrid([]);
MsPlDyeingItem.showpldyeingitemGrid([]);
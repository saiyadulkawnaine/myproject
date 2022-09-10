let MsPlKnitItemModel = require('./MsPlKnitModel');
//require('./../../datagrid-filter.js');
class MsPlKnitItemController {
	constructor(MsPlKnitItemModel)
	{
		this.MsPlKnitItemModel = MsPlKnitItemModel;
		this.formId='plknititemFrm';
		this.dataTable='#plknititemTbl';
		this.route=msApp.baseUrl()+"/plknititem"
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
			this.MsPlKnitItemModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsPlKnitItemModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
		$('#plknititemFrm [name=pl_knit_id]').val($('#plknitFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsPlKnitItemModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsPlKnitItemModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		MsPlKnitItem.get($('#plknitFrm  [name=id]').val())
		//MsPlKnitItem.resetForm();
		$('#plknititemFrm  [name=id]').val('')
		$('#plknititemFrm  [name=machine_id]').val('')
		$('#plknititemFrm  [name=machine_no]').val('')
		$('#plknititemFrm  [name=machine_gg]').val('')
		$('#plknititemFrm  [name=no_of_feeder]').val('')
		$('#plknititemFrm  [name=no_of_needle]').val('')
		$('#plknititemFrm  [name=rpm]').val('')
		$('#plknititemFrm  [name=hour]').val('')
		$('#plknititemFrm  [name=count]').val('')
		$('#plknititemFrm  [name=expected_effi_per]').val('')
		$('#plknititemFrm  [name=capacity]').val('')
		$('#plknititemFrm  [name=qty]').val('')
		$('#plknititemFrm  [name=pl_start_date]').val('')
		$('#plknititemFrm  [name=pl_end_date]').val('')
		$('#plknititemFrm  [name=remarks]').val('')
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsPlKnitItemModel.get(index,row);
	}

	get(pl_knit_id)
	{
		let params={};
		params.pl_knit_id=pl_knit_id;
		
		let data= axios.get(this.route,{params});
		data.then(function (response) {
			$('#plknititemTbl').datagrid('loadData', response.data);
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
		return '<a href="javascript:void(0)"  onClick="MsPlKnitItem.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}

	plknititemWindowOpen(){
		$('#plknititemsearchTbl').datagrid('loadData',[]);
		$('#plknititemWindow').window('open');
	}

	showplknititemGrid(data){
		let self = this;
		$('#plknititemsearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				onClickRow: function(index,row){
					$('#plknititemFrm [name=so_knit_ref_id]').val(row.id);
					$('#plknititemFrm [name=fabrication]').val(row.fabrication);
					$('#plknititemFrm [name=dia]').val(row.dia);
					$('#plknititemFrm [name=fabric_shape_id]').val(row.fabric_shape_id);
					$('#plknititemFrm [name=measurment]').val(row.measurment);
					$('#plknititemFrm [name=gsm_weight]').val(row.gsm_weight);
					$('#plknititemFrm [name=knitting_sales_order]').val(row.knitting_sales_order);
					$('#plknititemFrm [name=fabric_color]').val(row.fabric_color);
					$('#plknititemWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	} 
	searchItem()
	{
		let params={};
		params.sale_oreder_no=$('#plknititemsearchFrm  [name=sale_oreder_no]').val();
		params.buyer_id=$('#plknititemsearchFrm  [name=buyer_id]').val();
		let data= axios.get(this.route+"/getitem",{params});
		data.then(function (response) {
			$('#plknititemsearchTbl').datagrid('loadData',response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	plknitmachineWindowOpen(){
		MsPlKnitItem.showplknitmachineGrid([]);
		$('#plknitmachineWindow').window('open');
	}
	showplknitmachineGrid(data){
		let self = this;
		$('#plknitmachinesearchTbl').datagrid({
				border:false,
				singleSelect:true,
				fit:true,
				rownumbers:true,
				onClickRow: function(index,row){
					$('#plknititemFrm [name=machine_id]').val(row.id);
					$('#plknititemFrm [name=machine_no]').val(row.custom_no);
					
					$('#plknititemFrm [name=machine_gg]').val(row.gauge);
					$('#plknititemFrm [name=capacity]').val(row.prod_capacity);
					$('#plknititemFrm [name=no_of_feeder]').val(row.no_of_feeder);
					$('#plknitmachineWindow').window('close');
				}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	searchMachine()
	{
		let params={};
		params.dia_width=$('#plknititemsearchFrm  [name=dia_width]').val();
		params.no_of_feeder=$('#plknititemsearchFrm  [name=no_of_feeder]').val();
		
		let data= axios.get(this.route+"/getmachine",{params});
		data.then(function (response) {
			$('#plknitmachinesearchTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	formatPdf(value,row){
		return '<a href="javascript:void(0)"  onClick="MsPlKnitItem.pdf(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>PDF</span></a>';
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

	calculateTgtPerDay(){
		let self=this;
		let stitch_length;
		let no_of_feeder;
		let rpm;
		let no_of_needle;
		let hour;
		let expected_effi_per;
		let count;
		let capacity;

		stitch_length = ($('#plknititemFrm [name=stitch_length]').val())*1;
		no_of_feeder = ($('#plknititemFrm [name=no_of_feeder]').val())*1;
		rpm = ($('#plknititemFrm [name=rpm]').val())*1;
		no_of_needle = ($('#plknititemFrm [name=no_of_needle]').val())*1;
		hour = ($('#plknititemFrm [name=hour]').val())*1;
		expected_effi_per = ($('#plknititemFrm [name=expected_effi_per]').val())/100;
		count = ($('#plknititemFrm [name=count]').val())*1;

		capacity=(no_of_needle*no_of_feeder*stitch_length*rpm*hour*60*expected_effi_per)/(10*2.2046*36*840*2.54*count);
		
		$('#plknititemFrm [name=capacity]').val(capacity);
	}
}
window.MsPlKnitItem=new MsPlKnitItemController(new MsPlKnitItemModel());
MsPlKnitItem.showGrid([]);
MsPlKnitItem.showplknititemGrid([]);
let MsSoDyeingMktCostQpriceModel = require('./MsSoDyeingMktCostQpriceModel');

class MsSoDyeingMktCostQpriceController {
	constructor(MsSoDyeingMktCostQpriceModel)
	{
		this.MsSoDyeingMktCostQpriceModel = MsSoDyeingMktCostQpriceModel;
		this.formId='sodyeingmktcostqpriceFrm';
		this.dataTable='#sodyeingmktcostqpriceTbl';
		this.route=msApp.baseUrl()+"/sodyeingmktcostqprice"
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
			this.MsSoDyeingMktCostQpriceModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsSoDyeingMktCostQpriceModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	

	resetForm ()
	{
		msApp.resetForm(this.formId);
        $('#sodyeingmktcostqpricedtlcosi').html('');
		$('#sodyeingmktcostqpriceFrm [name=so_dyeing_mkt_cost_id]').val($('#sodyeingmktcostFrm [name=id]').val());
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsSoDyeingMktCostQpriceModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsSoDyeingMktCostQpriceModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		
		msApp.resetForm('sodyeingmktcostqpriceFrm');
        MsSoDyeingMktCostQpricedtl.resetForm();
		$('#sodyeingmktcostqpriceFrm [name=so_dyeing_mkt_cost_id]').val($('#sodyeingmktcostFrm [name=id]').val());
		MsSoDyeingMktCostQprice.get($('#sodyeingmktcostFrm [name=id]').val());
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsSoDyeingMktCostQpriceModel.get(index,row);
	}

	get(so_dyeing_mkt_cost_id)
	{
		let data= axios.get(this.route+"?so_dyeing_mkt_cost_id="+so_dyeing_mkt_cost_id);
		data.then(function (response) {
			$('#sodyeingmktcostqpriceTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});

	}

	showGrid(data){
		let self=this;
		$(this.dataTable).datagrid({
			border:false,
			singleSelect:true,
			fit:true,
			onClickRow: function(index,row){
				self.edit(index,row);
			}
		}).datagrid('enableFilter').datagrid('loadData',data);
	}

	formatDetail(value,row){
		return '<a href="javascript:void(0)"  onClick="MsSoDyeingMktCostQprice.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
    }

	pdf(){
		var id= $('#sodyeingmktcostqpriceFrm  [name=id]').val();
		if(id==""){
			alert("Select a Submission Reference First");
			return;
		}
		window.open(this.route+"/pdf?id="+id);	
	}


}
window.MsSoDyeingMktCostQprice=new MsSoDyeingMktCostQpriceController(new MsSoDyeingMktCostQpriceModel());
MsSoDyeingMktCostQprice.showGrid([]);
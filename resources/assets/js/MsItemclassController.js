//require('./jquery.easyui.min.js');
let MsItemclassModel = require('./MsItemclassModel');
require('./datagrid-filter.js');

class MsItemclassController {
	constructor(MsItemclassModel)
	{
		this.MsItemclassModel = MsItemclassModel;
		this.formId='itemclassFrm';
		this.dataTable='#itemclassTbl';
		this.route=msApp.baseUrl()+"/itemclass"
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

		// let profitcenterId= new Array();
		// $('#profitcencerBox2 option').map(function(i, el) {
		// 	profitcenterId.push($(el).val());
		// });
		// $('#profitcenter_id').val( profitcenterId.join());

		let formObj=msApp.get(this.formId);
		if(formObj.id){
			this.MsItemclassModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		}else{
			this.MsItemclassModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		}
	}
	resetForm ()
	{
		msApp.resetForm(this.formId);
        $('#itemclassFrm [id="costing_uom_id"]').combobox('setValue', '');
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsItemclassModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsItemclassModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#itemclassTbl').datagrid('reload');
		//$('#ItemaccountFrm  [name=id]').val(d.id);
		msApp.resetForm('itemclassFrm');
        //$('#itemclassFrm [id="costing_uom_id"]').combobox('setValue', '');
	}

	edit(index,row)
	{
        let self = this;
		row.route=this.route;
		row.formId=this.formId;
        let itemc=this.MsItemclassModel.get(index,row);	
		itemc.then(function (response) {	
			let Presponse = response;
            self.getUom(response.data.fromData.uomclass_id)
            .then(function(){
               msApp.set(index,row,Presponse.data) 
            })
		})
		.catch(function (error) {
			console.log(error);
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

	formatDetail(value,row)
	{
		return '<a href="javascript:void(0)"  onClick="MsItemclass.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
    getUom(uomclass_id){
        let data = {};
        data.uomclass_id=uomclass_id;
        let uom = msApp.getJson('/itemclass/getUomCodes',data)
        .then(function(response){
            $('select[name="costing_uom_id"]').empty();
            $('select[name="costing_uom_id"]').append('<option value="">-Select-</option>');
            $.each(response.data,function(key,value){
                $('select[name="costing_uom_id"]').append('<option value="'+ value.id +'">'+ value.code +'</option>');
            });
        }).catch(function(error){
            console.log(error);
        });
        return uom;
    }
}
window.MsItemclass=new MsItemclassController(new MsItemclassModel());
MsItemclass.showGrid();
$('#utilitemclasstabs').tabs({
	onSelect:function(title,index){
	   let itemclass_id = $('#itemclassFrm  [name=id]').val();

		var data={};
		data.itemclass_id=itemclass_id;

		if(index==1){
			if(itemclass_id===''){
				$('#utilitemclasstabs').tabs('select',0);
				msApp.showError('Select An Item class First',0);
				return;
			}
			$('#itemclassprofitcenterFrm  [name=itemclass_id]').val(itemclass_id)
			MsItemclassProfitcenter.create()
		}
	}
});
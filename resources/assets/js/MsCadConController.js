//require('./jquery.easyui.min.js');
require('./datagrid-filter.js');
let MsCadConModel = require('./MsCadConModel');
class MsCadConController {
	constructor(MsCadConModel)
	{
		this.MsCadConModel = MsCadConModel;
		this.formId='cadconFrm';
		this.dataTable='#cadconTbl';
		this.route=msApp.baseUrl()+"/cadcon"
	}

	submit()
	{
		//$('#dg').datagrid('acceptChanges')
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
		/*let formObj={};
		let i=1;
		$.each($('#dg').datagrid('getRows'), function (idx, val) {
			  formObj.id=val.id
				formObj['cad_id['+i+']']=val.cad_id
				formObj['style_fabrication_id['+i+']']=val.style_fabrication_id
				formObj['style_size_id['+i+']']=val.style_size_id
				formObj['cons['+i+']']=val.qty

			i++;
		});*/
		this.MsCadConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);

		///if(formObj.id){
		//	this.MsCadConModel.save(this.route+"/"+formObj.id,'PUT',msApp.qs.stringify(formObj),this.response);
		//}else{
		//	this.MsCadConModel.save(this.route,'POST',msApp.qs.stringify(formObj),this.response);
		//}
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	remove()
	{
		let formObj=msApp.get(this.formId);
		this.MsCadConModel.save(this.route+"/"+formObj.id,'DELETE',null,this.response);
	}

	delete(event,id)
	{
		event.stopPropagation()
		this.MsCadConModel.save(this.route+"/"+id,'DELETE',null,this.response);
	}

	response(d)
	{
		$('#cadconTbl').datagrid('reload');
		//$('#cadconFrm  [name=id]').val(d.id);
		//msApp.resetForm('cadconFrm');
	}

	edit(index,row)
	{
		row.route=this.route;
		row.formId=this.formId;
		this.MsCadConModel.get(index,row);
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
		return '<a href="javascript:void(0)"  onClick="MsCadCon.delete(event,'+row.id+')"><span class="btn btn-danger btn-xs"><i class="fa fa-search"></i>Delete</span></a>';
	}
	copy(iteration,count)
	{
		/*if($('#cadconFrm  #is_copy').is(":checked")){
			let cons=$('#cadconFrm  [name="cons['+iteration+']"]').val();
			let style_fabrication_id=$('#cadconFrm  [name="style_fabrication_id['+iteration+']"]').val();
			let style_color_id=$('#cadconFrm  [name="style_color_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_fabrication_id===$('#cadconFrm  [name="style_fabrication_id['+i+']"]').val() && style_color_id===$('#cadconFrm  [name="style_color_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="cons['+i+']"]').val(cons);
				}
			}
	    }*/
	    var is_copy_cons = $("input[name='is_copy_cons']:checked").val();

	    if(is_copy_cons==1)
		{
			let cons=$('#cadconFrm  [name="cons['+iteration+']"]').val();
			let style_fabrication_id=$('#cadconFrm  [name="style_fabrication_id['+iteration+']"]').val();
			let style_color_id=$('#cadconFrm  [name="style_color_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_fabrication_id===$('#cadconFrm  [name="style_fabrication_id['+i+']"]').val() && style_color_id===$('#cadconFrm  [name="style_color_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="cons['+i+']"]').val(cons);
				}
			}
		}

		if(is_copy_cons==2)
		{
			let cons=$('#cadconFrm  [name="cons['+iteration+']"]').val();
			//let style_fabrication_id=$('#cadconFrm  [name="style_fabrication_id['+iteration+']"]').val();
			let style_color_id=$('#cadconFrm  [name="style_color_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_color_id===$('#cadconFrm  [name="style_color_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="cons['+i+']"]').val(cons);
				}
			}
		}

		if(is_copy_cons==3)
		{
			let cons=$('#cadconFrm  [name="cons['+iteration+']"]').val();
			//let style_fabrication_id=$('#cadconFrm  [name="style_fabrication_id['+iteration+']"]').val();
			let gmtspart_id=$('#cadconFrm  [name="gmtspart_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(gmtspart_id===$('#cadconFrm  [name="gmtspart_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="cons['+i+']"]').val(cons);
				}
			}
		}

	}

	copyDia(iteration,count)
	{
		var is_copy_dia = $("input[name='is_copy_dia']:checked").val();
		if(is_copy_dia==1)
		{
            let dia=$('#cadconFrm  [name="dia['+iteration+']"]').val();
            let style_color_id=$('#cadconFrm  [name="style_color_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_color_id===$('#cadconFrm  [name="style_color_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="dia['+i+']"]').val(dia);
				}
			}
		}
		if(is_copy_dia==2)
		{
            let dia=$('#cadconFrm  [name="dia['+iteration+']"]').val();
            let style_size_id=$('#cadconFrm  [name="style_size_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_size_id===$('#cadconFrm  [name="style_size_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="dia['+i+']"]').val(dia);
				}
			}
		}
		if(is_copy_dia==3)
		{
            let dia=$('#cadconFrm  [name="dia['+iteration+']"]').val();
            let gmtspart_id=$('#cadconFrm  [name="gmtspart_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(gmtspart_id===$('#cadconFrm  [name="gmtspart_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="dia['+i+']"]').val(dia);
				}
			}
		}
		/*if($('#cadconFrm  #is_copy').is(":checked")){
			let dia=$('#cadconFrm  [name="dia['+iteration+']"]').val();
			let style_fabrication_id=$('#cadconFrm  [name="style_fabrication_id['+iteration+']"]').val();
			let style_color_id=$('#cadconFrm  [name="style_color_id['+iteration+']"]').val();
			for(var i=iteration;i<=count;i++)
			{
				if(style_fabrication_id===$('#cadconFrm  [name="style_fabrication_id['+i+']"]').val() && style_color_id===$('#cadconFrm  [name="style_color_id['+i+']"]').val())
				{
					$('#cadconFrm  [name="cons['+i+']"]').val(cons);
				}
			}
	    }*/
	}
}
window.MsCadCon=new MsCadConController(new MsCadConModel());
MsCadCon.showGrid();

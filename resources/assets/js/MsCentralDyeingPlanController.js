let MsCentralDyeingPlanModel = require('./MsCentralDyeingPlanModel');
require('./datagrid-filter.js');

class MsCentralDyeingPlanController {
	constructor(MsCentralDyeingPlanModel)
	{
		this.MsCentralDyeingPlanModel = MsCentralDyeingPlanModel;
		this.formId='centraldyeingplanFrm';
		this.dataTable='#centraldyeingplanTbl';
		this.route=msApp.baseUrl()+"/centraldyeingplan"
	}
	getParams(){
		let params={};
		params.company_id = $('#centraldyeingplanFrm  [name=company_id]').val();
		params.buyer_id = $('#centraldyeingplanFrm  [name=buyer_id]').val();
		params.date_from = $('#centraldyeingplanFrm  [name=date_from]').val();
		params.date_to = $('#centraldyeingplanFrm  [name=date_to]').val();
		return params;
	}
	
	get()
	{
		let params=this.getParams();
		if( params.date_from==''){
			alert('Please Select a date range ');
			return;
		}

		if(params.date_to==''){
			alert('Please Select a date range');
			return;
		}

		let from=new Date(params.date_from);
		let to=new Date(params.date_to);

		var fromDate = new Date(
		from.getFullYear(),
		from.getMonth(),
		from.getDate(),
		from.getHours(),
		from.getMinutes(),
		from.getSeconds()
		);
		var fromyyyy = fromDate.getFullYear().toString();                                    
		var frommm = (fromDate.getMonth()+1).toString();//getMonth() is zero-based

		var toDate = new Date(
		to.getFullYear(),
		to.getMonth(),
		to.getDate(),
		to.getHours(),
		to.getMinutes(),
		to.getSeconds()
		);
		var toyyyy = toDate.getFullYear().toString();                                    
		var tomm = (toDate.getMonth()+1).toString();//getMonth() is zero-based

		if(fromyyyy != toyyyy){
			alert('Cross Year not allowed ');
			return;
		}

		if(frommm != tomm){
			alert('Cross month not allowed ');
			return;
		}

		//let comp=$( "#myselect option:selected" ).text();
		let comp=$('#centraldyeingplanFrm  [name=company_id] option:selected').text()

		let formatted_month =msApp.months[toDate.getMonth()] + "-" + toDate.getFullYear();
		var title='Ship Date Wise Central Dyeing Plan : &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'+comp+'  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Production Month : '+formatted_month;
		var p = $('#centraldyeingplanlayout').layout('panel', 'center').panel('setTitle', title);
		

		let d= axios.get(this.route+'/getdata',{params})
		.then(function (response) {
			$('#centraldyeingplanTbl').datagrid('loadData', response.data);
		})
		.catch(function (error) {
			console.log(error);
		});
	}

	showGrid(data)
	{
		var dg = $(this.dataTable);
		dg.datagrid({
			border:false,
			singleSelect:true,
			showFooter:true,
			fit:true,
			rownumbers:true,
			emptyMsg:'No Record Found',
			onLoadSuccess: function(data){
				var qty=0;
				for(var i=0; i<data.rows.length; i++){
					qty+=data.rows[i]['qty'].replace(/,/g,'')*1;
				}
				$(this).datagrid('reloadFooter', [
				{
				 	qty: qty.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
				}
				]);
				
			}
		});
		dg.datagrid('enableFilter').datagrid('loadData', data);
	}

	resetForm ()
	{
		msApp.resetForm(this.formId);
	}

	imageWindow(flie_src)
	{
		var output = document.getElementById('centraldyeingplanImageWindowoutput');
		var fp=msApp.baseUrl()+"/images/"+flie_src;
		output.src =  fp;
		$('#centraldyeingplanImageWindow').window('open');
	}
	
	formatimage(value,row)
	{
		return '<img href="javascript:void(0)" width="15"  height="15" src="'+msApp.baseUrl()+'/images/'+row.flie_src+'" onClick="MsCentralDyeingPlan.imageWindow('+'\''+row.flie_src+'\''+')"/>';
	}

	
}
window.MsCentralDyeingPlan=new MsCentralDyeingPlanController(new MsCentralDyeingPlanModel());
MsCentralDyeingPlan.showGrid([]);

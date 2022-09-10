<table border="1" class="table_form">
<thead>
    <tr align="center">
    <td width="200px">GMT Item</td>
    <td width="150px">GMT Part</td>
    <td width="250px">Fabric Description</td>
    <td width="80px">Fabric Nature</td>
    <td width="80px">Fabric Looks</td>
    <td width="80px">Fabric Shape</td>
    <td width="80px">Material Source</td>
    <td width="60px">UOM</td>
    <td width="70px">GSM/Weight</td>
    <td width="70px">Cons</td>
    <td width="70px">Rate</td>
    <td width="80px">amount</td>
    <td width="80px">Yarns</td>
    </tr>
    </thead>
    <tbody>
     <?php 
	 $i=1;
	 $tot=0;
	 ?>
        @foreach($fabrics as $row=>$value)
        
        <?php
            if ($value['req_cons']){
            $cons=$value['req_cons'];
			}
            else{
            $cons='Add';
			}
			
			?>
           
            <tr>
                    <td width="200px" align="center">
                    {{ $value['style_gmt'] }}
                    <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}"  value="{{ $value['id'] }}"/>
                    
                    <input type="hidden" name="style_fabrication_id[{{ $i }}]" id="style_fabrication_id{{ $i }}" value="{{ $value['style_fabrication_id'] }}"/>
                    </td>
                    <td width="150px" align="center">{{ $value['gmtspart'] }}</td>
                    <td width="250px" align="center">{{ $value['fabric_description'] }}</td>
                    <td width="80px" align="center">{{ $value['fabricnature'] }}</td>
                    <td width="80px" align="center">{{ $value['fabriclooks'] }}</td>
                    <td width="80px" align="center">{{ $value['fabricshape'] }}</td>
                    <td width="80px" align="center">{{ $value['materialsourcing'] }}</td>
                    <td width="80px" align="center">{{ $value['uom_name'] }}</td>
                    <td width="70px" align="center"><input type="text" name="gsm_weight[{{ $i }}]" id="gsm_weight{{ $i }}" class="number integer" value="{{ $value['gsm_weight'] }}"/></td>
                    <td width="70px" align="right"><a href="javascript:void(0)" onclick="MsMktCostFabric.openConsWindow({{ $value['id'] }})">{{   $cons }}</a></td>
                    <td width="70px" align="right">{{ $value['rate'] }}</td>
                    <td width="80px" align="right">{{ $value['amount'] }}</td>
                    <td width="80px" align="center"><a href="javascript:void(0)" onclick="MsMktCostFabric.openYarnWindow({{ $value['id'] }})">Add</a></td>
            </tr>
			<?php 
			 $tot+= $value['amount'];
            $i++;
            ?>
        @endforeach
    </tbody>
    <tfoot>
    <td width="200px"></td>
    <td width="150px"></td>
    <td width="250px"></td>
    <td width="80px"></td>
    <td width="80px"></td>
    <td width="80px"></td>
    <td width="80px"></td>
    <td width="60px"></td>
    <td width="70px"></td>
    <td width="70px"></td>
    <td width="70px">Total</td>
    <td width="80px" align="right">{{ $tot}}</td>
    <td width="80px"></td>
    </tfoot>
</table>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
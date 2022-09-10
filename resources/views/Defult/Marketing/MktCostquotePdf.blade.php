<p>Dear  {{ $mktcost['buyer_agent'] }},</p>
<p>Referring to your inquiry as mentioned below matrix, we have prepared our best offer price. Please look into the price and consider very softly and also feel free to ask about any observation. </p>
<p> We always appreciate your cooperation for selecting us as your premium supplier of {{ $mktcost['buyer'] }} orders.</p>
</p>
<br/>

 <?php 
    $i=1;
	$rows= count($mktcost['fabrics']);
    ?>
<table cellspacing="0" cellpadding="2" border="1">
<thead>
    <tr align="center">
        <td width="20px">#</td>
        <td width="60px">Style Ref</td>
        <td width="120px">Item</td>
        <td width="50px">Price</td>
        <td width="100px">GMT Part</td>
        
        <td width="50px">Fab Looks</td>
        <td width="186px">Fabrication</td>
        <td width="50px">Gsm</td>
    </tr>
    </thead>
    <tbody>
     
   
    @foreach($mktcost['fabrics'] as $row=>$value)
   
    <tr>
    @if($i==1)
        <td width="20px" rowspan="{{ $rows }}">{{ $i }}</td>
        <td width="60px" rowspan="{{ $rows }}">{{ $mktcost['style'] }}</td>
        <td width="120px" rowspan="{{ $rows }}">{{ $mktcost['stylegmt'] }}</td>
        <td width="50px" rowspan="{{ $rows }}" align="right" >{{ number_format($mktcost['QuotedPrice']->quote_price,4) }}</td>
        @endif
        <td width="100px">{{ $value['gmtspart'] }}</td>
        <td width="50px">{{ $value['fabriclooks'] }}</td>
        <td width="186px">{{ $value['fabric_description'] }}</td>
        <td width="50px">{{ $value['gsm_weight'] }}</td>
    </tr>
    <?php 
    $i++;
    ?>
    @endforeach
    </tbody>
</table>
<p></p>
<p>Have a nice day.</p>
<br/>
<p>Best Regards,</p>
<br/>
<br/>

Lithe Group.






<br/>


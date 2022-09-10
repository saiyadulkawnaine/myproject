
<table border="1" style="border-style:dotted">
    <tr align="center" style="font-weight: bold;">
        <td width="70px" >SL</td>
        <td width="70px" >Benef. Comp.</td>
        <td width="70px" >Prod. Comp</td>
        <td width="70px">Dealing Mrchant</td>
        <td width="70px">Buyer</td>
        <td width="70px">Style Ref</td>
        <td width="70px">Order No</td>
        <td width="70px">Ship Date</td>
        <td width="70px">Image</td>
        <td width="70px">Order Qty</td>
        <td width="70px">Rate</td>
        <td width="70px">Selling Value</td>
        <td width="70px">Particulars</td>
        <td width="70px">Cutting - Pcs</td>
        <td width="70px">Screen Printing - Pcs</td>
        <td width="70px">Embroidery - Pcs</td>
        <td width="70px">Sewing - Pcs</td>
        <td width="70px">Finishing - Pcs</td>
        <td width="70px">Ex-Factory - Pcs</td>
        <td width="70px">No of Carton</td>
        <td width="70px">Sewing FOB Value</td>
        <td width="70px">Fin. FOB Value</td>
        <td width="70px">Ex-Factory FOB Value</td>
        <td width="70px">CM Mnfg</td>
        <td width="70px">CM Mkt</td>
    </tr>
    <?php
    $i=1;
    ?>
    @foreach($data as $row)
    <tr align="right">
        <td width="70px" align="center" rowspan="5" >{{$i}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->company_id}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->pcompany}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->dl_marchent}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->buyer_code}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->style_ref}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->sale_order_no}}</td>
        <td width="70px" align="center" rowspan="5">{{$row->ship_date}}</td>
        <td width="70px" align="center" rowspan="5"><img src="<?php echo url('/')."/images/".$row->flie_src?>" width="25" height="25" onClick="MsProdGmtCapacityAchievement.imageWindow('{{$row->flie_src}}')"/></td>
        <td width="70px" rowspan="5">{{number_format($row->order_qty,0)}}</td>
        <td width="70px" rowspan="5">{{number_format($row->order_rate,2)}}</td>
        <td width="70px" rowspan="5">{{number_format($row->order_amount,2)}}</td>
        <td width="70px" align="center">Required</td>
        <td width="70px">{{number_format($row->order_plan_cut_qty,0)}}</td>
        <td width="70px">{{number_format($row->screenprinttgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->embrotgt_qty,0)}}</td>
        <td width="70px">{{number_format($row->order_qty,0)}}</td>
        <td width="70px">{{number_format($row->order_qty,0)}}</td>
        <td width="70px">{{number_format($row->order_qty,0)}}</td>
        <td width="70px">{{number_format($row->required_carton,0)}}</td>
        <td width="70px">{{number_format($row->order_amount,2)}}</td>
        <td width="70px">{{number_format($row->order_amount,2)}}</td>
        <td width="70px">{{number_format($row->order_amount,2)}}</td>
        <td width="70px">{{number_format($row->required_cm,2)}}</td>
        <td width="70px"></td>
    </tr>
        <tr align="right">
        
        <td width="70px" align="center"> Month Achivement</td>
        <td width="70px">{{number_format($row->cutting_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->sew_qty,0)}}</td>
        <td width="70px">{{number_format($row->finishing_qty,0)}}</td>
        <td width="70px">{{number_format($row->exfactory_qty,0)}}</td>
        <td width="70px">{{number_format($row->no_of_carton,0)}}</td>
        <td width="70px">{{number_format($row->sew_amount,0)}}</td>
        <td width="70px">{{number_format($row->gmtfinishing_amount,0)}}</td>
        <td width="70px">{{number_format($row->exfactory_amount,0)}}</td>
        <td width="70px">{{number_format($row->cmmnuf,2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        
        <td width="70px" align="center">Achivement as of Last Month</td>
        <td width="70px">{{number_format($row->cuttingyesterday_qty,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->sewyesterday_qty,0)}}</td>
        <td width="70px">{{number_format($row->finishingyesterday_qty,0)}}</td>
        <td width="70px">{{number_format($row->exfactoryyesterday_qty,0)}}</td>
        <td width="70px">{{number_format($row->cartonyesterday_no_of_carton,0)}}</td>
        <td width="70px">{{number_format($row->sewyesterday_amount,0)}}</td>
        <td width="70px">{{number_format($row->finishingyesterday_amount,0)}}</td>
        <td width="70px">{{number_format($row->exfactoryyesterday_amount,0)}}</td>
        <td width="70px">{{number_format($row->cmmnuf_yesterday,2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        
        <td width="70px" align="center">Total Achivement</td>
        <td width="70px">{{number_format($row->total_cut,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->total_sew,0)}}</td>
        <td width="70px">{{number_format($row->total_finish,0)}}</td>
        <td width="70px">{{number_format($row->total_exfactory,0)}}</td>
        <td width="70px">{{number_format($row->total_no_of_carton,0)}}</td>
        <td width="70px">{{number_format($row->total_sew_amount,0)}}</td>
        <td width="70px">{{number_format($row->total_finish_amount,0)}}</td>
        <td width="70px">{{number_format($row->total_exfactory_amount,0)}}</td>
        <td width="70px">{{number_format($row->total_cm,2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right">
        <td width="70px" align="center">Yet to Achive</td>
        <td width="70px">{{number_format($row->yet_cut,0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{number_format($row->yet_sew,0)}}</td>
        <td width="70px">{{number_format($row->yet_finish,0)}}</td>
        <td width="70px">{{number_format($row->yet_exfactory,0)}}</td>
        <td width="70px">{{number_format($row->yet_no_of_carton,0)}}</td>
        <td width="70px">{{number_format($row->yet_sew_amount,0)}}</td>
        <td width="70px">{{number_format($row->yet_finish_amount,0)}}</td>
        <td width="70px">{{number_format($row->yet_exfactory_amount,0)}}</td>
        <td width="70px">{{number_format($row->yet_cm,2)}}</td>
        <td width="70px"></td>
    </tr>
    <?php
    $i++;
    ?>
    @endforeach
    <tr align="right" style="background-color:#B1F8B7; font-weight: bold;">
        <td width="70px" rowspan="5" colspan="12">Total</td>
        
        <td width="70px" align="center">Required</td>
        <td width="70px">{{ number_format($data->sum('order_plan_cut_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('screenprinttgt_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('embrotgt_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('order_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('order_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('order_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('required_carton'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('order_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('order_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('order_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('required_cm'),2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right" style="background-color:#B1F8B7">
        
        <td width="70px" align="center"> Month Achivement</td>
        <td width="70px">{{ number_format($data->sum('cutting_qty'),0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{ number_format($data->sum('sew_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('finishing_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('exfactory_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('no_of_carton'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('sew_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('finishing_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('exfactory_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('cmmnuf'),2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right" style="background-color:#B1F8B7">
        
        <td width="70px" align="center">Achivement as of Last Month</td>
        <td width="70px">{{ number_format($data->sum('cuttingyesterday_qty'),0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{ number_format($data->sum('sewyesterday_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('finishingyesterday_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('exfactoryyesterday_qty'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('cartonyesterday_no_of_carton'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('sewyesterday_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('finishingyesterday_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('exfactoryyesterday_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('cmmnuf_yesterday'),2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right" style="background-color:#B1F8B7">
        
        <td width="70px" align="center">Total Achivement</td>
        <td width="70px">{{ number_format($data->sum('total_cut'),0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{ number_format($data->sum('total_sew'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('total_finish'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('total_exfactory'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('total_no_of_carton'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('total_sew_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('total_finish_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('total_exfactory_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('total_cm'),2)}}</td>
        <td width="70px"></td>
    </tr>
    <tr align="right" style="background-color:#B1F8B7">
       
        <td width="70px" align="center">Yet to Achive</td>
        <td width="70px">{{ number_format($data->sum('yet_cut'),0)}}</td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px">{{ number_format($data->sum('yet_sew'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_finish'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_exfactory'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_no_of_carton'),0)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_sew_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_finish_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_exfactory_amount'),2)}}</td>
        <td width="70px">{{ number_format($data->sum('yet_cm'),2)}}</td>
        <td width="70px"></td>
    </tr>
</table>
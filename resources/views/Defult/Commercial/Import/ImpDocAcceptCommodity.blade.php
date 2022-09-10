<?php
    $i=1;
    $amount=0;
    $acceptance_value=0;
    $cumulative_qty=0;
?>
@if($impdocaccepts->isNotEmpty())

<table border="1" class="table_form">
    <caption>New</caption>
    <tr align="center">
        <td width="100px">PO No</td>
        <td width="100px">PI No</td>
        <td width="100px">Item Category</td>
        <td width="70px">Po Value </td>
        <td width="70px">MRR Value </td>
        <td width="70px">Acceptance Value</td>
        <td width="80px">Total Accepted Amount</td>
        <td width="80px">Yet to Accept</td>
        <td width="80px">Pay Mode</td>
    </tr>
    <tbody>      
        @foreach($impdocaccepts as $impdocaccept)
        <tr align="center">
            <td width="100px">
            {{ $impdocaccept->po_no }}
                <input type="hidden" name="imp_lc_po_id[{{ $i }}]" id="imp_lc_po_id{{ $i }}" value="{{ $impdocaccept->imp_lc_po_id }}"/>
                <input type="hidden" name="imp_doc_accept_id[{{ $i }}]" id="imp_doc_accept_id{{ $i }}" value="{{ $impdocaccept->imp_doc_accept_id }}"/>
            </td>
            <td width="100px">{{ $impdocaccept->pi_no }}</td>
            <td width="100px">{{ $impdocaccept->itemcategory}}</td>
            <td width="70px" align="right">{{ $impdocaccept->amount}}</td>
            <td width="70px" align="right"></td>
            <td width="70px" align="right">
                <input type="text" name="acceptance_value[{{ $i }}]" id="acceptance_value{{ $i }}" class="number integer" value=" {{ $impdocaccept->acceptance_value}}"/>
            </td>
            <td width="80px" align="right">
                {{ $impdocaccept->cumulative_qty}}
            </td>
            <td width="80px" align="right">
                {{ $impdocaccept->amount-$impdocaccept->cumulative_qty }}
            </td>
            <td width="80px">
                {{ $impdocaccept->paymode}}
            </td>
        </tr>
    <?php
      $i++;
    ?>           
    @endforeach
    </tbody>
    <tfoot>
        <td width="100px"></td>
        <td width="100px"></td>
        <td width="100px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="70px"></td>
        <td width="80px"></td>
        <td width="80px"></td>
        <td width="80px"></td>
    </tfoot>
</table>
@endif
@if($saved->isNotEmpty())

<table border="1" class="table_form">
  <caption>Saved</caption>
        
        <tr align="center">
            <td width="100px">PO No</td>
            <td width="100px">PI No</td>
            <td width="100px">Item Category</td>
            <td width="70px">Po Value </td>
            <td width="70px">MRR Value </td>
            <td width="70px">Acceptance Value</td>
            <td width="80px">Total Accepted Amount</td>
            <td width="80px">Yet to Accept</td>
            <td width="80px">Pay Mode</td>
        </tr>
    <tbody>
      
      
        @foreach($saved as $row)
         
        <tr align="center">
            <td width="100px">
                {{ $row->po_no }}
                <input type="hidden" name="imp_lc_po_id[{{ $i }}]" id="imp_lc_po_id{{ $i }}" value="{{ $row->imp_lc_po_id }}"/>
                <input type="hidden" name="imp_doc_accept_id[{{ $i }}]" id="imp_doc_accept_id{{ $i }}" value="{{ $row->imp_doc_accept_id }}"/>
            </td>
            <td width="100px">{{ $row->pi_no }}</td>
            <td width="100px">{{ $row->itemcategory}}</td>
            <td width="70px" align="right">{{ $row->amount}}</td>
            <td width="70px" align="right"></td>
            <td width="70px" align="right">
                <input type="text" name="acceptance_value[{{ $i }}]" id="acceptance_value{{ $i }}" class="number integer" value=" {{ $row->acceptance_value}}"/>
            </td>
            <td width="80px" align="right">{{ $row->cumulative_qty}}</td>
            <td width="80px" align="right">{{ $row->amount-$row->cumulative_qty}}</td>
            <td width="80px">{{ $row->paymode}}</td>
        </tr>
    <?php
        $i++;
        $amount+=$row->amount;
        $acceptance_value+=$row->acceptance_value;
        $cumulative_qty+=$row->cumulative_qty;
    ?>           
        @endforeach
    </tbody>
    <tfoot>
        <td width="100px"></td>
        <td width="100px"></td>
        <td width="100px">Total</td>
        <td width="70px" align="right">{{ $amount }}</td>
        <td width="70px"></td>
        <td width="70px" align="right">{{ $acceptance_value }}</td>
        <td width="80px" align="right">{{ $cumulative_qty }}</td>
        <td width="80px" align="right">{{ $amount-$cumulative_qty }}</td>
        <td width="80px"></td>
    </tfoot>
</table>
@endif
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>

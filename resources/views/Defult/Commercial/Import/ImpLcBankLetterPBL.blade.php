<table>
    <tr>
        <td width="180"><?php
            $impath=url('/')."/images/logo/premier_bank_logo.jpg";
            ?>
            <img src="<?php echo $impath;?>" height="100" widht="180"></td>
        <td width="46"></td>
        <td width="226" colspan="2" style="border:solid 1px #000;margin:5px auto;font-size:38px;" align="center"><strong>IRREVOCABLE DOCUMENTARY CREDIT APPLICATION FORM</strong></td>
        <td width="180"></td>
        <td width="46" style="border:solid 1px #000;margin:5px auto;" align="center"><strong><br/>STAMP</strong></td>
    </tr>
    <tr>
        <td width="180" colspan="6"></td>
    </tr>
    <tr>
        <td width="180" colspan="6"></td>
    </tr>
    <tr>
        <td width="180">___________________________<br/>Date:__________________</td>
        <td width="46"></td>
        <td width="226" colspan="2"></td>
        <td width="46" align="center"></td>
        <td width="180" style="border:solid 1px #000;margin:5px;"  cellpadding="2">&nbsp;&nbsp;<strong>LC No:</strong> {{ $implc->lc_no_i }}<br/>&nbsp;&nbsp;<strong>Date:</strong>{{ $implc->lc_date  }}</td>
    </tr>
</table>
<p></p>
<table border="1" cellpadding="2">
    <tr>
        <td width="340" colspan="2">&nbsp;<strong>Applicant:</strong><br>&nbsp;&nbsp;{{ $implc->company_name }}<br/>&nbsp;&nbsp;{{ $implc->company_address }}
        </td>
        <td width="340" colspan="2">&nbsp;<strong>Beneficiary:</strong><br>
        @if ($implc->lc_to_id)
            {{ $implc->lcto_supplier_name }}, <br>&nbsp;&nbsp;{{ $implc->lcto_factory_address }} <br>&nbsp;&nbsp;{{ $implc->lcto_supplier_contact }} 
        @else
            {{ $implc->supplier_name }}, <br>&nbsp;&nbsp;{{ $implc->factory_address }} <br>&nbsp;&nbsp;{{ $implc->supplier_contact }}
        @endif
        </td>
    </tr>
    <tr>
        <td width="400"  colspan="2">&nbsp;Advise By:&nbsp;&nbsp;SWIFT</td>

        <td width="280"  colspan="2">&nbsp;<strong>Currency & Amount:&nbsp;</strong>{{ $implc->currency_code }}&nbsp;{{ number_format($amount_pi,2) }}/-@if ($implc->incoterm_id)
            <br/>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#x2611;</span>&nbsp;{{ $implc->incoterm_id }}
        @endif</td>
    </tr>
    <tr>
        <td width="340"  colspan="2"{{--  rowspan="2" --}}>&nbsp;Goods (brief description):<br/>&nbsp;{{ $implc->commodity }}
        </td>
        <td width="170">&nbsp;Expiry: {{ $implc->expiry_date }}<br>&nbsp;Place: {{ $implc->expiry_place }}</td>
        <td width="170">&nbsp;Additional confirmation of credit:<br>&nbsp;{{ $implc->add_conf_ref }}</td>
    </tr>

    <tr>
        <td width="170">&nbsp;Indent/Contract/Proforma &nbsp;Invoice No :&nbsp;{{ $implc->pi_no }}<br/></td>
        <td width="170">&nbsp;H.S. Code No: {{ $implc->hs_code }}</td>
        <td width="340" colspan="2" rowspan="5">&nbsp;Credit available with:&nbsp;<br>&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#x2611;</span>&nbsp;By {{ $implc->credit_availed }}<br/>&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#x2611;</span>&nbsp;By {{ $implc->maturity_form }}<br><br>&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Deferred payment at<u>&nbsp;&nbsp;{{ $implc->tenor_days }}&nbsp;</u>&nbsp;against presentation of &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;documents detailed herein and beneficiary's draft at &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________ drawn on us.<br>&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Country of Origin &nbsp;<u>{{ $implc->origin_name }}</u><br><br>&nbsp;Shipment From :<u>&nbsp;{{ $implc->port_of_loading }}&nbsp;</u> To: <u>&nbsp;{{ $implc->port_of_discharge }}&nbsp;</u><br/>&nbsp;by&nbsp;&nbsp;<u>{{ $implc->delivery_mode }}</u>. Not later than <u>&nbsp;{{ date('d-M-Y',strtotime($implc->last_delivery_date)) }}&nbsp;</u><br><br>&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;<strong>Documents to be presented within <u>&nbsp;{{ $implc->doc_present_days }}&nbsp;</u> days from the date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;of issuance of the bills of lading or other shipping &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;documents but within the validity of the credit.</strong><br><br>&nbsp;Partial Shippment : {{ $implc->partial_shipment }}<br>&nbsp;Transshipment : {{ $implc->transhipment }}
        <br>
        <b><hr></b>
        &nbsp;Additional instruction:<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;L/C number must appear on all documents<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Invoice and the shipping documents to indicate H.S. code no. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I.R.C no. LCA no. , Shipping Mark, Bangladesh Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Registration & insurance cover note / policy no.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Shipment by Israeli Liner prohibited.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;All charges outside Bangladesh are on __________________<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Other instruction, if any : 
        <u>{{ $implc->gmts_qty }}&nbsp;{{ $implc->other_terms_condition }}</u>
        <br><br><br><br><br><br><br><br><br>
        </td>
        
    </tr>
    <tr>
        <td width="340" colspan="2">&nbsp;IRC No:&nbsp;{{ $implc->irc_no }}&nbsp;&nbsp;&nbsp;&nbsp;LCA No:&nbsp;{{ $implc->lca_no }}</td>
    </tr>
    <tr>
        <td width="340" colspan="2">&nbsp;Bangladesh Bank Registration No. :{{ $implc->ban_bank_reg_no }} & Date : {{ $implc->ban_bank_reg_date }}<br/></td>
    </tr>
    <tr>
        <td width="340" colspan="2">&nbsp;Insurance covered by:&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#x2611;</span>&nbsp;Opener&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Beneficiary<br>&nbsp;Name of Insurance Co:&nbsp;{{ $implc->insurance_company_name }}<br>&nbsp;Cover note / Policy No :&nbsp;{{ $implc->cover_note_no }}</td>
    </tr>
    <tr>
        <td width="340" colspan="2" rowspan="1">&nbsp;Required Documents :<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Beneficiary's signed commercial invoice in six/eight copies &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;certifying merchandise of _____________________origin.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Full set of clean "Shipped on Board" ocean Bills of Lading &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;made out or endorsed to the order of The Premier Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Limited , <u>{{$implc->branch_name}}</u> Branch &nbsp;__________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;showing Freight prepared / Freight to pay marked notify &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Applicant and yourselves.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Airway bill marked Freight paid showing you as consignee.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Postal Receipts marked Freight Paid and Showing you as &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;consignee.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;(Other shipping documents please state) ____________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;______________ showing you as consignee.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Certificate of Origin _________ copies.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Packing list / Weight note _________________ copies.<br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span>&nbsp;Others <u>{{ $implc->exp_lc_sc }}</u>.<br><br></td>

        {{-- <td width="340" colspan="2"></td> --}}
    </tr>
    <tr>
        <td colspan="2" width="680">
            <p>We request you to issue your irrevocable documentary credit for our account in accordance with the above instruction (marked with "x" where appropriate). The credit will be subject to the Uniform Customs and Practice for Documentary Credits (1993 Revision, Publication No. 500 of the International Chamber of Commerce, Paris, France), insofar as these are applicable.</p>
            <p>In consideration of your issuing the documentary credit in accordance with the details set out above we agree to be bound by the above terms and conditions and the terms and conditions overleaf and without prejudice to the generality of the same we specifically instruct you to pay or accept all drafts drawn pursuant thereto, and if no drafts are called for, to pay or incur a deferred payment undertaking, as the case may be for our account.</p>
            <br><table>
                <tr>
                    <td>Signature of Guarantor (If any):_________________________<br>Name of Guarantor :&nbsp;_________________________________<br>A/C No :&nbsp;&nbsp;__________________________________________</td>

                    <td>Signature of Applicant (If any):_________________________<br>Name of Applicant :&nbsp;&nbsp;<u>{{ $implc->company_name }}</u><br>A/C No :&nbsp;&nbsp;<u>{{ $implc->debit_ac_id }}</u></td>
                </tr>
            </table><br><br><br><br><br><br>
        </td>
    </tr>
</table>
<br>
<p>FEX - 1<br/><address>H.T.-15</address></p>
<br pagebreak="true"/>
{{-- <p align="center" style="font-size:40px;font-weight:bold">CONDITIONS</p> --}}
<h3 align="center">CONDITIONS</h3>
<table cellpadding="1">
    <tr>
        <td width="700" style="font-size:28px;">We further agree to be bound by the following terms and conditions (In addition to those set out overleaf)</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">1.</td>
        <td width="670px" align="left" style="font-size:25px;">In particular we authorize you to pay In legal tender of the place of payment or In any currency In which the drafts are drawn or If no drafts    are called for, in which the credit stipulates at any office of yours or your agents all or any of the said drafts or It no drafts are called for as the credit stipulates together with Interest that may be due thereunder or under the terms of the said letter of credit We further undertake to reimburse you with the amounts required to effect such payments together with interest at such rate as you may be currently charging or as may obtain at the due date or the date of payment, including the cost at the rate of exchange charged by you if currency Is acquired and If that be not possible or you, at your option, do not acquire such currency, to reimburse you with the taka cost to you of such payments
            Including interest and commission as aforesaid
        </td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">2.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree to pay on demand your, commission which shall be determined by you from time to lime on the full amount of the credit.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">3.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree that in the event of legal proceedings the amount due by us to you shall be ascertained by conversion to taka at the rate obtaining on the due date of the bill or the date of judgment or on the date of actual payment under such judgment as you may direct.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">4.</td>
        <td width="670px" align="left" style="font-size:25px;">We hereby authorize you, without your having any obligation to us to do so, to acquire-on our behalf at such time as you in your absolute discretion think fit sufficient foreign currency for delivery immediately thereafter or at such time as you may In your absolute discretion determine as may be necessary or as may appear necessary to effect such reimbursement as hereinbefore mentioned. If you decide at any time to releases such bills, securities or goods to us as you have at that time In your possession subject to the said credit, the rate of exchange applicable to the amount of payment so required Is to be at your option as you may determine .</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">5.</td>
        <td width="670px" align="left" style="font-size:25px;">As security for the repayment to you of all Indebtedness and the discharge by you of all  liabilities absolute or contingent which may be now or hereafter may be due or owing or become due or owing by us In respect of any transaction now or subsequently entered Into between us, Including the over mentioned credit we hereby transfer to you all right and interests that we may acquire in goods and chattels relating to the said credit and when such goods or chattels are at sea or In foreign ports we undertake to do so as soon as possible such further acts as may be necessary to validate such transfer by the law of the place applicable.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">6.</td>
        <td width="670px" align="left" style="font-size:25px;">We undertake that all goods shall be fully Insured against all risks Including war risks with insurers approved by you, that the Insurance policies shall be assigned to you and that until payment by us of all amounts due to you In respect of the said credit or discharge of all liabilities we may owe to you absolute or contingent any Insurance moneys payable are to be paid to you forth with and until so paid shall be held on/our behalf. We further authorize you in your discretion in the alternative to effect at our cost such further insurance as you think fit and to compromise any claim arising therefrom or from any Insurance herein before described.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">7.</td>
        <td width="670px" align="left" style="font-size:25px;">We acknowledge that all documents or goods pertaining to the said credit at any limo hold by you or your agents may be regarded as deposited with you or your agents as pledges in the absence of specific contrary arrangements and that as such you are entitled to soil or otherwise dispose  of the said goods or documents In such way and such terms as you think fit.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">8.</td>
        <td width="670px" align="left" style="font-size:25px;">We further agree to assign to you our right in the said goods or chattels as unpaid sellers on your first demand.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">9.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree that you may in your discretion realize any pledge or other security at such time as you may think fit and in particular notwithstanding that drafts. Elating to the said credit may not have matured.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">10.</td>
        <td width="670px" align="left" style="font-size:25px;">If at any time you are unable to obtain relevant instructions from us you or your agent may In your discretion (without having any obligation to do so) accept as tendered in lieu of Bills of Lading any documents issued by a carrier (Including a lighter age receipt) which acknowledge receipt of goods for transport.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">11.</td>
        <td width="670px" align="left" style="font-size:25px;">We are to be responsible for all risks arising from your acts or of any other person acting or purporting to act on your behalf together with all responsibility for the character, kind, quantity, delivery or existence of the merchandise purporting to be represented by any document and/or for any difference of character, quality, or quantity of merchandise shipped under this credit from that expressed in any invoice accompanying any of the said draft and/or for the validity, genuineness, sufficiency from or correctness of any documents even if such documents should, in fact, prove to be in any or all respects Incorrect, defective , Irregular, fraudulent or forged and/or for time, place, manner or orders in which shipment Is made and/or for partial or incomplete shipment and/or for failure or omission to ship any or all the merchandise referred to in the credit and/or for the character, adequacy, validity or genuineness of any insurance or policy or certificate of insurance or the solvency or responsibility of any insurer or any other risk connected with insurance and/or for any delay, default, fraud or deviation from instruction of the shipper or anyone else in connection with the merchandise or the shipping or other documents with respect thereto and/or for delay in arrival or failure to arrive either of the merchandise or any of the said documents and/or for any breach of contract between the shippers or vendors and the undersigned hereby agree not to claim from you damages or hold you in any manner responsible for any delay, oversight or mistake or negligence on your part or on the part of any of your agents or sub-agents issuing the credit or in complying with any Instructions of the undersigned or otherwise in relation to the applications of the said credit and the undersigned will hold you harmless from all loss or damage in respect of any such matters and from any and all damage and loss whatsoever suffered by you by reason of any and all action taken by you or your correspondent in good faith in furtherance of your above request or due to errors, omission, interruptions or delays in transmission or delivery of any and all messages by Mail, Cable, Telegraph or Wireless , whether or not the same be in cipher. If the above instructions require you to establish the credit by cable it is understood that this cable is sent entirely at our risk and that you are not to be held liable either for any mistake or omission which may happen in the transmission or the message or for its misinterpretation when received, also that in case of incorrect or over payment by reason of any such error you will hold us responsible for the amount thus paid in excess or otherwise.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">12.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree to ensure that all necessary export and import authorizations are obtained in respect of the transaction to which the letter of credit relates</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">13.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree to give you on demand any further security you may require and further that all the other funds, credit instruments and securities including proceeds thereof and also all collection items and proceeds thereof, now or hereafter handed to you or left in your possession by us or by any other person for our account or held by them for transit to or from you by mail or courier are hereby made security of this obligation.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">14.</td>
        <td width="670px" align="left" style="font-size:25px;">The receipt by you at any time of other collateral of whatsoever nature shall not be deemed a waiver of any of your rights or powers relating to any collateral which you may old at time of receipt.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">15.</td>
        <td width="670px" align="left" style="font-size:25px;">This obligation is to continue in force notwithstanding any change in the constitution of the undersigned whether arising from death or retirement of one or more new partner, or otherwise howsoever.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">16.</td>
        <td width="670px" align="left" style="font-size:25px;">This letter of credit can be revoked or altered only with consent of all parties interested.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">17.</td>
        <td width="670px" align="left" style="font-size:25px;">Irrespective of the Port to which Shipment is effected we shall retire the bills on demand or payment.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">18.</td>
        <td width="670px" align="left" style="font-size:25px;">The documents accepted in connection with the credit may in your sole discretion be those which are generally acceptable in accordance with the laws customs and usages at the place of negotiation.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">19.</td>
        <td width="670px" align="left" style="font-size:25px;">This agreement will also constitute an agreement between the undersigned and your correspondent whom you employ (as you are at liberty to do) for the purpose and in connection with this letter of credit agreement</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">20.</td>
        <td width="670px" align="left" style="font-size:25px;">We authorize you to debit our account with a) the amount of any bill or document paid or negotiated In accordance with the terms of this credit, b) such sums by way or margin mutually agreed with The Premier Bank Limited to be placed in a separate account, c) your charges in respect of this credit or any amendments and/or extensions to this credit, d) charges levied by your overseas correspondents and/or agents and e) payments in respect of premiums to an insurance company nominated by The Premier bank Limited.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">21.</td>
        <td width="670px" align="left" style="font-size:25px;">It is a term of this letter of credit agreement that it is to be subject to and interpreted in accordance with Bangladesh Law and that it is to be subject to the exclusive Jurisdiction of the Bangladeshi Courts (save and except that you are not thereby to be prevented from commencing proceedings before any other Court)</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">22.</td>
        <td width="670px" align="left" style="font-size:25px;">We agree that the address mentioned overleaf shall be an effective address for service in respect of any proceedings commenced in the Bangladeshi Courts as hereinbefore described.</td>
    </tr>
</table>
<p></p>
<p style="font-size:25px;"><strong>________________________________________________________</strong><br>Name, stamp and authorize signature (s) of the applicant.
    In consideration of your entering Into the foregoing Agreement at our request, we guarantee due compliance by the Applicant with the terms of the above letter of credit /agreement to you and to the banks advising, confirming, paying, Incurring, deferred payment undertaking, accepting and negotiating drafts thereunder and to hold you and the said banks harmless from all loss or damage In respect of any matters and from any and all damage or loss whatsoever suffered by you and the said banks by reason of any action taken under the above letter of credit agreement and agree that our liability shall be unaffected by any release of security or giving time or other Indulgence to the Applicant.<br>It Is a term of this guarantee that it Is to be subject to and Interpreted In accordance with Bangladesh] Law and that It Is to be subject to this exclusive jurisdiction of the Bangladeshi Court (Save and except that you are not thereby to be prevented from commencing proceedi11gs before any other Court).<br>We agree that<strong>________________________________________________________________________________________________________________________________</strong><br>Should be an effective address for service upon us in respect of any proceedings commenced In the Bangladeshi Courts as herein before described.
</p>
<p></p>
<p></p>
<p style="font-size:25px;"><strong>__________________________</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Guarantor's Signature</p>
<table border="1" cellpadding="0" style="font-size:29px;">
    <tr>
        <td width="680" colspan="2" align="center"><strong>For Bank's Use</strong></td>
    </tr>
    <tr>
        <td width="380"> LC No. ____________________Value__________ @Tk.______________<br> Advised through ______________________<br><table>
            <tr>
                <td width="190"> Margin % &nbsp;________________________<br> Commission ______________________<br> Postage&nbsp;&nbsp;_________________________<br> Cable Charges ____________________<br> FCC    ____________________________<br> Total ____________________________
                </td>
                <td width="190" style="border:solid 1px #000" height="100px">&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span> Total liability within limit  sanctioned &nbsp;&nbsp;to the applicant, Ref. No.<br><br><br>&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:40px">&#9744;</span> Approved</td>
            </tr>
        </table>
        </td>
        <td width="300">&nbsp;Sanctioned limit Tk.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L/C Tk.   _________________________________<br>&nbsp;Present&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pad Tk._________________________________<br>&nbsp;Outstanding&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lim Tk. _________________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TR Tk.__________________________________<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Others Tk._______________________________<br>&nbsp;Sub. Total.  ___________________________________________<br>&nbsp;Proposed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;L/C Tk. _________________________________<br>&nbsp;Total Liability&nbsp;&nbsp;&nbsp;&nbsp;L/C Tk.  _________________________________
           </td>
    </tr>
</table>
<P style="font-size:28px;">FEX - 1</P>
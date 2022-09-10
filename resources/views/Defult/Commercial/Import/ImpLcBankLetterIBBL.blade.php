<p></p>
<table>
    <tr>
        <td align="left" width="260px"><strong>To</strong><br/><strong>{{$implc->contact}}</strong></td>
        <td width="150px"></td>
        <td></td>
        <td width="80px"><strong>F-27</strong></td>
    </tr>
    <tr>
        <td align="left"  width="260px"><strong>{{$implc->bank_name}}<br/>
        {{$implc->branch_name}}</strong></td>
        <td style="border:solid 1px #000;margin:5px;" width="150px"  cellpadding="2">&nbsp;&nbsp;<strong>LC No:</strong> {{ $implc->lc_no }}<br/>&nbsp;&nbsp;<strong>Date:</strong>{{-- {{ $implc->lc_date  }} --}}</td>
        <td></td>
        <td align="center" style="border:solid 1px #000" width="80px">Adhesive<br/> Stamp</td>
    </tr>
    <tr>
        <td align="left" width="260px"><strong>{{$implc->bank_address}}</strong></td>
        <td width="150px"></td>
        <td></td>
        <td width="80px"></td>
    </tr>
</table>
<p></p>
<p style="font-size:40px;font-weight:bold" align="center">Application and Agreement for Irrevocable Letter of Credit</p>
<p>I/We, hereby request you to open an Irrevocable Letter of Credit ("Credit") through your correspondent as per detais given below:</p>
<table border="1" style="border-collapse: 1px" cellpadding="3px">
    <tr>
        <td><strong>Advise Credit by</strong></td>
        <td><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->credit_to_be_advised }}</td>
        <td colspan="2">Advising Bank:{{ $implc->advise_bank }}</td>
        <td>Transferable:<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>Yes <span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>No</td>
    </tr>
    <tr>
        <td><strong>Shipment & Expiry</strong></td>
        <td colspan="2"><strong>Latest Shipment Date:&nbsp;</strong>{{ $implc->last_ship_date }}</td>
        <td><strong>Expiry Date:&nbsp;</strong>{{ $implc->expiry_date }}</td>
        <td><strong>Place:&nbsp;</strong>{{ $implc->incoterm_place }}</td>
    </tr>
    <tr>
        <td rowspan="2"><strong>Applicant <br/>(Name & Adress)</strong></td>
        <td colspan="4">{{ $implc->company_name }}<br/>{{ $implc->company_address }}</td>
    </tr>
    <tr>
        <td colspan="4" style="margin-left: 0px">
            <table cellspacing="0" cellpadding="0" style="margin-left: 0px">
                <tr>
                    <td><strong>TIN:{{ $implc->tin_number }}</strong></td>
                    <td colspan="3">IRC NO:{{ $implc->irc_no }}&nbsp;&nbsp;Phone:{{ $implc->company_phone }}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td rowspan="2"><strong>Beneficiary<br/>(Name & Adress)</strong></td>
    @if ($implc->lc_to_id)
        <td colspan="4">{{ $implc->lcto_supplier_name }},<br/>{{ $implc->lcto_factory_address }}<br/>{{ $implc->lcto_supplier_contact }}</td> 
    @else
        <td colspan="4">{{ $implc->supplier_name }},<br/>{{ $implc->factory_address }}<br/>{{ $implc->supplier_contact }}</td>  
    @endif
    </tr>
    <tr>
        <td colspan="4" style="margin-left: 0px">
            <table  cellspacing="0" cellpadding="0" style="margin-left: 0px">
                <tr>
            @if ($implc->lc_to_id)
                <td>Email:{{ $implc->lcto_supplier_email }}</td> 
            @else
                <td>Email:{{ $implc->supplier_email }}</td>
            @endif
                <td>Fax:</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><strong>Currency, Amount & Tolerance</strong></td>
        <td colspan="3">Currency:{{ $implc->currency_code }}&nbsp;{{ $amount_pi }}/-&nbsp;&nbsp;In Word:&nbsp;{{ $implc->inword }}</td>
        <td>Tolerance:{{ $implc->tolerance }}%</td>
    </tr>
    <tr>
        <td><strong>Credit available by</strong></td>
        <td colspan="4"><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->credit_availed }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->maturity_form }}
        </td>
    </tr>
    <tr>
        <td><strong>Drafts at</strong></td>
        <td colspan="4"><strong><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->pay_term }}&nbsp;{{ $implc->tenor }}&nbsp;Days&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;{{ $implc->maturity_form }}</strong></td>
    </tr>
    <tr>
        <td rowspan="3"><strong>Shipment details</strong></td>
        <td colspan="2"><strong>Partial Shipment:&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->partial_shipment }}</strong></td>
        <td colspan="2"><strong>Transshipment:&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->transhipment }}</strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Shipment From:&nbsp;</strong>{{ $implc->port_of_loading }}</td>
        <td colspan="2"><strong>Shipment To:&nbsp;</strong>{{ $implc->final_destination }}</td>
    </tr>
    <tr>
        <td  colspan="4">Shipment By:&nbsp;&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->delivery_mode }}
        </td>
    </tr>
    <tr>
        <td rowspan="3"><strong>Quantity and description of goods &/of Service</strong></td>
        <td colspan="4">{{ $implc->commodity }}</td>
    </tr>
    <tr>
        <td colspan="4">Proforma Invoice / Indent No:&nbsp;{{ $implc->pi_no }} 
        </td>
    </tr>
    <tr>
        <td colspan="2"><strong>H.S Code:</strong>{{ $implc->hs_code }}</td>    
        <td colspan="2"><strong>Country of Origin:</strong>{{ $implc->origin_name }}</td>    
    </tr>
    <tr>
        <td><strong>Terms of Delivery:</strong></td>
        <td colspan="2"><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>
            &nbsp;&nbsp;{{ $implc->incoterm_id }}
        </td>
        <td colspan="2"><strong>LCAF No:</strong>&nbsp;{{ $implc->lcaf_no }}</td>
    </tr>
   <tr>
        <td><strong>Insurance</strong></td>
        <td colspan="4">Cover Note No:&nbsp;{{ $implc->cover_note_no }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:{{ $implc->cover_note_date }}
            <br/>Name & Address of the Company:&nbsp;&nbsp;{{ $implc->insurance_company_name }},<br/>{{ $implc->insurance_company_address }}.
        </td>
    </tr>
     <tr>
        <td><strong>Inspection to be Conducted By</strong></td>
        <td colspan="4"><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->psi_company }}
        </td>
    </tr>
    <tr>
        <td><strong>Bank Charges</strong></td>
        <td colspan="2">Charges outside Bangladesh are on&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->outside_charge_id }}
        </td>
        <td colspan="2">Charges in Bangladesh are on&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->inside_charge_id }}</td>
    </tr>
    <tr>
        <td><strong>Period for Presentation</strong></td>
        <td colspan="4">Within &nbsp;<strong>{{ $implc->doc_present_days }}&nbsp;&nbsp;days</strong> from the date of Shipment but within the Credit validity.</td>
    </tr> 
    <tr>
        <td><strong>Confirmation Instructions</strong></td>
        <td colspan="2"><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;Confirmed ; 
        @if ($implc->add_conf_ref_id==1)
            &nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;Add Confirmation Required:Yes;<br/>Confirmation Bank:{{ $implc->add_conf_bank }}
        @endif  
        
        </td>
        <td colspan="2">Charges are on :&nbsp;&nbsp;<span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#x2611;</span>&nbsp;{{ $implc->add_conf_charge }}
        </td>
    </tr>
    <tr>
        <td rowspan="2"><strong>For Back to Back L/C</strong></td>
        <td colspan="4">Master {{ $implc->exp_lc_sc }}
        </td>
    </tr>
    <tr>
        <td colspan="2">Value:&nbsp;&nbsp;{{ $implc->lc_sc_amount }} USD/-
        </td>
        <td colspan="2">Garments Qty: {{ $implc->gmts_qty }}
        </td>
    </tr>
    <tr>
        <td><strong>Documents Required</strong></td>
        <td colspan="4"><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;Signed Commercial Invoices in <strong>........</strong> folds.<br/><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;Full set of Shipped on Board Bill of Lading/AWB/TR/..............&.............Non Negotiable copies marked &nbsp;&nbsp;&nbsp;'Freight Pre-Paid'/Freight Collect' Consigned or endorsed to the order of Islami Bank Bangladesh Ltd. notify your Branch & us.<br/><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;Certificate of Origin in duplicate issued by any Govt. Approved agency/ competent authority.<br/><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;Packing list in ..... folds.<br/><span style="font-family: dejavusans;font-weight:bold;font-size:30px">&#9744;</span>&nbsp;Other documents (if any):
        </td>
    </tr>
    <tr>
        <td><strong>Other Terms & Conditions(if any)</strong></td>
        <td colspan="4">{{ $implc->other_terms_condition }}</td>
    </tr>
    <tr>
        <td colspan="5" align="center"><strong>The Credit is subject to the Uniform Customs and Practice for Documentary Credits (UCP) Latest Version</strong></td>
    </tr>
</table>
<p></p>
<p>In consideration of of your opening irrevocable Letter of Credit on the above terms and conditions, the undersigned unconditionally agree as per conditions overleaf</p>
<p></p>
<p align="right"><strong>(Signature of Applicant)</strong><br/><strong>A/C No:{{ $implc->debit_ac_id }}</strong>&nbsp;&nbsp;&nbsp;</p>


<br pagebreak="true"/>

<p align="center" style="font-size:45px;font-weight:bold">Conditions</p>
<table cellpadding="2">
    <tr>
        <td width="30px" align="left" style="font-size:25px;">1.</td>
        <td width="600px" align="left" style="font-size:25px;">To accept or pay upon presentation all drafts drawn pursuant thereto.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">2.</td>
        <td width="600px" align="left" style="font-size:25px;">Notwithstanding anything contained in this Agreement you may on payment debit our account with all sums paid in connection with this Credit, the documents or the goods including commission and charges or with the whole or part of the amount of the credits at any time or times if you think fit and on demand we will place you in funds to meet such debits. In the case of credits in currencies other than Taka you may at any time or times as you may think fit pass any such debits in the currency of the credit and/or in foreign exchange and/or in Taka at your rate(s) of exchange unless otherwise arranged. We will place funds in the currency in which the debits are passed, the bills are drawn or the Acceptances are given, or at your option in Taka at your selling rate of exchange for the currency. That it is at your sole option to claim, payment of any bill drawn pursuant hereto either at the rate of exchange ruling on the date of its negotiation abroad or the date of its payment by you or the date of presentation -to me, us or due date of its negotiation abroad or the date of its payment by you or the date of presentation to me, us or in the event of any legal proceedings being taken in respect of such bill at the rate ruling at the date of institution of the proceedings or of the decree in such proceeding, and I am/we are bound to make payment of the said bill at whichever of the above rates you may name. Without affecting your rights as before mentioned we agree duly to accept and pay at maturity any bill drawn upon us under this credit or to provide you with funds to meet any acceptance given by you under this credit three days earlier of due date if required. In the event of any default you may sell the documents, or goods before or after arrival and any deficit 1/We will pay you on demand as aforesaid and indemnify you against all claims, demands, costs and expenses incurred in connection with this credit.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">3.</td>
        <td width="600px" align="left" style="font-size:25px;">To pay on demand your commission which shall be determined by the Bank from time to time, on the full amount of the credit.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">4.</td>
        <td width="600px" align="left" style="font-size:25px;">Until the payment of every indebtedness and liability absolute or contingent which now is or hereafter may become due and owing by the undersigned to you on any transaction now or hereafter had with you, including transactions under other Letters of Credit, the undersigned agree that the title and ownership of all goods shipped under or in connection with the said credit or in any way relating thereto whether or not released to the undersigned against trust to be in and/or of the proceeds of such goods and of all Bills of lading, policies or certificates of insurance or other documents given therefore, shall be and remain, in you and the undersigned hereby give you full power and authority at your discretion, by yourselves or enough agents at any time to have and take possession thereof and of all policies certificates of insurance thereon and proceeds of such policies and certificates and to hold and or collect the same or under the terms expressed below to dispose thereof at any time and irrespective of the maturity or the Drafts or acceptance under the said credit.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">5.</td>
        <td width="600px" align="left" style="font-size:25px;">In the absence of written instructions given by the undersigned, expressly to the country. The undersigned authorize you and your correspondents to receive anti accept as ‘Bills Lading’ under the said credit. Any documents issued or on behalf of any carrier including lighter age receipt which acknowledge receipt at goods for transportation. Whatever the specific provision of such documents and the date of each document to be regarded as the date of Bill of Lading and/or of shipment with the terms of said credit. And the undersigned authorize you or your correspondents to accept as sufficiently evidencing “Insurance” under the said credit. Either policies or certificates of such Insurance.”</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">6.</td>
        <td width="600px" align="left" style="font-size:25px;">The undersigned assume all risks of any person using the said credits who are hereby accepted as the agents of the undersigned together with all responsibility for the character, kind, quality, quantity delivery or existence of the merchandise purporting to be represented by any documents and or for any difference of character, quality,  quantity of merchandise shipped under this credit from that expressed fit any invoice accompanying any of said drafts andlor for the validity, genuineness, sufficiency from or correctness of any documents even if such documents should in fact, prove to begin any or all incorrect , defective, irregular, fraudulent or forged anylor for time, place, manner or order in which shipment is made andlor for partial or incomplete shipment and or for failure or omission to ship any or all of the merchandise , referred to in it credit of for the character, adequacy, validity, genuineness of any insurer or any other risks connected with insurance and or, for any delay, default, fraud or deviation from instructions of the shipper or anyone else in connection with said merchandise or the shipping or other documents with respect thereto and/or for delay in arrival or failure to arriver either of the merchandise or of any of the said documents and/or for any breach of contract between the shippers or vendors and undersigned hereby agree not to claim from you damages or hold you. In any manner responsible for any delay, oversight or, mistake or negligence on your part or on the part of any your agents or sub agents in issuing the credit or in complying with any instructions of the undersigned or otherwise in relation to the application and said credit and the undersigned will hold you harmless from an loss or damage in respect of any such matters and from any/all damage and loss whatsoever suffered by you by reason of any and all action taken by you or your correspondent in good faith, in furtherance of our above request or due errors, omissions, interruptions or delays in transmission or delivery of any and all message by mail, cable, telegraph or wireless whether or not the same be in cipher.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">7.</td>
        <td width="600px" align="left" style="font-size:25px;">The undersigned agree so caused to be procured promptly the necessary import and export or other license for the said merchandise and will keep the same adequately covered these policies of fire, marine and War-risk insurance in companies satisfactory to you assigning the policies or certificates of insurance to you or making the loss or adjustment if any payable to you at your option.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">8.</td>
        <td width="600px" align="left" style="font-size:25px;">And the undersigned agree to give you on demand any further or other security you may require , and further agree that and all other funds credit instruments, properly and securities and proceeds thereof also any and all collection item and proceeds thereof now or hereafter handed to you or for any purpose left in you possession by the undersigned or their account or at their disposal , for transit to or from you by mail or courier, for any of the said purpose are hereby made security for this obligation, and also for any and all other obligations or liabilities absolute or contingent, deficit, or not due which are or may at any time be owing by the undersigned to you and may be held or disposed of as you may see fit, and applied , towards payment of and all such obligations and liabilities , all of which in the event of default by the undersigned in any part thereof or of bankruptcy, insolvency, receivership or general assignment of the undersigned, shall subject to your option forthwith become due and payable and the undersigned hereby authorize you for any obligation covered by this instrument  or any other indebtedness due from the undersigned to you shall not be punctually met forth without further demand or notice or advertisement of any kinds, all of which hereby, expressly waived to sell dispose of the whole or any part of said funds, credits, instruments, properties and security, arrived and or to arrive at arty broker’s exchange, or by public or privet sail or otherwise, at your option with permission yourselves to recover the purchasers, in whole or in part, without accountability safe for the purchase, price and free from any right of redemption, which is hereby waived and released; and to apply the net proceeds thereof against any and all obligations or liabilities of the undersigned to you however, arising.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">9.</td>
        <td width="600px" align="left" style="font-size:25px;">The receipt by you at any time of other collateral of whatsoever nature, shall notice deemed a waiver of any of your rights or power relating to any collateral which you may hold at the time of receipt.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">10.</td>
        <td width="600px" align="left" style="font-size:25px;">This obligation is to continue in force notwithstanding any charge-in membership of any partnership of the undersigned whether from the death or retirement of one or more new partners.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">11.</td>
        <td width="600px" align="left" style="font-size:25px;">This letter of credit can be revoked or altered only with the consent, of all parties interested.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">12.</td>
        <td width="600px" align="left" style="font-size:25px;">That whenever shipments are made to port other than Bangladesh we shall retire the bills on demand of payment.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">13.</td>
        <td width="600px" align="left" style="font-size:25px;">The documents accepted in connection with this credit may be those which are generally acceptable in accordance with these laws, customs and usages at the place of negotiation.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">14.</td>
        <td width="600px" align="left" style="font-size:25px;">This will also constitute an agreement between the undersigned and your correspondent whom you may employ (as you are at liberty to do) for the purpose and in connection with tills credit agreement.</td>
    </tr>
    <tr>
        <td width="30px" align="left" style="font-size:25px;">15.</td>
        <td width="600px" align="left" style="font-size:25px;">The undersigned authorize you to debit my/our account with all your charges including cash security on account of this Credit and also Amendment Extension of this Credit as well as charge levied by your overseas Correspondents/Agents.</td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<table cellpadding="2">
    <tr>
        <td align="right"><strong>(Signature of Application)</strong></td>
    </tr>
    <tr><td></td></tr>
    <tr>
        <td style="font-size:25px;">We guarantee due compliance with the terms of the above Credit Agreement to the bank issuing, advising and negotiating drafts there under and to hold the said bank harmless from all loss or damage inspect of any  matters and from any/all damage or loss whatsoever suffered by the said banks be reasons of any action taken under the above Credit Agreement.</td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td align="right"><strong>(Signature of Guarantors)</strong></td>
    </tr>
</table>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>A simple, clean, and responsive HTML invoice template</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>
                        <td class="title">
                            <a href="{{config('app.name')}}" class="f-fallback email-masthead_name" style="color: #A8AAAF; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                <img class="img-fluid ml-5 mt-3" src="{{asset('/assets/images/company-assets/logos/'.Auth::user()->tenant->logo ?? 'logo.png')}}" alt="{{Auth::user()->tenant->company_name ?? 'CNXRetail'}}" height="75" width="120" style="display:block;">
                            </a>
                        </td>

                        <td>
                            Invoice #: {{$invoice->invoice_no}}<br />
                            Created: {{date('d F, Y', strtotime($invoice->issue_date))}}<br />
                            Due: {{date('d F, Y', strtotime($invoice->due_date))}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td>
                            {{Auth::user()->tenant->company_name ?? 'CNX247'}}<br />
                            {{Auth::user()->tenant->phone ?? 'CNX247'}}<br />
                            {{Auth::user()->tenant->email ?? 'CNX247'}}
                        </td>

                        <td>
                            {{$invoice->contact->company_name ?? ''}} <br />
                            {{$invoice->contact->first_name ?? ''}} <br />
                            {{$invoice->contact->email ?? ''}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td>Quantity</td>
            <td>Unit</td>
            <td>Total</td>
        </tr>
        @foreach($invoice->invoiceDetail as $item)
        <tr class="item">
            <td>{{$item->getInvoiceService->service ?? ''}}</td>

            <td>{{number_format($item->quantity ?? 0)}}</td>
            <td>{{number_format($item->unit_cost ?? 0, 2)}}</td>
            <td>{{number_format($item->quantity * $item->unit_cost,2)}}</td>
        </tr>
        @endforeach
        <tr class="total">
            <td></td>
            <td></td>
            <td></td>

            <td>Total: {{Auth::user()->tenant->currency->symbol ?? 'N'}}{{number_format($invoice->total,2)}}</td>
        </tr>

    </table>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <p>Click this link to make payment: <a href="{{route('pay-invoice-online', $invoice->slug)}}"  target="_blank" class="btn btn-sm btn-secondary">Make payment</a></p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

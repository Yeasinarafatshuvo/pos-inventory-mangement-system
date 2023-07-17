@extends('backend.layouts.app')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<style>
    .modal.fade .modal-dialog {
        transition: opacity 0.3s ease-out;
        opacity: 0;
    }
    .modal.fade.show .modal-dialog {
    opacity: 1;
    }
    .shadow_class{
        box-shadow: 0 0 50px #ccc;
    }

    @media print {
        #dtBasicExample {
            width: 100%;
            margin: 0 auto;
        }
        #title{
            display: block;
        }
    }
</style>
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Information about CRM Report')}}</h1>
	</div>
</div>


<div class="card">
    <div class="card-body" style=" overflow-x: scroll;">
        <table id="dtBasicExample" class="table table-striped table-bordered table-hover text-center" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('User Name')}}</th>
                    <th>{{translate('Whatsapp Sent')}}</th>
                    <th>{{translate('Whatsapp Response')}}</th>
                    <th>{{translate('Whatsapp Comment')}}</th>
                    <th>{{translate('Email Sent')}}</th>
                    <th>{{translate('Email Response')}}</th>
                    <th>{{translate('Email Comment')}}</th>
                    <th>{{translate('Phone Call')}}</th>
                    <th>{{translate('Phone Call Response')}}</th>
                    <th>{{translate('Phone Call Comment')}}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($crm_report_list as $crm_report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><nobr>{{ $crm_report->created_at->format('d-m-Y') }}</nobr></td>
                        <td>{{ getUserName($crm_report->user_id) }}</td>
                        <td>{{ $crm_report->number_of_whatsapp_sent }}</td>
                        <td>{{ $crm_report->number_of_whatsapp_response }}</td>
                        <td>{{ $crm_report->whatsapp_comment }}</td>
                        <td>{{ $crm_report->number_of_email_sent }}</td>
                        <td>{{ $crm_report->number_of_email_response }}</td>
                        <td>{{ $crm_report->email_comment }}</td>
                        <td>{{ $crm_report->number_of_phone_call }}</td>
                        <td>{{ $crm_report->number_of_phone_call_response }}</td>
                        <td>{{ $crm_report->phone_call_comment }}</td>
                    </tr>
                @endforeach
            </tbody>

            

            <div class="aiz-pagination">
            
            </div>
      </table>

        <div class="text-center">
            <button class="btn btn-danger text-white" onclick="print_report()"><i class="fa-solid fa-print"></i></button>
        </div>


    </div>
</div>

@endsection

@section('modal')
    @include('backend.inc.delete_modal')
@endsection

@section('script')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PrintArea/2.4.1/jquery.PrintArea.min.js" integrity="sha512-mPA/BA22QPGx1iuaMpZdSsXVsHUTr9OisxHDtdsYj73eDGWG2bTSTLTUOb4TG40JvUyjoTcLF+2srfRchwbodg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    function print_report() {
        var printContents = document.getElementById("dtBasicExample").outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;


        window.print();
        document.body.innerHTML = originalContents;
    }
    </script>

@endsection

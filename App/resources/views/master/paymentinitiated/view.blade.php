@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Operations</li>
					<li class="breadcrumb-item">Payment Initiated</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header text-center">
							<div class="form-row  d-flex justify-content-center">
								<div class="col-md-4">	</div>
								<div class="col-md-4 my-2">
									<h5>Payment Initiated</h5>
								</div>
								<div class="col-md-4 my-2 text-right text-md-right">
									@if($crud['restore']==1)
										<!--<a href="{{ url('/') }}/master/paymentinitiated/trash-view" class="btn  btn-outline-light btn-sm m-r-10" type="button" > Trash view </a>-->
									@endif
									@if($crud['add']==1)
										<!--<a href="{{ url('/') }}/master/paymentinitiated/create" class="btn  btn-outline-success btn-air-success btn-sm" type="button" >Create</a> <!-- full-right -->
									@endif
								</div>
							</div>
						</div>
						<div class="card-body " >
                            <table class="table" id="tblpaymentinitiated">
                                <thead>
                                    <tr>
                                        
                                        <th class="text-center">Technical Assistant</th>
                                        <th class="text-center">Beneficiary </th>
                                        <th class="text-center">Contractor</th>
										<th class="text-center">Housing Type</th>
                                        <th class="text-center">Housing Phase </th>
										<th class="text-center">Status</th>
                                        <th class="text-center">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        let RootUrl=$('#txtRootUrl').val();
        const LoadTable=async()=>{
			@if($crud['view']==1)
			$('#tblpaymentinitiated').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"master/paymentinitiated/data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength' 
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Phase Update',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2},
					{"className": "dt-center", "targets":3}
				]
			});
			@endif
        }
// 		$(document).on('click','.btnEdit',function(){
// 			window.location.replace("{{url('/')}}/master/paymentRelased");
// 		});
		
		$(document).on('click','.btnDelete',function(){
			let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Delete this paymentinitiated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-danger",
                confirmButtonText: "Yes, Delete it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{url('/')}}/master/paymentinitiated/delete/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblpaymentinitiated').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}else{
                    		toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}
                    }
            	});
            });
		});
		
// 		$(document).on('click','.btnEdit',function(){
// 			let ID=$(this).attr('data-id');
// 			swal({
//                 title: "Are you sure?",
//                 text: "You want Start this payment Relased!",
//                 type: "success",
//                 showCancelButton: true,
//                 confirmButtonClass: "btn-outline-primary",
//                 confirmButtonText: "Yes, Relased it!",
//                 closeOnConfirm: false
//             },
//             function(){swal.close();
//             	$.ajax({
//             		type:"post",
//                     url:"{{url('/')}}/master/paymentinitiated/initiaze/"+ID,
//                     headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
//                     dataType:"json",
//                     success:function(response){
//                     	swal.close();
//                     	if(response.status==true){
//                     		$('#tblpaymentinitiated').DataTable().ajax.reload();
//                     		toastr.success(response.message, "Success", {
//                                 positionClass: "toast-top-right",
//                                 containerId: "toast-top-right",
//                                 showMethod: "slideDown",
//                                 hideMethod: "slideUp",
//                                 progressBar: !0
//                             })
//                     	}else{
//                     		toastr.error(response.message, "Failed", {
//                                 positionClass: "toast-top-right",
//                                 containerId: "toast-top-right",
//                                 showMethod: "slideDown",
//                                 hideMethod: "slideUp",
//                                 progressBar: !0
//                             })
//                     	}
//                     }
//             	});
//             });
// 		});
        $(document).on('click','.btnEdit',function(){
// 			window.location.replace("{{url('/')}}/master/paymentinitiated");
            	let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Payment Relesed this Phase !",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-primary",
                confirmButtonText: "Yes, Payment Relesed it!",
                closeOnConfirm: false
            },
            function(){swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{url('/')}}/master/PhaseUpdate/PaymentRelased/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblPhaseUpdate').DataTable().ajax.reload();
                    		toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}else{
                    		toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                    	}
                    }
            	});
            });
		});
        LoadTable();
    });
</script>
@endsection

<!-- https://www.aspsnippets.com/questions/142715/Make-HTML-Table-cell-editable-using-jQuery-in-ASPNet-MVC/ -->
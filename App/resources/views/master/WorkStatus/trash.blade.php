@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Operations</li>
					<li class="breadcrumb-item"><a href="{{ url('/') }}/master/AssignedOfficers/" data-original-title="" title="">Housing Work Status</a></li>
					<li class="breadcrumb-item">Trash View</li>
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
							<div class="form-row align-items-center">
								<div class="col-md-4">	</div>
								<div class="col-md-4 my-2">
									<h5>AssignedOfficers Trash View</h5>
								</div>
								<div class="col-md-4 my-2 text-right text-md-right">
									<a href="{{ url('/') }}/master/AssignedOfficers/" class="btn  btn-outline-light btn-sm m-r-10" type="button" > Back</a>
								</div>
							</div>
						</div>
						<div class="card-body " >
                            <table class="table" id="tblAssignedOfficers">
                                <thead>
                                    <tr>
									<th class="text-center">APHTID</th>
                                        <th class="text-center">Housing Type</th>
                                        <th class="text-center">Beneficiary </th>
										<th class="text-center">ActiveStatus</th>
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
			$('#tblAssignedOfficers').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
                "ajax": {"url": RootUrl+"master/AssignedOfficers/trash-data?_token="+$('meta[name=_token]').attr('content'),"headers":{ 'X-CSRF-Token' : $('meta[name=_token]').attr('content') } ,"type": "POST"},
				deferRender: true,
				responsive: true,
				dom: 'Bfrtip',
				"iDisplayLength": 10,
				"lengthMenu": [[10, 25, 50,100,250,500, -1], [10, 25, 50,100,250,500, "All"]],
				buttons: [
					'pageLength' 
					@if($crud['excel']==1) ,{extend: 'excel',footer: true,title: 'Assigned Officers',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif 
					@if($crud['copy']==1) ,{extend: 'copy',footer: true,title: 'Assigned Officers',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['csv']==1) ,{extend: 'csv',footer: true,title: 'Assigned Officers',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['print']==1) ,{extend: 'print',footer: true,title: 'Assigned Officers',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
					@if($crud['pdf']==1) ,{extend: 'pdf',footer: true,title: 'Assigned Officers',"action": DataTableExportOption,exportOptions: {columns: "thead th:not(.noExport)"}} @endif
				],
				columnDefs: [
					{"className": "dt-center", "targets":2},
					{"className": "dt-center", "targets":3}
				]
			});
			@endif
        }
		
		$(document).on('click','.btnRestore',function(){
			let ID=$(this).attr('data-id');
			swal({
                title: "Are you sure?",
                text: "You want Restore this Item!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-outline-primary",
                confirmButtonText: "Yes, Restore it!",
                closeOnConfirm: false
            },
            function(){
                swal.close();
            	$.ajax({
            		type:"post",
                    url:"{{url('/')}}/master/AssignedOfficers/restore/"+ID,
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);swal.close();},
                    success:function(response){
                    	swal.close();
                    	if(response.status==true){
                    		$('#tblAssignedOfficers').DataTable().ajax.reload();
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
@extends('layouts.layout')
@section('content')
<div class="container-fluid">
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ url('/') }}" data-original-title="" title=""><i class="f-16 fa fa-home"></i></a></li>
					<li class="breadcrumb-item">Master</li>
					<li class="breadcrumb-item">Approved Housing Type</li>
                    <li class="breadcrumb-item">@if($isEdit==true)Update @else Create @endif</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row d-flex justify-content-center">
		<div class="col-sm-6">
			<div class="card">
				<div class="card-header text-center"><h5 class="mt-10">Approved Housing Type</h5></div>
				<div class="card-body " >
                    
                    <div class="row">
                    <div class="col-sm-12">
                            <div class="form-group">
                                <label class="lstHousingType">  Housing Type  <span class="required"> * </span></label>
                                <select class="form-control select2 multiselect" id="lstHousingType" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->HTID; }?>">
  
                                </select>
                                <div class="errors" id="lstHousingType-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="lstBeneficiary"> Beneficiary’s   <span class="required"> * </span></label>
                                <select class="form-control select2" id="lstBeneficiary" data-HPID="<?php if($isEdit==true){ echo $EditData[0]->BID; }?>">
  
                                </select>
                                <div class="errors" id="lstBeneficiary-err"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 d-none">
                            <div class="form-group">
                                <label class="txtApprovedHousingType"> Active Status</label>
                                <select class="form-control" id="lstActiveStatus">
                                    <option value="1" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="1") selected @endif @endif >Active</option>
                                    <option value="0" @if($isEdit==true) @if($EditData[0]->ActiveStatus=="0") selected @endif @endif>Inactive</option>
                                </select>
                                <div class="errors" id="txtApprovedHousingType-err"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            @if($crud['view']==true)
                            <a href="{{url('/')}}/master/ApprovedHousingType/" class="btn btn-sm btn-outline-dark" id="btnCancel">Back</a>
                            @endif
                            
                            @if((($crud['add']==true) && ($isEdit==false))||(($crud['edit']==true) && ($isEdit==true)))
                                <button class="btn btn-sm btn-outline-success" id="btnSave">@if($isEdit==true) Update @else Save @endif</button>
                            @endif
                        </div>

                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){
        const formValidation = () => {
    $('.errors').html('');
    let status = true;
    let lstHousingType = $('#lstHousingType').val();
    let lstBeneficiary = $('#lstBeneficiary').val();
    // alert(lstBeneficiary);
    if (lstHousingType == "") {
        $('#lstHousingType-err').html('The Housing Type name is required.');
        status = false;
    }
    if (lstBeneficiary == "") {
        $('#lstBeneficiary-err').html('The Beneficiary name is required.');
        status = false;
    }
    
    return status;
}

        const gethp=async()=>{
            let editHPID=$('#lstHousingType').attr('data-HPID');
			$('#lstHousingType').select2('destroy');
            $('#lstHousingType option').remove();
			$('#lstHousingType').append('<option value="">Select a HOUSING TYPE</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/housingphase/gethp",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.HID==editHPID){selected="selected";}
						$('#lstHousingType').append('<option '+selected+'  value="'+item.HID+'">'+item.htype+'</option>');
					}
				}
			});
			$('#lstHousingType').select2();
        }
        const getBen=async()=>{
            let editHPID=$('#lstBeneficiary').attr('data-HPID');
			$('#lstBeneficiary').select2('destroy');
            $('#lstBeneficiary option').remove();
			$('#lstBeneficiary').append('<option value="">Select a Beneficiary</option>');
			$.ajax({
				type:"post",
				url:"{{url('/')}}/master/housingphase/getben",
				headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
				success:function(response){
					for(item of response){
						let selected="";
						if(item.BID==editHPID){selected="selected";}
						$('#lstBeneficiary').append('<option '+selected+'  value="'+item.BID+'">'+item.Name+'</option>');
					}
				}
			});
			$('#lstBeneficiary').select2();
        }
        $('#btnSave').click(function(){
            let status=formValidation();
            if(status){
                swal({
                    title: "Are you sure?",
                    text: "You want @if($isEdit==true)Update @else Save @endif this Approved Housing Type!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-outline-primary",
                    confirmButtonText: "Yes, @if($isEdit==true)Update @else Save @endif it!",
                    closeOnConfirm: false
                },function(){
                    swal.close();
                    btnLoading($('#btnSave'));
                    let postUrl="{{url('/')}}/master/ApprovedHousingType/create";
                    let formData=new FormData();
                   
                    formData.append('HID',$('#lstHousingType').val());
                    formData.append('BID',$('#lstBeneficiary').val());
                    formData.append('ActiveStatus',$('#lstActiveStatus').val());
                    
                    @if($isEdit==true)
                        formData.append('APHTID',"{{$EditData[0]->APHTID}}");
                        postUrl="{{url('/')}}/master/ApprovedHousingType/edit/{{$EditData[0]->APHTID}}";
                    @endif
                    $.ajax({
                        type:"post",
                        url:postUrl,
                        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                        data:formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded / evt.total) * 100;
                                    percentComplete=parseFloat(percentComplete).toFixed(2);
                                    $('#divProcessText').html(percentComplete+'% Completed.<br> Please wait for until upload process complete.');
                                    //Do something with upload progress here
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            ajaxindicatorstart("Please wait Upload Process on going.");

                            var percentVal = '0%';
                            setTimeout(() => {
                            $('#divProcessText').html(percentVal+' Completed.<br> Please wait for until upload process complete.');
                            }, 100);
                        },
                        error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                        complete: function(e, x, settings, exception){btnReset($('#btnSave'));ajaxindicatorstop();},
                        success:function(response){
                            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                            if(response.status==true){
                                swal({
                                    title: "SUCCESS",
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-outline-primary",
                                    confirmButtonText: "Okay",
                                    closeOnConfirm: false
                                },function(){
                                    @if($isEdit==true)
                                        window.location.replace("{{url('/')}}/master/ApprovedHousingType");
                                    @else
                                        window.location.reload();
                                    @endif
                                    
                                });
                                
                            }else{
                                toastr.error(response.message, "Failed", {
                                    positionClass: "toast-top-right",
                                    containerId: "toast-top-right",
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    progressBar: !0
                                })
                                if(response['errors']!=undefined){
                                    $('.errors').html('');
                                    $.each( response['errors'], function( KeyName, KeyValue ) {
                                        var key=KeyName;
                                        if(key=="HID"){$('#lstHousingType-err').html(KeyValue);}
                                        if(key=="BID"){$('#lstBeneficiary-err').html(KeyValue);}
                                    });
                                }
                            }
                        }
                    });
                });
            }
        });
        gethp();
        getBen();
    });
</script>
@endsection
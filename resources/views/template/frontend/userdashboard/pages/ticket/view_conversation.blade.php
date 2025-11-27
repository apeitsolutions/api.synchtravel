<?php // dd($ticket,$conversation,$client); ?>
@extends('template/frontend/userdashboard/layout/default')
@section('content')



<style>
  /* Hide the file input */
  input[type="file"] {
    display: none;
  }

  /* Style the icon */
  .custom-file-input {
    display: inline-block;
    cursor: pointer;
  }

  /* Optional: Add some styling to the icon */
  .uil-paperclip {
    font-size: 24px; /* Adjust the size as needed */
    color: #007BFF; /* Change the color as needed */
  }
</style>

@if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700"> {{session()->get('message')}}</span>
            </div>
            <div>
                <button type="button" @click="show = false" class=" text-yellow-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif
<div class="row mt-5 mb-5">
                            <!-- start chat users-->
                            <div class="col-xxl-3 col-xl-6 order-xl-1">
                               <div class="card">
                                    <div class="card-body">
                                        

                                        <div class="mt-3 text-center">
                                            <img src="{{asset('public/admin_package/assets/images/users/avatar-5.jpg')}}" alt="shreyu" class="img-thumbnail avatar-lg rounded-circle">
                                            <h4>{{$client->company_name ?? ''}}</h4>
                                            <!--<button class="btn btn-primary btn-sm mt-1"><i class="uil uil-envelope-add me-1"></i>Send Email</button>-->
                                            <p class="text-muted mt-2 font-14">Last Interacted: <strong>Few hours back</strong></p>
                                        </div>

                                       
                                    </div> <!-- end card-body -->
                                </div>
                            </div>
                            <!-- end chat users-->
                            <!-- chat area -->
                            <div class="col-xxl-6 col-xl-12 order-xl-2">
                                <div class="card">
                                    <div class="card-body px-0 pb-0">
                                        <ul class="conversation-list px-3" data-simplebar="init" style="max-height: 538px">
                                            <div class="simplebar-wrapper" style="margin: 0px -24px;">
                                            <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer">
                                                
                                            </div>
                                            </div>
                                            <div class="simplebar-mask">
                                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px 24px;">
                                            <div id="demo"></div>
                                           
                                           
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: auto; height: 872px;">
                                            
                                        </div>
                                        </div>
                                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                            <div class="simplebar-scrollbar" style="width: 0px; display: none;">
                                                
                                            </div>
                                            </div>
                                            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                                <div class="simplebar-scrollbar" style="height: 331px; transform: translate3d(0px, 0px, 0px); display: block;">
                                                    
                                                </div>
                                                </div>
                                                </ul>

                                        <div class="row px-3 pb-3">
                                            <div class="col">
                                                <?php if(isset($ticket->status)){if($ticket->status != 'Resoloved'){?>
                                                <div class="mt-2 bg-light p-3 rounded">
                                                    
                                                    <form class="needs-validation" action="{{ URL::to('super_admin/conversation/submit',[$ticket->id]) }}" id="formId" enctype="multipart/form-data">
                                                       @csrf
                                                       
                                                       
                                                        <div class="row">
                                                            <div class="col mb-2 mb-sm-0">
                                                                <input type="file" name="getFilesupload" id="fileInput" onchange="displayFileName()" />
                                                                <input type="text" id="message_type_empty"  name="message_type" class="form-control border-0" required placeholder="Enter your text">
                                                                <div class="invalid-feedback">
                                                                    Please enter your messsage
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-sm-auto">
                                                                <div class="btn-group">
                                                                   <label for="fileInput" class="custom-file-input">
                                                                      <i class="uil uil-paperclip"></i>
                                                                    </label>
                                                                    
                                                                    <!-- Add the hidden file input -->
                                                                    <input type="file" name="file_type_2" id="fileInput1" />
                                                                    <!--<a href="#" class="btn btn-light"><i class="uil uil-paperclip"></i></a>-->
                                                                    <a href="#" class="btn btn-light"> <i class="uil uil-smile"></i> </a>
                                                                    <div class="d-grid">
                                                                        <button type="button" id="OnSubmitForm" class="btn btn-success chat-send"><i class="uil uil-message"></i></button>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end col -->
                                                            
                                                        </div> <!-- end row-->
                                                    </form>
                                                   
                                                </div>
                                                 <?php
                                                            }
                                                     
                                                            }
                                                            ?>
                                            </div> <!-- end col-->
                                        </div>
                                        <!-- end row -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card -->
                            </div>
                            <!-- end chat area-->

                            <!-- start user detail -->
                            <div class="col-xxl-3 col-xl-6 order-xl-1 order-xxl-2">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <form action="{{URL::to('super_admin/updated_status_ticket',[$ticket->id])}}" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-7">
                                                <label for="emailaddress" class="form-label">Status</label>
                                                    <select name="status" class="form-control">
                                                    <option <?php if(isset($ticket->status)){if($ticket->status == 'Pending') {echo 'selected';}} ?> value="Pending">Pending</option>
                                                    <option <?php if(isset($ticket->status)){if($ticket->status == 'In-Process') {echo 'selected';}} ?> value="In-Process">In-Process</option>
                                                    <option <?php if(isset($ticket->status)){if($ticket->status == 'Resoloved') {echo 'selected';}} ?> value="Resoloved">Resoloved</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-5 mt-3">
                                                    <?php if(isset($ticket->status)){if($ticket->status != 'Resoloved'){?>
                                                     <button name="submit" class="btn btn-primary mt-1" type="submit">submit</button>
                                                     <?php
                                                     }
                                                     else
                                                     {
                                                     ?>
                                                     <button class="btn mt-1" style="background-color:#727cf559;" type="button">submit</button>
                                                     <?php
                                                     }
                                                     }
                                                     ?>
                                                </div>
                                            </div>
                                        </form>
                                        

                                        <div class="mt-3">
                                            

                                            <p class="mt-4 mb-1"><strong><i class="uil uil-at"></i> Email:</strong> {{$client->email ?? ''}}</p>
                                           

                                            <p class="mt-3 mb-1"><strong><i class="uil uil-phone"></i> Phone Number:</strong>{{$client->phone ?? ''}}</p>
                                            

                                            <!--<p class="mt-3 mb-1"><strong><i class="uil uil-location"></i> Location:</strong>{{$client->email ?? ''}}</p>-->
                                           

                                            
                                            

                                            
                                            
                                        </div>
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                            <!-- end user detail -->
                        </div>
    
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
  function displayFileName() {
    var fileInput = document.getElementById('fileInput');
    var fileNameDisplay = document.getElementById('message_type_empty');

    if (fileInput.files.length > 0) {
      fileNameDisplay.value = fileInput.files[0].name;
    } else {
      fileNameDisplay.value = ''; // Clear the value if no file is selected
    }
  }
</script>
 <script>
    var ticket = {!!json_encode($ticket)!!};
    
    function allConversationGet(){
      $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                }
                            });
                            let text = "";
                           var ticket_id='<?php echo $ticket->id;  ?>';
                             var customer_name='<?php echo $ticket->company_name; ?>';
                            $.ajax({
                                url: "{{ url('super_admin/view_all_conversation') }}",
                                method: 'get',
                                data: {
                                    "ticket_id": ticket_id,
                                    "customer_name": customer_name,
                                },
                                success: function(result){
                                    if(ticket.subject && ticket.subject != null && ticket.subject != ''){
                                        
                                        var dateString      = ticket.created_at;
                                        var dateTime        = new Date(dateString);
                                        var hours           = dateTime.getHours();
                                        var minutes         = dateTime.getMinutes();
                                        var seconds         = dateTime.getSeconds();
                                        var getTime         = hours+ ':'+ minutes;
                                        
                                        text                += `<li class="clearfix">
                                                                    <div class="chat-avatar">
                                                                        <img src="{{asset('public/admin_package/assets/images/users/avatar-5.jpg')}}" class="rounded" alt="Shreyu N">
                                                                        <i>${getTime}</i>
                                                                    </div>
                                                                    <div class="conversation-text">
                                                                        <div class="ctext-wrap">
                                                                            <i>${customer_name}</i>
                                                                            <p>${ticket.subject} <br> <b>(DESCRIPTION)</b> </p>
                                                                        </div>
                                                                    </div>
                                                                </li>`;
                                    }
                                    
                                     console.log(result);
                                     result.forEach(myConversation);
                                     document.getElementById("demo").innerHTML = text;
                                     function myConversation(item, index) {
                                    //   text += index + ": " + item.message + "<br>";
                                     
                                      if(item.message_sent == 'client'){
                                           var dateString = item.created_at;
                                                var dateTime = new Date(dateString);
                                                
                                                var hours = dateTime.getHours();
                                                var minutes = dateTime.getMinutes();
                                                var seconds = dateTime.getSeconds();
                                                var getTime=hours+ ':'+ minutes;
                                                console.log("Time: " + hours + ":" + minutes + ":" + seconds);
                                          
                                          
                                         text += `<li class="clearfix">
                                                <div class="chat-avatar">
                                                    <img src="{{asset('public/admin_package/assets/images/users/avatar-5.jpg')}}" class="rounded" alt="Shreyu N">
                                                    <i>
                                                        ${getTime}
                                                    </i>
                                                </div>
                                                <div class="conversation-text">
                                                    <div class="ctext-wrap">
                                                        <i>${customer_name}</i>
                                                        <p>
                                                            ${item.message}
                                                        </p>
                                                    </div>
                                                     `;
                                                if(item.uuid){
                                                    var temp=item.message;
                                                    var parts = temp.split('.');
                                                  text +=` <div class="card mt-2 mb-1 shadow-none border text-start">
                                                        <div class="p-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded">
                                                                            .${parts[1]}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col ps-0">
                                                                    <a href="javascript:void(0);" class="text-muted fw-bold">${item.message}</a>
                                                                  
                                                                </div>
                                                                <div class="col-auto">
                                                                    <!-- Button -->
                                                                    
                                                                    <a href="https://system.alhijaztours.net/super_admin/downloadfileclient/${item.uuid}" target="_blank" class="btn btn-link btn-lg text-muted">
                                                                        <i class="dripicons-download"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                                    
                                                }
                                                    
                                                    
                                               text +=`</div>
                                               
                                            </li>`;
                                      }
                                      if(item.message_sent == 'Admin'){
                                           var dateString = item.created_at;
                                                var dateTime = new Date(dateString);
                                                
                                                var hours = dateTime.getHours();
                                                var minutes = dateTime.getMinutes();
                                                var seconds = dateTime.getSeconds();
                                                var getTime=hours+ ':' + minutes;
                                                console.log("Time: " + hours + ":" + minutes + ":" + seconds);
                                           text +=`<li class="clearfix odd">
                                                <div class="chat-avatar">
                                                    <img src="{{asset('public/admin_package/assets/images/users/avatar-1.jpg')}}" class="rounded" alt="dominic">
                                                    <i>
                                                     ${getTime}
                                                        </i>
                                                </div>
                                                <div class="conversation-text">
                                                    <div class="ctext-wrap">
                                                        <i>Me</i>
                                                        <p>
                                                           ${item.message}
                                                        </p>
                                                    </div>
                                                    
                                                `;
                                                if(item.uuid){
                                                    var temp=item.message;
                                                    var parts = temp.split('.');
                                                  text +=` <div class="card mt-2 mb-1 shadow-none border text-start">
                                                        <div class="p-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-auto">
                                                                    <div class="avatar-sm">
                                                                        <span class="avatar-title rounded">
                                                                            .${parts[1]}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col ps-0">
                                                                    <a href="javascript:void(0);" class="text-muted fw-bold">${item.message}</a>
                                                                  
                                                                </div>
                                                                <div class="col-auto">
                                                                    <!-- Button -->
                                                                    
                                                                    <a href="{{URL::to('super_admin/downloadfile/${item.uuid}')}}" target="_blank" class="btn btn-link btn-lg text-muted">
                                                                        <i class="dripicons-download"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                                    
                                                }
                                                    
                                                    
                                               text +=` </div>
                                                
                                            </li>`;
                                      }
                                      
                                    }
                                },
                                error:function(error){
                                    console.log(error);
                                }
                            });  
    }
      $(document).ready(function () {

    allConversationGet();

});  
</script>
<script>
$(document).ready(function () {
$("#OnSubmitForm").click(function(ev) {

    GetDataFun();
});
});                
    function GetDataFun(){
        
               
                    var form    = $("#formId");
                    var url     = form.attr('action');
                    var formData = new FormData(form[0]);
                    $.ajax({
                        type    : "post",
                        url     : url,
                        data    : formData,
                        contentType: false,
                         processData: false,
                        success : function(data) {
                           if(data.message == 'success')
                            {
                                allConversationGet();
                              $('#message_type_empty').val('');
                              console.log(data);
                            } 
              
                        },
                        error   : function(data) {
                            alert("some Error");
                        }
                    });   
    }
</script> 
@endsection

<?php

// dd($ticket);

?>
@extends('template/frontend/userdashboard/layout/default')
 @section('content')

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
    
    
    

<div id="bs-example-modal-lg" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 mb-4">
                    <a href="" class="text-success">
                        <span>Client Detail</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="" method="post" id="myform">
                        @csrf
                  
                    <input type="hidden" name="customer_id" id="customer_id_get">

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Ticket Id</label>
                        <input class="form-control" readonly type="text" id="ticket_id" name="ticket_id" value=""> 
                            </div>
                            <input type="hidden"  value=""  id="agent_id"  name="agent_id" />
                            <div class="col-md-6">
                                 <label for="emailaddress" class="form-label">Company Name</label>
                        <input class="form-control" readonly type="text" id="company_name" name="company_name">
                            </div>
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Email</label>
                        <input class="form-control" readonly type="email" id="email" name="email"> 
                            </div>
                            <div class="col-md-6">
                                 <label for="emailaddress" class="form-label">Client</label>
                        <input class="form-control" type="text" readonly id="umrah_operator" name="customer_name">
                            </div>
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            
                            <div class="col-md-12">
                                 <label for="emailaddress" class="form-label">phone</label>
                        <input class="form-control" readonly type="text" id="phone" name="phone">
                            </div>
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Ticket Type</label>
                        <input class="form-control" readonly type="text" id="ticket_type" name="ticket_type"> 
                            </div>
                            <div class="col-md-6">
                                 <label for="emailaddress" class="form-label">Subject</label>
                        <input class="form-control" readonly type="text" id="subject" name="subject">
                            </div>
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Ticket Priorty</label>
                        <input class="form-control" readonly type="text" id="ticket_priorty" name="ticket_priorty"> 
                            </div>
                            <div class="col-md-4">
                                 <label for="emailaddress" class="form-label">Additinal Email</label>
                        <input class="form-control" readonly type="text" id="additinal_email" name="additinal_email">
                            </div>
                            <div class="col-md-4">
                                 <label for="emailaddress" class="form-label">Status</label>
                                <select name="status"  id="StatusSlc" class="form-control">
                            
                                    <option value="Pending">Pending</option>
                                    <option value="In-Process">In-Process</option>
                                    <option value="Resoloved">Resoloved</option>
                                </select>
                            </div>
                        </div>
                       
                    </div>
                     
                    
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label" id='clientLable'></label>
                      <textarea cols="20" class="form-control"  value="" readonly="" id="add_description"  name="add_comment" ></textarea>
                            </div>
                            
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                           
                            
                            <div class="col-md-12">
                                <div class="">
                        <h5 class="mb-5" style="font-weight: bold;">Admin Disscussion</h5>
                       
                    </div>
                                 <label for="emailaddress" class="form-label">Add Discussion</label>
                         <textarea cols="20" class="form-control"  value=""  name="add_comment" ></textarea>
                            </div>
                        </div>
                       
                    </div>

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn" style="background-color:#727cf559;"  type="disable">submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    
    


 











    <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Support Ticket</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                        <th class="" style="text-align: center;">ID</th>
                            <th class="" style="text-align: center;">Ticket ID</th>
                            <th class="" style="text-align: center;">Company Name</th>
                            
                           
                            
                              <th class="" style="text-align: center;">Ticket Type</th>
                              <th class="" style="text-align: center;">Ticket Priorty</th>
                               <th class="" style="text-align: center;">Ticket Status</th>
                                <th class="" style="text-align: center;">Ticket Date</th>
                              <th class="" style="text-align: center;">Details</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody style="text-align: center;">
                                                    @foreach($ticket as $ticketData)
                                                    
                                                    
                            <tr>
                                <td class="">{{$ticketData->id}}</td>
                                <td class="">{{$ticketData->ticket_id}}</td>
                                <td class="">{{$ticketData->company_name}} </td>
                               
                                
                               
                                <td class="">{{$ticketData->ticket_type}}</td>
                                <td class="">{{$ticketData->ticket_priorty}}</td>
                                <?php
                                
                                $dateString = $ticketData->created_at;
                                $dateTime = new DateTime($dateString);
                                
                                // Format the date as per your requirement
                                $formattedDate = $dateTime->format('d-m-Y');
                                ?>
                               
                                <td class="">{{$ticketData->status}}</td>
                                 <td class="">{{$formattedDate}}</td>
                                <td>
                                    
                                    
                                    <a href="{{URL::to('super_admin/conversation')}}/{{$ticketData->customer_name ?? $ticketData->company_name}}/{{$ticketData->id}}" target="_blank" class="btn btn-primary">
                                        <?php 
                                            $unread_message=count($ticketData->conversation_unread_message);
                                                   
                                                    ?>
                                                    <?php
                                                    if($unread_message != 0)
                                                    {
                                                    ?> 
                                                    <span class="badge bg-danger float-end" style="margin-top: 3px;margin-left: 4px;">{{$unread_message}} </span>
                                                    <?php    
                                                    }
                                                    ?>
                                        
                                        View Conversation 
                                    
                                    </a>
                                   
                                </td>
                                
                               
                               

                            </tr>

@endforeach

                                                    </tbody>
                                                </table>                                           
                                            </div> <!-- end preview-->
                                        
                                            
                                        </div> <!-- end tab-content-->
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
            



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

 <script type="text/javascript">
    $(document).ready(function(){
            $('.openModal').click(function(){
    var id = $(this).attr('data-id');
    var ticket_id = $(this).attr('data-ticket_id');
//   alert(ticket_id);
    $('#ticket_id').val(ticket_id);

    var title = $(this).attr('data-title');
    $('#title').val(title);
    var data_company_name = $(this).attr('data-company_name');
  console.log('data_company_name'+data_company_name);
    $('#company_name').val(data_company_name);
    $('#company_name_1').html(data_company_name);

var data_umrah_operator = $(this).attr('data-umrah-operator');



console.log('data_umrah_operator'+data_umrah_operator);

    $('#umrah_operator').val(data_umrah_operator);
    
 $('#clientLable').text('Client'+ '-' +data_umrah_operator);
 
    var data_email = $(this).attr('data-email');
    $('#email').val(data_email);

    var data_email = $(this).attr('data-email');
    $('#email').val(data_email);

    var data_phone = $(this).attr('data-phone');
    $('#phone').val(data_phone);

    var data_ticket_type = $(this).attr('data-ticket_type');
    $('#ticket_type').val(data_ticket_type);

    var data_ticket_priorty = $(this).attr('data-ticket_priorty');
    $('#ticket_priorty').val(data_ticket_priorty);

    var data_subject = $(this).attr('data-subject');
    $('#subject').val(data_subject);

    var data_description = $(this).attr('data-description');
    $('#add_description').text(data_description);

var data_additinal_email = $(this).attr('data-additinal_email');
    $('#additinal_email').val(data_additinal_email);
     var data_ticket_id = $(this).attr('data-ticket_id');
    $('#ticket_id').val(data_ticket_id);

var data_diss_detail = $(this).attr('data-diss_detail');
    $('#diss_detail').val(data_diss_detail);
 
 
var data_status = $(this).attr('data-status');
$('#StatusSlc').val(data_status);
 
 var data_customer_id = $(this).attr('data-customer-id');
   $('#customer_id_get').val(data_customer_id);
    $("#myform").attr('action', '{{URL::to("super_admin/ticket_view/submit/")}}' + '/' + id);
    $('#bs-example-modal-lg').modal('toggle');
});

            });
        </script>















<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example_1').DataTable({
           
           "order": [[ 0, 'desc' ], [ 1, 'desc' ]] 
        });
    } );
</script>

 
    @endsection
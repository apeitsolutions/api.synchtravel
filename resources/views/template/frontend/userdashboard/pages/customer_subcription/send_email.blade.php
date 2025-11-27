@extends('template/frontend/userdashboard/layout/default')

@section('content')

@if(session()->has('success'))

<div x-data="{ show: true }" x-show="show"

     class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">

    <div>

        <span class="font-semibold text-yellow-700"> {{session()->get('success')}}</span>

    </div>

    <div>

        <button type="button" @click="show = false" class=" text-yellow-700">

            <span class="text-2xl">&times;</span>

        </button>

    </div>

</div>

@endif

<div class="mt-5" >

   

    	<div class="card">

            <div class="card-header">

                <h2 class="font-medium">Send Email</h2>

            </div>

            <!-- <div class="sabb">

                <a class="button inline-block bg-theme-1 text-white" href="{{--url('super_admin/b2bagents')--}}">

                    Back 

                </a>

            </div> -->   

       

 
 <div class="card-body">
	<form class="ps-3 pe-3"  method="post" action="{{url('super_admin/send_mail')}}"> 

	    @csrf
	    
	    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Select One Or More Company:</label>
                        <select class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" name="agent_email[]" data-placeholder="Choose ...">
		   

		     	@foreach($data as $key =>$value)

			     	<option value="{{$value->email ?? ''}}">{{$value->company_name ?? ''}}</option>

		     	@endforeach

		 	</select> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Email Subject:</label>
                        <input type="text" class="form-control" name="subject">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Select Email Template :</label>
                       	<select name="transfer_dropdown" id="transfer_dropdown" class="form-control">

	    		<option value="">Select Email Template</option>

	     		<option id="Welcome" value="Welcome" >Welcome</option>

	     		<option id="Sales" value="Sales">Sales</option>

	     		<option id="Marketting" value="Marketting" >Marketting</option>

	     		<option id="Custom" value="Custom">Custom</option>

	 		</select>	
                            </div>
                            
                            
                        </div>
                       
                    </div>

	   
	    
	
	
	
	
		
		<div class="">
			<button type="submit" name="submit" class="btn btn-info">Send Email</button>	
		</div>
	</form>
</div>
</div>
   </div>
<script>

	$(document).ready(function(){
		var find_expected_class = $('body').find('.note-editable');
		find_expected_class.css("height","500px"); 
	});

	$('.emte').hide();

	$(".selectpicker").change(function () {

		var id = $(this).find('option:selected').attr('id');
		if(id == 'Welcome'){

			$('.emte').fadeOut();
			$('#welcome_editor').fadeIn();
		}else if(id == 'Marketting'){

			$('.emte').fadeOut();
			$('#marketting_editor').fadeIn();

		}else if(id == 'Sales'){

			$('.emte').fadeOut();
			$('#sales_editor').fadeIn();
		}else if(id == 'Custom'){

			$('.emte').fadeOut();
			$('#custom_editor').fadeIn();
		}else{
			$('.emte').fadeOut();
		}
	});

</script>

@endsection
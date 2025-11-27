@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php $currency=Session::get('currency_symbol');?>
    
    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
    </style>
  
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Subscription</a></li>
                                    <li class="breadcrumb-item active">View Customer Subscription</li>
                                </ol>
                            </div>
                            <h4 class="page-title">View Customer Subscription</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    
                    <div class="col-md-12">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ session('error') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <table class="table nowrap table-striped table-vcenter dataTable no-footer" id="my_Table" role="grid" aria-describedby="example_info">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="text-align: center;">Sr No.</th>
                                                    <th style="text-align: center;">Name</th>
                                                    <th style="text-align: center;">Token</th>
                                                    <th style="text-align: center;">Balance</th>
                                                    <th style="text-align: center;">Email</th>
                                                    <th style="text-align: center;">Company Name</th>
                                                    <th style="text-align: center;">Country</th>
                                                    <th style="text-align: center;">Company Logo</th>
                                                    <th style="text-align: center;">Action</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                <?php $i = 1; ?>
                                                @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                    @foreach($all_Users as $all_Users_value)
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $all_Users_value->name }}</td>
                                                            <td>
                                                                {{ Str::limit($all_Users_value->Auth_key, 15) }}
                                                                <br>
                                                                <button class="btn btn-primary btn-sm" onclick="copyToClipboard('{{ $all_Users_value->Auth_key }}')">Copy</button>
                                                            </td>
                                                            <td>{{ $all_Users_value->balance }}</td>
                                                            <td>{{ $all_Users_value->email }}</td>
                                                            <td>{{ $all_Users_value->company_name }}</td>
                                                            <td>
                                                                @foreach($all_countries as $all_countries_value)
                                                                    @if($all_countries_value->id == $all_Users_value->country)
                                                                        {{ $all_countries_value->name }}
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                @if(isset($all_Users_value->company_logo) && $all_Users_value->company_logo != null && $all_Users_value->company_logo != '')
                                                                    <img src="{{ config('img_url_Admin') }}/public/images/customerSubcription/{{ $all_Users_value->company_logo }}" style="height:100px;width:150px">
                                                                @else
                                                                    <img src="{{ config('img_url_Admin') }}/public/images/customerSubcription/no_img_found.jpg" style="height:100px;width:150px">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ URL::to('super_admin/subcirbed_customer_ledger/'.$all_Users_value->id.'') }}" class="btn btn-success btn-sm">ledger</a>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div> 
                    </div>
                    
                </div>
            </div>
        </section>
    </div>

@endsection

@section('scripts')

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->

    <script>
        $(document).ready(function(){
            $('#my_Table').DataTable({
                pagingType: 'full_numbers',
            });
        });
        
        function copyToClipboard(token) {
            var tempInput   = document.createElement("input");
            tempInput.value = token;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Token copied to clipboard!");
        }
    </script>

@stop

@section('slug')

@stop
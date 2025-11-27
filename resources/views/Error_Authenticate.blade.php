<div class="row" style="text-align: center;font-size: 50px;padding-top: 250px;">
    <div class="col-lg-12">
        <p>{{ $Error_Message ?? '' }}</p>
    </div>
</div>

@section('scripts')
        
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" ></script>
    
    <script>
        var Error_Message = {!!json_encode($Error_Message)!!};
        alert(Error_Message);
    </script>
  
@endsection

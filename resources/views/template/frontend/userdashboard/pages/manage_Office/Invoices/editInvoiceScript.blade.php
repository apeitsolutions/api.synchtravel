@section('edit_Invoice')

    <script>
        
        $('#transportation_no_of_vehicle').keyup(function() {
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            $('#vehicle_per_markup_value').val('');
            $('#markup_price_per_vehicle_converted').val('');
            
            var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle').val();
            var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle').val();
            var no_of_pax_days                      =  $('#no_of_pax_days').val();
            var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
            var t_trans                             =  t_trans1.toFixed(2);
            $('#transportation_vehicle_total_price').val(t_trans);
            var total_trans1    = t_trans/no_of_pax_days;
            var total_trans     = total_trans1.toFixed(2);
            $('#transportation_price_per_person').val(total_trans);
            // $('#transportation_price_per_person_select').val(t_trans);
            
            $('#tranf_no_of_vehicle').val(transportation_no_of_vehicle);
            $('#tranf_price_per_vehicle').val(t_trans);
            $('#tranf_price_all_vehicle').val(total_trans);
            
            var select_exchange_type    = $('#vehicle_select_exchange_type_ID').val();
            var exchange_Rate           = $('#vehicle_exchange_Rate_ID').val();
            if(select_exchange_type == 'Divided'){
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
            }else{
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
            }
            
            without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
            transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
            transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
            
            $('#without_markup_price_converted_destination').val(without_markup_price_converted_destination);
            $('#transportation_vehicle_total_price_converted').val(transportation_vehicle_total_price_converted);
            $('#transportation_price_per_person_converted').val(transportation_price_per_person_converted);
            
            $('#transportation_price_per_person_select').val(transportation_vehicle_total_price_converted);
            
            add_numberElseI();
            
        });
        
        $('#transportation_price_per_vehicle').keyup(function() {
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            $('#vehicle_per_markup_value').val('');
            $('#markup_price_per_vehicle_converted').val('');
            
            var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle').val();
            var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle').val();
            var no_of_pax_days                      =  $('#no_of_pax_days').val();
            var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
            var t_trans                             =  t_trans1.toFixed(2);
            $('#transportation_vehicle_total_price').val(t_trans);
            var total_trans1    = t_trans/no_of_pax_days;
            var total_trans     = total_trans1.toFixed(2);
            $('#transportation_price_per_person').val(total_trans);
            // $('#transportation_price_per_person_select').val(t_trans);
            
            $('#tranf_no_of_vehicle').val(transportation_no_of_vehicle);
            $('#tranf_price_per_vehicle').val(t_trans);
            $('#tranf_price_all_vehicle').val(total_trans);
            
            var select_exchange_type    = $('#vehicle_select_exchange_type_ID').val();
            var exchange_Rate           = $('#vehicle_exchange_Rate_ID').val();
            if(select_exchange_type == 'Divided'){
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
            }else{
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
            }
            
            without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
            transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
            transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
            
            $('#without_markup_price_converted_destination').val(without_markup_price_converted_destination);
            $('#transportation_vehicle_total_price_converted').val(transportation_vehicle_total_price_converted);
            $('#transportation_price_per_person_converted').val(transportation_price_per_person_converted);
            
            $('#transportation_price_per_person_select').val(transportation_vehicle_total_price_converted);
            
            add_numberElseI();
            
        });
    
        $('#return_transportation_price_per_vehicle').keyup(function() {    
            var return_transportation_price_per_vehicle =  $('#return_transportation_price_per_vehicle').val();
            var return_transportation_no_of_vehicle =  $('#return_transportation_no_of_vehicle').val();
            var no_of_pax_days =  $('#no_of_pax_days').val();
            
            var return_t_trans1 = return_transportation_price_per_vehicle * return_transportation_no_of_vehicle;
            var return_t_trans = return_t_trans1.toFixed(2);
            console.log('return_t_trans'+return_t_trans);
            $('#return_transportation_vehicle_total_price').val(return_t_trans)
            var return_total_trans1 = return_t_trans/no_of_pax_days;
            var return_total_trans = return_total_trans1.toFixed(2);
              console.log('return_total_trans'+return_total_trans);
            $('#return_transportation_price_per_person').val(return_total_trans)
            
            // $('#return_transportation_price_per_person_select').val(return_total_trans);
            add_numberElse();
        });
    
        $("#flights_type").on('change',function () {
            var id = $(this).val();
            $('#flights_departure_code').val(id);
        });
        
        $("#departure_Flight_Type").change(function () {
            var id = $(this).val();
            $('#flights_arrival_code').val(id);
        });
      
        $("#markup_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            
            $('#markup_value_markup_mrk').text(id);
            var markup_val =  $('#markup_value').val();
            
            var flights_prices =  $('#flights_prices').val();
            if(id == '%')
            {
                $('#markup_value').keyup(function() {
                    var markup_val =  $('#markup_value').val();
                    var total1 = (flights_prices * markup_val/100) + parseFloat(flights_prices) ;
                    var total = total1.toFixed(2);
                    $('#total_markup').val(total);
                    add_numberElse_1();
                });
            }
            else
            {
                $('#markup_value').keyup(function() {
                    var markup_val =  $('#markup_value').val();
                    console.log(markup_val);
                    var total1 = parseFloat(flights_prices) + parseFloat(markup_val);
                    var total = total1.toFixed(2);
                    $('#total_markup').val(total);
                    add_numberElse_1();
                });
            }
        });
          
        $('#property_city').change(function(){
            var arr=[];
            $('#property_city option:selected').each(function(){
            var $value =$(this).attr('type');
            // console.log($value);
            arr.push($value);
        });
    // console.log(arr);
    var json_data=JSON.stringify(arr);
    $('#tour_location_city').val(json_data); 
    $('#packages_get_city').val(json_data);
     
    }); 
    
        $("#visa_type").change(function () {
            var id = $(this).find('option:selected').attr('attr');
            
            $('#visa_type_select').val(id);
            
            
            });
        
        $("#visa_markup_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            $('#visa_mrk').text(id);
            // var markup_val =  $('#visa_markup').val();
            // var visa_price_select =  $('#visa_price_select').val();
            if(id == '%')
            {
                $('#visa_markup').keyup(function() {
                    var markup_val =  $('#visa_markup').val();
                    var visa_price_select =  $('#visa_price_select').val();
                    var total1 = (visa_price_select * markup_val/100) + parseFloat(visa_price_select);
                    var total = total1.toFixed(2);
                    $('#total_visa_markup').val(total);
                    add_numberElse_1();
                });
            }
            else
            {
                $('#visa_markup').keyup(function() {
                    var markup_val =  $('#visa_markup').val();
                    var visa_price_select =  $('#visa_price_select').val();
                    var total1 =  parseFloat(visa_price_select) +  parseFloat(markup_val);
                    var total = total1.toFixed(2);
                    $('#total_visa_markup').val(total);
                    add_numberElse_1();
                });
            }
        });
            
        $('#visa_fee').keyup(function() {
            
            var visa_fee = this.value;
            $('#visa_price_select').val(visa_fee);
            add_numberElse();
        });
            
        $("#transportation_pick_up_location").on('keyup',function () {
                setTimeout(function() { 
                    var id = $('#transportation_pick_up_location').val();
                    console.log('transportation_pick_up_location :'+id+'');
                    $('#transportation_pick_up_location_select').val(id);
                }, 10000);
            });
            
        $("#transportation_drop_off_location").change(function () {
                setTimeout(function() { 
                    var id = $('#transportation_drop_off_location').val();
                    console.log('transportation_drop_off_location :'+id+'');
                    $('#transportation_drop_off_location_select').val(id);
                }, 10000);
            });
            
        $("#return_transportation_pick_up_location").change(function () {
            var id = this.value;
            
            $('#return_transportation_pick_up_location_select').val(id);
            
            
            });
            
        $("#return_transportation_drop_off_location").change(function () {
            var id = this.value;
            
            $('#return_transportation_drop_off_location_select').val(id);
            
            
            });
            
        $("#transportation_price_per_person").change(function () {
            var id = this.value;
            
            $('#transportation_price_per_person_select').val(id);
            
            
            
            
            });
            
        $("#transportation_markup_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            console.log(id);
            $('#transportation_markup_mrk').text(id);
            if(id == '%'){
                $('#transportation_markup').keyup(function() {
                    var markup_val =  $('#transportation_markup').val();
                    var transportation_price =  $('#transportation_price_per_person_select').val();
                    var total1 = (transportation_price * markup_val/100) + parseFloat(transportation_price);
                    var total = total1.toFixed(2);
                    $('#transportation_markup_total').val(total);
                    
                    $('.transfer_markup_price_invoice').val(total);
                    $('.transfer_markup_type_invoice').val(id);
                    $('.transfer_markup_invoice').val(markup_val);
                    
                    add_numberElse_1I();
                });
            }
            else{
                $('#transportation_markup').keyup(function() {
                    var markup_val =  $('#transportation_markup').val();
                    console.log(markup_val);
                    var transportation_price =  $('#transportation_price_per_person_select').val();
                    var total1 = parseFloat(transportation_price) + parseFloat(markup_val);
                    var total = total1.toFixed(2);
                    $('#transportation_markup_total').val(total);
                    
                    $('.transfer_markup_price_invoice').val(total);
                    $('.transfer_markup_type_invoice').val(id);
                    $('.transfer_markup_invoice').val(markup_val);
                
                    add_numberElse_1I();
                });
            }
        });
      
        $('#flights_per_person_price').keyup(function() {
       
        var flights_per_person_price = this.value;
       $('#flights_prices').val(flights_per_person_price);
        add_numberElse();
     
    });
    
        $("#flights_inc").click(function () {
            $('#flights_cost').toggle();
        });
    
        $("#transportation").click(function () {
       $('#transportation_cost').toggle();
    	
      });
    
        $("#visa_inc").click(function () {
       $('#visa_cost').toggle();
    	
      });
    
        $("#slect_trip").change(function () {
        var id = $(this).find('option:selected').attr('value');
        // alert(id);
        if(id == 'Return')
        {
        $('#add_more_destination').fadeOut();
        	$('#select_return').fadeIn();
        
        }
        else if(id == 'All_Round')
        {
        	$('#select_return').fadeOut();
        	$('#add_more_destination').fadeIn();
        
        }
        else
        {
          	$('#select_return').fadeOut();
          	$('#add_more_destination').fadeOut();
        }
    
    
    
    
    
    
      });
    
        $("#visa_type").change(function () {
        var id = $(this).find('option:selected').attr('value');
    // alert(id);
    if(id == 'Others')
    {
    	$('#SubmitForm_sec').fadeOut();
    	$('#SubmitForm_sec').fadeIn();
    
    }
    
    else
    {
    	$('#SubmitForm_sec').fadeOut();
    }
    
    
    
    
      });
      
    </script>
    
    <script>
        
            function add_numberElseI(){
                var flights_prices=parseFloat($("#flights_prices").val());  
                if(isNaN(flights_prices)) 
                {
                    flights_prices=0;
                }
                else
                {
                    var flights_prices=parseFloat($("#flights_prices").val()); 
                }
                var visa_price_select=parseFloat($("#visa_fee").val());  
                if(isNaN(visa_price_select)) 
                {
                    visa_price_select=0;
                }
                else
                {
                    var visa_price_select=parseFloat($("#visa_fee").val());
                }
          
                var transportation_price_per_person_select=parseFloat($("#transportation_price_per_person").val());
                if(isNaN(transportation_price_per_person_select)) 
                {
                    transportation_price_per_person_select=0;
                }
                else
                {
                    var transportation_price_per_person_select=parseFloat($("#transportation_price_per_person").val());
                }
                
                var count =$("#city_No").val();
                
                // var city_slc =$(".city_slc").val();
                // var count = city_slc.length;
                var quad_hotel=0;
                var triple_hotel=0;
                var double_hotel=0;
                var more_quad_hotel=0;
                var more_triple_hotel=0;
                var more_double_hotel=0;
           
                for(var i=1; i<=5; i++){
                    var hotel_acc_type = $('#hotel_acc_type_'+i+'').val();
                    var hotel_markup = parseFloat($("#hotel_acc_price_"+i+'').val());
                    
                    if(isNaN(hotel_markup)) 
                    {
                        hotel_markup=0;
                    }
                    else
                    {
                        var hotel_markup=parseFloat($("#hotel_acc_price_"+i+'').val());
                    }
                    
                    if(hotel_acc_type == 'Quad')
                    {
                        quad_hotel = quad_hotel + hotel_markup;
                        var quad_hotel1 = quad_hotel.toFixed(2);
                        $('#quad_cost_price').val(quad_hotel1);
                    }
                    if(hotel_acc_type == 'Triple')
                    {
                        triple_hotel = triple_hotel + hotel_markup;
                        var triple_hotel1 = triple_hotel.toFixed(2);
                        $('#triple_cost_price').val(triple_hotel1);
                    }
                    if(hotel_acc_type == 'Double')
                    {
                        double_hotel = double_hotel + hotel_markup;
                        var double_hotel1 = double_hotel.toFixed(2);
                        $('#double_cost_price').val(double_hotel1);
                    }
                           
                }
                
                var sumData = flights_prices + visa_price_select + transportation_price_per_person_select;
                if(quad_hotel != 0){
                    var quadCost = quad_hotel + sumData;
                }else{
                    var quadCost = 0;
                }
                if(triple_hotel != 0){
                    var tripleCost = triple_hotel + sumData;
                }else{
                    var tripleCost = 0;
                }
                if(double_hotel != 0){
                    var doubleCost = double_hotel + sumData;
                }else{
                    var doubleCost = 0;
                }
                quadCost = quadCost.toFixed(2);
                $('#quad_cost_price').val(quadCost);
                tripleCost = tripleCost.toFixed(2);
                $('#triple_cost_price').val(tripleCost);
                doubleCost = doubleCost.toFixed(2);
                $('#double_cost_price').val(doubleCost);
                
                for(var k=1; k<=50; k++){
                    
                    var more_hotel_acc_type=$('#more_hotel_acc_type_'+k+'').val(); 
                    var more_hotel_markup=$('#more_hotel_acc_price_'+k+'').val();  
                    
                    if(isNaN(more_hotel_markup)) 
                    {
                        more_hotel_markup=0;
                    }
                    else
                    {
                        var more_hotel_markup=parseFloat($("#more_hotel_acc_price_"+k+'').val());
                    }
                    if(more_hotel_acc_type == 'Quad')
                    {
                       more_quad_hotel = more_quad_hotel + more_hotel_markup;
                       var more_quad_hotel1 = more_quad_hotel.toFixed(2);
                         $('#quad_cost_price').val(more_quad_hotel1);
                    }
                    if(more_hotel_acc_type == 'Triple')
                    {
                        more_triple_hotel = more_triple_hotel + more_hotel_markup;
                        var more_triple_hotel1 = more_triple_hotel.toFixed(2);
                        $('#triple_cost_price').val(more_triple_hotel1);
                    }
                    if(more_hotel_acc_type == 'Double')
                    {
                       more_double_hotel = more_double_hotel + more_hotel_markup;
                       var more_double_hotel1 = more_double_hotel.toFixed(2);
                        $('#double_cost_price').val(more_double_hotel1);
                    }
                }
                
                var morequadCost = sumData + more_quad_hotel;
                morequadCost = morequadCost.toFixed(2);
                
                if(more_quad_hotel == 0){
                    if(quadCost != 0){
                        $('#quad_cost_price').val(quadCost);   
                    }else{
                        $('#quad_cost_price').val(0);   
                    }
                }else{
                    $('#quad_cost_price').val(morequadCost);   
                }
                
                var moretripleCost = sumData + more_triple_hotel;
                moretripleCost = moretripleCost.toFixed(2);
                
                if(more_triple_hotel == 0){
                    if(tripleCost != 0){
                        $('#triple_cost_price').val(tripleCost);   
                    }else{
                        $('#triple_cost_price').val(0);   
                    }  
                }else{
                    $('#triple_cost_price').val(moretripleCost);   
                }
                
                
                var moredoubleCost = sumData + more_double_hotel;
                moredoubleCost = moredoubleCost.toFixed(2);
                
                if(more_double_hotel == 0){
                    if(doubleCost != 0){
                        //$('#double_cost_price').val(doubleCost);   
                    }else{
                        $('#double_cost_price').val(0);   
                    }   
                }else{
                    //$('#double_cost_price').val(moredoubleCost);
                }
            }
        
            function add_numberElse_1I(){
                var count =$("#city_No").val();
                // var city_slc =$(".city_slc").val();
                // var count = city_slc.length;
                var quad_hotel=0;
                var triple_hotel=0;
                var double_hotel=0;
                var more_quad_hotel=0;
                var more_triple_hotel=0;
                var more_double_hotel=0;
                
                var total_markup = parseFloat($("#total_markup").val());  
                if(isNaN(total_markup)) 
                {
                    total_markup=0;
                }
                else
                {
                    var total_markup=parseFloat($("#total_markup").val()); 
                }
                
                var total_visa_markup = parseFloat($("#total_visa_markup").val());  
                if(isNaN(total_visa_markup)) 
                {
                    total_visa_markup=0;
                }
                else
                {
                    var total_visa_markup = parseFloat($("#total_visa_markup").val());
                }
                
                var transportation_markup_total=parseFloat($("#transportation_markup_total").val());
                if(isNaN(transportation_markup_total)) 
                {
                    transportation_markup_total=0;
                }
                else
                {
                    var transportation_markup_total=parseFloat($("#transportation_markup_total").val());
                }
               
                for(var i=1; i<=10; i++){
                    
                    var hotel_acc_type=$('#hotel_acc_type_'+i+'').val();
                    var hotel_markup=parseFloat($("#hotel_markup_total_"+i+'').val());
                    
                    if(isNaN(hotel_markup)) 
                    { 
                        hotel_markup=0;
                    }
                    else
                    {
                        // console.log("hotel_markup : " + hotel_markup);
                        var hotel_markup = parseFloat($("#hotel_markup_total_"+i+'').val());
                    }
                    
                    if(hotel_acc_type == 'Quad')
                    {
                        quad_hotel      = quad_hotel + hotel_markup + more_quad_hotel;
                        var quad_hotel1 = quad_hotel.toFixed(2);
                        $('#quad_grand_total_amount').val(quad_hotel1);
                    }
                    if(hotel_acc_type == 'Triple')
                    {
                        triple_hotel        = triple_hotel  +hotel_markup + more_triple_hotel;
                        var triple_hotel1   = triple_hotel.toFixed(2);
                        $('#triple_grand_total_amount').val(triple_hotel1);
                    }
                    if(hotel_acc_type == 'Double')
                    {
                        double_hotel        = double_hotel + hotel_markup  + more_double_hotel;
                        var double_hotel1   = double_hotel.toFixed(2);
                        $('#double_grand_total_amount').val(double_hotel1);
                    }
                }
                
                var sumData = total_markup + total_visa_markup + transportation_markup_total;
                $('#without_acc_sale_price').val(sumData);
                
                console.log('sumData : '+sumData);
                
                if(quad_hotel != 0){
                   var quadCost = quad_hotel + sumData;
                }else{
                    var quadCost = 0;
                }
                
                if(triple_hotel != 0){
                    var tripleCost = triple_hotel + sumData;
                }else{
                    var tripleCost = 0;
                }
                
                if(double_hotel != 0){
                    var doubleCost = double_hotel + sumData;
                }else{
                    var doubleCost = 0;
                }
                
                quadCost = quadCost.toFixed(2);
                $('#quad_grand_total_amount').val(quadCost);
                tripleCost = tripleCost.toFixed(2);
                $('#triple_grand_total_amount').val(tripleCost);
                doubleCost = doubleCost.toFixed(2);
                $('#double_grand_total_amount').val(doubleCost);
                
                for(var k=1; k<=20; k++){
                    var more_hotel_acc_type = $('#more_hotel_acc_type_'+k+'').val(); 
                    var more_hotel_markup   = $('#more_hotel_markup_total_'+k+'').val();
                    if(isNaN(more_hotel_markup)) 
                    {
                        more_hotel_markup=0;
                    }
                    else
                    {
                        var more_hotel_markup=parseFloat($("#more_hotel_markup_total_"+k+'').val());
                        // $('#more_hotel_invoice_markup_'+k+'').val(more_hotel_markup);
                    }
                    if(more_hotel_acc_type == 'Quad')
                    {
                        more_quad_hotel         = more_quad_hotel + more_hotel_markup;
                        var more_quad_hotel1    = 0;
                        more_quad_hotel1        = more_quad_hotel.toFixed(2);
                        $('#quad_grand_total_amount').val(more_quad_hotel1);
                    }
                    if(more_hotel_acc_type == 'Triple')
                    {
                        more_triple_hotel       = more_triple_hotel + more_hotel_markup;
                        var more_triple_hotel1  = 0;
                        more_triple_hotel1      = more_triple_hotel.toFixed(2);
                        $('#triple_grand_total_amount').val(more_triple_hotel1);
                    }
                    if(more_hotel_acc_type == 'Double')
                    {
                        more_double_hotel       = more_double_hotel +more_hotel_markup;
                        var more_double_hotel1  = 0;
                        more_double_hotel1      = more_double_hotel.toFixed(2);
                        $('#double_grand_total_amount').val(more_double_hotel1);
                    }
                }
            
                var morequadCost = sumData + more_quad_hotel;
                morequadCost = morequadCost.toFixed(2);
                
                if(more_quad_hotel == 0){
                    if(quadCost != 0){
                        $('#quad_grand_total_amount').val(quadCost);   
                    }else{
                        $('#quad_grand_total_amount').val(0);   
                    }   
                }else{
                    $('#quad_grand_total_amount').val(morequadCost);   
                }
                
                var moretripleCost = sumData + more_triple_hotel;
                moretripleCost = moretripleCost.toFixed(2);
                
                if(more_triple_hotel == 0){
                    if(tripleCost != 0){
                        $('#triple_grand_total_amount').val(tripleCost);   
                    }else{
                        $('#triple_grand_total_amount').val(0);   
                    }   
                }else{
                    $('#triple_grand_total_amount').val(moretripleCost);   
                }
                
                var moredoubleCost = sumData + more_double_hotel;
                moredoubleCost = moredoubleCost.toFixed(2);
                
                if(more_double_hotel == 0){
                    if(doubleCost != 0){
                        $('#double_grand_total_amount').val(doubleCost);   
                    }else{
                        $('#double_grand_total_amount').val(0);   
                    }   
                }else{
                    $('#double_grand_total_amount').val(moredoubleCost);
                }
            }
            
        </script>
    
    <script>
    
        var currency_GBP = $('#currency_GBP_for_costing').val();
        $('.currency_value_exchange_2T').html(currency_GBP);
    
        $(".CC_id_store").change(function (){
            var CC_id_store =  $(this).find('option:selected').attr('attr_id');
            $("#conversion_type_Id").val(CC_id_store);
        });
        
        var user_hotels = {!!json_encode($user_hotels)!!};
        
        // Transportation
        $('#transportation_drop_of_date_Old').change(function () {
            
            var h = "hours";
            var m = "minutes";
            
            var transportation_drop_of_date = $(this).val();
            var transportation_pick_up_date = $('#transportation_pick_up_date').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            
            var date1 = new Date(transportation_pick_up_date);
            var date2 = new Date(transportation_drop_of_date);
            var timediff = date2 - date1;
            
            var minutes_Total = Math.floor(timediff / minute);
            
            var total_hours   = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            
            var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#transportation_Time_Div').css('display','');
            $('#transportation_total_Time').val(total_hours+h+ ' : ' +minutes+m);
            
        });
        
        $('#return_transportation_pick_up_date_Old').change(function () {
            
            var h = "hours";
            var m = "minutes";
            
            var return_transportation_pick_up_date = $('#return_transportation_pick_up_date').val();
            var return_transportation_drop_of_date = $('#return_transportation_drop_of_date').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            
            var return_date1 = new Date(return_transportation_pick_up_date);
            var return_date2 = new Date(return_transportation_drop_of_date);
            var return_timediff = return_date2 - return_date1;
            
            var return_minutes_Total = Math.floor(return_timediff / minute);
            
            var return_total_hours   = Math.floor(return_timediff / hour)
            var return_total_hours_minutes = parseFloat(return_total_hours) * 60;
            
            var return_minutes = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
            
            $('#return_transportation_Time_Div').css('display','');
            $('#return_transportation_total_Time').val(return_total_hours+h+ ' : ' +return_minutes+m);
        
        });
        
        $('#return_transportation_drop_of_date_Old').change(function () {
            
            var h = "hours";
            var m = "minutes";
            
            var return_transportation_pick_up_date = $('#return_transportation_pick_up_date').val();
            var return_transportation_drop_of_date = $('#return_transportation_drop_of_date').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            
            var return_date1 = new Date(return_transportation_pick_up_date);
            var return_date2 = new Date(return_transportation_drop_of_date);
            var return_timediff = return_date2 - return_date1;
            
            var return_minutes_Total = Math.floor(return_timediff / minute);
            
            var return_total_hours   = Math.floor(return_timediff / hour)
            var return_total_hours_minutes = parseFloat(return_total_hours) * 60;
            
            var return_minutes = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
            
            $('#return_transportation_Time_Div').css('display','');
            $('#return_transportation_total_Time').val(return_total_hours+h+ ' : ' +return_minutes+m);
        
        });
        
        function addGoogleApi_Old(id){
            var places = new google.maps.places.Autocomplete(
                document.getElementById(id)
            );
            
            google.maps.event.addListener(places, "place_changed", function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = (geocoder = new google.maps.Geocoder());
                geocoder.geocode({ latLng: latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address = results[0].formatted_address;
                            var pin =
                            results[0].address_components[
                        results[0].address_components.length - 1
                      ].long_name;
                            var country =
                              results[0].address_components[
                                results[0].address_components.length - 2
                              ].long_name;
                            var state =
                              results[0].address_components[
                                results[0].address_components.length - 3
                              ].long_name;
                            var city =
                              results[0].address_components[
                                results[0].address_components.length - 4
                              ].long_name;
                            var country_code =
                              results[0].address_components[
                                results[0].address_components.length - 2
                              ].short_name;
                            $('#country').val(country);
                            $('#lat').val(latitude);
                            $('#long').val(longitude);
                            $('#pin').val(pin);
                            $('#city').val(city);
                            $('#country_code').val(country_code);
                            $('#pickup_CountryCode').val(country_code)
                        }
                    }
                });
            });
        }
        
        $("#more_transportationI_Old").click(function(){
            
            var MTD_id = $('#MTD_id_input').val();
            
            var data = `<div class="row" id="click_delete_${MTD_id}">
                            <div class="col-xl-3" style="padding: 10px;">
                                <label for="">Pick-up Location</label>
                                <input type="text" id="more_transportation_pick_up_location_${MTD_id}" name="more_transportation_pick_up_location[]" class="form-control">
                            </div>
                            <div class="col-xl-3" style="padding: 10px;">
                                <label for="">Drop-off Location</label>
                                <input type="text" id="more_transportation_drop_off_location_${MTD_id}" name="more_transportation_drop_off_location[]" class="form-control">
                            </div>
                            <div class="col-xl-3" style="padding: 10px;">
                                <label for="">Pick-up Date & Time</label>
                                <input type="datetime-local" id="more_transportation_pick_up_date_${MTD_id}" onchange="MTPD_function(${MTD_id})" name="more_transportation_pick_up_date[]" class="form-control">
                            </div>
                            
                            <div class="col-xl-3" style="padding: 10px;">
                                <label for="">Drop-of Date & Time</label>
                                <input type="datetime-local" id="more_transportation_drop_of_date_${MTD_id}" onchange="MTDD_function(${MTD_id})" name="more_transportation_drop_of_date[]" class="form-control">
                            </div>
                            
                            <div class="col-xl-3" style="padding: 10px;" id="more_transportation_Time_Div_${MTD_id}">
                                <label for="">Estimate Time</label>
                                <input type="text" id="more_transportation_total_Time_${MTD_id}" name="more_transportation_total_Time[]" class="form-control" readonly style="padding: 10px;">
                            </div>
                            
                            <div class="col-xl-9" style="padding: 20px;">
                                <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowTransI(${MTD_id})"  id="${MTD_id}">Delete</button>
                            </div>
                        </div>`;
            $("#append_transportation").append(data);
            
            addGoogleApi('more_transportation_pick_up_location_'+MTD_id+'');
            addGoogleApi('more_transportation_drop_off_location_'+MTD_id+'');
            
            $('#more_transportation_drop_of_date_'+MTD_id+'').change(function () {
            
                var h = "hours";
                var m = "minutes";
                
                var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+MTD_id+'').val();
                var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+MTD_id+'').val();
                
                var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
                
                var date1 = new Date(transportation_pick_up_date);
                var date2 = new Date(transportation_drop_of_date);
                var timediff = date2 - date1;
                
                var minutes_Total = Math.floor(timediff / minute);
                
                var total_hours   = Math.floor(timediff / hour)
                var total_hours_minutes = parseFloat(total_hours) * 60;
                
                var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
                
                $('#more_transportation_Time_Div_'+MTD_id+'').css('display','');
                $('#more_transportation_total_Time_'+MTD_id+'').val(total_hours+h+ ' : ' +minutes+m);
                
            });
        
            MTD_id = parseFloat(MTD_id) + 1;
            $('#MTD_id_input').val(MTD_id);
            
        });
        
        $('#transfer_supplier_Old').change(function (){
            var ids = $(this).find('option:selected').attr('attr-id');
            $('#transfer_supplier_selected_id').val(ids);
            $('#transfer_supplier_list_body').empty();
            
            $('#select_transportation_Original').css('display','none');
            $('#select_transportation').empty();
            
            $('#transportation_pick_up_location_select').val('');
            $('#transportation_drop_off_location_select').val('');
            $('#transportation_price_per_person_select').val('');
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            // $('#transportation_main_divI').empty();
            var i = 1;
            $.each(destination_details, function(key, value) {
                var vehicle_detailsE    = value.vehicle_details;
                var transfer_type       = value.transfer_type;
                
                if(vehicle_detailsE != null && vehicle_detailsE != ''){
                    var vehicle_details = JSON.parse(vehicle_detailsE);
                    $.each(vehicle_details, function(key, value1) {
                        var transfer_supplier_Id = value1.transfer_supplier_Id;
                        if(ids == transfer_supplier_Id){
                            $('#transfer_supplier_list_div').css('display','');
                            var TS_data =  `<tr>
                                                <td>${value.id}<input type="hidden" value="${value.id}" id="transfer_id_td_${i}"><input type="hidden" value="${value1.vehicle_id}" id="transfer_vehicle_id_td_${i}"></td>
                                                <td>${value.transfer_company}<input type="hidden" value="${value.transfer_company}" id="transfer_company_td_${i}"></td>
                                                <td>${value.pickup_City}<input type="hidden" value="${value.pickup_City}" id="pickup_City_td_${i}"></td>
                                                <td>${value.dropof_City}<input type="hidden" id="dropof_City_td_${i}" value="${value.dropof_City}"></td>
                                                <td>${value.available_from}<input type="hidden"id="available_from_td_${i}" value="${value.available_from}"></td>
                                                <td>${value.available_to}<input type="hidden" id="available_to_td_${i}" value="${value.available_to}"></td>
                                                <td>${value.transfer_type}<input type="hidden" id="transfer_Type_td_${i}" value="${value.transfer_type}"></td>
                                                <td>${value1.vehicle_Fare}<input type="hidden" id="total_fare_markup_td_${i}" value="${value.vehicle_Fare}"></td>
                                                <td>
                                                    <input type="hidden" id="ocupancy_btn_switch_${i}" value="0" class="ocupancy_btn_switch_class_all">
                                                    <a type="button" class="btn btn-primary ocupancy_btn_class_all" id="occupy_btn_${i}" onclick="occupy_function(${i})">Occupy</a>
                                                </td>
                                            </tr>`;
                            $('#transfer_supplier_list_body').append(TS_data);
                            i = i + 1;
                        }
                        
                    });
                }
            });
        });
        
        var TD_id1  = 1;
        var MTD_id1 = 1;
        
        function occupy_function_Old(id){
            var transfer_supplier_id    = $('#transfer_supplier').find('option:selected').attr('attr-id');
            var transfer_id_td          = $('#transfer_id_td_'+id+'').val();
            var transfer_Type_td        = $('#transfer_Type_td_'+id+'').val();
            var transfer_vehicle_id_td  = $('#transfer_vehicle_id_td_'+id+'').val();
            var ocupancy_btn_switch     = $('#ocupancy_btn_switch_'+id+'').val();
            
            $.each(destination_details, function(key, value) {
                
                var id_DD                   = value.id
                var transfer_type           = value.transfer_type;
                var select_exchange_type    = value.select_exchange_type;
                $('#vehicle_select_exchange_type_ID').val(select_exchange_type);
                
                if(id_DD == transfer_id_td){
                    
                    if(transfer_Type_td == "One-Way"){
                        $('#vehicle_markup_type').val('');
                        $('#vehicle_markup_value').val('');
                        $('#vehicle_total_price_with_markup').val('');
                        $('#vehicle_markup_value_converted').val('');
                        $('#vehicle_total_price_with_markup_converted').val('');
                        
                        $('#vehicle_per_markup_value').val('');
                        $('#markup_price_per_vehicle_converted').val('');
                        
                        $('#currency_SAR').val('');
                        $('#currency_GBP').val('');
                        
                        $('#without_markup_price_converted_destination').val('');
                        $('#transportation_vehicle_total_price_converted').val('');
                        $('#transportation_price_per_person_converted').val('');
                        
                        $('#transportation_markup').val('');
                        $('#transportation_markup_total').val('');
                        
                        $('#transportation_price_per_person_select').val(0);
                        
                        $('#transportation_cost').css('display','');
                        
                        $('.ocupancy_btn_class_all').css('background-color','rebeccapurple');
                        $('.ocupancy_btn_switch_class_all').val(0);
                        
                        $('#transportation_pick_up_date').val('');
                        $('#transportation_drop_of_date').val('');
                        $('#transportation_total_Time').val('');
                        $('#transportation_Time_Div').css('display','');
                        
                        $('#select_transportation').css('display','');
                        $('#select_return').css('display','none');
                        
                        $('#select_transportation_Original').css('display','');
                        
                        $("#append_transportation").empty();
                        $('#add_more_destination').css('display','none');
                        
                        $("#transportation_main_divI").empty();
                        $('#transportation_main_divI').css('display','none');
                        
                        $('#return_transportation_pick_up_location').val('');
                        $('#return_transportation_drop_off_location').val('');
                        $('#return_transportation_Time_Div').css('display','none');
                        $('#return_transportation_pick_up_date').val('');
                        $('#return_transportation_drop_of_date').val('');
                        $('#return_transportation_total_Time').val('');
                        
                        $('#transportation_no_of_vehicle').val('');
                        $('#transportation_vehicle_total_price').val('');
                        $('#transportation_price_per_person').val('');
                        
                        var pickup_City = value.pickup_City;
                        var dropof_City = value.dropof_City;
                        
                        var destination_id = value.id;
                        $('#destination_id').val(destination_id);
                        
                        $('#transportation_pick_up_location').val(pickup_City);
                        $('#transportation_drop_off_location').val(dropof_City);
                        
                        $('#transportation_pick_up_location_select').val(pickup_City);
                        $('#transportation_drop_off_location_select').val(dropof_City);
                        
                        $('#slect_trip').empty();
                        slect_trip_data =  `<option value="One-Way" Selected>One-Way</option>`;
                        $('#slect_trip').append(slect_trip_data);
                        
                        var vehicle_detailsE = value.vehicle_details;
                        if(vehicle_detailsE != null && vehicle_detailsE != ''){
                            var vehicle_details = JSON.parse(vehicle_detailsE);
                            $.each(vehicle_details, function(key, value1) {
                                var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                var total_fare_markup       = value1.total_fare_markup;
                                var vehicle_Name            = value1.vehicle_Name;
                                var vehicle_Fare            = value1.vehicle_Fare;
                                var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                var exchange_Rate           = value1.exchange_Rate;
                                var vehicle_id              = value1.vehicle_id;
                                
                                if(transfer_supplier_id == transfer_supplier_Id){
                                    if(transfer_vehicle_id_td == vehicle_id){
                                    
                                        $('#transportation_vehicle_typeI').empty();
                                        var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                        $('#transportation_vehicle_typeI').append(transportation_vehicle_type_Data);
                                        
                                        $('#transportation_price_per_vehicle').val(vehicle_Fare);
                                        $('#without_markup_price_converted_destination').val(vehicle_total_Fare);
                                        $('#transfer_exchange_rate_destination').val(exchange_Rate);
                                        
                                        $('#vehicle_exchange_Rate_ID').val(exchange_Rate);
                                        
                                        // $('#transportation_price_per_vehicle').val(total_fare_markup);
                                        // $('#transportation_price_per_person_select').val(total_fare_markup);
                                    }
                                }
                                
                            });
                        }
                        
                        var currency_conversion = value.currency_conversion;
                        console.log(currency_conversion);
                                        
                        var value_c         = value.currency_conversion;
                        const usingSplit    = value_c.split(' ');
                        var value_1         = usingSplit['0'];
                        var value_2         = usingSplit['2'];
                        $(".currency_value1_T").html(value_1);
                        $(".currency_value_exchange_1_T").html(value_2);
                        
                        $("#currency_SAR").val(value_1);
                        $("#currency_GBP").val(value_2);
                        
                        exchange_currency_funs(value_1,value_2);
                    }
                    else if(transfer_Type_td == "Return"){
                        $('#vehicle_markup_type').val('');
                        $('#vehicle_markup_value').val('');
                        $('#vehicle_total_price_with_markup').val('');
                        $('#vehicle_markup_value_converted').val('');
                        $('#vehicle_total_price_with_markup_converted').val('');
                        
                        $('#vehicle_per_markup_value').val('');
                        $('#markup_price_per_vehicle_converted').val('');
                        
                        $('#currency_SAR').val('');
                        $('#currency_GBP').val('');
                        
                        $('#without_markup_price_converted_destination').val('');
                        $('#transportation_vehicle_total_price_converted').val('');
                        $('#transportation_price_per_person_converted').val('');
                        
                        $('#transportation_markup').val('');
                        $('#transportation_markup_total').val('');
                        
                        $('#transportation_price_per_person_select').val(0);
                        
                        $('#transportation_cost').css('display','');
                        
                        $('#return_transportation_Time_Div').css('display','');
                        $('#return_transportation_pick_up_date').val('');
                        $('#return_transportation_drop_of_date').val('');
                        $('#return_transportation_total_Time').val('');
                        
                        $('#transportation_no_of_vehicle').val('');
                        $('#transportation_vehicle_total_price').val('');
                        $('#transportation_price_per_person').val('');
                        
                        $('#transportation_pick_up_date').val('');
                        $('#transportation_drop_of_date').val('');
                        $('#transportation_total_Time').val('');
                        $('#transportation_Time_Div').css('display','none');
                        
                        $('#select_transportation').css('display','');
                        $('#select_return').css('display','');
                        
                        $('#select_transportation_Original').css('display','');
                        
                        $("#append_transportation").empty();
                        $('#add_more_destination').css('display','none');
                        
                        $("#transportation_main_divI").empty();
                        $('#transportation_main_divI').css('display','none');
                        
                        $('.ocupancy_btn_class_all').css('background-color','rebeccapurple');
                        $('.ocupancy_btn_switch_class_all').val(0)
                        
                        var pickup_City     = value.pickup_City;
                        var dropof_City     = value.dropof_City;
                        
                        var destination_id  = value.id;
                        $('#destination_id').val(destination_id);
                        
                        $('#transportation_pick_up_location').val(pickup_City);
                        $('#transportation_drop_off_location').val(dropof_City);
                        
                        $('#transportation_pick_up_location_select').val(pickup_City);
                        $('#transportation_drop_off_location_select').val(dropof_City);
                        
                        $('#slect_trip').empty();
                        slect_trip_data =  `<option value="Return" Selected>Return</option>`;
                        $('#slect_trip').append(slect_trip_data);
                        
                        var vehicle_detailsE = value.vehicle_details;
                        if(vehicle_detailsE != null && vehicle_detailsE != ''){
                            var vehicle_details = JSON.parse(vehicle_detailsE);
                            $.each(vehicle_details, function(key, value1) {
                                var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                var total_fare_markup       = value1.total_fare_markup;
                                var vehicle_Name            = value1.vehicle_Name;
                                var vehicle_Fare            = value1.vehicle_Fare;
                                var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                var exchange_Rate           = value1.exchange_Rate;
                                var vehicle_id              = value1.vehicle_id;
                                
                                if(transfer_supplier_id == transfer_supplier_Id){
                                    if(transfer_vehicle_id_td == vehicle_id){
                                        $('#transportation_vehicle_typeI').empty();
                                        var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                        $('#transportation_vehicle_typeI').append(transportation_vehicle_type_Data);
                                        
                                        $('#transportation_price_per_vehicle').val(vehicle_Fare);
                                        $('#without_markup_price_converted_destination').val(vehicle_total_Fare);
                                        $('#transfer_exchange_rate_destination').val(exchange_Rate);
                                        
                                        $('#vehicle_exchange_Rate_ID').val(exchange_Rate);
                                        
                                        // $('#transportation_price_per_vehicle').val(total_fare_markup);
                                        // $('#transportation_price_per_person_select').val(total_fare_markup);
                                    }
                                }
                                
                            });
                        }
                        
                        var return_pickup_City  = value.return_pickup_City;
                        var return_dropof_City  = value.return_dropof_City;
                        
                        $('#return_transportation_pick_up_location').val(return_pickup_City);
                        $('#return_transportation_drop_off_location').val(return_dropof_City);
                        
                        var currency_conversion = value.currency_conversion;
                        console.log(currency_conversion);
                        var value_c         = value.currency_conversion;
                        const usingSplit    = value_c.split(' ');
                        var value_1         = usingSplit['0'];
                        var value_2         = usingSplit['2'];
                        $(".currency_value1_T").html(value_1);
                        $(".currency_value_exchange_1_T").html(value_2);
                        
                        $("#currency_SAR").val(value_1);
                        $("#currency_GBP").val(value_2);
                        
                        exchange_currency_funs(value_1,value_2);
                    }
                    else{
                        
                        if(ocupancy_btn_switch == 0){
                            $('#vehicle_markup_type').val('');
                            $('#vehicle_markup_value').val('');
                            $('#vehicle_total_price_with_markup').val('');
                            $('#vehicle_markup_value_converted').val('');
                            $('#vehicle_total_price_with_markup_converted').val('');
                            
                            $('#vehicle_per_markup_value').val('');
                            $('#markup_price_per_vehicle_converted').val('');
                            
                            $('#currency_SAR').val('');
                            $('#currency_GBP').val('');
                            
                            $('#vehicle_select_exchange_type_ID').val('');
                            $('#vehicle_exchange_Rate_ID').val('');
                            
                            $('#without_markup_price_converted_destination').val('');
                            $('#transportation_vehicle_total_price_converted').val('');
                            $('#transportation_price_per_person_converted').val('');
                            
                            $('#transfer_markup_type_invoice').val('');
                            $('#transfer_markup_invoice').val('');
                            $('#transfer_markup_price_invoice').val('');
                            $('#transfer_exchange_rate_destination').val('');
                            
                            $('#destination_id').val('');
                            
                            $('#transportation_markup').val('');
                            $('#transportation_markup_total').val('');
                            
                            $('#transportation_price_per_person_select').val(0);
                            
                            $('#transportation_cost').css('display','');
                        
                            $('#occupy_btn_'+id+'').css('background-color','red');
                            
                            $('#transportation_main_divI').css('display','');
                            
                            $('#select_transportation').css('display','none');
                            $('#select_return').css('display','none');
                            
                            $('#select_transportation_Original').css('display','none');
                            
                            $("#append_transportation").empty();
                            
                            $('#return_transportation_pick_up_location').val('');
                            $('#return_transportation_drop_off_location').val('');
                            $('#return_transportation_Time_Div').css('display','none');
                            $('#return_transportation_pick_up_date').val('');
                            $('#return_transportation_drop_of_date').val('');
                            $('#return_transportation_total_Time').val('');
                            
                            $('#transportation_pick_up_location').val('');
                            $('#transportation_drop_off_location').val('');
                            $('#transportation_Time_Div').css('display','none');
                            $('#transportation_pick_up_date').val('');
                            $('#transportation_drop_of_date').val('');
                            $('#transportation_total_Time').val('');
                            
                            $('#slect_trip').empty();
                            slect_trip_data =  `<option></option>
                                                <option value="One-Way">One-Way</option>
                                                <option value="Return">Return</option>
                                                <option value="All_Round">All Round</option>`;
                            $('#slect_trip').append(slect_trip_data);
                            $('#transportation_price_per_vehicle').val('');
                            $('#transportation_no_of_vehicle').val('');
                            $('#transportation_vehicle_total_price').val('');
                            $('#transportation_price_per_person').val('');
                            $('#transportation_vehicle_typeI').empty();
                            TVTI_data = `<option value=""></option>
                                        <option value="Bus">Bus</option>
                                        <option value="Coach">Coach</option>
                                        <option value="Vain">Vain</option>
                                        <option value="Car">Car</option>`;
                            $('#transportation_vehicle_typeI').append(TVTI_data);
                            
                            var pickup_City = value.pickup_City;
                            var dropof_City = value.dropof_City;
                            
                            $('#transportation_pick_up_location_select').val(pickup_City);
                            $('#transportation_drop_off_location_select').val(dropof_City);
                        
                            var allR_data = `<div class="row" id="allRound_Div_${TD_id1}">
                                                <h3>Transportation Details :</h3>
                                                
                                                <input type="hidden" value="All_Round${TD_id1}" name="all_round_Type[]" id="all_round_Type_${TD_id1}">
                                                
                                                <input type="hidden" name="destination_id[]" id="destination_id_${TD_id1}">
                                                
                                                <input type="hidden" name="vehicle_select_exchange_type[]" id="vehicle_select_exchange_type_ID_${TD_id1}" value="${select_exchange_type}">
                                            
                                                <input type="hidden" name="vehicle_exchange_Rate[]" id="vehicle_exchange_Rate_ID_${TD_id1}">
                                                
                                                <input type="hidden" name="currency_SAR[]" id="currency_SAR_${TD_id1}">
                                                
                                                <input type="hidden" name="currency_GBP[]" id="currency_GBP_${TD_id1}">
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Pick-up Location</label>
                                                    <input type="text" value="${pickup_City}" id="transportation_pick_up_location_${TD_id1}" name="transportation_pick_up_location[]" class="form-control pickup_location">
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Drop-off Location</label>
                                                    <input type="text" value="${dropof_City}" id="transportation_drop_off_location_${TD_id1}" name="transportation_drop_off_location[]" class="form-control dropof_location">
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Pick-up Date & Time</label>
                                                    <input type="datetime-local" id="transportation_pick_up_date_${TD_id1}" name="transportation_pick_up_date[]" class="form-control" onchange="TPUD_function(${TD_id1})">
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Drop-of Date & Time</label>
                                                    <input type="datetime-local" id="transportation_drop_of_date_${TD_id1}" name="transportation_drop_of_date[]" class="form-control" onchange="TDOP_function(${TD_id1})">
                                                </div>
                                                
                                                <div class="col-xl-3" style="display:none" id="transportation_Time_Div_${TD_id1}">
                                                    <label for="">Estimate Time</label>
                                                    <input type="text" id="transportation_total_Time_${TD_id1}" name="transportation_total_Time[]" class="form-control transportation_total_Time1" readonly style="padding: 10px;">
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Select Trip Type</label>
                                                    <select name="transportation_trip_type[]" id="slect_trip_${TD_id1}" class="form-control" data-placeholder="Choose ...">
                                                        <option value="All_Round" Selected>All Round</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Type</label>
                                                    <select name="transportation_vehicle_type[]" id="transportation_vehicle_typeI_${TD_id1}" class="form-control"  data-placeholder="Choose ...">
                                                        <option value="">Choose ...</option>
                                                        <option value="Bus">Bus</option>
                                                        <option value="Coach">Coach</option>
                                                        <option value="Vain">Vain</option>
                                                        <option value="Car">Car</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">No Of Vehicle</label>
                                                    <input type="text" id="transportation_no_of_vehicle_${TD_id1}" name="transportation_no_of_vehicle[]" class="form-control" onkeyup="TNOV_function(${TD_id1})">
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Price Per Vehicle</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" id="transportation_price_per_vehicle_${TD_id1}" name="transportation_price_per_vehicle[]" class="form-control" onchange="TPPV_function(${TD_id1})" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Total Vehicle Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" id="transportation_vehicle_total_price_${TD_id1}" name="transportation_vehicle_total_price[]" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Price Per Person</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" id="transportation_price_per_person_${TD_id1}" name="transportation_price_per_person[]" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Exchange Rate</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" name="transfer_exchange_rate_destination[]" id="transfer_exchange_rate_destination_${TD_id1}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Price Per Vehicle</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                        <input type="text" name="without_markup_price_converted_destination[]" id="without_markup_price_converted_destination_${TD_id1}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Total Vehicle Price</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                        <input type="text" name="transportation_vehicle_total_price_converted[]" id="transportation_vehicle_total_price_converted_${TD_id1}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Price Per Person</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                        <input type="text" name="transportation_price_per_person_converted[]" id="transportation_price_per_person_converted_${TD_id1}" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                
                                                <!--Costing--!>
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Markup Type</label>
                                                    <select name="vehicle_markup_type[]" id="vehicle_markup_type_${TD_id1}" class="form-control" onchange="vehicle_markup_AllR(${TD_id1})">
                                                        <option value="">Markup Type</option>
                                                        <option value="%">Percentage</option>
                                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Markup Value</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" class="form-control" id="vehicle_markup_value_${TD_id1}" name="vehicle_markup_value[]" onkeyup="vehicle_markup_AllR(${TD_id1})" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Markup Value per Vehicle</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" class="form-control" id="vehicle_per_markup_value_${TD_id1}" name="vehicle_per_markup_value[]" onkeyup="vehicle_per_markup_AllR(${TD_id1})">
                                                    </div>
                                                </div>
                                                
                                                <input type="text" class="form-control d-none" id="markup_price_per_vehicle_converted_${TD_id1}" name="markup_price_per_vehicle_converted[]" readonly>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Total Price</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                        <input type="text" class="form-control" id="vehicle_total_price_with_markup_${TD_id1}" name="vehicle_total_price_with_markup[]" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Markup Value</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                        <input type="text" class="form-control" id="vehicle_markup_value_converted_${TD_id1}" name="vehicle_markup_value_converted[]" readonly>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Vehicle Markup Price</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                        <input type="text" class="form-control" id="vehicle_total_price_with_markup_converted_${TD_id1}" name="vehicle_total_price_with_markup_converted[]" readonly>
                                                    </div>
                                                </div>
                                                <!--End Costing--!>
                                                
                                                <input type="hidden" class="transfer_markup_type_invoice" name="transfer_markup_type_invoice[]">
                                                <input type="hidden" class="transfer_markup_invoice" name="transfer_markup_invoice[]">
                                                <input type="hidden" class="transfer_markup_price_invoice" name="transfer_markup_price_invoice[]">
                                                
                                                <div id="append_transportation_${TD_id1}"></div>
                                            </div>
                                            <div class="col-xl-6 R_AllRound_class_${TD_id1}" style="padding: 10px;"></div>
                                            <div class="col-xl-6 R_AllRound_class_${TD_id1}" style="padding: 10px;">
                                                <a type="button" class="btn btn-primary" id="R_AllRound_Id_${TD_id1}" onclick="R_AllRound_function(${TD_id1},${id})" style="float: right;">Remove</a>
                                            </div>`;
                            
                            $('#transportation_main_divI').append(allR_data);
                            
                            var destination_id = value.id;
                            $('#destination_id_'+TD_id1+'').val(destination_id);
                            
                            var all_round_Type = $('#all_round_Type_'+TD_id1+'').val();
                            
                            addGoogleApi('transportation_pick_up_location_'+TD_id1+'');
                            addGoogleApi('transportation_drop_off_location_'+TD_id1+'');
                            
                            var vehicle_detailsE = value.vehicle_details;
                            if(vehicle_detailsE != null && vehicle_detailsE != ''){
                                var vehicle_details = JSON.parse(vehicle_detailsE);
                                $.each(vehicle_details, function(key, value1) {
                                    var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                    var total_fare_markup       = value1.total_fare_markup;
                                    var vehicle_Name            = value1.vehicle_Name;
                                    var vehicle_Fare            = value1.vehicle_Fare;
                                    var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                    var exchange_Rate           = value1.exchange_Rate;
                                    var vehicle_id              = value1.vehicle_id;
                                    
                                    if(transfer_supplier_id == transfer_supplier_Id){
                                        if(transfer_vehicle_id_td == vehicle_id){
                                            $('#transportation_vehicle_typeI_'+TD_id1+'').empty();
                                            var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                            $('#transportation_vehicle_typeI_'+TD_id1+'').append(transportation_vehicle_type_Data);
                                            
                                            $('#transportation_price_per_vehicle_'+TD_id1+'').val(vehicle_Fare);
                                            $('#without_markup_price_converted_destination_'+TD_id1+'').val(vehicle_total_Fare);
                                            $('#transfer_exchange_rate_destination_'+TD_id1+'').val(exchange_Rate);
                                            
                                            $('#vehicle_exchange_Rate_ID_'+TD_id1+'').val(exchange_Rate);
                                            
                                            // $('#transportation_price_per_vehicle_'+TD_id1+'').val(total_fare_markup);
                                            // $('#transportation_price_per_person_select').val(total_fare_markup);
                                        }
                                    }
                                    
                                });
                            }
                            
                            var more_destination_details_E  = value.more_destination_details;
                            if(more_destination_details_E != null && more_destination_details_E != ''){
                                var more_destination_details_D  = JSON.parse(more_destination_details_E);
                                $.each(more_destination_details_D, function(key, value2) {
                                    var subLocationPic   = value2.subLocationPic;
                                    var subLocationdrop  = value2.subLocationdrop;
                                    
                                    var data = `<div class="row" id="click_delete_${MTD_id1}">
                                                    
                                                    <input type="hidden" name="more_all_round_Type[]" id="more_all_round_Type_${MTD_id1}">
                                                    
                                                    <div class="col-xl-3" style="padding: 10px;">
                                                        <label for="">Pick-up Location</label>
                                                        <input type="text" value="${subLocationPic}" id="more_transportation_pick_up_location_${MTD_id1}" name="more_transportation_pick_up_location[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3" style="padding: 10px;">
                                                        <label for="">Drop-off Location</label>
                                                        <input type="text" value="${subLocationdrop}" id="more_transportation_drop_off_location_${MTD_id1}" name="more_transportation_drop_off_location[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3" style="padding: 10px;">
                                                        <label for="">Pick-up Date & Time</label>
                                                        <input type="datetime-local" id="more_transportation_pick_up_date_${MTD_id1}" name="more_transportation_pick_up_date[]" class="form-control" onchange="MTPD_function(${MTD_id1})">
                                                    </div>
                                                    
                                                    <div class="col-xl-3" style="padding: 10px;">
                                                        <label for="">Drop-of Date & Time</label>
                                                        <input type="datetime-local" id="more_transportation_drop_of_date_${MTD_id1}" name="more_transportation_drop_of_date[]" class="form-control" onchange="MTDD_function(${MTD_id1})">
                                                    </div>
                                                    
                                                    <div class="col-xl-3" style="display:none" id="more_transportation_Time_Div_${MTD_id1}">
                                                        <label for="">Estimate Time</label>
                                                        <input type="text" id="more_transportation_total_Time_${MTD_id1}" name="more_transportation_total_Time[]" class="form-control" readonly style="padding: 10px;">
                                                    </div>
                                                
                                                    <div class="mt-2 d-none">
                                                        <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowTransI(${MTD_id1})" id="${MTD_id1}">Delete</button>
                                                    </div>
                                                </div>`;
                                    $('#append_transportation_'+TD_id1+'').append(data);
                                    
                                    $('#more_all_round_Type_'+MTD_id1+'').val(all_round_Type);
                                    
                                    addGoogleApi('more_transportation_pick_up_location_'+MTD_id1+'');
                                    addGoogleApi('more_transportation_drop_off_location_'+MTD_id1+'');
                                    
                                    MTD_id1++;
                                });
                            }
                            
                            var currency_conversion = value.currency_conversion;
                            console.log(currency_conversion);            
                            var value_c         = value.currency_conversion;
                            const usingSplit    = value_c.split(' ');
                            var value_1         = usingSplit['0'];
                            var value_2         = usingSplit['2'];
                            $('.currency_value1_T_'+TD_id1+'').html(value_1);
                            $('.currency_value_exchange_1_T_'+TD_id1+'').html(value_2);
                            
                            $('#currency_SAR_'+TD_id1+'').val(value_1);
                            $('#currency_GBP_'+TD_id1+'').val(value_2);
                            
                            exchange_currency_funs(value_1,value_2);
                            
                            TD_id1++;
                            
                            $('#transportation_price_switchAR').val(TD_id1);
                            
                            $('#ocupancy_btn_switch_'+id+'').val(1);
                            
                        }else{
                            alert('Already Occupied')
                        }
                    }
                }
            });
        }
        
        function R_AllRound_function_Old(TD_id1,id){
            $('#allRound_Div_'+TD_id1+'').remove();
            $('.R_AllRound_class_'+TD_id1+'').remove();
            $('#ocupancy_btn_switch_'+id+'').val(0);
            $('#occupy_btn_'+id+'').css('background-color','rebeccapurple');
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            var transportation_price_switch = $('#transportation_price_switchAR').val();
            var Tran_no_of_Vehicle          = 0;
            var Tran_price_per_vehicle      = 0;
            var Tran_price_all_vehicle      = 0;
            var Tran_Orignal_price          = 0;
            var Tran_price_all_vehicleC     = 0;
            var transportation_markup       = 0;
            var transportation_markup_total = 0;
            for(x=1; x<transportation_price_switch; x++){
                var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
                var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
                var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
                var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
                var transportation_vehicle_total_price_converted = $('#transportation_vehicle_total_price_converted_'+x+'').val();
                var vehicle_markup_value_converted                  = $('#vehicle_markup_value_converted_'+x+'').val();
                var vehicle_total_price_with_markup_converted       = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
                
                if(transportation_price_per_person != null && transportation_price_per_person != ''){
                    Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                    Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                    $('#transportation_price_per_person_select').val(Tran_Orignal_price);
                }
                
                if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                    Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                    Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                    $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
                }
                
                if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                    Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                    Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                    $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
                }
                
                if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                    Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                    Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                    $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
                }
                
                if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                    Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                    Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                    $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
                }
                
                if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                    transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                    transportation_markup = transportation_markup.toFixed(2);
                    $('#transportation_markup').val(transportation_markup);
                }
                
                if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                    transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                    transportation_markup_total = transportation_markup_total.toFixed(2);
                    $('#transportation_markup_total').val(transportation_markup_total);
                }
            }
            add_numberElseI();
            
        }
        
        function deleteRowTransI_Old(id){
            $('#click_delete_'+id+'').remove();
            
            var MTD_id  = $('#MTD_id_input').val();
            MTD_id      = parseFloat(MTD_id) - 1;
            $('#MTD_id_input').val(MTD_id);
        }
        
        function TPUD_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var transportation_drop_of_date = $('#transportation_drop_of_date_'+id+'').val();
            var transportation_pick_up_date = $('#transportation_pick_up_date_'+id+'').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            var date1               = new Date(transportation_pick_up_date);
            var date2               = new Date(transportation_drop_of_date);
            var timediff            = date2 - date1;
            var minutes_Total       = Math.floor(timediff / minute);
            var total_hours         = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            var minutes             = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#transportation_Time_Div_'+id+'').css('display','');
            $('#transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
        }
        
        function TDOP_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var transportation_drop_of_date = $('#transportation_drop_of_date_'+id+'').val();
            var transportation_pick_up_date = $('#transportation_pick_up_date_'+id+'').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            var date1               = new Date(transportation_pick_up_date);
            var date2               = new Date(transportation_drop_of_date);
            var timediff            = date2 - date1;
            var minutes_Total       = Math.floor(timediff / minute);
            var total_hours         = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            var minutes             = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#transportation_Time_Div_'+id+'').css('display','');
            $('#transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
            
        }
        
        function TRPUD_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var return_transportation_pick_up_date  = $('#return_transportation_pick_up_date_'+id+'').val();
            var return_transportation_drop_of_date  = $('#return_transportation_drop_of_date_'+id+'').val();
            var second=1000, minute=second*60, hour = minute*60, day=hour*24, week=day*7;
            var return_date1                        = new Date(return_transportation_pick_up_date);
            var return_date2                        = new Date(return_transportation_drop_of_date);
            var return_timediff                     = return_date2 - return_date1;
            var return_minutes_Total                = Math.floor(return_timediff / minute);
            var return_total_hours                  = Math.floor(return_timediff / hour)
            var return_total_hours_minutes          = parseFloat(return_total_hours) * 60;
            var return_minutes                      = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
            $('#return_transportation_Time_Div_'+id+'').css('display','');
            $('#return_transportation_total_Time_'+id+'').val(return_total_hours+h+ ' : ' +return_minutes+m);
        }
        
        function TRDOP_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var return_transportation_pick_up_date  = $('#return_transportation_pick_up_date_'+id+'').val();
            var return_transportation_drop_of_date  = $('#return_transportation_drop_of_date_'+id+'').val();
            var second=1000, minute=second*60, hour = minute*60, day=hour*24, week=day*7;
            var return_date1                        = new Date(return_transportation_pick_up_date);
            var return_date2                        = new Date(return_transportation_drop_of_date);
            var return_timediff                     = return_date2 - return_date1;
            var return_minutes_Total                = Math.floor(return_timediff / minute);
            var return_total_hours                  = Math.floor(return_timediff / hour)
            var return_total_hours_minutes          = parseFloat(return_total_hours) * 60;
            var return_minutes                      = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
            $('#return_transportation_Time_Div_'+id+'').css('display','');
            $('#return_transportation_total_Time_'+id+'').val(return_total_hours+h+ ' : ' +return_minutes+m);
        }
        
        function TNOV_function_Old(id){
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_type').val('');
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            $('#vehicle_per_markup_value').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            $('#vehicle_markup_type_'+id+'').val('');
            $('#vehicle_markup_value_'+id+'').val('');
            $('#vehicle_total_price_with_markup_'+id+'').val('');
            $('#vehicle_markup_value_converted_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            $('#vehicle_per_markup_value_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            
            var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
            var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle_'+id+'').val();
            var no_of_pax_days                      =  $('#no_of_pax_days').val();
            var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
            var t_trans                             =  t_trans1.toFixed(2);
            $('#transportation_vehicle_total_price_'+id+'').val(t_trans);
            var total_trans1                        = t_trans/no_of_pax_days;
            var total_trans                         = total_trans1.toFixed(2);
            $('#transportation_price_per_person_'+id+'').val(total_trans);
            $('#transportation_price_per_person_select_'+id+'').val(t_trans);
            
            var select_exchange_type    = $('#vehicle_select_exchange_type_ID_'+id+'').val();
            var exchange_Rate           = $('#vehicle_exchange_Rate_ID_'+id+'').val();
            
            if(select_exchange_type == 'Divided'){
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
            }else{
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
            }
            
            without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
            transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
            transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
            
            $('#without_markup_price_converted_destination_'+id+'').val(without_markup_price_converted_destination);
            $('#transportation_vehicle_total_price_converted_'+id+'').val(transportation_vehicle_total_price_converted);
            $('#transportation_price_per_person_converted_'+id+'').val(transportation_price_per_person_converted);
            
            var transportation_price_switch = $('#transportation_price_switchAR').val();
            var Tran_no_of_Vehicle = 0;
            var Tran_price_per_vehicle = 0;
            var Tran_price_all_vehicle = 0;
            var Tran_Orignal_price = 0;
            var Tran_price_all_vehicleC = 0;
            for(x=1; x<transportation_price_switch; x++){
                var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
                var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
                var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
                var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
                var transportation_vehicle_total_price_converted  = $('#transportation_vehicle_total_price_converted_'+x+'').val();
                
                if(transportation_price_per_person != null && transportation_price_per_person != ''){
                    Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                    Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                    $('#transportation_price_per_person_select').val(Tran_Orignal_price);
                }
                
                if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                    Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                    Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                    $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
                }
                
                if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                    Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                    Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                    $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
                }
                
                if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                    Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                    Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                    // $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
                }
                
                if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                    Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                    Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                    $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
                }
                console.log(Tran_price_all_vehicleC);
            }
            add_numberElseI();
        }
        
        function TPPV_function_Old(id){
            // var Tran_Orignal_price = $('#transportation_price_per_person_select').val();
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_type').val('');
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            $('#vehicle_per_markup_value').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            $('#vehicle_markup_type_'+id+'').val('');
            $('#vehicle_markup_value_'+id+'').val('');
            $('#vehicle_total_price_with_markup_'+id+'').val('');
            $('#vehicle_markup_value_converted_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            $('#vehicle_per_markup_value_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            
            // var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
            var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
            var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle_'+id+'').val();
            var no_of_pax_days                      =  $('#no_of_pax_days').val();
            var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
            var t_trans                             =  t_trans1.toFixed(2);
            $('#transportation_vehicle_total_price_'+id+'').val(t_trans);
            var total_trans1                        = t_trans/no_of_pax_days;
            var total_trans                         = total_trans1.toFixed(2);
            $('#transportation_price_per_person_'+id+'').val(total_trans);
            $('#transportation_price_per_person_select_'+id+'').val(t_trans);
            
            var select_exchange_type    = $('#vehicle_select_exchange_type_ID_'+id+'').val();
            var exchange_Rate           = $('#vehicle_exchange_Rate_ID_'+id+'').val();
            if(select_exchange_type == 'Divided'){
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
            }else{
                var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
                var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
                var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
            }
            
            without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
            transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
            transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
            
            $('#without_markup_price_converted_destination_'+id+'').val(without_markup_price_converted_destination);
            $('#transportation_vehicle_total_price_converted_'+id+'').val(transportation_vehicle_total_price_converted);
            $('#transportation_price_per_person_converted_'+id+'').val(transportation_price_per_person_converted);
            
            var transportation_price_switch = $('#transportation_price_switchAR').val();
            var Tran_no_of_Vehicle      = 0;
            var Tran_price_per_vehicle  = 0;
            var Tran_price_all_vehicle  = 0;
            var Tran_Orignal_price      = 0;
            var Tran_price_all_vehicleC = 0;
            for(x=1; x<transportation_price_switch; x++){
                var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
                var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
                var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
                var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
                var transportation_vehicle_total_price_converted  = $('#transportation_vehicle_total_price_converted_'+x+'').val();
                
                if(transportation_price_per_person != null && transportation_price_per_person != ''){
                    Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                    Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                    $('#transportation_price_per_person_select').val(Tran_Orignal_price);
                }
                
                if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                    Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                    Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                    $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
                }
                
                if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                    Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                    Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                    $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
                }
                
                if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                    Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                    Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                    // $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
                }
                
                if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                    Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                    Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                    $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                    $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
                }
                
                console.log(Tran_price_all_vehicleC);
                
            }
            add_numberElseI();
        }
        
        function MTPD_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+id+'').val();
            var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+id+'').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            var date1               = new Date(transportation_pick_up_date);
            var date2               = new Date(transportation_drop_of_date);
            var timediff            = date2 - date1;
            var minutes_Total       = Math.floor(timediff / minute);
            var total_hours         = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            
            var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#more_transportation_Time_Div_'+id+'').css('display','');
            $('#more_transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
        }
        
        function MTDD_function_Old(id){
            var h = "hours";
            var m = "minutes";
            var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+id+'').val();
            var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+id+'').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            var date1               = new Date(transportation_pick_up_date);
            var date2               = new Date(transportation_drop_of_date);
            var timediff            = date2 - date1;
            var minutes_Total       = Math.floor(timediff / minute);
            var total_hours         = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            
            var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#more_transportation_Time_Div_'+id+'').css('display','');
            $('#more_transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
        }
        
        function TPUL_function_Old(id){
            addGoogleApi('transportation_pick_up_location_'+id+'');
        }
        
        function TDOL_function_Old(id){
            addGoogleApi('transportation_drop_off_location_'+id+'');
        }
        
        function TRPUL_function_Old(id){
            addGoogleApi('transportation_pick_up_location_'+id+'');
        }
        
        function TRDOL_function_Old(id){
            addGoogleApi('transportation_drop_off_location_'+id+'');
        }
        
        function vehicle_per_markup_OWandR_Old(){
            var vehicle_per_markup_value        = $('#vehicle_per_markup_value').val();
            var transportation_no_of_vehicle    = $('#transportation_no_of_vehicle').val();
            var total_price_all_vehicle_vehicle = parseFloat(transportation_no_of_vehicle) * parseFloat(vehicle_per_markup_value);
            total_price_all_vehicle_vehicle     = total_price_all_vehicle_vehicle.toFixed(2);
            $('#vehicle_markup_value').val(total_price_all_vehicle_vehicle);
            var without_markup_price_converted_destination      = $('#without_markup_price_converted_destination').val();
            
            var vehicle_markup_type                             = $('#vehicle_markup_type').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID').val();;
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID').val();;
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted').val(vehicle_markup_value_converted);
            $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_per_markup_value').val('');
                $('#vehicle_total_price_with_markup').val('');
                $('#vehicle_markup_value').val('');
                $('#vehicle_markup_value_converted').val('');
                $('#vehicle_total_price_with_markup_converted').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted').val(total_C);
                $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = (without_markup_price_converted_destination * price_per_vehicle_with_converted/100) + parseFloat(without_markup_price_converted_destination);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted').val(S_total_C);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted').val(total_C);
                $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = parseFloat(without_markup_price_converted_destination) + parseFloat(price_per_vehicle_with_converted);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted').val(S_total_C);
            }
        }
        
        function vehicle_per_markup_OWandR1_Old(id){
            var vehicle_per_markup_value        = $('#vehicle_per_markup_value_'+id+'').val();
            var transportation_no_of_vehicle    = $('#transportation_no_of_vehicle_'+id+'').val();
            var total_price_all_vehicle_vehicle = parseFloat(transportation_no_of_vehicle) * parseFloat(vehicle_per_markup_value);
            total_price_all_vehicle_vehicle     = total_price_all_vehicle_vehicle.toFixed(2);
            $('#vehicle_markup_value_'+id+'').val(total_price_all_vehicle_vehicle);
            var without_markup_price_converted_destination      = $('#without_markup_price_converted_destination_'+id+'').val();
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
            // $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_per_markup_value_'+id+'').val('');
                $('#vehicle_markup_value_'+id+'').val('');
                $('#vehicle_total_price_with_markup_'+id+'').val('');
                $('#vehicle_markup_value_converted_'+id+'').val('');
                $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = (without_markup_price_converted_destination * price_per_vehicle_with_converted/100) + parseFloat(without_markup_price_converted_destination);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = parseFloat(without_markup_price_converted_destination) + parseFloat(price_per_vehicle_with_converted);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
            }
            
            var transportation_price_switch     = $('#transportation_price_switchAR').val();
            var transportation_markup           = 0;
            var transportation_markup_total     = 0;
            for(x=1; x<transportation_price_switch; x++){
                var vehicle_markup_value_converted              = $('#vehicle_markup_value_converted_'+x+'').val();
                var vehicle_total_price_with_markup_converted   = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
                
                if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                    transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                    transportation_markup = transportation_markup.toFixed(2);
                    $('#transportation_markup').val(transportation_markup);
                }
                
                if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                    transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                    transportation_markup_total = transportation_markup_total.toFixed(2);
                    $('#transportation_markup_total').val(transportation_markup_total);
                }
                
            }
            add_numberElseI();
            
        }
        
        function vehicle_per_markup_AllR_Old(id){
            var vehicle_per_markup_value        = $('#vehicle_per_markup_value_'+id+'').val();
            var transportation_no_of_vehicle    = $('#transportation_no_of_vehicle_'+id+'').val();
            var total_price_all_vehicle_vehicle = parseFloat(transportation_no_of_vehicle) * parseFloat(vehicle_per_markup_value);
            total_price_all_vehicle_vehicle     = total_price_all_vehicle_vehicle.toFixed(2);
            $('#vehicle_markup_value_'+id+'').val(total_price_all_vehicle_vehicle);
            var without_markup_price_converted_destination      = $('#without_markup_price_converted_destination_'+id+'').val();
            
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
                var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
            // $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_total_price_with_markup_'+id+'').val('');
                $('#vehicle_markup_value_'+id+'').val('');
                $('#vehicle_markup_value_converted_'+id+'').val('');
                $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
                $('#vehicle_per_markup_value_'+id+'').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = (without_markup_price_converted_destination * price_per_vehicle_with_converted/100) + parseFloat(without_markup_price_converted_destination);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
                
                var S_total1_C = parseFloat(without_markup_price_converted_destination) + parseFloat(price_per_vehicle_with_converted);
                var S_total_C  = S_total1_C.toFixed(2);
                $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
            }
            
            var transportation_price_switch     = $('#transportation_price_switchAR').val();
            var transportation_markup           = 0;
            var transportation_markup_total     = 0;
            for(x=1; x<transportation_price_switch; x++){
                var vehicle_markup_value_converted              = $('#vehicle_markup_value_converted_'+x+'').val();
                var vehicle_total_price_with_markup_converted   = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
                
                if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                    transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                    transportation_markup = transportation_markup.toFixed(2);
                    $('#transportation_markup').val(transportation_markup);
                }
                
                if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                    transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                    transportation_markup_total = transportation_markup_total.toFixed(2);
                    $('#transportation_markup_total').val(transportation_markup_total);
                }
                
            }
            add_numberElseI();
            
        }
        
        function vehicle_markup_OWandR_Old(){
            var vehicle_markup_type                             = $('#vehicle_markup_type').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID').val();;
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID').val();;
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value) * parseFloat(exchange_vehicle_exchange_Rate_IDRate);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted').val(vehicle_markup_value_converted);
            $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_per_markup_value').val('');
                $('#vehicle_total_price_with_markup').val('');
                $('#vehicle_markup_value').val('');
                $('#vehicle_markup_value_converted').val('');
                $('#vehicle_total_price_with_markup_converted').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted').val(total_C);
                $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted').val(total_C);
                $('#transportation_markup_total').val(total_C);
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
            }
        }
        
        function vehicle_markup_OWandR1_Old(id){
            var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();;
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();;
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value) * parseFloat(exchange_vehicle_exchange_Rate_IDRate);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
            $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_per_markup_value_'+id+'').val('');
                $('#vehicle_markup_value_'+id+'').val('');
                $('#vehicle_total_price_with_markup_'+id+'').val('');
                $('#vehicle_markup_value_converted_'+id+'').val('');
                $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type_'+id+'').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type_'+id+'').append(transportation_markup_type);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                $('#transportation_markup_total').val(total_C);
                add_numberElse_1I();
                
                $('#transportation_markup_type_'+id+'').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type_'+id+'').append(transportation_markup_type);
            }
        }
        
        function vehicle_markup_AllR_Old(id){
            $('#transportation_markup').val('');
            $('#transportation_markup_total').val('');
            
            $('#vehicle_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
            
            var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
            var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
            var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
            var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
            var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();
            var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();
            
            if(vehicle_select_exchange_type_ID == 'Divided'){
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            }
            else{
                var vehicle_markup_value_converted = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            }
            
            vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
            $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
            // $('#transportation_markup').val(vehicle_markup_value_converted);
            
            if(vehicle_markup_type == ''){
                alert('Select Markup Type');
                $('#vehicle_total_price_with_markup_'+id+'').val('');
                $('#vehicle_markup_value_'+id+'').val('');
                $('#vehicle_markup_value_converted_'+id+'').val('');
                $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
                $('#vehicle_per_markup_value_'+id+'').val('');
            }
            else if(vehicle_markup_type == '%'){
                var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
            }
            else{
                var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
                var total  = total1.toFixed(2);
                $('#vehicle_total_price_with_markup_'+id+'').val(total);
                add_numberElse_1I();
                
                var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
                var total_C  = total1_C.toFixed(2);
                $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
                // $('#transportation_markup_total').val(total_C);
                add_numberElse_1I();
                
                $('#transportation_markup_type').empty();
                var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
                $('#transportation_markup_type').append(transportation_markup_type);
            }
            
            var transportation_price_switch     = $('#transportation_price_switchAR').val();
            var transportation_markup           = 0;
            var transportation_markup_total     = 0;
            for(x=1; x<transportation_price_switch; x++){
                var vehicle_markup_value_converted              = $('#vehicle_markup_value_converted_'+x+'').val();
                var vehicle_total_price_with_markup_converted   = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
                
                if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                    transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                    transportation_markup = transportation_markup.toFixed(2);
                    $('#transportation_markup').val(transportation_markup);
                }
                
                if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                    transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                    transportation_markup_total = transportation_markup_total.toFixed(2);
                    $('#transportation_markup_total').val(transportation_markup_total);
                }
                
            }
            add_numberElseI();
        }
        
        // End Transportation
    
        // Accomodation Invoice
        function selectCitiesI(){
            var country = $('#property_countryI').val();
            console.log(country);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/country_cites1') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": country,
                },
                success: function(result){
                    
                    console.log(result);
                
                    var options         = result.options;
                    var first_option    = result.first_option;
                
                    $('.property_city_newInput_I').css('display','none');
                    $('.property_city_new_selectI').css('display','');
                    $('.property_city_new_selectI').html(options);
                    
                    console.log(first_option);
                    
                    $('.more_property_city_new_selectI').val(first_option);
    
                    var tour_location_city      = $('#tour_location_city1').val();
                    var tour_location_city_arr  = JSON.parse(tour_location_city);
                    var tour_location_city_L    = tour_location_city_arr.length;
                    if(tour_location_city_L > 0){
                        for(var i=1; i<=tour_location_city_L; i++){
                            $('#property_city_new'+i+'').removeAttr('value');
                            $('#tour_location_city').removeAttr('value');
                            $('#packages_get_city').removeAttr('value');
                        }
                    }
                },
                error:function(error){
                    //  console.log(error);
                }
            });
        }
        
        function put_tour_locationI(id){    
            $('#tour_location_city').removeAttr('value');
            var city_No = $('#city_No').val();
            var arr2    = [];
            for(var i=1; i<=city_No; i++){
                var property_city_new  = $('#property_city_new'+i+'').val();
                console.log('property_city_new : '+property_city_new);
                $('.more_property_city_new_selectI').val(property_city_new);
                
                if(property_city_new == null || property_city_new == '' || property_city_new == 0){
                }else{
                    arr2.push(property_city_new);
                }
            }
            
            var json_data = JSON.stringify(arr2);
            $('#tour_location_city').val(json_data);
            $('#packages_get_city').val(json_data);
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
            
        }
        
        $("#add_hotel_accomodation").on('click',function(){
            
            $('#tour_location_city').removeAttr('value');
            $('#packages_get_city').removeAttr('value');
            
            var city_No = $('#city_No').val();
            if(city_No > 0){
                
                $("#append_accomodation_data_cost1").empty();
                $("#append_accomodation_data_cost").empty();
                $("#append_accomodation").empty();
                
                var packages_get_city = $('#city_No').val();
                
                var j = 0;
                for (let i = 1; i <= city_No; i++) {
                    
                    var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotel${i}">
                                    
                                    <h4>
                                        City #${i}
                                    </h4> 
                                    <div class="row" style="padding-bottom: 25px;">
                                        <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control makkah_accomodation_check_in_class_${i} check_in_hotel_${i}">
                                        </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" class="form-control makkah_accomodation_check_out_date_class_${i} check_out_hotel_${i}"></div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Select City</label>
                                            <select type="text" id="property_city_new${i}" onchange="put_tour_location(${i})" name="hotel_city_name[]" class="form-control property_city_new"></select>
                                        </div>
                        
                                        <div class="col-xl-3">
                                            <label for="">Hotel Name</label>
                                            
                                            <input type="text" id="switch_hotel_name${i}" name="switch_hotel_name[]" value="1" style="display:none">
                                            
                                            <div class="input-group" id="add_hotel_div${i}">
                                                <input type="text" onchange="hotel_funI(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_${i}">
                                            </div>
                                            <a style="float: right;font-size: 10px;width: 102px;" onclick="select_hotel_btn(${i})" class="btn btn-primary select_hotel_btn${i}">
                                                SELECT HOTEL
                                            </a>
                                            
                                            <div class="input-group" id="select_hotel_div${i}" style="display:none">
                                                <select onchange="get_room_types(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name_select[]" class="form-control acc_hotel_name_class_${i} get_room_types_${i}"></select>
                                            </div>
                                            <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="add_hotel_btn(${i})" class="btn btn-primary add_hotel_btn${i}">
                                                ADD HOTEL
                                            </a>
                                            <input type="text" id="select_hotel_id${i}" hidden name="hotel_id[]" value="">
                                        </div>
                                        
                                        
                                        <div class="col-xl-3"><label for="">No Of Nights</label>
                                        <input readonly type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                        
                                        <input readonly type="hidden" id="acc_nights_key_${i}" value="${i}" class="form-control">
                                        
                                        <div class="col-xl-3"><label for="">Room Type</label>
                                            
                                            <div class="input-group hotel_type_add_div_${i}">
                                                <select onchange="hotel_type_funI(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                                                    <option value="">Choose ...</option>
                                                    <option attr="4" value="Quad">Quad</option>
                                                    <option attr="3" value="Triple">Triple</option>
                                                    <option attr="2" value="Double">Double</option>
                                                </select>
                                            </div>
                                            
                                            <select onchange="hotel_type_funInvoice(${i})" style="display:none" name="acc_type_select[]" id="hotel_type_${i}" class="hotel_type_select_div_${i} form-control other_Hotel_Type hotel_type_class_${i} hotel_type_select_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                            </select>

                                            <select onchange="add_new_room_type(${i})" name="new_rooms_type[]" style="display:none;" id="new_rooms_type_${i}" class="form-control other_Hotel_Type new_rooms_type_${i} "  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                            </select>
                                            <input type="text" id="select_add_new_room_type_${i}" hidden name="new_add_room[]" value="false">
                                            
                                        </div>
                                        
                                        <div class="col-xl-3" id="new_room_supplier_div_${i}" style="display:none">
                                            <label for="">Select Supplier</label>
                                            <select class="form-control" id="new_room_supplier_${i}" name="new_room_supplier[]">
                                                <option>Select One</option>
                                            </select>
                                        </div>
                                    
                                        <div class="col-xl-3">
                                            <label for="">Quantity</label>
                                            <input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}" onkeyup="acc_qty_class_Invoice(${i})">
                                            
                                            <div class="row" style="padding: 2px;">
                                                <div class="col-lg-6">
                                                    <a style="display: none;font-size: 10px;" class="btn btn-success" id="room_quantity_${i}"></a>
                                                    <input type="hidden" class="room_quantity_${i}">
                                                </div>
                                                <div class="col-lg-6">
                                                    <a style="display: none;font-size: 10px;" class="btn btn-primary" id="room_available_${i}"></a>
                                                    <input type="hidden" class="room_available_${i}">
                                                </div>
                                            </div>
                                            
                                            <div class="row" style="padding: 2px;">
                                                <div class="col-lg-6">
                                                    <a style="display: none;font-size: 10px;" class="btn btn-info" id="room_booked_quantity_${i}"></a>
                                                    <input type="hidden" class="room_booked_quantity_${i}">
                                                </div>
                                                <div class="col-lg-6">
                                                    <a style="display: none;font-size: 10px;" class="btn btn-danger" id="room_over_booked_quantity_${i}"></a>
                                                    <input type="hidden" class="room_over_booked_quantity_${i}">
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Pax</label>
                                            <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Meal Type</label>
                                            <select name="hotel_meal_type[]" id="hotel_meal_type_${i}" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Room only">Room only</option>
                                                <option value="Breakfast">Breakfast</option>
                                                <option value="Lunch">Lunch</option>
                                                <option value="Dinner">Dinner</option>
                                            </select>
                                        </div>
                                        
                                        <div id="hotel_price_for_week_end_${i}" class="row"></div>
                                        
                                        <h4 class="mt-4">Purchase Price in <a class="currency_value1" style="color: black;"></a></h4>
                                        
                                            <input type="hidden" id="hotel_invoice_markup_${i}" name="hotel_invoice_markup[]">
                                            
                                            <input type="hidden" id="hotel_supplier_id_${i}" name="hotel_supplier_id[]">
                                            
                                            <input type="hidden" id="hotel_type_id_${i}" name="hotel_type_id[]">
                                            
                                            <input type="hidden" id="hotel_type_cat_${i}" name="hotel_type_cat[]">
                                            
                                            <input type="hidden" id="hotelRoom_type_id_${i}" name="hotelRoom_type_id[]">
                                            
                                            <input type="hidden" id="hotelRoom_type_idM_${i}" name="hotelRoom_type_idM[]">
                                            
                                            <div class="col-xl-4">
                                                <label for="">Price Per Room/Night</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                          
                                                        </a>
                                                    </span>
                                                    <input type="text" id="makkah_acc_room_price_${i}" onkeyup="makkah_acc_room_price_funsI(${i})" value="" name="price_per_room_purchase[]" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <label for="">Price Per Person/Night</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value2">
                                                         
                                                        </a>
                                                    </span>
                                                    <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price_purchase[]" class="form-control makkah_acc_price_class_${i}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                                 <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value3">
                                                          
                                                        </a>
                                                    </span>
                                                    <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount_purchase[]" class="form-control makkah_acc_total_amount_class_${i}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-6">
                                                <label for="">Exchange Rate</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                          
                                                        </a>
                                                    </span>
                                                    <input type="text" id="exchange_rate_price_funs_${i}" onkeyup="exchange_rate_price_funsI(${i})" value="" name="exchange_rate_price[]" class="form-control">
                                                </div>
                                            </div>
                                                
                                            <div class="col-xl-6"></div>
                                            
                                            <h4 class="mt-4">Purchase Price in <a class="currency_value_exchange_1" style="color: black;"></a></h4>
                                            
                                            <div class="col-xl-4">
                                                <label for="">Price Per Room/Night</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                          
                                                        </a>
                                                    </span>
                                                    <input type="text" id="price_per_room_exchange_rate_${i}" name="price_per_room_sale[]" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <label for="">Price Per Person/Night</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                          
                                                        </a>
                                                    </span>
                                                    <input type="text" id="price_per_person_exchange_rate_${i}"   name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                                 <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                          
                                                        </a>
                                                    </span>
                                                    <input readonly type="text"  id="price_total_amout_exchange_rate_${i}"  name="acc_total_amount[]" class="form-control">
                                                </div>
                                            </div>
                        
                                        <div id="append_add_accomodation_${i}"></div>
                                        <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_Invoice(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                        
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Room Amenities</label>
                                                <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                              
                                            </div>
                                        </div>
                        
                                        <div class="col-xl-12"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                        
                                        <div class="mt-2">
                                            <a href="javascript:;" onclick="remove_hotels(${i})" id="${i}" class="btn btn-danger" style="float: right;"> 
                                                Delete Hotel
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>`;
          
          
                    var data_cost=`<div class="row" id="costing_acc${i}" style="margin-bottom:20px;">
                    
                                        <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                        
                                        <input type="text" name="hotel_name_markup[]" hidden id="hotel_name_markup${i}">
                                        
                                        <input type="hidden" name="acc_hotel_CityName[]" id="acc_hotel_CityName${i}">
                                        <input type="hidden" name="acc_hotel_HotelName[]" id="acc_hotel_HotelName${i}">
                                        <input type="hidden" name="acc_hotel_CheckIn[]" id="acc_hotel_CheckIn${i}">
                                        <input type="hidden" name="acc_hotel_CheckOut[]" id="acc_hotel_CheckOut${i}">
                                        <input type="hidden" name="acc_hotel_NoOfNights[]" id="acc_hotel_NoOfNights${i}">
                                        <input type="hidden" name="acc_hotel_Quantity[]" id="acc_hotel_Quantity${i}">
                                        
                                        <div class="col-xl-12">
                                            <h4 id="acc_cost_html_${i}">Accomodation Cost</h4>
                                        </div>
                                
                                        <div class="col-xl-3">
                                            <label>Room Type</label>
                                            <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label>Price Per Room/Night</label>
                                            <div class="input-group">
                                                <input type="text" id="hotel_acc_price_per_night_${i}" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label>Cost Price/Room</label>
                                            <div class="input-group">
                                                <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">    
                                            <label>Markup Type</label>
                                            <select name="markup_type[]" onchange="hotel_markup_typeI(${i})" id="hotel_markup_types_${i}" class="form-control">
                                                <option value="">Markup Type</option>
                                                <option value="%">Percentage</option>
                                                <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                <option value="per_Night">Per Night</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3 markup_value_Div_${i}" style="display:none;margin-top:10px">
                                            <label>Markup Value</label>
                                            <input type="hidden" id="" name="" class="form-control">
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]" onkeyup="hotel_markup_funI(${i})">
                                                <span class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}" class="currency_value1">SAR</div></button>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 exchnage_rate_Div_${i}" style="display:none;margin-top:10px">
                                            <label>Exchange Rate</label>
                                            <div class="input-group">
                                                <input type="text" id="hotel_exchage_rate_per_night_${i}" readonly name="exchage_rate_single[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 markup_price_Div_${i}" style="display:none;margin-top:10px">
                                            <label>Markup Price</label>
                                            <div class="input-group">
                                                <input type="text" id="hotel_exchage_rate_markup_total_per_night_${i}" readonly="" name="markup_total_per_night[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                </span>
                                            </div> 
                                        </div>
                                        
                                        <div class="col-xl-3 markup_total_price_Div_${i}" style="display:none;margin-top:10px">
                                            <label>Markup Total Price</label>
                                            <div class="input-group">
                                                <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot" readonly>
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                </span>
                                            </div> 
                                        </div>
                                        
                                    </div>`;
          
                    $("#append_accomodation_data_cost").append(data_cost);
                  
                    $("#append_accomodation").append(data);
                    
                    var country = $('#property_country').val();
                
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    
                    $.ajax({
                        url: "{{ url('/country_cites1') }}",
                        method: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": country,
                        },
                        success: function(result){
                            var options = result.options;
                            $('.property_city_new').html(options);
                        },
                        error:function(error){
                        }
                    });
                    
                    j = j + 1;
                    
                    var places_D1 = new google.maps.places.Autocomplete(
                        document.getElementById('acc_hotel_name_'+i+'')
                    );
                    
                    google.maps.event.addListener(places_D1, "place_changed", function () {
                        var places_D1 = places_D1.getPlace();
                        // console.log(places_D1);
                        var address = places_D1.formatted_address;
                        var latitude = places_D1.geometry.location.lat();
                        var longitude = places_D1.geometry.location.lng();
                        var latlng = new google.maps.LatLng(latitude, longitude);
                        var geocoder = (geocoder = new google.maps.Geocoder());
                        geocoder.geocode({ latLng: latlng }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[0]) {
                                    var address = results[0].formatted_address;
                                    var pin = results[0].address_components[
                                        results[0].address_components.length - 1
                                    ].long_name;
                                    var country =  results[0].address_components[
                                        results[0].address_components.length - 2
                                      ].long_name;
                                    var state = results[0].address_components[
                                            results[0].address_components.length - 3
                                        ].long_name;
                                    var city = results[0].address_components[
                                            results[0].address_components.length - 4
                                        ].long_name;
                                    var country_code = results[0].address_components[
                                            results[0].address_components.length - 2
                                        ].short_name;
                                    $('#country').val(country);
                                    $('#lat').val(latitude);
                                    $('#long').val(longitude);
                                    $('#pin').val(pin);
                                    $('#city').val(city);
                                    $('#country_code').val(country_code);
                                }
                            }
                        });
                    });
                    
                    $('#property_city_new'+i+'').on('change',function(){
                        
                        $('#room_booked_quantity_'+i+'').css('display','none');
                        $('#room_booked_quantity_'+i+'').val('');
                        
                        // HOTEl NAME
                        $('#add_hotel_div'+i+'').css('display','');
                        $('.select_hotel_btn'+i+'').css('display','');
                        $('#select_hotel_div'+i+'').css('display','none');
                        $('.add_hotel_btn'+i+'').css('display','none');
                        $('#acc_hotel_name_class_'+i+'').css('display','');
                        
                        // HOTEl TYPE
                        $('.hotel_type_select_div_'+i+'').css('display','none');
                        $('.hotel_type_add_div_'+i+'').css('display','');
                        $('.hotel_type_class_'+i+'').empty();
                        var dataHTC =   `<option value="">Choose ...</option>
                                        <option attr="4" value="Quad">Quad</option>
                                        <option attr="3" value="Triple">Triple</option>
                                        <option attr="2" value="Double">Double</option>`;
                        $('.hotel_type_class_'+i+'').append(dataHTC);
                        
                        $('#switch_hotel_name'+i+'').val(1);
                        $('.acc_qty_class_'+i+'').empty();
                        $('.acc_pax_class_'+i+'').empty();
                        
                        // Meal Type
                        $('#hotel_meal_type_'+i+'').empty();
                        var hote_MT_data = `<option value="">Choose ...</option>
                                            <option value="Room only">Room only</option>
                                            <option value="Breakfast">Breakfast</option>
                                            <option value="Lunch">Lunch</option>
                                            <option value="Dinner">Dinner</option>`;
                        $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                        
                         // Price Section
                        $('#hotel_price_for_week_end_'+i+'').empty();
                        $('#makkah_acc_room_price_'+i+'').val('');
                        $('#makkah_acc_price_'+i+'').val('');
                        $('#makkah_acc_total_amount_'+i+'').val('');
                        $('#exchange_rate_price_funs_'+i+'').val('');
                        $('#price_per_room_exchange_rate_'+i+'').val('');
                        $('#price_per_person_exchange_rate_'+i+'').val('');
                        $('#price_total_amout_exchange_rate_'+i+'').val('');
                        
                        var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        $('.get_room_types_'+i+'').empty();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('/get_hotels_list') }}",
                            method: 'get',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "property_city_new": property_city_new,
                            },
                            success: function(result){
                                var user_hotels = result['user_hotels'];
                                $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                                $.each(user_hotels, function(key, value) {
                                    var attr_ID         = value.id;
                                    var property_name   = value.property_name;
                                    var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                    $('.get_room_types_'+i+'').append(data_append);
                                });
                            },
                            error:function(error){
                                console.log(error);
                            }
                        });
                        
                        var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                        var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                        var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                        var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                        var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                        if(switch_hotel_name == 1){
                            var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                        }else{
                            var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                        }
                        var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                        $('#acc_cost_html_'+i+'').html(html_data);
                        
                        $("#acc_hotel_CityName"+i+'').val(property_city_newN);
                        
                        $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                        $("#acc_hotel_CheckIn"+i+'').val(start_dateN);
                        $("#acc_hotel_CheckOut"+i+'').val(enddateN);
                        $("#acc_hotel_NoOfNights"+i+'').val(acomodation_nightsN);
                        $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                    });
                    
                    $('.check_in_hotel_'+i+'').on('change',function(){
                        
                        // Total
                        $('#room_quantity_'+i+'').css('display','none');
                        $('.room_quantity_'+i+'').val('');
                        
                        // Booked
                        $('#room_booked_quantity_'+i+'').css('display','none');
                        $('.room_booked_quantity_'+i+'').val('');
                        
                        // Availaible
                        $('#room_available_'+i+'').css('display','none');
                        $('.room_available_'+i+'').val('');
                        
                        // Over Booked
                        $('#room_over_booked_quantity_'+i+'').css('display','none');
                        $('.room_over_booked_quantity_'+i+'').val('');
                        
                        $('.acc_qty_class_'+i+'').val('');
                        $('.acc_pax_class_'+i+'').val('');
                        
                        $('#acc_hotel_name_'+i+'').val('');
                        // Room Type
                        $('#hotel_type_'+i+'').empty();
                        var hotel_RT_data = `<option value="">Choose ...</option>
                                            <option attr="2" value="Double">Double</option>
                                            <option attr="3" value="Triple">Triple</option>
                                            <option attr="4" value="Quad">Quad</option>`;
                        $('#hotel_type_'+i+'').append(hotel_RT_data);
                        
                        var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        $('.get_room_types_'+i+'').empty();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('/get_hotels_list') }}",
                            method: 'get',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "property_city_new": property_city_new,
                            },
                            success: function(result){
                                var user_hotels = result['user_hotels'];
                                $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                                $.each(user_hotels, function(key, value) {
                                    var attr_ID         = value.id;
                                    var property_name   = value.property_name;
                                    var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                    $('.get_room_types_'+i+'').append(data_append);
                                });
                            },
                            error:function(error){
                                console.log(error);
                            }
                        });
                        
                        // Total Nights
                        var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                        var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                        const date1     = new Date(start_date);
                        const date2     = new Date(enddate);
                        const diffTime  = Math.abs(date2 - date1);
                        const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        var diff        = (diffDays);
                        $("#acomodation_nights_"+i+'').val(diff);
                        
                        $("#acc_hotel_NoOfNights"+i+'').val(diff);
                        $("#acc_hotel_CheckIn"+i+'').val(start_date);
                        
                        // HOTEl NAME
                        $('#add_hotel_div'+i+'').css('display','');
                        $('.select_hotel_btn'+i+'').css('display','');
                        $('#select_hotel_div'+i+'').css('display','none');
                        $('.add_hotel_btn'+i+'').css('display','none');
                        $('#acc_hotel_name_class_'+i+'').css('display','');
                        
                        // HOTEl TYPE
                        // $('.get_room_types_'+i+'').empty();
                        $('.hotel_type_select_div_'+i+'').css('display','none');
                        $('.hotel_type_add_div_'+i+'').css('display','');
                        $('.hotel_type_class_'+i+'').empty();
                        var dataHTC =   `<option value="">Choose ...</option>
                                        <option attr="4" value="Quad">Quad</option>
                                        <option attr="3" value="Triple">Triple</option>
                                        <option attr="2" value="Double">Double</option>`;
                        $('.hotel_type_class_'+i+'').append(dataHTC);
                        
                        $('#switch_hotel_name'+i+'').val(1);
                        $('.acc_qty_class_'+i+'').empty();
                        $('.acc_pax_class_'+i+'').empty();
                        
                        // Meal Type
                        $('#hotel_meal_type_'+i+'').empty();
                        var hote_MT_data = `<option value="">Choose ...</option>
                                            <option value="Room only">Room only</option>
                                            <option value="Breakfast">Breakfast</option>
                                            <option value="Lunch">Lunch</option>
                                            <option value="Dinner">Dinner</option>`;
                        $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                        
                         // Price Section
                        $('#hotel_price_for_week_end_'+i+'').empty();
                        $('#makkah_acc_room_price_'+i+'').val('');
                        $('#makkah_acc_price_'+i+'').val('');
                        $('#makkah_acc_total_amount_'+i+'').val('');
                        $('#exchange_rate_price_funs_'+i+'').val('');
                        $('#price_per_room_exchange_rate_'+i+'').val('');
                        $('#price_per_person_exchange_rate_'+i+'').val('');
                        $('#price_total_amout_exchange_rate_'+i+'').val('');
                        
                        var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                        var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                        var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                        var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                        var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                        if(switch_hotel_name == 1){
                            var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                        }else{
                            var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                        }
                        var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                        $('#acc_cost_html_'+i+'').html(html_data);
                        
                        $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                        $("#acc_hotel_CheckIn"+i+'').val(start_dateN);
                        $("#acc_hotel_CheckOut"+i+'').val(enddateN);
                        $("#acc_hotel_NoOfNights"+i+'').val(acomodation_nightsN);
                        $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                    });
                    
                    $('.check_out_hotel_'+i+'').on('change',function(){
                        
                        // Total
                        $('#room_quantity_'+i+'').css('display','none');
                        $('.room_quantity_'+i+'').val('');
                        
                        // Booked
                        $('#room_booked_quantity_'+i+'').css('display','none');
                        $('.room_booked_quantity_'+i+'').val('');
                        
                        // Availaible
                        $('#room_available_'+i+'').css('display','none');
                        $('.room_available_'+i+'').val('');
                        
                        // Over Booked
                        $('#room_over_booked_quantity_'+i+'').css('display','none');
                        $('.room_over_booked_quantity_'+i+'').val('');
                        
                        $('.acc_qty_class_'+i+'').val('');
                        $('.acc_pax_class_'+i+'').val('');
                        
                        $('#acc_hotel_name_'+i+'').val('');
                        // Room Type
                        $('#hotel_type_'+i+'').empty();
                        var hotel_RT_data = `<option value="">Choose ...</option>
                                            <option attr="2" value="Double">Double</option>
                                            <option attr="3" value="Triple">Triple</option>
                                            <option attr="4" value="Quad">Quad</option>`;
                        $('#hotel_type_'+i+'').append(hotel_RT_data);
                        
                        var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        $('.get_room_types_'+i+'').empty();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ url('/get_hotels_list') }}",
                            method: 'get',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "property_city_new": property_city_new,
                            },
                            success: function(result){
                                var user_hotels = result['user_hotels'];
                                $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                                $.each(user_hotels, function(key, value) {
                                    var attr_ID         = value.id;
                                    var property_name   = value.property_name;
                                    var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                    $('.get_room_types_'+i+'').append(data_append);
                                });
                            },
                            error:function(error){
                                console.log(error);
                            }
                        });
                        
                        // Total Nights
                        var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                        var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                        const date1     = new Date(start_date);
                        const date2     = new Date(enddate);
                        const diffTime  = Math.abs(date2 - date1);
                        const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        var diff        = (diffDays);
                        $("#acomodation_nights_"+i+'').val(diff);
                        
                        $("#acc_hotel_NoOfNights"+i+'').val(diff);
                        $("#acc_hotel_CheckOut"+i+'').val(enddate);
                        
                        // HOTEl NAME
                        $('#add_hotel_div'+i+'').css('display','');
                        $('.select_hotel_btn'+i+'').css('display','');
                        $('#select_hotel_div'+i+'').css('display','none');
                        $('.add_hotel_btn'+i+'').css('display','none');
                        $('#acc_hotel_name_class_'+i+'').css('display','');
                        
                        // HOTEl TYPE
                        // $('.get_room_types_'+i+'').empty();
                        $('.hotel_type_select_div_'+i+'').css('display','none');
                        $('.hotel_type_add_div_'+i+'').css('display','');
                        $('.hotel_type_class_'+i+'').empty();
                        var dataHTC =   `<option value="">Choose ...</option>
                                        <option attr="4" value="Quad">Quad</option>
                                        <option attr="3" value="Triple">Triple</option>
                                        <option attr="2" value="Double">Double</option>`;
                        $('.hotel_type_class_'+i+'').append(dataHTC);
                        
                        $('#switch_hotel_name'+i+'').val(1);
                        $('.acc_qty_class_'+i+'').empty();
                        $('.acc_pax_class_'+i+'').empty();
                        
                        $('#hotel_meal_type_'+i+'').empty();
                        var hote_MT_data = `<option value="">Choose ...</option>
                                            <option value="Room only">Room only</option>
                                            <option value="Breakfast">Breakfast</option>
                                            <option value="Lunch">Lunch</option>
                                            <option value="Dinner">Dinner</option>`;
                        $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                        
                        // Price Section
                        $('#hotel_price_for_week_end_'+i+'').empty();
                        $('#makkah_acc_room_price_'+i+'').val('');
                        $('#makkah_acc_price_'+i+'').val('');
                        $('#makkah_acc_total_amount_'+i+'').val('');
                        $('#exchange_rate_price_funs_'+i+'').val('');
                        $('#price_per_room_exchange_rate_'+i+'').val('');
                        $('#price_per_person_exchange_rate_'+i+'').val('');
                        $('#price_total_amout_exchange_rate_'+i+'').val('');
                        
                        var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                        var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                        var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                        var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                        var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                        var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                        if(switch_hotel_name == 1){
                            var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                        }else{
                            var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                        }
                        var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                        $('#acc_cost_html_'+i+'').html(html_data);
                        
                        $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                        $("#acc_hotel_CheckIn"+i+'').val(start_dateN);
                        $("#acc_hotel_CheckOut"+i+'').val(enddateN);
                        $("#acc_hotel_NoOfNights"+i+'').val(acomodation_nightsN);
                        $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                        
                    });
                    
                    var value_c         = $("#currency_conversion1").val();
                    const usingSplit    = value_c.split(' ');
                    var value_1         = usingSplit['0'];
                    var value_2         = usingSplit['2'];
                    $(".currency_value1").html(value_1);
                    $(".currency_value_exchange_1").html(value_2);
                    exchange_currency_funs(value_1,value_2);
                    
                    // hotel_markup
                    $('#hotel_markup_'+i+'').on('change',function(){
                        var hotel_markup_total = $('#hotel_markup_total_'+i+'').val();
                        console.log('hotel_markup_total1 :'+hotel_markup_total);
                        $('#hotel_invoice_markup_'+i+'').val(hotel_markup_total);
                    });
                }
                $("#select_accomodation").slideToggle();
            }
            else{
                alert("Select Hotels Quantity");
            }
            
        });
        
        var city_No1        = $('#city_No1').val();
        var img_Counter1    = $('#img_Counter1').val();
        var j               = img_Counter1;
        $("#add_hotel_accomodation_editI").on('click',function(){
            
            city_No1 = parseFloat(city_No1) + 1;
            $('#city_No1').val(city_No1);
            
            var city_No = $('#city_No').val();
            
            var i = $('#count_hotel').val();
            $('#count_hotel').val(i);
            $('#city_No').val(i);
            
            var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotel${i}">
                                    
                            <h4>
                                City #${i}
                            </h4> 
                            <div class="row" style="padding-bottom: 25px;">
                                <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" onchange="makkah_accomodation_check_inI(${i})" class="form-control makkah_accomodation_check_in_class_${i} check_in_hotel_${i}">
                                </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}" name="acc_check_out[]" onchange="makkah_accomodation_check_outI(${i})" class="form-control makkah_accomodation_check_out_date_class_${i} check_out_hotel_${i}"></div>
                                
                                <div class="col-xl-3">
                                    <label for="">Select City</label>
                                    <select type="text" id="property_city_new${i}" onchange="put_tour_locationI(${i})" name="hotel_city_name[]" class="form-control property_city_new"></select>
                                </div>
                
                                <div class="col-xl-3">
                                    <label for="">Hotel Name</label>
                                    
                                    <input type="text" id="switch_hotel_name${i}" name="switch_hotel_name[]" value="1" style="display:none">
                                    
                                    <div class="input-group" id="add_hotel_div${i}">
                                        <input type="text" onkeyup="hotel_funI(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_${i}">
                                    </div>
                                    <a style="float: right;font-size: 10px;width: 102px;" onclick="select_hotel_btn(${i})" class="btn btn-primary select_hotel_btn${i}">
                                        SELECT HOTEL
                                    </a>
                                    
                                    <div class="input-group" id="select_hotel_div${i}" style="display:none">
                                        <select onchange="get_room_types(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name_select[]" class="form-control acc_hotel_name_class_${i} get_room_types_${i}"></select>
                                    </div>
                                    <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="add_hotel_btn(${i})" class="btn btn-primary add_hotel_btn${i}">
                                        ADD HOTEL
                                    </a>
                                </div>
                                
                                
                                <div class="col-xl-3"><label for="">No Of Nights</label>
                                <input readonly type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                
                                <input readonly type="hidden" id="acc_nights_key_${i}" value="${i}" class="form-control">
                                
                                <div class="col-xl-3"><label for="">Room Type</label>
                                    
                                    <div class="input-group hotel_type_add_div_${i}">
                                        <select onchange="hotel_type_funI(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}" data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                            <option attr="4" value="Quad">Quad</option>
                                            <option attr="3" value="Triple">Triple</option>
                                            <option attr="2" value="Double">Double</option>
                                        </select>
                                    </div>
                                    
                                   
                                    
                                    <select onchange="hotel_type_funInvoice(${i})" style="display:none" name="acc_type_select[]" id="hotel_type_${i}" class="hotel_type_select_div_${i} form-control other_Hotel_Type hotel_type_class_${i} hotel_type_select_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                            </select>

                                            <select onchange="add_new_room_type(${i})" name="new_rooms_type[]" style="display:none;" id="new_rooms_type_${i}" class="form-control other_Hotel_Type new_rooms_type_${i} "  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                            </select>
                                            <input type="text" id="select_add_new_room_type_${i}" hidden name="new_add_room[]" value="false">
                                    
                                </div>
                                
                                <div class="col-xl-3" id="new_room_supplier_div_${i}" style="display:none">
                                    <label for="">Select Supplier</label>
                                    <select class="form-control" id="new_room_supplier_${i}" name="new_room_supplier[]">
                                        <option>Select One</option>
                                    </select>
                                </div>
                                        
                                <div class="col-xl-3">
                                    <label for="">Quantity</label>
                                    <input type="text" onkeyup="acc_qty_classI(${i})" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}">
                                    <div class="row" style="padding: 2px;">
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-success" id="room_quantity_${i}"></a>
                                            <input type="hidden" class="room_quantity_${i}">
                                        </div>
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-primary" id="room_available_${i}"></a>
                                            <input type="hidden" class="room_available_${i}">
                                        </div>
                                    </div>
                                    
                                    <div class="row" style="padding: 2px;">
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-info" id="room_booked_quantity_${i}"></a>
                                            <input type="hidden" class="room_booked_quantity_${i}">
                                        </div>
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-danger" id="room_over_booked_quantity_${i}"></a>
                                            <input type="hidden" class="room_over_booked_quantity_${i}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Pax</label>
                                    <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Meal Type</label>
                                    <select name="hotel_meal_type[]" id="hotel_meal_type_${i}" class="form-control"  data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                                    </select>
                                </div>
                                
                                <div id="hotel_price_for_week_end_${i}" class="row"></div>
                                
                                <h4 class="mt-4">Purchase Price</h4>
                                
                                    <input type="hidden" id="hotel_invoice_markup_${i}" name="hotel_invoice_markup[]">
                                    
                                    <div class="col-xl-4">
                                        <label for="">Price Per Room/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="makkah_acc_room_price_${i}" onkeyup="makkah_acc_room_price_funsI(${i})" value="" name="price_per_room_purchase[]" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <label for="">Price Per Person/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value2">
                                                 
                                                </a>
                                            </span>
                                            <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price_purchase[]" class="form-control makkah_acc_price_class_${i}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                         <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value3">
                                                  
                                                </a>
                                            </span>
                                            <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount_purchase[]" class="form-control makkah_acc_total_amount_class_${i}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6">
                                        <label for="">Exchange Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="exchange_rate_price_funs_${i}" onkeyup="exchange_rate_price_funsI(${i})" value="" name="exchange_rate_price[]" class="form-control">
                                        </div>
                                    </div>
                                        
                                    <div class="col-xl-6"></div>
                                    
                                    <h4 class="mt-4">Sale Price</h4>
                                    <div class="col-xl-4">
                                        <label for="">Price Per Room/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="price_per_room_exchange_rate_${i}" name="price_per_room_sale[]" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <label for="">Price Per Person/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="price_per_person_exchange_rate_${i}" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                         <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_3">
                                                  
                                                </a>
                                            </span>
                                            <input readonly type="text"  id="price_total_amout_exchange_rate_${i}"  name="acc_total_amount[]" class="form-control">
                                        </div>
                                    </div>
                
                                <div id="append_add_accomodation_${i}"></div>
                                <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_Invoice(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Room Amenities</label>
                                        <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                      
                                    </div>
                                </div>
                
                                <div class="col-xl-12"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                
                                <div class="mt-2">
                                    <a href="javascript:;" onclick="remove_hotels(${i})" id="${i}" class="btn btn-danger" style="float: right;"> 
                                        Delete Hotel
                                    </a>
                                </div>
                                
                            </div>
                        </div>`;
          
            var data_cost=`<div class="row" id="costing_acc${i}" style="margin-bottom:20px;">
            
                                <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                
                                <input type="text" name="hotel_name_markup[]" hidden>
                                
                                <input type="hidden" name="acc_hotel_CityName[]" id="acc_hotel_CityName${i}">
                                <input type="hidden" name="acc_hotel_HotelName[]" id="acc_hotel_HotelName${i}">
                                <input type="hidden" name="acc_hotel_CheckIn[]" id="acc_hotel_CheckIn${i}">
                                <input type="hidden" name="acc_hotel_CheckOut[]" id="acc_hotel_CheckOut${i}">
                                <input type="hidden" name="acc_hotel_NoOfNights[]" id="acc_hotel_NoOfNights${i}">
                                <input type="hidden" name="acc_hotel_Quantity[]" id="acc_hotel_Quantity${i}">
                                
                                <div class="col-xl-12">
                                    <h4 id="acc_cost_html_${i}">Accomodation Cost</h4>
                                </div>
                        
                                <div class="col-xl-3">
                                    <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="input-group">
                                        <input type="text" id="hotel_acc_price_per_night_${i}" readonly="" name="without_markup_price_single[]" class="form-control">    
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="input-group">
                                        <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">         
                                    <select name="markup_type[]" onchange="hotel_markup_typeI(${i})" id="hotel_markup_types_${i}" class="form-control">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                        <option value="per_Night">Per Night</option>
                                    </select>
                                </div>
                                
                                <div class="col-xl-3 markup_value_Div_${i}" style="display:none;margin-top:10px">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]" onkeyup="hotel_markup_funI(${i})">
                                        <span class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}" class="currency_value1">SAR</div></button>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 exchnage_rate_Div_${i}" style="display:none;margin-top:10px">
                                    <input type="text" id="hotel_exchage_rate_per_night_${i}" readonly="" name="exchage_rate_single[]" class="form-control">    
                                </div>
                                
                                <div class="col-xl-3 markup_price_Div_${i}" style="display:none;margin-top:10px">
                                    <div class="input-group">
                                        <input type="text" id="hotel_exchage_rate_markup_total_per_night_${i}" readonly="" name="markup_total_per_night[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div> 
                                </div>
                                
                                <div class="col-xl-3 markup_total_price_Div_${i}" style="display:none;margin-top:10px" style="margin-top:10px">
                                    <div class="input-group">
                                        <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div> 
                                </div>
                                
                            </div>`;
          
            $("#append_accomodation_data_cost").append(data_cost);
          
            $("#append_accomodation").append(data);
            // NEW
                
                var country = $('#property_countryI').val();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                
                $.ajax({
                    url: "{{ url('/country_cites1') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": country,
                    },
                    success: function(result){
                        console.log(result);
                        var options = result.options;
                        $('.property_city_new').html(options);
                        $('#property_city_new'+i+'').html(options);
                        
                    },
                    error:function(error){
                        // console.log(error);
                    }
                });
                
                $('.accomodation_image_edit'+j+'').change(function () {
                    var c = $('#del_counter1').val();
                    if (typeof (FileReader) != "undefined") {
                        var dvPreview = $("#dvPreview");
                        dvPreview.html("");
                        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                        $($(this)[0].files).each(function () {
                            var file = $(this);
                            if (regex.test(file[0].name.toLowerCase())) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    
                                    var img = $("<img />");
                                    img.attr("style", "height:150px;width:233px;margin-bottom: 10px");
                                    img.attr("src", e.target.result);
                                    img.attr("id", j);
                                    
                                    var img_Name = e.target.result;
                                    
                                    var befor_Img = `<div class="col-md-3" id="accImg${c}" style="text-align: center;">
                                                        <input type="text" name="accomodation_image_else${j}[]" class="form-control" value="${img_Name}" readonly hidden>
                                                    </div>`;
                                    
                                    var after_Img = `<button class="btn btn-danger" type="button" onclick="remove_acc_img(${c})" style="margin-bottom: 10px">Delete</button>`;
                                    
                                    dvPreview.append(befor_Img)
                                    
                                    var accImg = $('#accImg'+c+'');
                                    
                                    accImg.append(img);
                                    accImg.append(after_Img);
                                    
                                    c = parseFloat(c)+1;
                                }
                                reader.readAsDataURL(file[0]);
                            } else {
                                alert(file[0].name + " is not a valid image file.");
                                dvPreview.html("");
                                return false;
                            }
                        });
                    } else {
                        alert("This browser does not support HTML5 FileReader.");
                    }
                });
                
                j = parseFloat(j) + 1;
                
                var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('acc_hotel_name_'+i+'')
                );
                
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    // console.log(places_D1);
                    var address = places_D1.formatted_address;
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
                                        results[0].address_components.length - 2
                                    ].short_name;
                                $('#country').val(country);
                                $('#lat').val(latitude);
                                $('#long').val(longitude);
                                $('#pin').val(pin);
                                $('#city').val(city);
                                $('#country_code').val(country_code);
                            }
                        }
                    });
                });
                
                $('#property_city_new'+i+'').on('change',function(){
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    // Meal Type
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                     // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    
                    var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    $('.get_room_types_'+i+'').empty();
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('/get_hotels_list') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "property_city_new": property_city_new,
                        },
                        success: function(result){
                            var user_hotels = result['user_hotels'];
                            $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                            $.each(user_hotels, function(key, value) {
                                var attr_ID         = value.id;
                                var property_name   = value.property_name;
                                var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                $('.get_room_types_'+i+'').append(data_append);
                            });
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                    $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                    $('#acc_hotel_CityName'+i+'').val(property_city_newN);
                    $('#acc_hotel_CheckIn'+i+'').val(start_dateN);
                    $('#acc_hotel_CheckOut'+i+'').val(enddateN);
                    $('#acc_hotel_NoOfNights'+i+'').val(acomodation_nightsN);
                    $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                });
                
                $('.check_in_hotel_'+i+'').on('change',function(){
                    
                    var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    $('.get_room_types_'+i+'').empty();
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('/get_hotels_list') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "property_city_new": property_city_new,
                        },
                        success: function(result){
                            var user_hotels = result['user_hotels'];
                            $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                            $.each(user_hotels, function(key, value) {
                                var attr_ID         = value.id;
                                var property_name   = value.property_name;
                                var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                $('.get_room_types_'+i+'').append(data_append);
                            });
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                    
                    // Total Nights
                    var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    const date1     = new Date(start_date);
                    const date2     = new Date(enddate);
                    const diffTime  = Math.abs(date2 - date1);
                    const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    var diff        = (diffDays);
                    $("#acomodation_nights_"+i+'').val(diff);
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    // $('.get_room_types_'+i+'').empty();
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    // Meal Type
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                     // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                    $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                    $('#acc_hotel_CityName'+i+'').val(property_city_newN);
                    $('#acc_hotel_CheckIn'+i+'').val(start_dateN);
                    $('#acc_hotel_CheckOut'+i+'').val(enddateN);
                    $('#acc_hotel_NoOfNights'+i+'').val(acomodation_nightsN);
                    $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                });
                
                $('.check_out_hotel_'+i+'').on('change',function(){
                    
                    var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    $('.get_room_types_'+i+'').empty();
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('/get_hotels_list') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "property_city_new": property_city_new,
                        },
                        success: function(result){
                            var user_hotels = result['user_hotels'];
                            $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                            $.each(user_hotels, function(key, value) {
                                var attr_ID         = value.id;
                                var property_name   = value.property_name;
                                var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                $('.get_room_types_'+i+'').append(data_append);
                            });
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                    
                    // Total Nights
                    var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    const date1     = new Date(start_date);
                    const date2     = new Date(enddate);
                    const diffTime  = Math.abs(date2 - date1);
                    const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    var diff        = (diffDays);
                    $("#acomodation_nights_"+i+'').val(diff);
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    // $('.get_room_types_'+i+'').empty();
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                    // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                    $("#acc_hotel_HotelName"+i+'').val(acc_hotel_nameN);
                    $('#acc_hotel_CityName'+i+'').val(property_city_newN);
                    $('#acc_hotel_CheckIn'+i+'').val(start_dateN);
                    $('#acc_hotel_CheckOut'+i+'').val(enddateN);
                    $('#acc_hotel_NoOfNights'+i+'').val(acomodation_nightsN);
                    $('#acc_hotel_Quantity'+i+'').val(acc_qty_classN);
                    
                });
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                exchange_currency_funs(value_1,value_2);
                 
                // hotel_markup
                $('#hotel_markup_'+i+'').on('change',function(){
                    var hotel_markup_total = $('#hotel_markup_total_'+i+'').val();
                    console.log('hotel_markup_total1 :'+hotel_markup_total);
                    $('#hotel_invoice_markup_'+i+'').val(hotel_markup_total);
                });
                
                $("#select_accomodation").slideToggle();
                
                i  = parseFloat(i) + 1;
                $('#count_hotel').val(i);
        });
        
        function makkah_accomodation_check_inI(id){
            
            // Total
            $('#room_quantity_'+id+'').css('display','none');
            $('.room_quantity_'+id+'').val('');
            
            // Booked
            $('#room_booked_quantity_'+id+'').css('display','none');
            $('.room_booked_quantity_'+id+'').val('');
            
            // Availaible
            $('#room_available_'+id+'').css('display','none');
            $('.room_available_'+id+'').val('');
            
            // Over Booked
            $('#room_over_booked_quantity_'+id+'').css('display','none');
            $('.room_over_booked_quantity_'+id+'').val('');
            
            $('#acc_hotel_name_'+id+'').val('');
            // Room Type
            $('#hotel_type_'+id+'').empty();
            var hotel_RT_data = `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
            $('#hotel_type_'+id+'').append(hotel_RT_data);
            
            var property_city_new = $('#property_city_new'+id+'').find('option:selected').attr('value');
            $('.get_room_types_'+id+'').empty();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/get_hotels_list') }}",
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "property_city_new": property_city_new,
                },
                success: function(result){
                    var user_hotels = result['user_hotels'];
                    $('.get_room_types_'+id+'').append('<option>Select Hotel</option>');
                    $.each(user_hotels, function(key, value) {
                        var attr_ID         = value.id;
                        var property_name   = value.property_name;
                        var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                        $('.get_room_types_'+id+'').append(data_append);
                    });
                },
                error:function(error){
                    console.log(error);
                }
            });
            
            $('#switch_hotel_name'+id+'').val(1);
            $('#add_hotel_div'+id+'').css('display','');
            $('#select_hotel_div'+id+'').css('display','none');
            $('.select_hotel_btn'+id+'').css('display','');
            $('.add_hotel_btn'+id+'').css('display','none');
            $('.hotel_type_add_div_'+id+'').css('display','');
            $('.hotel_type_select_div_'+id+'').css('display','none');
            
            $('.acc_qty_class_'+id+'').val('');
            $('.acc_pax_class_'+id+'').val('');
            
            $('.more_hotel_type_select_class_'+id+'').empty();
            
            // Meal Type
            $('#hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#hotel_meal_type_'+id+'').append(hote_MT_data);
            
            // Price Section
            $('#makkah_acc_room_price_funs_'+id+'').val('');
            $('#acc_price_get_'+id+'').val('');
            $('#acc_total_amount_'+id+'').val('');
            
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
            
            // Total Nights
            var start_date  = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#makkah_accomodation_check_out_date_'+id+'').val();
            const date1     = new Date(start_date);
            const date2     = new Date(enddate);
            const diffTime  = Math.abs(date2 - date1);
            const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            var diff        = (diffDays);
            $("#acomodation_nights_"+id+'').val(diff);
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            console.log(property_city_newN);
            if(property_city_newN == null || property_city_newN == ''){
                var property_city_newN = $('.property_city_new_'+id+'').val();
            }
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
            
            $('#makkah_acc_room_price_'+id+'').attr('readonly', false);
        }
        
        function makkah_accomodation_check_outI(id){
            
            // Total
            $('#room_quantity_'+id+'').css('display','none');
            $('.room_quantity_'+id+'').val('');
            
            // Booked
            $('#room_booked_quantity_'+id+'').css('display','none');
            $('.room_booked_quantity_'+id+'').val('');
            
            // Availaible
            $('#room_available_'+id+'').css('display','none');
            $('.room_available_'+id+'').val('');
            
            // Over Booked
            $('#room_over_booked_quantity_'+id+'').css('display','none');
            $('.room_over_booked_quantity_'+id+'').val('');
            
            $('#acc_hotel_name_'+id+'').val('');
            // Room Type
            $('#hotel_type_'+id+'').empty();
            var hotel_RT_data = `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
            $('#hotel_type_'+id+'').append(hotel_RT_data);
            
            var property_city_new = $('#property_city_new'+id+'').find('option:selected').attr('value');
            $('.get_room_types_'+id+'').empty();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/get_hotels_list') }}",
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "property_city_new": property_city_new,
                },
                success: function(result){
                    var user_hotels = result['user_hotels'];
                    $('.get_room_types_'+id+'').append('<option>Select Hotel</option>');
                    $.each(user_hotels, function(key, value) {
                        var attr_ID         = value.id;
                        var property_name   = value.property_name;
                        var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                        $('.get_room_types_'+id+'').append(data_append);
                    });
                },
                error:function(error){
                    console.log(error);
                }
            });
            
            $('#switch_hotel_name'+id+'').val(1);
            $('#add_hotel_div'+id+'').css('display','');
            $('#select_hotel_div'+id+'').css('display','none');
            $('.select_hotel_btn'+id+'').css('display','');
            $('.add_hotel_btn'+id+'').css('display','none');
            $('.hotel_type_add_div_'+id+'').css('display','');
            $('.hotel_type_select_div_'+id+'').css('display','none');
            
            $('.acc_qty_class_'+id+'').val('');
            $('.acc_pax_class_'+id+'').val('');
            
            $('.more_hotel_type_select_class_'+id+'').empty();
            
            // Meal Type
            $('#hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#hotel_meal_type_'+id+'').append(hote_MT_data);
            
            // Price Section
            $('#makkah_acc_room_price_funs_'+id+'').val('');
            $('#acc_price_get_'+id+'').val('');
            $('#acc_total_amount_'+id+'').val('');
            
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
            
            // Total Nights
            var start_date  = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#makkah_accomodation_check_out_date_'+id+'').val();
            const date1     = new Date(start_date);
            const date2     = new Date(enddate);
            const diffTime  = Math.abs(date2 - date1);
            const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            var diff        = (diffDays);
            $("#acomodation_nights_"+id+'').val(diff);
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            if(property_city_newN == null || property_city_newN == ''){
                var property_city_newN = $('.property_city_new_'+id+'').val();
            }
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
            
            $('#makkah_acc_room_price_'+id+'').attr('readonly', false);
        }
        
        function hotel_funI(id){
            var acc_hotel_name = $('#acc_hotel_name_'+id+'').val();
            $('#hotel_name_acc_'+id+'').val(acc_hotel_name);
            $('#hotel_name_markup'+id+'').val(acc_hotel_name);
            
            var places_D1 = new google.maps.places.Autocomplete(
                document.getElementById('acc_hotel_name_'+id+'')
            );
            
            google.maps.event.addListener(places_D1, "place_changed", function () {
                var places_D1 = places_D1.getPlace();
                // console.log(places_D1);
                var address = places_D1.formatted_address;
                var latitude = places_D1.geometry.location.lat();
                var longitude = places_D1.geometry.location.lng();
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = (geocoder = new google.maps.Geocoder());
                geocoder.geocode({ latLng: latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address = results[0].formatted_address;
                            var pin = results[0].address_components[
                                results[0].address_components.length - 1
                            ].long_name;
                            var country =  results[0].address_components[
                                results[0].address_components.length - 2
                              ].long_name;
                            var state = results[0].address_components[
                                    results[0].address_components.length - 3
                                ].long_name;
                            var city = results[0].address_components[
                                    results[0].address_components.length - 4
                                ].long_name;
                            var country_code = results[0].address_components[
                                    results[0].address_components.length - 2
                                ].short_name;
                            $('#country').val(country);
                            $('#lat').val(latitude);
                            $('#long').val(longitude);
                            $('#pin').val(pin);
                            $('#city').val(city);
                            $('#country_code').val(country_code);
                        }
                    }
                });
            });
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            if(property_city_newN == null || property_city_newN == ''){
                var property_city_newN = $('.property_city_new_'+id+'').val();
            }
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function select_hotel_btn(id){
            var start_date  = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#makkah_accomodation_check_out_date_'+id+'').val();
            
            if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                
                $('#hotel_invoice_markup_'+id+'').val('');
                $('#hotel_supplier_id_'+id+'').val('');
                $('#hotel_type_id_'+id+'').val('');
                $('#hotel_type_cat_'+id+'').val('');
                $('#hotelRoom_type_id_'+id+'').val('');
                $('#hotelRoom_type_idM_'+id+'').val('');
                
                
                $('#switch_htfI_'+id+'').val(0);
                // Total
                $('#room_quantity_'+id+'').css('display','none');
                $('.room_quantity_'+id+'').val('');
                
                // Booked
                $('#room_booked_quantity_'+id+'').css('display','none');
                $('.room_booked_quantity_'+id+'').val('');
                
                // Availaible
                $('#room_available_'+id+'').css('display','none');
                $('.room_available_'+id+'').val('');
                
                // Over Booked
                $('#room_over_booked_quantity_'+id+'').css('display','none');
                $('.room_over_booked_quantity_'+id+'').val('');
                
                $('#switch_hotel_name'+id+'').val(0);
                $('#add_hotel_div'+id+'').css('display','none');
                $('#select_hotel_div'+id+'').css('display','');
                $('.select_hotel_btn'+id+'').css('display','none');
                $('.add_hotel_btn'+id+'').css('display','');
                $('.hotel_type_add_div_'+id+'').css('display','none');
                $('.hotel_type_select_div_'+id+'').css('display','');
                
                $('.acc_qty_class_'+id+'').val('');
                $('.acc_pax_class_'+id+'').val('');
                
                $('.hotel_type_select_class_'+id+'').empty();
                
                // Meal Type
                $('#hotel_meal_type_'+id+'').empty();
                var hote_MT_data = `<option value="">Choose ...</option>
                                    <option value="Room only">Room only</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>`;
                $('#hotel_meal_type_'+id+'').append(hote_MT_data);
                
                // Price Section
                $('#hotel_price_for_week_end_'+id+'').empty();
                $('#makkah_acc_room_price_'+id+'').val('');
                $('#makkah_acc_price_'+id+'').val('');
                $('#makkah_acc_total_amount_'+id+'').val('');
                $('#exchange_rate_price_funs_'+id+'').val('');
                $('#price_per_room_exchange_rate_'+id+'').val('');
                $('#price_per_person_exchange_rate_'+id+'').val('');
                $('#price_total_amout_exchange_rate_'+id+'').val('');
                
                var property_city_new = $('#property_city_new'+id+'').find('option:selected').attr('value');
                
                if(property_city_new == null || property_city_new == ''){
                    var property_city_new = $('.property_city_new_'+id+'').val();
                    console.log('if : '+property_city_new)
                }
                
                $('.get_room_types_'+id+'').empty();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/get_hotels_list') }}",
                    method: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "property_city_new": property_city_new,
                    },
                    success: function(result){
                        var user_hotels = result['user_hotels'];
                        $('.get_room_types_'+id+'').append('<option>Select Hotel</option>');
                        $.each(user_hotels, function(key, value) {
                            var attr_ID         = value.id;
                            var property_name   = value.property_name;
                            var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                            $('.get_room_types_'+id+'').append(data_append);
                        });
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
                
            }else{
                alert('Select Date First!');
            }
        }
        
        function add_hotel_btn(id){
            
            $('#switch_htfI_'+id+'').val(0);
            // Total
            $('#room_quantity_'+id+'').css('display','none');
            $('.room_quantity_'+id+'').val('');
            
            // Booked
            $('#room_booked_quantity_'+id+'').css('display','none');
            $('.room_booked_quantity_'+id+'').val('');
            
            // Availaible
            $('#room_available_'+id+'').css('display','none');
            $('.room_available_'+id+'').val('');
            
            // Over Booked
            $('#room_over_booked_quantity_'+id+'').css('display','none');
            $('.room_over_booked_quantity_'+id+'').val('');
            
            $('#switch_hotel_name'+id+'').val(1);
            $('#add_hotel_div'+id+'').css('display','');
            $('#select_hotel_div'+id+'').css('display','none');
            $('.add_hotel_btn'+id+'').css('display','none');
            $('.select_hotel_btn'+id+'').css('display','');
            $('.hotel_type_add_div_'+id+'').css('display','');
            $('.hotel_type_select_div_'+id+'').css('display','none');
            
            $('.acc_qty_class_'+id+'').val('');
            $('.acc_pax_class_'+id+'').val('');
            
            $('.hotel_type_class_'+id+'').empty();
            var dataHTC =   `<option value="">Choose ...</option>
                            <option attr="4" value="Quad">Quad</option>
                            <option attr="3" value="Triple">Triple</option>
                            <option attr="2" value="Double">Double</option>`;
            
            $('.hotel_type_class_'+id+'').append(dataHTC);
            
            // Meal Type
            $('#hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#hotel_meal_type_'+id+'').append(hote_MT_data);
            
            
            // Price Section
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
            
            $('#makkah_acc_room_price_'+id+'').attr('readonly', false);
        }
        
        function countDaysOfWeekBetweenDates(sDate,eDate){
            const startDate = new Date(sDate)
            const endDate = new Date(eDate);
            const daysOfWeekCount = { 
                0: 0,
                1: 0,
                2: 0,
                3: 0,
                4: 0,
                5: 0,
                6: 0
            };
            while (startDate < endDate) {
                daysOfWeekCount[startDate.getDay()] = daysOfWeekCount[startDate.getDay()] + 1;
                startDate.setDate(startDate.getDate() + 1);
            }
            return daysOfWeekCount;
        };
        
        var supplier_detail = {!!json_encode($supplier_detail)!!};
        
        function get_room_types(id){
            
            var hotel_id = $('.get_room_types_'+id+'').find('option:selected').attr('attr_ID');
            console.log('id is '+hotel_id);
            $('#select_hotel_id'+id+'').val(hotel_id);
            
            ids                 = $('.get_room_types_'+id+'').find('option:selected').attr('attr_ID');
            var start_date      = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddate         = $('#makkah_accomodation_check_out_date_'+id+'').val();
            const weekDaysCount = countDaysOfWeekBetweenDates(start_date, enddate);
            
            var Sunday_D    = weekDaysCount[0];
            var Monday_D    = weekDaysCount[1];
            var Tuesday_D   = weekDaysCount[2];
            var Wednesday_D = weekDaysCount[3];
            var Thursday_D  = weekDaysCount[4];
            var Friday_D    = weekDaysCount[5];
            var Saturday_D  = weekDaysCount[6];
            var total_days = parseFloat(Sunday_D) + parseFloat(Monday_D) + parseFloat(Tuesday_D) + parseFloat(Wednesday_D) + parseFloat(Thursday_D) + parseFloat(Friday_D) + parseFloat(Saturday_D);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/get_rooms_list') }}",
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": ids,
                    "check_in": start_date,
                    "check_out": enddate,
                },
                success: function(result){
                    var user_rooms              = result['user_rooms'];
                    var price_All               = 0;
                    var price_Single            = 0;
                    var total_WeekDays          = 0;
                    var total_WeekEnds          = 0;
                    var price_WeekDays          = 0;
                    var price_WeekEnds          = 0;
                    var more_total_WeekDays     = 0;
                    var more_total_WeekEnds     = 0;
                    var more_price_WeekDays     = 0;
                    var more_price_WeekEnds     = 0;
                    if(user_rooms !== null && user_rooms !== '' && user_rooms.length != 0){
                        $('#new_rooms_type_'+id+'').css('display','none');
                        $('#new_room_supplier_div_'+id+'').css('display','none');
                        $('.hotel_type_add_div_'+id+'').css('display','none');
                        $('.hotel_type_select_div_'+id+'').css('display','');
                        $('.hotel_type_select_class_'+id+'').empty();
                        $('.hotel_type_select_class_'+id+'').append('<option value="">Select Hotel Type...</option>')
                        
                        if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                            $.each(user_rooms, function(key, value) {
                                var availible_from          = value.availible_from;
                                var availible_to            = value.availible_to;
                                var more_room_type_details  = value.more_room_type_details;
                                
                                if(Date.parse(start_date) >= Date.parse(availible_from) && Date.parse(enddate) <= Date.parse(availible_to)){
                                    
                                    var price_week_type     = value.price_week_type;
                                    var room_supplier_name  = value.room_supplier_name;
                                    var room_supplier_id    = value.room_supplier_name;
                                    $.each(supplier_detail, function(key, supplier_detailS) {
                                        var id = supplier_detailS.id;
                                        if(id == room_supplier_name){
                                            room_supplier_name  = supplier_detailS.room_supplier_name;
                                        }
                                    });
                                    
                                    var room_meal_type      = value.room_meal_type;
                                    var hotelRoom_type_id   = value.id;
                                    var hotelRoom_type_idM  = '';
                                    
                                    if(value.room_type_cat != null && value.room_type_cat != ''){
                                        var room_type_cat   = value.room_type_cat;
                                        var room_type_name  = value.room_type_name;
                                    }else{
                                        var room_type_id    = ''
                                        var room_type_name  = ''
                                    }
                                    
                                    var room_booked_quantity = value.booked;
                                    var room_quantity        = value.quantity;
                                    console.log('room_quantity : '+room_quantity);
                                    if(room_booked_quantity != null && room_booked_quantity != ''){
                                        var room_booked_quantity = value.booked;
                                    }else{
                                        var room_booked_quantity = 0;
                                    }
                                    
                                    if(price_week_type != null && price_week_type != ''){
                                        if(price_week_type == 'for_all_days'){
                                            var price_all_days  = value.price_all_days;
                                            var room_type_id    = value.room_type_id;
                                            if(room_type_id != null && room_type_id != ''){
                                                if(room_type_id == 'Single'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="1" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Double'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="2" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Triple'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="3" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Quad'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="4" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                                }
                                                else{
                                                    $('.hotel_type_select_class_'+id+'').append('');
                                                }
                                            }
                                            
                                        }else{
                                            var weekdays        = value.weekdays;
                                            var weekdays_price  = value.weekdays_price;
                                            
                                            var weekends_price  = value.weekends_price;
                                            if(weekdays != null && weekdays != ''){
                                                var weekdays1       = JSON.parse(weekdays);
                                                $.each(weekdays1, function(key, weekdaysValue) {
                                                    if(weekdaysValue == 'Sunday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Sunday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Sunday_D);
                                                    }else if(weekdaysValue == 'Monday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Monday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Monday_D);
                                                    }else if(weekdaysValue == 'Tuesday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Tuesday_D);
                                                    }else if(weekdaysValue == 'Wednesday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Wednesday_D);
                                                    }else if(weekdaysValue == 'Thursday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Thursday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Thursday_D);
                                                    }else if(weekdaysValue == 'Friday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Friday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Friday_D);
                                                    }else if(weekdaysValue == 'Saturday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Saturday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Saturday_D);
                                                    }else{
                                                        price_WeekDays   = price_WeekDays;
                                                        total_WeekDays   = total_WeekDays;
                                                    }
                                                });
                                            }
                                            
                                            var weekends = value.weekends;
                                            if(weekends != null && weekends != ''){
                                                var weekends1   = JSON.parse(weekends);
                                                $.each(weekends1, function(key, weekendValue) {
                                                    if(weekendValue == 'Sunday'){
                                                        price_WeekEnds  = parseFloat(price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Sunday_D);
                                                    }else if(weekendValue == 'Monday'){
                                                        price_WeekEnds    = parseFloat(price_WeekEnds) + parseFloat(Monday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Monday_D);
                                                    }else if(weekendValue == 'Tuesday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Tuesday_D);
                                                    }else if(weekendValue == 'Wednesday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Wednesday_D);
                                                    }else if(weekendValue == 'Thursday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Thursday_D);
                                                    }else if(weekendValue == 'Friday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Friday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Friday_D);
                                                    }else if(weekendValue == 'Saturday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Saturday_D);
                                                    }else{
                                                        price_WeekEnds = price_WeekEnds;
                                                        total_WeekEnds = total_WeekEnds;
                                                    }
                                                });
                                            }
                                            
                                            var room_type_id    = value.room_type_id;
                                            if(room_type_id != null && room_type_id != ''){
                                                if(room_type_id == 'Single'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="1" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Double'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="2" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Triple'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="3" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Quad'){
                                                    $('.hotel_type_select_class_'+id+'').append('<option attr-room_quantity="'+room_quantity+'" attr-room_booked_quantity="'+room_booked_quantity+'" attr="4" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_id+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                                }
                                                else{
                                                    $('.hotel_type_select_class_'+id+'').append('');
                                                }
                                            }
                                        }
                                    }else{
                                        console.log('price_week_type is empty!')
                                    }
                                }
                                
                                // if(more_room_type_details != null && more_room_type_details != ''){
                                //     var more_room_type_details1 = JSON.parse(more_room_type_details);
                                //     $.each(more_room_type_details1, function(key, value1) {
                                //         var more_room_av_from       = value1.more_room_av_from;
                                //         var more_room_av_to         = value1.more_room_av_to;
                                //         var more_room_supplier_name = value1.more_room_supplier_name;
                                //         var more_room_supplier_id   = value1.more_room_supplier_name;
                                //         $.each(supplier_detail, function(key, supplier_detailS) {
                                //             var id = supplier_detailS.id;
                                //             if(id == more_room_supplier_name){
                                //                 more_room_supplier_name  = supplier_detailS.room_supplier_name;
                                //             }
                                //         });
                                        
                                //         var hotelRoom_type_id      = value.id;
                                //         var hotelRoom_type_idM     = value1.room_gen_id;
                                        
                                //         if(value1.more_room_type_name != null && value1.more_room_type_name != ''){
                                //             var more_room_type_cat   = value1.more_room_type_id;
                                //             var more_room_type_name  = value1.more_room_type_name;
                                //         }else{
                                //             var more_room_type_id    = ''
                                //             var more_room_type_name  = ''
                                //         }
                                        
                                //         if(Date.parse(start_date) >= Date.parse(more_room_av_from) && Date.parse(enddate) <= Date.parse(more_room_av_to)){
                                //             var more_room_meal_type = value1.more_room_meal_type;
                                //             if(more_room_meal_type != null && more_room_meal_type != ''){
                                //                 // var more_room_meal_type1    = JSON.parse(more_room_meal_type);
                                //                 var more_room_meal_type1    = more_room_meal_type;
                                //                 var more_room_meal_type2    = more_room_meal_type1;
                                //             }else{
                                //                 var more_room_meal_type2 = '';
                                //             }
                                            
                                //             var more_week_price_type = value1.more_week_price_type;
                                //             if(more_week_price_type != null && more_week_price_type != ''){
                                //                 // var more_week_price_type1    = JSON.parse(more_week_price_type);
                                //                 var more_week_price_type1    = more_week_price_type;
                                //                 var more_week_price_type2    = more_week_price_type1;
                                //                 if(more_week_price_type2 == 'for_all_days'){
                                //                     var more_price_all_days  = value1.more_price_all_days;
                                //                     var more_room_type = value1.more_room_type;
                                //                     if(more_room_type != null && more_room_type != ''){
                                //                         if(more_room_type == 'Single'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Double'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Triple'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Quad'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else{
                                //                             $('.hotel_type_select_class_'+id+'').append('');
                                //                         }
                                //                     }
                                //                 }else{
                                //                     var more_week_end_price     = value1.more_week_end_price
                                //                     var more_week_days_price    = value1.more_week_days_price;
                                                    
                                //                     var more_weekdays = value1.more_weekdays;
                                //                     if(more_weekdays != null && more_weekdays != ''){
                                //                         var more_weekdays1          = JSON.parse(more_weekdays);
                                //                         // console.log(more_weekdays1);
                                //                         $.each(more_weekdays1, function(key, more_weekdaysValue) {
                                //                             if(more_weekdaysValue == 'Sunday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Sunday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Sunday_D);
                                //                             }else if(more_weekdaysValue == 'Monday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Monday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Monday_D);
                                //                             }else if(more_weekdaysValue == 'Tuesday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Tuesday_D);
                                //                             }else if(more_weekdaysValue == 'Wednesday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Wednesday_D);
                                //                             }else if(more_weekdaysValue == 'Thursday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Thursday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Thursday_D);
                                //                             }else if(more_weekdaysValue == 'Friday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Friday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Friday_D);
                                //                             }else if(more_weekdaysValue == 'Saturday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Saturday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Saturday_D);
                                //                             }else{
                                //                                 more_price_WeekDays  = more_price_WeekDays;
                                //                                 more_total_WeekDays  = more_total_WeekDays;
                                //                             }
                                //                         });
                                //                     }
                                                    
                                //                     var more_weekend = value1.more_weekend;
                                //                     if(more_weekend != null && more_weekend != ''){
                                //                         var more_weekend1 = JSON.parse(more_weekend);
                                //                         $.each(more_weekend1, function(key, more_weekendValue) {
                                //                             if(more_weekendValue == 'Sunday'){
                                //                                 more_price_WeekEnds  = parseFloat(more_price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Sunday_D);
                                //                             }else if(more_weekendValue == 'Monday'){
                                //                                 more_price_WeekEnds    = parseFloat(more_price_WeekEnds) + parseFloat(Monday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Monday_D);
                                //                             }else if(more_weekendValue == 'Tuesday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Tuesday_D);
                                //                             }else if(more_weekendValue == 'Wednesday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Wednesday_D);
                                //                             }else if(more_weekendValue == 'Thursday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Thursday_D);
                                //                             }else if(more_weekendValue == 'Friday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Friday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Friday_D);
                                //                             }else if(more_weekendValue == 'Saturday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Saturday_D);
                                //                             }else{
                                //                                 more_price_WeekEnds = more_price_WeekEnds;
                                //                                 more_total_WeekEnds = more_total_WeekEnds;
                                //                             }
                                //                         });
                                //                     }
                                                    
                                //                     var more_room_type    = value1.more_room_type;
                                //                     if(more_room_type != null && more_room_type != ''){
                                //                         if(more_room_type == 'Single'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Double'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Triple'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Quad'){
                                //                             $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else{
                                //                             $('.hotel_type_select_class_'+id+'').append('');
                                //                         }
                                //                     }
                                                    
                                //                     var price_WE_WD = parseFloat(more_price_WeekDays) + parseFloat(more_price_WeekEnds);
                                //                 }
                                //             }else{
                                //                 console.log('more_week_price_type is empty!')
                                //             }
                                //         }
                                //     });
                                    
                                //     // console.log(more_room_type_details1);
                                // }
                                
                            });
                        }else{
                            alert('Select Date First!');
                        }
                        
                    }else{
                        alert('Room are Empty');
                        $('.hotel_type_select_div_'+id+'').html('<option value="Select One">Select One</option>');
                    
                    console.log('rooms not fount ');
                  
                    var roomsTypes = `<option>Select One</option>`;
                        result['rooms_types'].forEach((roomType)=>{
                            var room_data = JSON.stringify(roomType)
                            roomsTypes += `<option value='${room_data}'>${roomType['room_type']}</option>`;
                        })
                        
                           var roomSupplier = ``;
                           var roomSupplierHtml = ``;                           console.log(result['rooms_supplier']);
                        result['rooms_supplier'].forEach((roomSupplier)=>{
                            console.log(roomSupplierHtml);
                            roomSupplierHtml += `<option value="${roomSupplier['id']}" >${roomSupplier['room_supplier_name']}</option>`;
                        })
                        
                        
                        console.log(roomSupplierHtml);
                        $('#new_rooms_type_'+id+'').html(roomsTypes);
                        $('#new_room_supplier_'+id+'').html(roomSupplierHtml);
                        
                        
                    
                    $('.hotel_type_select_div_'+id+'').css('display','none');
                    $('#new_rooms_type_'+id+'').css('display','block');
                    $('#new_room_supplier_div_'+id+'').css('display','block');
                    
                    $('#select_add_new_room_type_'+id+'').val(true)
                    }
                    
                    var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
                    if(property_city_newN == null || property_city_newN == ''){
                        var property_city_newN = $('.property_city_new_'+id+'').val();
                    }
                    var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+id+'').html(html_data);
                    
                    $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
                    $('#acc_hotel_CityName'+id+'').val(property_city_newN);
                    $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
                    $('#acc_hotel_CheckOut'+id+'').val(enddateN);
                    $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
                    $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
                    
                },
            });
        }
        
        function add_new_room_type(id){
            var room_type_data = $('#new_rooms_type_'+id+'').val();
            var room_type_Obj = JSON.parse(room_type_data);
            $('#hotel_acc_type_'+id+'').val(room_type_Obj['parent_cat']);
            
            $('.acc_qty_class_'+id+'').val('');
            $('.acc_pax_class_'+id+'').val('');
            
            // Price Section
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
        }
        
        function more_add_new_room_type(id){
            var room_type_data = $('#more_new_rooms_type_'+id+'').val();
            var room_type_Obj = JSON.parse(room_type_data);
            $('#more_hotel_acc_type_'+id+'').val(room_type_Obj['parent_cat']);
    
            // Price Section
            $('#more_hotel_price_for_week_end_'+id+'').empty();
            $('#more_makkah_acc_room_price_funs_'+id+'').val('');
            $('#more_acc_price_get_'+id+'').val('');
            $('#more_acc_total_amount_'+id+'').val('');
            $('#more_exchange_rate_price_funs_'+id+'').val('');
            $('#more_price_per_room_exchange_rate_'+id+'').val('');
            $('#more_price_per_person_exchange_rate_'+id+'').val('');
            $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            
            // More Switch
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
        }
        
        function hotel_type_funInvoice(id){
            
            $('.acc_qty_class_'+id+'').val('');
            $('.acc_pax_class_'+id+'').val('');
            
            // Price Section
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
            
            var hotel_type = $('.hotel_type_select_class_'+id+'').val();
            $('#hotel_acc_type_'+id+'').val(hotel_type);
            $('#hotel_meal_type_'+id+'').empty();
            
            var hotel_attr_type         = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-type');
            var hotel_price_All         = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-all');
            var hotel_total_days        = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_days');
            var hotel_room_meal_type    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_meal_type');
            var hotel_price_weekdays    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekdays');
            var hotel_total_weekdays    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekdays');
            var hotel_price_weekends    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekends');
            var hotel_total_weekends    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekends');
            var dataMRMT                = `<option value="${hotel_room_meal_type}">${hotel_room_meal_type}</option>`;
            
            var attr_room_supplier_name = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_supplier_name');
            var attr_room_type_cat      = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_type_cat');
            var attr_room_type_name     = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_type_name');
            var hotelRoom_type_id       = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-hotelRoom_type_id');
            var hotelRoom_type_idM      = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-hotelRoom_type_idM');
            
            // Total
            $('#room_quantity_'+id+'').css('display','');
            var room_quantity    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_quantity');
            $('#room_quantity_'+id+'').html('Total : '+room_quantity);
            $('.room_quantity_'+id+'').val(room_quantity);
            
            // Booked
            $('#room_booked_quantity_'+id+'').css('display','');
            var room_booked_quantity    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_booked_quantity');
            $('#room_booked_quantity_'+id+'').html('Booked : '+room_booked_quantity);
            $('.room_booked_quantity_'+id+'').val(room_booked_quantity);
            
            // Available/Over Booked
            if(parseFloat(room_booked_quantity) > parseFloat(room_quantity)){
                var room_over_booked_quantity = parseFloat(room_booked_quantity) - parseFloat(room_quantity);
                $('#room_over_booked_quantity_'+id+'').css('display','');
                $('#room_over_booked_quantity_'+id+'').html('Over Booked : '+room_over_booked_quantity);
                $('.room_over_booked_quantity_'+id+'').val(room_over_booked_quantity);
                
                room_available = 0;
                $('#room_available_'+id+'').css('display','');
                $('#room_available_'+id+'').html('Available : '+room_available);
                $('.room_available_'+id+'').val(room_available);
            }else{
                var room_available = parseFloat(room_quantity) - parseFloat(room_booked_quantity);
                $('#room_available_'+id+'').css('display','');
                $('#room_available_'+id+'').html('Available : '+room_available);
                $('.room_available_'+id+'').val(room_available);
            }
            
            if(hotel_attr_type == 'for_All_Days' || hotel_attr_type == 'more_for_All_Days'){
                var room_price = $('#makkah_acc_room_price_'+id+'').val(hotel_price_All);
                $('#hotel_meal_type_'+id+'').append(dataMRMT);
                var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For All Days)</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_days}" class="form-control no_of_nights_all_days${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_All}" class="form-control price_per_night_all_days${id}" readonly>
                                                        </div>`;
                $('#hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
                $('#hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
                $('#hotel_type_id_'+id+'').val(attr_room_type_cat);
                $('#hotel_type_cat_'+id+'').val(attr_room_type_name);
                $('#hotelRoom_type_id_'+id+'').val(hotelRoom_type_id);
                $('#hotelRoom_type_idM_'+id+'').val(hotelRoom_type_idM);
                
            }else if(hotel_attr_type == 'for_Week_Days' || hotel_attr_type == 'more_for_Week_Days'){
                
                var hotel_type_price    = $('.hotel_type_select_class_'+id+'').val();
                if(hotel_type_price == 'Double')
                {
                    hotel_type_price = 2;
                }
                else if(hotel_type_price == 'Triple')
                {
                    hotel_type_price = 3;
                }
                else if(hotel_type_price == 'Quad')
                {
                    hotel_type_price = 4;
                }else{
                    hotel_type_price = 1;
                }
                
                var total       = parseFloat(room_price)/parseFloat(hotel_type_price);
                total           = total.toFixed(2);
                var grand_total = parseFloat(room_price) * parseFloat(acomodation_nights);
                grand_total     = grand_total.toFixed(2);
                $('#makkah_acc_price_'+id+'').val(total);
                $('#makkah_acc_total_amount_'+id+'').val(grand_total);
                
                var price_per_person_weekdays       = parseFloat(hotel_price_weekdays)/parseFloat(hotel_type_price);
                var price_per_person_weekends       = parseFloat(hotel_price_weekends)/parseFloat(hotel_type_price);
                var total_price_per_person_weekdays = parseFloat(price_per_person_weekdays) * parseFloat(hotel_total_weekdays);
                var total_price_per_person_weekends = parseFloat(price_per_person_weekends) * parseFloat(hotel_total_weekends);
                var TP_WEWD = parseFloat(total_price_per_person_weekdays) + parseFloat(total_price_per_person_weekends);
                
                var TP_WEWD1 = parseFloat(hotel_price_weekdays) + parseFloat(hotel_price_weekends);
                var room_price = $('#makkah_acc_room_price_'+id+'').val(TP_WEWD1);
                
                $('#hotel_meal_type_'+id+'').append(dataMRMT);
                var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For Weekdays)</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_weekdays}" class="form-control no_of_nights_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_weekdays}" class="form-control price_per_night_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Person/Night</label>
                                                            <input type="text" value="${price_per_person_weekdays}" class="form-control price_per_person_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Total Amount/Per Person</label>
                                                            <input type="text" value="${total_price_per_person_weekdays}" class="form-control total_price_per_person_weekdays${id}" readonly>
                                                        </div>
                                                        <h4 class="mt-4">Price Details(For WeekEnds)</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_weekends}" class="form-control no_of_nights_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_weekends}" class="form-control price_per_night_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Person/Night</label>
                                                            <input type="text" value="${price_per_person_weekends}" class="form-control price_per_person_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Total Amount/Per Person</label>
                                                            <input type="text" value="${total_price_per_person_weekends}" class="form-control total_price_per_person_weekends${id}" readonly>
                                                        </div>`;
                $('#hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
                $('#hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
                $('#hotel_type_id_'+id+'').val(attr_room_type_cat);
                $('#hotel_type_cat_'+id+'').val(attr_room_type_name);
                $('#hotelRoom_type_id_'+id+'').val(hotelRoom_type_id);
                $('#hotelRoom_type_idM_'+id+'').val(hotelRoom_type_idM);
            }else{
                alert('Select Room Type');
            }
            
            // Price Calculations
            var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var hotel_type_price = $('#hotel_type_'+id+'').val();
            }else{
                var hotel_type_price = $('.hotel_type_select_class_'+id+'').val();
            }
            var room_price          = $('#makkah_acc_room_price_'+id+'').val();
            var acomodation_nights  = $('#acomodation_nights_'+id+'').val(); 
            if(hotel_type_price == 'Double')
            {
                hotel_type_price = 2;
            }
            else if(hotel_type_price == 'Triple')
            {
                hotel_type_price = 3;
            }
            else if(hotel_type_price == 'Quad')
            {
                hotel_type_price = 4;
            }else{
                hotel_type_price = 1;
            }
            
            var total       = parseFloat(room_price)/parseFloat(hotel_type_price);
            total           = total.toFixed(2);
            var grand_total = parseFloat(room_price) * parseFloat(acomodation_nights);
            grand_total     = grand_total.toFixed(2);
            $('#makkah_acc_price_'+id+'').val(total);
            $('#makkah_acc_total_amount_'+id+'').val(grand_total);
            
            $('#makkah_acc_room_price_'+id+'').attr('readonly', true);
            $('#makkah_acc_price_'+id+'').attr('readonly', true);
            $('#makkah_acc_total_amount_'+id+'').attr('readonly', true);
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function acc_qty_classI(id){
            var acc_qty_class   = $('.acc_qty_class_'+id+'').val();
            var room_available  = $('.room_available_'+id+'').val();
            
            if(parseFloat(acc_qty_class) > parseFloat(room_available)){
                alert('You Enter Quantity greater then Availability!');
            }else{
                console.log('OK');
            }
            
            var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_qty_class = $('.acc_qty_class_'+id+'').val();
                var hotel_type = $('.hotel_type_class_'+id+'').find('option:selected').attr('attr');
                var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                $('.acc_pax_class_'+id+'').val(mult);
            }else{
                var acc_qty_class = $('.acc_qty_class_'+id+'').val();
               
                var room_select_type = $('#select_add_new_room_type_'+id+'').val();
                        if(room_select_type == 'false'){
                             var hotel_type = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr');
                             var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                        }else{
                            var room_type_data = $('#new_rooms_type_'+id+'').val();
                            var room_type_Obj = JSON.parse(room_type_data);
                            
                            var mult            = parseFloat(acc_qty_class) * parseFloat(room_type_Obj['no_of_persons']);
                            
                            console.log(room_type_data);
                        }
                
                
                $('.acc_pax_class_'+id+'').val(mult);
                
                
            }
            
            var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
            if(property_city_newN == null || property_city_newN == ''){
                var property_city_newN = $('.property_city_new_'+id+'').val();
            }
            var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
            }
            var html_data = `Accomodation Cost ${property_city_newN} - ${acc_hotel_nameN} <a class="btn">(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
            $('#acc_cost_html_'+id+'').html(html_data);
            
            $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $('#acc_hotel_CityName'+id+'').val(property_city_newN);
            $('#acc_hotel_CheckIn'+id+'').val(start_dateN);
            $('#acc_hotel_CheckOut'+id+'').val(enddateN);
            $('#acc_hotel_NoOfNights'+id+'').val(acomodation_nightsN);
            $('#acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function hotel_type_funI(id){
            
            var switch_htfI = $('#switch_htfI_'+id+'').val();
            if(switch_htfI == 1){
                alert('Select/Add Hotel First');
            }else{
                var hotel_type = $('#hotel_type_'+id+'').val();
                $('#hotel_acc_type_'+id+'').val(hotel_type);
                
                $('.acc_qty_class_'+id+'').val('');
                $('.acc_pax_class_'+id+'').val('');
            
                // Price Section
                $('#hotel_price_for_week_end_'+id+'').empty();
                $('#makkah_acc_room_price_'+id+'').val('');
                $('#makkah_acc_price_'+id+'').val('');
                $('#makkah_acc_total_amount_'+id+'').val('');
                $('#exchange_rate_price_funs_'+id+'').val('');
                $('#price_per_room_exchange_rate_'+id+'').val('');
                $('#price_per_person_exchange_rate_'+id+'').val('');
                $('#price_total_amout_exchange_rate_'+id+'').val('');
            }
        }
        
        function makkah_acc_room_price_funsI(id){
            var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var hotel_type_price = $('#hotel_type_'+id+'').val();
            }else{
                var hotel_type_price = $('.hotel_type_select_class_'+id+'').val();
            }
            var room_price          = $('#makkah_acc_room_price_'+id+'').val();
            var acomodation_nights  = $('#acomodation_nights_'+id+'').val(); 
            if(hotel_type_price == 'Double')
            {
                hotel_type_price = 2;
            }
            else if(hotel_type_price == 'Triple')
            {
                hotel_type_price = 3;
            }
            else if(hotel_type_price == 'Quad')
            {
                hotel_type_price = 4;
            }else{
                hotel_type_price = 1;
            }
            
            var total           = parseFloat(room_price)/parseFloat(hotel_type_price);
            var total1          = total.toFixed(2);
            var grand_total     = parseFloat(room_price) * parseFloat(acomodation_nights);
            var grand_total1    = grand_total.toFixed(2);
            $('#makkah_acc_price_'+id+'').val(total1);
            $('#makkah_acc_total_amount_'+id+'').val(grand_total);
        }
        
        function exchange_rate_price_funsI(id){
            
            $('#hotel_markup_'+id+'').val('');
            $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val('');
            $('#hotel_markup_total_'+id+'').val('');
            
            var makkah_acc_room_price           = $('#makkah_acc_room_price_'+id+'').val();
            var makkah_acc_price                = $('#makkah_acc_price_'+id+'').val();
            var makkah_acc_total_amount         = $('#makkah_acc_total_amount_'+id+'').val();
            var exchange_rate_price_funs        = $('#exchange_rate_price_funs_'+id+'').val();
            $('#hotel_exchage_rate_per_night_'+id+'').val(exchange_rate_price_funs);
            
            var currency_conversion = $("#select_exchange_type").val();
            if(currency_conversion == 'Divided'){
                var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price)/parseFloat(exchange_rate_price_funs);
                var price_per_person_exchangeRate=parseFloat(makkah_acc_price)/parseFloat(exchange_rate_price_funs);
                var price_total_exchangeRate=parseFloat(makkah_acc_total_amount)/parseFloat(exchange_rate_price_funs);  
            }else{
                var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price) * parseFloat(exchange_rate_price_funs);
                var price_per_person_exchangeRate=parseFloat(makkah_acc_price) * parseFloat(exchange_rate_price_funs);
                var price_total_exchangeRate=parseFloat(makkah_acc_total_amount) * parseFloat(exchange_rate_price_funs);  
            }
            
            var price_per_room_exchangeRate     = price_per_room_exchangeRate.toFixed(2);
            var price_per_person_exchangeRate   = price_per_person_exchangeRate.toFixed(2);
            var price_total_exchangeRate        = price_total_exchangeRate.toFixed(2);
            $('#price_per_room_exchange_rate_'+id+'').val(price_per_room_exchangeRate);
            $('#hotel_acc_price_per_night_'+id+'').val(price_per_room_exchangeRate);
            $('#price_per_person_exchange_rate_'+id+'').val(price_per_person_exchangeRate);
            $('#price_total_amout_exchange_rate_'+id+'').val(price_total_exchangeRate);
            $('#hotel_acc_price_'+id+'').val(price_total_exchangeRate);
            
            var double_cost_price   = 0;
            var triple_cost_price   = 0;
            var quad_cost_price     = 0;
            for(var k=1; k<=20; k++){
                var price_total_amout_exchange_rate = $('#price_total_amout_exchange_rate_'+k+'').val();
                if(price_total_amout_exchange_rate != null && price_total_amout_exchange_rate != '' && price_total_amout_exchange_rate != 0){
                    var more_switch_hotel_name = $('#switch_hotel_name'+k+'').val();
                    if(more_switch_hotel_name != 1){
                        var hotel_type_price    = $('.hotel_type_select_class_'+k+'').val();
                    }else{
                        var hotel_type_price    = $('#hotel_type_'+k+'').val();
                    }
                    
                    if(hotel_type_price == 'Double'){
                        double_cost_price       = parseFloat(double_cost_price) + parseFloat(price_total_amout_exchange_rate);
                        var double_cost_price1  = double_cost_price.toFixed(2);
                        $('#double_cost_price').val(double_cost_price1);
                    }else if(hotel_type_price == 'Triple'){
                        triple_cost_price       = parseFloat(triple_cost_price) + parseFloat(price_total_amout_exchange_rate);
                        var triple_cost_price1  = triple_cost_price.toFixed(2);
                        $('#triple_cost_price').val(triple_cost_price1);
                    }else if(hotel_type_price == 'Quad'){
                        quad_cost_price         = parseFloat(quad_cost_price) + parseFloat(price_total_amout_exchange_rate);
                        var quad_cost_price1    = quad_cost_price.toFixed(2);
                        $('#quad_cost_price').val(quad_cost_price1);
                    }else{
                        console.log('Hotel Type Not Found!!');
                    }
                }
                else{
                    console.log('Hotel Type Not Found!!');
                }
            }
            
        }
        
        function hotel_markup_typeI(id){
            
            $('#hotel_markup_'+id+'').val('');
            $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val('');
            $('#hotel_markup_total_'+id+'').val('');
            
            var ids                             = $('#hotel_markup_types_'+id+'').val();
            var prices                          = $('#hotel_acc_price_'+id+'').val();
            var hotel_acc_price_per_night       = $('#hotel_acc_price_per_night_'+id+'').val();
            var hotel_exchage_rate_per_night    = $('#hotel_exchage_rate_per_night_'+id+'').val();
            
            add_numberElseI();
            if(ids == ''){
                alert('Select markup Type');
                $('.markup_value_Div_'+id+'').css('display','none');
                $('.exchnage_rate_Div_'+id+'').css('display','none');
                $('.markup_price_Div_'+id+'').css('display','none');
                $('.markup_total_price_Div_'+id+'').css('display','none');
            }else{
                $('.markup_value_Div_'+id+'').css('display','');
                $('.exchnage_rate_Div_'+id+'').css('display','');
                $('.markup_price_Div_'+id+'').css('display','');
                $('.markup_total_price_Div_'+id+'').css('display','');
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                $('#hotel_markup_mrk_'+id+'').text(value_1);
            }
            
        }
        
        function hotel_markup_funI(id){
            var ids                             = $('#hotel_markup_types_'+id+'').val();
            var prices                          = $('#hotel_acc_price_'+id+'').val();
            var hotel_acc_price_per_night       = $('#hotel_acc_price_per_night_'+id+'').val();
            var hotel_exchage_rate_per_night    = $('#hotel_exchage_rate_per_night_'+id+'').val();
            var acomodation_nights              = $('#acomodation_nights_'+id+'').val();
            add_numberElseI();
            if(ids == '%'){
                var markup_val  =  $('#hotel_markup_'+id+'').val();
                var total1      = prices * markup_val/100;
                var total       = total1.toFixed(2);
                $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3      = total2.toFixed(2);
                $('#hotel_markup_total_'+id+'').val(total3);
                $('#hotel_invoice_markup_'+id+'').val(total3);
                add_numberElse_1I();
            }else if(ids == 'per_Night'){
                var markup_val  =  $('#hotel_markup_'+id+'').val();
                var total1      = (parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night)) * acomodation_nights;
                var total       = total1.toFixed(2);
                $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3       = total2.toFixed(2);
                $('#hotel_markup_total_'+id+'').val(total3);
                $('#hotel_invoice_markup_'+id+'').val(total3);
                add_numberElse_1I();
            }else{
                var markup_val  =  $('#hotel_markup_'+id+'').val();
                var total1      = parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night);
                var total       = total1.toFixed(2);
                $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3       = total2.toFixed(2);
                $('#hotel_markup_total_'+id+'').val(total3);
                $('#hotel_invoice_markup_'+id+'').val(total3);
                add_numberElse_1I();
            }
        }
        
        function acc_qty_class_function(id){
            
            var acc_qty_class   = $('.acc_qty_class_'+id+'').val();
            var hotel_type      = $('.hotel_type_class_'+id+'').find('option:selected').attr('attr');
            var mult            = parseFloat(acc_qty_class) * parseFloat(hotel_type);
            $('.acc_pax_class_'+id+'').val(mult);
            
            // var switch_hotel_name = $('#switch_hotel_name'+id+'').val()
            // if(switch_hotel_name == 1){
            //     var acc_qty_class = $('.acc_qty_class_'+id+'').val();
            //     var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
            //     var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
            //     $('.acc_pax_class_'+id+'').val(mult);
            // }else{
            //     var acc_qty_class = $('.acc_qty_class_'+id+'').val();
            //     var hotel_type = $('.hotel_type_select_class_'+i+'').find('option:selected').attr('attr');
            //     var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
            //     $('.acc_pax_class_'+id+'').val(mult);
            // }
        }
        
        function api_edit_func(id){
            var places_D1 = new google.maps.places.Autocomplete(
                document.getElementById('acc_hotel_name_'+id+'')
            );
            
            google.maps.event.addListener(places_D1, "place_changed", function () {
                var places_D1 = places_D1.getPlace();
                // console.log(places_D1);
                var address = places_D1.formatted_address;
                var latitude = places_D1.geometry.location.lat();
                var longitude = places_D1.geometry.location.lng();
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = (geocoder = new google.maps.Geocoder());
                geocoder.geocode({ latLng: latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address = results[0].formatted_address;
                            var pin = results[0].address_components[
                                results[0].address_components.length - 1
                            ].long_name;
                            var country =  results[0].address_components[
                                results[0].address_components.length - 2
                              ].long_name;
                            var state = results[0].address_components[
                                    results[0].address_components.length - 3
                                ].long_name;
                            var city = results[0].address_components[
                                    results[0].address_components.length - 4
                                ].long_name;
                            var country_code = results[0].address_components[
                                    results[0].address_components.length - 2
                                ].short_name;
                            $('#country').val(country);
                            $('#lat').val(latitude);
                            $('#long').val(longitude);
                            $('#pin').val(pin);
                            $('#city').val(city);
                            $('#country_code').val(country_code);
                        }
                    }
                });
            });
        }
        
        // add_more_accomodation_Invoice_edit
        var divId = $('#divId_m_a_e_i').val();
        var ids = [];
        function add_more_accomodation_Invoice_edit(id){
            
            divId = parseFloat(divId) + 1;
            $('#divId_m_a_e_i').val(divId);
        
            var data1 = `<div id="click_delete_${divId}" class="mb-2 mt-3" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <div class="row" style="padding:20px;">
                            
                                <div class="col-xl-3">
                                    <label for="">Check In</label>
                                    <input type="date" id="more_makkah_accomodation_check_in_${divId}" name="more_acc_check_in[]" class="form-control more_date makkah_accomodation_check_in_class_${divId} more_check_in_hotel_${divId}">
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Check Out</label>
                                    <input type="date" id="more_makkah_accomodation_check_out_date_${divId}"  name="more_acc_check_out[]" onchange="more_makkah_accomodation_check_out(${divId})" class="form-control more_makkah_accomodation_check_out_date_class_${divId}" more_check_out_hotel_${divId}>
                                </div>
                                
                                <div class="col-xl-3">
                                    <input type="text" id="more_switch_hotel_name${divId}" name="more_switch_hotel_name[]" value="1" style="display:none" class="more_switch_hotel_name">
                                    <label for="">Hotel Name</label>
                                    <div class="input-group" id="more_add_hotel_div${divId}">
                                        <input type="text" onchange="more_hotel_funI(${divId})" id="more_acc_hotel_name_${divId}" name="more_acc_hotel_name[]" class="form-control more_acc_hotel_name_class_${divId}">
                                    </div>
                                </div>
                                
                                <div class="col-xl-3"><label for="">No Of Nights</label>
                                    <input readonly type="text" id="more_acomodation_nights_${divId}" name="more_acc_no_of_nightst[]" class="form-control acomodation_nights_class_${divId}">
                                </div>
                
                                <div class="col-xl-3">
                                    <label for="">Room Type</label>
                            
                                    <div class="input-group more_hotel_type_add_div_${divId} more_hotel_type_add_div">
                                        <select onchange="more_hotel_type_fun(${divId})" name="more_acc_type[]" id="more_hotel_type_${divId}" class="form-control other_Hotel_Type more_hotel_type_class_${divId}" data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                            <option attr="4" value="Quad">Quad</option>
                                            <option attr="3" value="Triple">Triple</option>
                                            <option attr="2" value="Double">Double</option>
                                        </select>
                                    </div>
                                
                                    <div class="input-group more_hotel_type_select_div_${divId} more_hotel_type_select_div" style="display:none">
                                        <select onchange="more_hotel_type_funInvoice(${divId})" name="more_acc_type_select[]" id="more_hotel_type_select_${divId}" class="form-control other_Hotel_Type more_hotel_type_select_class_${divId}" data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-xl-3"><label for="">Quantity</label><input onkeyup="more_acc_qty_classInvoice(${divId})" type="text" id="simpleinput" name="more_acc_qty[]" class="form-control more_acc_qty_class_${divId}"></div>
                                <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="more_acc_pax[]" class="form-control more_acc_pax_class_${divId}" readonly></div>
                                
                                <div class="col-xl-3">
                                    <label for="">Meal Type</label>
                                    <select name="more_hotel_meal_type[]" id="more_hotel_meal_type_${divId}" class="form-control more_hotel_meal_type"  data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                                    </select>
                                </div>
                            
                                <div id="more_hotel_price_for_week_end_${divId}" class="row more_hotel_price_for_week_end"></div>
                                
                                <h4 class="mt-4">Purchase Price1</h4>
                                    
                                    <input type="hidden" id="more_hotel_invoice_markup_${divId}" name="more_hotel_invoice_markup[]">
                                    
                                    <input type="hidden" id="more_hotel_supplier_id_${divId}" name="more_hotel_supplier_id[]">
                                
                                    <input type="hidden" id="more_hotel_type_id_${divId}" name="more_hotel_type_id[]">
                                                
                                    <input type="hidden" id="more_hotel_type_cat_${divId}" name="more_hotel_type_cat[]">
                                    
                                    <input type="hidden" id="more_hotelRoom_type_id_${divId}" name="more_hotelRoom_type_id[]">
                                    
                                    <input type="hidden" id="more_hotelRoom_type_idM_${divId}" name="more_hotelRoom_type_idM[]">
                                
                                 <div class="col-xl-4">
                                <label for="">Price Per Room/Night</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                    <input type="text" onkeyup="more_makkah_acc_room_price_funsI(${divId})" id="more_makkah_acc_room_price_funs_${divId}" name="more_price_per_room_purchase[]" class="form-control more_makkah_acc_room_price_funs">
                                </div>
                            
                                </div>
                                
                                <div class="col-xl-4">
                                <label for="">Price Per Person/Night</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                    <input type="text" onchange="more_acc_price(${divId})" id="more_acc_price_get_${divId}" name="more_acc_price_purchase[]" class="form-control more_acc_price_get">
                                </div>
                        
                                </div>
                                <div class="col-xl-4">
                                <label for="">Total Amount/Room</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                   
                                                </a>
                                            </span>
                                    <input readonly type="text" id="more_acc_total_amount_${divId}" name="more_acc_total_amount_purchase[]" class="form-control more_acc_total_amount"></div>
                                </div>
                                
                                
                                <div class="col-xl-6">
                                <label for="">Exchange Rate</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                   
                                                </a>
                                            </span>
                                    <input type="text" id="more_exchange_rate_price_funs_${divId}" onkeyup="more_exchange_rate_price_funsI(${divId})" name="more_exchange_rate_price[]" class="form-control more_exchange_rate_price_funs"></div>
                                </div>
                                <div class="col-xl-6">
                                </div>
                                
                                
                                <h4 class="mt-4">Sale Price</h4>
                                
                                 <div class="col-xl-4">
                                <label for="">Price Per Room/Night</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                  
                                                </a>
                                            </span>
                                    <input type="text" id="more_price_per_room_exchange_rate_${divId}" name="more_price_per_room_sale[]" class="form-control more_price_per_room_exchange_rate">
                                </div>
                            
                                </div>
                            
                                <div class="col-xl-4">
                                <label for="">Price Per Person/Night</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                  
                                                </a>
                                            </span>
                                    <input type="text" id="more_price_per_person_exchange_rate_${divId}" name="more_acc_price[]" class="form-control more_price_per_person_exchange_rate">
                                </div>
                            
                                </div>
                                <div class="col-xl-4">
                                <label for="">Total Amount/Room</label>
                                 <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                  
                                                </a>
                                            </span>
                                    <input readonly type="text" id="more_price_total_amout_exchange_rate_${divId}" name="more_acc_total_amount[]" class="form-control more_price_total_amout_exchange_rate"></div>
                                </div>
                                
                                
                                <div class="mt-2">
                                    <a href="javascript:;"  onclick="deleteRowaccI(${divId})"  id="${divId}" class="btn btn-info" style="float: right;">Delete </a>
                                </div>
                            </div>
                        </div>`;
        
        
            var data_cost = `<div style="padding-bottom: 5px;" class="row click_delete_${divId}" id="click_delete_${divId}">
                                <div class="col-xl-3">
                                    <input type="text" name="more_hotel_name_markup[]" hidden id="more_hotel_name_markup${divId}">
                                    <h4 class="" id="">More Accomodation Cost</h4>
                                </div>
                                <div class="col-xl-9"></div>
                                
                                <input type="hidden" id="more_hotel_Type_Costing" name="more_markup_Type_Costing[]" value="more_hotel_Type_Costing" class="form-control">
                                
                                <div class="col-xl-2">
                                    <input type="text" id="more_hotel_acc_type_${divId}" readonly="" name="more_room_type[]" class="form-control">
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_acc_price_per_night_${divId}" readonly="" name="more_without_markup_price_single[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_acc_price_${divId}" readonly="" name="more_without_markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">     
                                    <select name="more_markup_type[]" onchange="more_hotel_markup_type_accI(${divId})" id="more_hotel_markup_types_${divId}" class="form-control">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                        <option value="per_Night">Per Night</option>
                                    </select>
                                </div>
                                
                                <div class="col-xl-2">         
                                    <input type="hidden" id="" name="" class="form-control">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text" class="form-control" id="more_hotel_markup_${divId}" name="more_markup[]" onkeyup="get_markup_invoice_price(${divId})">
                                        <span class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_hotel_markup_mrk_${divId}" class="currency_value1">SAR</div></button>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-1" style="display:none">
                                    <input type="text" id="more_hotel_exchage_rate_per_night_${divId}" readonly="" name="more_exchage_rate_single[]" class="form-control">    
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_exchage_rate_markup_total_per_night_${divId}" readonly="" name="more_markup_total_per_night[]" class="form-control"> 
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2" style="margin-top:10px">
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_markup_total_${divId}" name="more_markup_price[]" value="" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                            </div>`;
        
                $("#append_accomodation_data_cost1").append(data_cost);
                $("#append_add_accomodation_"+id+'').append(data1);
                
                var places_D1 = new google.maps.places.Autocomplete(
                        document.getElementById('more_acc_hotel_name_'+divId+'')
                    );
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    var address = places_D1.formatted_address;
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
                                        results[0].address_components.length - 2
                                    ].short_name;
                                $('#country').val(country);
                                $('#lat').val(latitude);
                                $('#long').val(longitude);
                                $('#pin').val(pin);
                                $('#city').val(city);
                                $('#country_code').val(country_code);
                            }
                        }
                    });
                });
                
                var more_hotel_city = $('#more_hotel_city'+divId+'').val()
                $('#more_hotel_name_markup'+divId+'').val(more_hotel_city);
                
                divId = divId + 1;
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                exchange_currency_funs(value_1,value_2);
        }
        
        function remove_hotelsI(id){    
            $('#del_hotelI'+id+'').remove();
            $('#costing_accI'+id+'').remove();
            // $('#click_deleteI_'+id+'').remove();
            
            var city_No1 = $('#city_No').val();
            var city_No = parseFloat(city_No1) - 1;
            $('#city_No').val(city_No);
            
            put_tour_locationI();
            put_tour_location_else();
            add_numberElse();
        }
        
        function deleteRowaccI(id){
            $('#click_delete_'+id+'').remove();
            $('#click_deleteI_'+id+'').remove();
            var indexNo = ids.indexOf(id);
            ids.splice(indexNo,1);
            grandTotalCalc();
            add_numberElse();
            
            var more_acc_new_id = $('#more_acc_new_id').val();
            var divId           = parseFloat(more_acc_new_id) - 1;
            $('#more_acc_new_id').val(divId);
        }
        
        // More Accomodation Invoice
        var ids = [];
        function add_more_accomodation_Invoice(id){
            
            var acc_nights_key = $('#acc_nights_key_'+id+'').val();
            
            var decodeURI_city = $('.property_city_new'+id+'').val();
            
            if(decodeURI_city == null || decodeURI_city == ''){
                var decodeURI_city = $('.property_city_new_'+id+'').val();
            }
            
            console.log('decodeURI_city : '+decodeURI_city);
            
            var divId = $('#more_acc_new_id').val();
            
            // Parent Data
            var parent_check_in                 = $('.makkah_accomodation_check_in_class_'+id+'').val();
            var parent_check_out                = $('.makkah_accomodation_check_out_date_class_'+id+'').val();
            var parent_switch_hotel_name        = $('#switch_hotel_name'+id+'').val();
            if(parent_switch_hotel_name == 1){
                var parent_acc_hotel_name           = $('.acc_hotel_name_class_'+id+'').val();
                var parent_hotel_type_select_class  = $('.hotel_type_class_'+id+'').find('option:selected').attr('value');
            }else{
                var parent_acc_hotel_name           = $('.get_room_types_'+id+'').find('option:selected').attr('value');
                var parent_attr_id                  = $('.get_room_types_'+id+'').find('option:selected').attr('attr_id');
                var parent_hotel_type_select_class  = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('value');
            }
            var parent_acomodation_nights_class = $('.acomodation_nights_class_'+id+'').val();
            var parent_acc_qty_class            = $('.acc_qty_class_'+id+'').val();
            var parent_acc_pax_class            = $('.acc_pax_class_'+id+'').val();
            var parent_hotel_meal_type          = $('#hotel_meal_type_'+id+'').val();
            
            var parent_hotel_invoice_markup = $('#hotel_invoice_markup_'+id+'').val();
            var parent_hotel_supplier_id    = $('#hotel_supplier_id_'+id+'').val();
            var parent_hotel_type_id        = $('#hotel_type_id_'+id+'').val();
            var parent_hotel_type_cat       = $('#hotel_type_cat_'+id+'').val();
            var parent_hotelRoom_type_id    = $('#hotelRoom_type_id_'+id+'').val();
            var parent_hotelRoom_type_idM   = $('#hotelRoom_type_idM_'+id+'').val();
            
            var parent_makkah_acc_room_price            = $('#makkah_acc_room_price_'+id+'').val();
            var parent_makkah_acc_price                 = $('#makkah_acc_price_'+id+'').val();
            var parent_makkah_acc_total_amount          = $('#makkah_acc_total_amount_'+id+'').val();
            var parent_exchange_rate_price_funs         = $('#exchange_rate_price_funs_'+id+'').val();
            var parent_price_per_room_exchange_rate     = $('#price_per_room_exchange_rate_'+id+'').val();
            var parent_price_per_person_exchange_rate   = $('#price_per_person_exchange_rate_'+id+'').val();
            var parent_price_total_amout_exchange_rate  = $('#price_total_amout_exchange_rate_'+id+'').val();
            
            var parent_acc_hotel_CityName   = $('#acc_hotel_CityName'+id+'').val();
            var parent_acc_hotel_HotelName  = $('#acc_hotel_HotelName'+id+'').val();
            var parent_acc_hotel_CheckIn    = $('#acc_hotel_CheckIn'+id+'').val();
            var parent_acc_hotel_CheckOut   = $('#acc_hotel_CheckOut'+id+'').val();
            var parent_acc_hotel_NoOfNights = $('#acc_hotel_NoOfNights'+id+'').val();
            var parent_acc_hotel_Quantity   = $('#acc_hotel_Quantity'+id+'').val();
            // End Parent Data
            
            var data1 = `<div id="click_delete_${divId}" class="mb-2 mt-3" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <div class="row" style="padding:20px;">
                            
                                <div class="col-xl-3">
                                    <label for="">Check In</label>
                                    <input value="${parent_check_in}" type="date" id="more_makkah_accomodation_check_in_${divId}" name="more_acc_check_in[]" onchange="more_makkah_accomodation_check_in_class(${divId})" class="form-control more_date makkah_accomodation_check_in_class_${divId} more_check_in_hotel_${divId}">
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Check Out</label>
                                    <input value="${parent_check_out}" type="date" id="more_makkah_accomodation_check_out_date_${divId}" name="more_acc_check_out[]" onchange="more_makkah_accomodation_check_out(${divId})" class="form-control more_makkah_accomodation_check_out_date_class_${divId} more_check_out_hotel_${divId}">
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Hotel Name</label>
                                    
                                    <input type="text" id="more_switch_hotel_name${divId}" name="more_switch_hotel_name[]" value="${parent_switch_hotel_name}" style="display:none" class="more_switch_hotel_name">`;
                                    
                                    if(parent_switch_hotel_name == 1){
                                        data1 += `<div class="input-group" id="more_add_hotel_div${divId}">
                                                    <input value="${parent_acc_hotel_name}" type="text" onkeyup="more_hotel_funI(${divId})" id="more_acc_hotel_name_${divId}" name="more_acc_hotel_name[]" class="form-control more_acc_hotel_name_class_${divId}">
                                                </div>
                                                <a style="float: right;font-size: 10px;width: 102px;" onclick="more_select_hotel_btn(${divId})" class="btn btn-primary more_select_hotel_btn${divId}">
                                                    SELECT HOTEL
                                                </a>
                                                
                                                <div class="input-group" id="more_select_hotel_div${divId}" style="display:none">
                                                    <select onchange="more_get_room_types(${divId})" id="more_acc_hotel_name_select_${divId}" name="more_acc_hotel_name_select[]" class="form-control more_get_room_types_${divId}">
                                                        <option attr_id="" value=""></option>
                                                    </select>
                                                </div>
                                                <input type="text" id="more_select_hotel_id${divId}"   name="more_hotel_id[]" value="">
                                                <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="more_add_hotel_btn(${divId})" class="btn btn-primary more_add_hotel_btn${divId}">
                                                    ADD HOTEL
                                                </a>`;
                                    }else{
                                        data1 += `<div class="input-group" id="more_add_hotel_div${divId}" style="display:none">
                                                    <input value="" type="text" onkeyup="more_hotel_funI(${divId})" id="more_acc_hotel_name_${divId}" name="more_acc_hotel_name[]" class="form-control more_acc_hotel_name_class_${divId}">
                                                </div>
                                                <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="more_select_hotel_btn(${divId})" class="btn btn-primary more_select_hotel_btn${divId}">
                                                    SELECT HOTEL
                                                </a>
                                                
                                                <div class="input-group" id="more_select_hotel_div${divId}">
                                                    <select onchange="more_get_room_types(${divId})" id="more_acc_hotel_name_select_${divId}" name="more_acc_hotel_name_select[]" class="form-control more_get_room_types_${divId}">
                                                        <option attr_id="${parent_attr_id}" value="${parent_acc_hotel_name}">${parent_acc_hotel_name}</option>
                                                    </select>
                                                </div>
                                                <input type="text" id="more_select_hotel_id${divId}"   name="more_hotel_id[]" value="">
                                                <a style="float: right;font-size: 10px;width: 102px;" onclick="more_add_hotel_btn(${divId})" class="btn btn-primary more_add_hotel_btn${divId}">
                                                    ADD HOTEL
                                                </a>`;
                                    }
                                    
                        data1 +=`</div>
                                
                                <div class="col-xl-3"><label for="">No Of Nights</label>
                                    <input value="${parent_acomodation_nights_class}" readonly type="text" id="more_acomodation_nights_${divId}" name="more_acc_no_of_nightst[]" class="form-control acomodation_nights_class_${divId}">
                                </div>
                
                                <input readonly type="hidden" id="acc_nights1_${divId}" value="${parent_acomodation_nights_class}" class="form-control">
                                <input type='hidden' name="more_hotel_city[]" value="${decodeURI_city}" id="more_hotel_city${divId}"/>
                                <div class="col-xl-3">
                                    <label for="">Room Type</label>
                            
                                    <div class="input-group more_hotel_type_add_div_${divId} more_hotel_type_add_div">
                                        <select onchange="more_hotel_type_fun(${divId})" name="more_acc_type[]" id="more_hotel_type_${divId}" class="form-control other_Hotel_Type more_hotel_type_class_${divId}" data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>`
                                            if(parent_hotel_type_select_class == 'Double'){
                                                data1 +=    `<option attr="2" value="Double" selected>Double</option>
                                                            <option attr="3" value="Triple">Triple</option>
                                                            <option attr="4" value="Quad">Quad</option>`
                                            }else if(parent_hotel_type_select_class == 'Triple'){
                                                data1 +=    `<option attr="2" value="Double">Double</option>
                                                            <option attr="3" value="Triple" selected>Triple</option>
                                                            <option attr="4" value="Quad">Quad</option>`
                                            }else if(parent_hotel_type_select_class == 'Quad'){
                                                data1 +=    `<option attr="2" value="Double">Double</option>
                                                            <option attr="3" value="Triple">Triple</option>
                                                            <option attr="4" value="Quad" selected>Quad</option>`
                                            }else{
                                                data1 +=    `<option attr="2" value="Double">Double</option>
                                                            <option attr="3" value="Triple">Triple</option>
                                                            <option attr="4" value="Quad">Quad</option>`
                                            }
                            data1 +=    `</select>
                                    </div>
                                
                                    
                                    <select onchange="more_hotel_type_funInvoice(${divId})" style="display:none" name="more_acc_type_select[]" id="more_hotel_type_select_${divId}" class="more_hotel_type_select_div_${divId} form-control other_Hotel_Type more_hotel_type_select_class_${divId}" data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                    </select>

                                    <select onchange="more_add_new_room_type(${divId})" name="more_new_rooms_type[]" style="display:none;" id="more_new_rooms_type_${divId}" class="form-control other_Hotel_Type more_new_rooms_type_${divId} "  data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                    </select>
                                    <input type="text" id="more_select_add_new_room_type_${divId}" hidden name="more_new_add_room[]" value="false">
                                
                                </div>
                              
                                    <div class="col-xl-3" id="more_new_room_supplier_div_${divId}" style="display:none">
                                        <label for="">Select Supplier</label>
                                        <select class="form-control" id="more_new_room_supplier_${divId}" name="more_new_room_supplier[]">
                                            <option>Select One</option>
                                        </select>
                                    </div>
                                    
                                <div class="col-xl-3">
                                    <label for="">Quantity</label>
                                    <input onkeyup="more_acc_qty_classInvoice(${divId})" type="text" id="simpleinput" name="more_acc_qty[]" class="form-control more_acc_qty_class_${divId}">
                                    
                                    <div class="row" style="padding: 2px;">
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-success" id="more_room_quantity_${divId}"></a>
                                            <input type="hidden" class="more_room_quantity_${divId}">
                                        </div>
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-primary" id="more_room_available_${divId}"></a>
                                            <input type="hidden" class="more_room_available_${divId}">
                                        </div>
                                    </div>
                                    
                                    <div class="row" style="padding: 2px;">
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-info" id="more_room_booked_quantity_${divId}"></a>
                                            <input type="hidden" class="more_room_booked_quantity_${divId}">
                                        </div>
                                        <div class="col-lg-6">
                                            <a style="display: none;font-size: 10px;" class="btn btn-danger" id="more_room_over_booked_quantity_${divId}"></a>
                                            <input type="hidden" class="more_room_over_booked_quantity_${divId}">
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-xl-3">
                                    <label for="">Pax</label>
                                    <input type="text" id="simpleinput" name="more_acc_pax[]" class="form-control more_acc_pax_class_${divId}" readonly>
                                </div>
                                
                                
                                <div class="col-xl-3">
                                    <label for="">Meal Type</label>
                                    <select name="more_hotel_meal_type[]" id="more_hotel_meal_type_${divId}" class="form-control more_hotel_meal_type"  data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>`
                                        if(parent_hotel_meal_type == 'Room only'){
                                            data1 +=    `<option value="Room only" selected>Room only</option>
                                                        <option value="Breakfast">Breakfast</option>
                                                        <option value="Lunch">Lunch</option>
                                                        <option value="Dinner">Dinner</option>`
                                        }else if(parent_hotel_meal_type == 'Breakfast'){
                                            data1 +=    `<option value="Room only">Room only</option>
                                                        <option value="Breakfast" selected>Breakfast</option>
                                                        <option value="Lunch">Lunch</option>
                                                        <option value="Dinner">Dinner</option>`
                                        }else if(parent_hotel_meal_type == 'Lunch'){
                                            data1 +=    `<option value="Room only">Room only</option>
                                                        <option value="Breakfast">Breakfast</option>
                                                        <option value="Lunch" selected>Lunch</option>
                                                        <option value="Dinner">Dinner</option>`
                                        }else if(parent_hotel_meal_type == 'Dinner'){
                                            data1 +=    `<option value="Room only">Room only</option>
                                                        <option value="Breakfast">Breakfast</option>
                                                        <option value="Lunch" selected>Lunch</option>
                                                        <option value="Dinner">Dinner</option>`
                                        }else{
                                            data1 +=    `<option value="Room only">Room only</option>
                                                        <option value="Breakfast">Breakfast</option>
                                                        <option value="Lunch">Lunch</option>
                                                        <option value="Dinner" selected>Dinner</option>`
                                        }
                        data1 +=`</select>
                                </div>
                            
                                <div id="more_hotel_price_for_week_end_${divId}" class="row more_hotel_price_for_week_end"></div>
                                
                                <h4 class="mt-4">Purchase Price in <a class="currency_value1" style="color: black;"></a></h4>
                                
                                <input type="hidden" id="more_hotel_invoice_markup_${divId}" name="more_hotel_invoice_markup[]">
                                
                                <input type="hidden" id="more_hotel_supplier_id_${divId}" name="more_hotel_supplier_id[]">
                                
                                <input type="hidden" id="more_hotel_type_id_${divId}" name="more_hotel_type_id[]">
                                            
                                <input type="hidden" id="more_hotel_type_cat_${divId}" name="more_hotel_type_cat[]">
                                
                                <input type="hidden" id="more_hotelRoom_type_id_${divId}" name="more_hotelRoom_type_id[]">
                                
                                <input type="hidden" id="more_hotelRoom_type_idM_${divId}" name="more_hotelRoom_type_idM[]">
                                
                                <div class="col-xl-4">
                                    <label for="">Price Per Room/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a></span>
                                        <input type="text" onkeyup="more_makkah_acc_room_price_funsI(${divId},${id})" id="more_makkah_acc_room_price_funs_${divId}" name="more_price_per_room_purchase[]" class="form-control more_makkah_acc_room_price_funs">
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <label for="">Price Per Person/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a></span>
                                        <input type="text" onchange="more_acc_price(${divId})" id="more_acc_price_get_${divId}" name="more_acc_price_purchase[]" class="form-control more_acc_price_get">
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <label for="">Total Amount/Room</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a></span>
                                        <input readonly type="text" id="more_acc_total_amount_${divId}" name="more_acc_total_amount_purchase[]" class="form-control more_acc_total_amount">
                                    </div>
                                </div>
                                
                                <div class="col-xl-6">
                                    <label for="">Exchange Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a></span>
                                        <input type="text" id="more_exchange_rate_price_funs_${divId}" onkeyup="more_exchange_rate_price_funsI(${divId})" name="more_exchange_rate_price[]" class="form-control more_exchange_rate_price_funs">
                                    </div>
                                </div>
                                
                                <div class="col-xl-6"></div>
                                
                                <h4 class="mt-4">Purchase Price in <a class="currency_value_exchange_1" style="color: black;"></a></h4>
                                
                                <div class="col-xl-4">
                                    <label for="">Price Per Room/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        <input type="text" id="more_price_per_room_exchange_rate_${divId}" name="more_price_per_room_sale[]" class="form-control more_price_per_room_exchange_rate">
                                    </div>
                                </div>
                            
                                <div class="col-xl-4">
                                    <label for="">Price Per Person/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        <input type="text" id="more_price_per_person_exchange_rate_${divId}" name="more_acc_price[]" class="form-control more_price_per_person_exchange_rate">
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <label for="">Total Amount/Room</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        <input readonly type="text" id="more_price_total_amout_exchange_rate_${divId}" name="more_acc_total_amount[]" class="form-control more_price_total_amout_exchange_rate">
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <a href="javascript:;"  onclick="deleteRowacc(${divId})"  id="${divId}" class="btn btn-info" style="float: right;">Delete </a>
                                </div>
                                
                            </div></div>`;
        
        
            var data_cost = `<div style="padding-bottom: 5px;" class="row click_delete_${divId}" id="click_delete_${divId}">
                                <div class="col-xl-12">
                                    <input type="text" name="more_hotel_name_markup[]" hidden id="more_hotel_name_markup${divId}">
                                    <h4 class="d-none" id="">More Accomodation Cost ${decodeURI_city}</h4>
                                    <h4>
                                        More Accomodation Cost ${decodeURI_city} - <a id="new_MAHN_${divId}">${parent_acc_hotel_name}</a>
                                        <a class="btn" id="more_acc_cost_html_${divId}">
                                            (Quantity : <b style="color: #cdc0c0;"></b>) 
                                            (Check in : <b style="color: #cdc0c0;">${parent_check_in}</b>) 
                                            (Check Out : <b style="color: #cdc0c0;">${parent_check_out}</b>) 
                                            (Nights : <b style="color: #cdc0c0;">${parent_acomodation_nights_class}</b>)
                                        </a>
                                    </h4>
                                </div>
                                
                                <input type="hidden" id="more_hotel_Type_Costing" name="more_markup_Type_Costing[]" value="more_hotel_Type_Costing" class="form-control">
                                
                                <input value="${parent_acc_hotel_CityName}" type="hidden" name="more_acc_hotel_CityName[]" id="more_acc_hotel_CityName${divId}" value="${decodeURI_city}">
                                <input value="${parent_acc_hotel_HotelName}" type="hidden" name="more_acc_hotel_HotelName[]" id="more_acc_hotel_HotelName${divId}">
                                <input value="${parent_acc_hotel_CheckIn}" type="hidden" name="more_acc_hotel_CheckIn[]" id="more_acc_hotel_CheckIn${divId}">
                                <input value="${parent_acc_hotel_CheckOut}" type="hidden" name="more_acc_hotel_CheckOut[]" id="more_acc_hotel_CheckOut${divId}">
                                <input value="${parent_acc_hotel_NoOfNights}" type="hidden" name="more_acc_hotel_NoOfNights[]" id="more_acc_hotel_NoOfNights${divId}">
                                <input type="hidden" name="more_acc_hotel_Quantity[]" id="more_acc_hotel_Quantity${divId}">
                                
                                <div class="col-xl-3">
                                    <label>Room Type</label>
                                    <input value="${parent_hotel_type_select_class}" type="text" id="more_hotel_acc_type_${divId}" readonly="" name="more_room_type[]" class="form-control">
                                </div>
                                
                                <div class="col-xl-3">
                                    <label>Price Per Room/Night</label>
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_acc_price_per_night_${divId}" readonly="" name="more_without_markup_price_single[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <label>Cost Price/Room</label>
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_acc_price_${divId}" readonly="" name="more_without_markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-3"> 
                                    <label>Markup Type</label>
                                    <select name="more_markup_type[]" onchange="more_hotel_markup_type_accI(${divId})" id="more_hotel_markup_types_${divId}" class="form-control">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                        <option value="per_Night">Per Night</option>
                                    </select>
                                </div>
                                
                                <div class="col-xl-3 more_markup_value_Div_${divId}" style="display:none;margin-top:10px">
                                    <label>Markup Value</label>
                                    <input type="hidden" id="" name="" class="form-control">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text" class="form-control" id="more_hotel_markup_${divId}" name="more_markup[]" onkeyup="get_markup_invoice_price(${divId})">
                                        <span class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_hotel_markup_mrk_${divId}" class="currency_value1">SAR</div></button>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 more_exchnage_rate_Div_${divId}" style="display:none;margin-top:10px">
                                    <label>Exchange Rate</label>
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_exchage_rate_per_night_${divId}" readonly name="more_exchage_rate_single[]" class="form-control">    
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 more_markup_price_Div_${divId}" style="display:none;margin-top:10px">
                                    <label>Markup Price</label>
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_exchage_rate_markup_total_per_night_${divId}" readonly name="more_markup_total_per_night[]" class="form-control"> 
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 more_markup_total_price_Div_${divId}" style="display:none;margin-top:10px">
                                    <label>Markup Total Price</label>
                                    <div class="input-group">
                                        <input type="text" id="more_hotel_markup_total_${divId}" name="more_markup_price[]" class="form-control" readonly>
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                            </div>`;
        
                $("#append_accomodation_data_cost1").append(data_cost);
                $("#append_add_accomodation_"+id+'').append(data1);
                
                if(parent_switch_hotel_name == 0){
                    more_get_room_types(divId);
                }
                
                var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
                var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
                var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
                var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
                var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
                if(switch_hotel_name == 1){
                    var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
                }else{
                    var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
                }
                var new_MAHN = `${acc_hotel_nameN}`;
                $('#new_MAHN_'+id+'').html(new_MAHN);
                var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
                $('#more_acc_cost_html_'+id+'').html(html_data);
                
                $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
                $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
                $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
                $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
                $("#more_acc_hotel_Quantity"+id+'').val(acc_qty_classN);
                
                var places_D1 = new google.maps.places.Autocomplete(
                        document.getElementById('more_acc_hotel_name_'+divId+'')
                    );
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    var address = places_D1.formatted_address;
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
                                        results[0].address_components.length - 2
                                    ].short_name;
                                $('#country').val(country);
                                $('#lat').val(latitude);
                                $('#long').val(longitude);
                                $('#pin').val(pin);
                                $('#city').val(city);
                                $('#country_code').val(country_code);
                            }
                        }
                    });
                });
                
                var more_hotel_city = $('#more_hotel_city'+divId+'').val()
                $('#more_hotel_name_markup'+divId+'').val(more_hotel_city);
                
                divId  = parseFloat(divId) + 1;
                $('#more_acc_new_id').val(divId);
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                exchange_currency_funs(value_1,value_2);
                
                
        }
        
        function more_hotel_funI(id){
            var acc_hotel_name = $('#more_acc_hotel_name_'+id+'').val();
            $('#more_hotel_name_acc_'+id+'').val(acc_hotel_name);
            $('#more_hotel_name_markup'+id+'').val(acc_hotel_name);
            
            var places_D1 = new google.maps.places.Autocomplete(
                document.getElementById('more_acc_hotel_name_'+id+'')
            );
            
            google.maps.event.addListener(places_D1, "place_changed", function () {
                var places_D1 = places_D1.getPlace();
                // console.log(places_D1);
                var address = places_D1.formatted_address;
                var latitude = places_D1.geometry.location.lat();
                var longitude = places_D1.geometry.location.lng();
                var latlng = new google.maps.LatLng(latitude, longitude);
                var geocoder = (geocoder = new google.maps.Geocoder());
                geocoder.geocode({ latLng: latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address = results[0].formatted_address;
                            var pin = results[0].address_components[
                                results[0].address_components.length - 1
                            ].long_name;
                            var country =  results[0].address_components[
                                results[0].address_components.length - 2
                              ].long_name;
                            var state = results[0].address_components[
                                    results[0].address_components.length - 3
                                ].long_name;
                            var city = results[0].address_components[
                                    results[0].address_components.length - 4
                                ].long_name;
                            var country_code = results[0].address_components[
                                    results[0].address_components.length - 2
                                ].short_name;
                            $('#country').val(country);
                            $('#lat').val(latitude);
                            $('#long').val(longitude);
                            $('#pin').val(pin);
                            $('#city').val(city);
                            $('#country_code').val(country_code);
                        }
                    }
                });
            });
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var new_MAHN = `${acc_hotel_nameN}`;
            $('#new_MAHN_'+id+'').html(new_MAHN);
            var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
            $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
            $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
            $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN);
            
        }
        
        function more_hotel_type_fun(id){
            
            var more_switch_htfI = $('#more_switch_htfI_'+id+'').val();
            console.log('more_switch_htfI : '+more_switch_htfI);
            if(more_switch_htfI == 1){
                alert('Select/Add Hotel First');
            }else{
            
                var hotel_type = $('#more_hotel_type_'+id+'').val();
                $('#more_hotel_acc_type_'+id+'').val(hotel_type);
                
                $('.more_acc_qty_class_'+id+'').val('');
                $('.more_acc_pax_class_'+id+'').val('');
                
                // Price Section
                $('#more_hotel_price_for_week_end_'+id+'').empty();
                $('#more_makkah_acc_room_price_funs_'+id+'').val('');
                $('#more_acc_price_get_'+id+'').val('');
                $('#more_acc_total_amount_'+id+'').val('');
                $('#more_exchange_rate_price_funs_'+id+'').val('');
                $('#more_price_per_room_exchange_rate_'+id+'').val('');
                $('#more_price_per_person_exchange_rate_'+id+'').val('');
                $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            }
        }
        
        function more_makkah_accomodation_check_in_class(id){
            
            // Total
            $('#more_room_quantity_'+id+'').css('display','none');
            $('.more_room_quantity_'+id+'').val('');
            
            // Booked
            $('#more_room_booked_quantity_'+id+'').css('display','none');
            $('.more_room_booked_quantity_'+id+'').val('');
            
            // Availaible
            $('#more_room_available_'+id+'').css('display','none');
            $('.more_room_available_'+id+'').val('');
            
            // Over Booked
            $('#more_room_over_booked_quantity_'+id+'').css('display','none');
            $('.more_room_over_booked_quantity_'+id+'').val('');
            
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
            
            $('#more_acc_hotel_name_'+id+'').val('');
            // Room Type
            $('#more_hotel_type_'+id+'').empty();
            var hotel_MRT_data = `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
            $('#more_hotel_type_'+id+'').append(hotel_MRT_data);
            
            
            $('#more_switch_hotel_name'+id+'').val(1);
            $('#more_add_hotel_div'+id+'').css('display','');
            $('#more_select_hotel_div'+id+'').css('display','none');
            $('.more_select_hotel_btn'+id+'').css('display','');
            $('.more_add_hotel_btn'+id+'').css('display','none');
            $('.more_hotel_type_add_div_'+id+'').css('display','');
            $('.more_hotel_type_select_div_'+id+'').css('display','none');
            
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
            
            $('.more_hotel_type_select_class_'+id+'').empty();
            
            // Meal Type
            $('#more_hotel_meal_type_'+id+'').empty();
            var hotel_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#more_hotel_meal_type_'+id+'').append(hotel_MT_data);
            
            // Price Section
            $('#more_makkah_acc_room_price_funs_'+id+'').val('');
            $('#more_acc_price_get_'+id+'').val('');
            $('#more_acc_total_amount_'+id+'').val('');
            
            $('#more_hotel_price_for_week_end_'+id+'').empty();
            $('#more_makkah_acc_room_price_'+id+'').val('');
            $('#more_makkah_acc_price_'+id+'').val('');
            $('#more_makkah_acc_total_amount_'+id+'').val('');
            $('#more_exchange_rate_price_funs_'+id+'').val('');
            $('#more_price_per_room_exchange_rate_'+id+'').val('');
            $('#more_price_per_person_exchange_rate_'+id+'').val('');
            $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            
            var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            const date1     = new Date(start_date);
            const date2     = new Date(enddate);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            var diff=(diffDays);
            $("#more_acomodation_nights_"+id+'').val(diff);
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var new_MAHN = `${acc_hotel_nameN}`;
            $('#new_MAHN_'+id+'').html(new_MAHN);
            var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
            $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
            $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
            $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function more_makkah_accomodation_check_out(id){
            
            // Total
            $('#more_room_quantity_'+id+'').css('display','none');
            $('.more_room_quantity_'+id+'').val('');
            
            // Booked
            $('#more_room_booked_quantity_'+id+'').css('display','none');
            $('.more_room_booked_quantity_'+id+'').val('');
            
            // Availaible
            $('#more_room_available_'+id+'').css('display','none');
            $('.more_room_available_'+id+'').val('');
            
            // Over Booked
            $('#more_room_over_booked_quantity_'+id+'').css('display','none');
            $('.more_room_over_booked_quantity_'+id+'').val('');
            
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
            
            $('#more_acc_hotel_name_'+id+'').val('');
            // Room Type
            $('#more_hotel_type_'+id+'').empty();
            var hotel_MRT_data = `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
            $('#more_hotel_type_'+id+'').append(hotel_MRT_data);
            
            $('#more_switch_hotel_name'+id+'').val(1);
            $('#more_add_hotel_div'+id+'').css('display','');
            $('#more_select_hotel_div'+id+'').css('display','none');
            $('.more_select_hotel_btn'+id+'').css('display','');
            $('.more_add_hotel_btn'+id+'').css('display','none');
            $('.more_hotel_type_add_div_'+id+'').css('display','');
            $('.more_hotel_type_select_div_'+id+'').css('display','none');
            
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
            
            $('.more_hotel_type_select_class_'+id+'').empty();
            
            // Meal Type
            $('#more_hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#more_hotel_meal_type_'+id+'').append(hote_MT_data);
            
            // Price Section
            $('#more_makkah_acc_room_price_funs_'+id+'').val('');
            $('#more_acc_price_get_'+id+'').val('');
            $('#more_acc_total_amount_'+id+'').val('');
            
            $('#more_hotel_price_for_week_end_'+id+'').empty();
            $('#more_makkah_acc_room_price_'+id+'').val('');
            $('#more_makkah_acc_price_'+id+'').val('');
            $('#more_makkah_acc_total_amount_'+id+'').val('');
            $('#more_exchange_rate_price_funs_'+id+'').val('');
            $('#more_price_per_room_exchange_rate_'+id+'').val('');
            $('#more_price_per_person_exchange_rate_'+id+'').val('');
            $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            
            var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            const date1     = new Date(start_date);
            const date2     = new Date(enddate);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            var diff=(diffDays);
            $("#more_acomodation_nights_"+id+'').val(diff);
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var new_MAHN = `${acc_hotel_nameN}`;
            $('#new_MAHN_'+id+'').html(new_MAHN);
            var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
            $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
            $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
            $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function more_select_hotel_btn(id){
            var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            
            if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                
                $('#more_hotel_invoice_markup_'+id+'').val('');
                $('#more_hotel_supplier_id_'+id+'').val('');
                $('#more_hotel_type_id_'+id+'').val('');
                $('#more_hotel_type_cat_'+id+'').val('');
                $('#more_hotelRoom_type_id_'+id+'').val('');
                $('#more_hotelRoom_type_idM_'+id+'').val('');
                
                $('#more_switch_htfI_'+id+'').val(0);
                
                var more_hotel_city = $('#more_hotel_city'+id+'').val();
                
                $('.more_get_room_types_'+id+'').empty();
                var dataHTC =   `<option attr_id="" value=""></option>`;
                $('.more_get_room_types_'+id+'').append(dataHTC);
                $('.more_acc_hotel_name_class_'+id+'').val('');
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/get_hotels_list') }}",
                    method: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "property_city_new": more_hotel_city,
                    },
                    success: function(result){
                        var user_hotels = result['user_hotels'];
                        $('.more_get_room_types_'+id+'').append('<option>Select Hotel</option>');
                        $.each(user_hotels, function(key, value) {
                            var attr_ID         = value.id;
                            var property_name   = value.property_name;
                            var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                            $('.more_get_room_types_'+id+'').append(data_append);
                        });
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
                
                // Total
                $('#more_room_quantity_'+id+'').css('display','none');
                $('.more_room_quantity_'+id+'').val('');
                
                // Booked
                $('#more_room_booked_quantity_'+id+'').css('display','none');
                $('.more_room_booked_quantity_'+id+'').val('');
                
                // Availaible
                $('#more_room_available_'+id+'').css('display','none');
                $('.more_room_available_'+id+'').val('');
                
                // Over Booked
                $('#more_room_over_booked_quantity_'+id+'').css('display','none');
                $('.more_room_over_booked_quantity_'+id+'').val('');
                
                // Price Section
                $('#more_hotel_price_for_week_end_'+id+'').empty();
                $('#more_makkah_acc_room_price_funs_'+id+'').val('');
                $('#more_acc_price_get_'+id+'').val('');
                $('#more_acc_total_amount_'+id+'').val('');
                $('#more_exchange_rate_price_funs_'+id+'').val('');
                $('#more_price_per_room_exchange_rate_'+id+'').val('');
                $('#more_price_per_person_exchange_rate_'+id+'').val('');
                $('#more_price_total_amout_exchange_rate_'+id+'').val('');
                $('.more_hotel_type_class_'+id+'').empty();
                var dataHTC =   `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
                
                $('.more_hotel_type_class_'+id+'').append(dataHTC);
                
                // More Switch
                
                $('.more_acc_qty_class_'+id+'').val('');
                $('.more_acc_pax_class_'+id+'').val('');
                
                $('#more_acc_hotel_name_'+id+'').val('');
                
                $('#more_switch_hotel_name'+id+'').val(0);
                $('#more_add_hotel_div'+id+'').css('display','none');
                $('.more_select_hotel_btn'+id+'').css('display','none');
                $('#more_select_hotel_div'+id+'').css('display','');
                $('.more_add_hotel_btn'+id+'').css('display','');
                $('.more_hotel_type_add_div_'+id+'').css('display','none');
                $('.more_hotel_type_select_div_'+id+'').css('display','');
                
                $('.more_hotel_type_select_class_'+id+'').empty();
                var dataMHTC =   `<option value=""></option>`;
                $('.more_hotel_type_select_class_'+id+'').append(dataMHTC);
                
            }else{
                alert('Select Date First!');
            }
        }
        
        function more_add_hotel_btn(id){
            var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            
            if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                
                $('#more_switch_htfI_'+id+'').val(0);
                
                $('.more_get_room_types_'+id+'').empty();
                var dataHTC =   `<option attr_id="" value=""></option>`;
                $('.more_get_room_types_'+id+'').append(dataHTC);
                $('.more_acc_hotel_name_class_'+id+'').val('');
                
                // Total
                $('#more_room_quantity_'+id+'').css('display','none');
                $('.more_room_quantity_'+id+'').val('');
                
                // Booked
                $('#more_room_booked_quantity_'+id+'').css('display','none');
                $('.more_room_booked_quantity_'+id+'').val('');
                
                // Availaible
                $('#more_room_available_'+id+'').css('display','none');
                $('.more_room_available_'+id+'').val('');
                
                // Over Booked
                $('#more_room_over_booked_quantity_'+id+'').css('display','none');
                $('.more_room_over_booked_quantity_'+id+'').val('');
                
                // Price Section
                $('#more_hotel_price_for_week_end_'+id+'').empty();
                $('#more_makkah_acc_room_price_funs_'+id+'').val('');
                $('#more_acc_price_get_'+id+'').val('');
                $('#more_acc_total_amount_'+id+'').val('');
                $('#more_exchange_rate_price_funs_'+id+'').val('');
                $('#more_price_per_room_exchange_rate_'+id+'').val('');
                $('#more_price_per_person_exchange_rate_'+id+'').val('');
                $('#more_price_total_amout_exchange_rate_'+id+'').val('');
                
                $('#more_switch_hotel_name'+id+'').val(1);
                $('#more_add_hotel_div'+id+'').css('display','');
                $('.more_select_hotel_btn'+id+'').css('display','');
                $('#more_select_hotel_div'+id+'').css('display','none');
                $('.more_add_hotel_btn'+id+'').css('display','none');
                $('.more_hotel_type_add_div_'+id+'').css('display','');
                $('.more_hotel_type_select_div_'+id+'').css('display','none');
                
                $('.more_acc_qty_class_'+id+'').val('');
                $('.more_acc_pax_class_'+id+'').val('');
                
                $('.more_hotel_type_class_'+id+'').empty();
                var dataHTC =   `<option value="">Choose ...</option>
                                <option attr="2" value="Double">Double</option>
                                <option attr="3" value="Triple">Triple</option>
                                <option attr="4" value="Quad">Quad</option>`;
                
                $('.more_hotel_type_class_'+id+'').append(dataHTC);
                
                // // More Room Type
                $('#more_hotel_meal_type_'+id+'').empty();
                var hote_MT_data = `<option value="">Choose ...</option>
                                    <option value="Room only">Room only</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>`;
                $('#more_hotel_meal_type_'+id+'').append(hote_MT_data);
                
                // More Price Section
                $('#more_makkah_acc_room_price_funs_'+id+'').val('');
                $('#more_makkah_acc_room_price_funs_'+id+'').attr('readonly', false);
                $('#more_acc_price_get_'+id+'').val('');
                $('#more_acc_total_amount_'+id+'').val('');
                $('#more_exchange_rate_price_funs_'+id+'').val('');
                $('#more_price_per_room_exchange_rate_'+id+'').val('');
                $('#more_price_per_person_exchange_rate_'+id+'').val('');
                $('#more_price_total_amout_exchange_rate_'+id+'').val('');
                
                $('.more_hotel_type_select_class_'+id+'').empty();
                var dataMHTC =   `<option value=""></option>`;
                $('.more_hotel_type_select_class_'+id+'').append(dataMHTC);
                
            }else{
                alert('Select Date First!');
            }
        }
        
        function more_get_room_types(id){
            
            var hotel_id = $('.more_get_room_types_'+id+'').find('option:selected').attr('attr_ID');
            console.log('id is '+hotel_id);
            $('#more_select_hotel_id'+id+'').val(hotel_id);
        
            ids                 = $('.more_get_room_types_'+id+'').find('option:selected').attr('attr_ID');
            var start_date      = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddate         = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            const weekDaysCount = countDaysOfWeekBetweenDates(start_date, enddate);
            
            var Sunday_D    = weekDaysCount[0];
            var Monday_D    = weekDaysCount[1];
            var Tuesday_D   = weekDaysCount[2];
            var Wednesday_D = weekDaysCount[3];
            var Thursday_D  = weekDaysCount[4];
            var Friday_D    = weekDaysCount[5];
            var Saturday_D  = weekDaysCount[6];
            var total_days = parseFloat(Sunday_D) + parseFloat(Monday_D) + parseFloat(Tuesday_D) + parseFloat(Wednesday_D) + parseFloat(Thursday_D) + parseFloat(Friday_D) + parseFloat(Saturday_D);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/get_rooms_list') }}",
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": ids,
                    "check_in": start_date,
                    "check_out": enddate,
                },
                success: function(result){
                    var user_rooms              = result['user_rooms'];
                    var price_All               = 0;
                    var price_Single            = 0;
                    var total_WeekDays          = 0;
                    var total_WeekEnds          = 0;
                    var price_WeekDays          = 0;
                    var price_WeekEnds          = 0;
                    var more_total_WeekDays     = 0;
                    var more_total_WeekEnds     = 0;
                    var more_price_WeekDays     = 0;
                    var more_price_WeekEnds     = 0;
                    if(user_rooms !== null && user_rooms !== '' && user_rooms.length != 0){
                        
                        $('#more_new_rooms_type_'+id+'').css('display','none');
                        $('#more_new_room_supplier_div_'+id+'').css('display','none');
                        $('.more_hotel_type_add_div_'+id+'').css('display','none');
                        $('.more_hotel_type_select_div_'+id+'').css('display','');
                        $('.more_hotel_type_select_class_'+id+'').empty();
                        $('.more_hotel_type_select_class_'+id+'').append('<option value="">Select Hotel Type...</option>')
                        
                        if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                            $.each(user_rooms, function(key, value) {
                                var availible_from          = value.availible_from;
                                var availible_to            = value.availible_to;
                                var more_room_type_details  = value.more_room_type_details;
                                
                                if(Date.parse(start_date) >= Date.parse(availible_from) && Date.parse(enddate) <= Date.parse(availible_to)){
                                    
                                    var price_week_type     = value.price_week_type;
                                    var room_supplier_name  = value.room_supplier_name;
                                    var room_supplier_id    = value.room_supplier_name;
                                    $.each(supplier_detail, function(key, supplier_detailS) {
                                        var id = supplier_detailS.id;
                                        if(id == room_supplier_name){
                                            room_supplier_name  = supplier_detailS.room_supplier_name;
                                        }
                                    });
                                    
                                    var room_meal_type      = value.room_meal_type;
                                    var more_hotelRoom_type_id = value.id;
                                    var more_hotelRoom_type_idM = '';
                                    
                                    if(value.room_type_cat != null && value.room_type_cat != ''){
                                        var room_type_cat   = value.room_type_cat;
                                        var room_type_name  = value.room_type_name;
                                    }else{
                                        var room_type_id    = ''
                                        var room_type_name  = ''
                                    }
                                    
                                    var more_room_booked_quantity = value.booked;
                                    var more_room_quantity        = value.quantity;
                                    if(more_room_booked_quantity != null && more_room_booked_quantity != ''){
                                        var more_room_booked_quantity = value.booked;
                                    }else{
                                        var more_room_booked_quantity = 0;
                                    }
                                    
                                    if(price_week_type != null && price_week_type != ''){
                                        if(price_week_type == 'for_all_days'){
                                            var price_all_days  = value.price_all_days;
                                            var room_type_id    = value.room_type_id;
                                            if(room_type_id != null && room_type_id != ''){
                                                console.log('room_type_id if : '+room_type_id);
                                                
                                                if(room_type_id == 'Single'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="1" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Double'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="2" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Triple'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="3" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Quad'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="4" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                                }
                                                else{
                                                    $('.more_hotel_type_select_class_'+id+'').append('');
                                                }
                                            }
                                            
                                        }else{
                                            var weekdays        = value.weekdays;
                                            var weekdays_price  = value.weekdays_price;
                                            
                                            var weekends_price  = value.weekends_price;
                                            if(weekdays != null && weekdays != ''){
                                                var weekdays1       = JSON.parse(weekdays);
                                                $.each(weekdays1, function(key, weekdaysValue) {
                                                    if(weekdaysValue == 'Sunday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Sunday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Sunday_D);
                                                    }else if(weekdaysValue == 'Monday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Monday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Monday_D);
                                                    }else if(weekdaysValue == 'Tuesday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Tuesday_D);
                                                    }else if(weekdaysValue == 'Wednesday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Wednesday_D);
                                                    }else if(weekdaysValue == 'Thursday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Thursday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Thursday_D);
                                                    }else if(weekdaysValue == 'Friday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Friday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Friday_D);
                                                    }else if(weekdaysValue == 'Saturday'){
                                                        price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Saturday_D) * parseFloat(weekdays_price);
                                                        total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Saturday_D);
                                                    }else{
                                                        price_WeekDays   = price_WeekDays;
                                                        total_WeekDays   = total_WeekDays;
                                                    }
                                                });
                                            }
                                            
                                            var weekends = value.weekends;
                                            if(weekends != null && weekends != ''){
                                                var weekends1       = JSON.parse(weekends);
                                                $.each(weekends1, function(key, weekendValue) {
                                                    if(weekendValue == 'Sunday'){
                                                        price_WeekEnds  = parseFloat(price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Sunday_D);
                                                    }else if(weekendValue == 'Monday'){
                                                        price_WeekEnds    = parseFloat(price_WeekEnds) + parseFloat(Monday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Monday_D);
                                                    }else if(weekendValue == 'Tuesday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Tuesday_D);
                                                    }else if(weekendValue == 'Wednesday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Wednesday_D);
                                                    }else if(weekendValue == 'Thursday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Thursday_D);
                                                    }else if(weekendValue == 'Friday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Friday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Friday_D);
                                                    }else if(weekendValue == 'Saturday'){
                                                        price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(weekends_price);
                                                        total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Saturday_D);
                                                    }else{
                                                        price_WeekEnds = price_WeekEnds;
                                                        total_WeekEnds = total_WeekEnds;
                                                    }
                                                });
                                            }
                                            
                                            var room_type_id    = value.room_type_id;
                                            if(room_type_id != null && room_type_id != ''){
                                                console.log('room_type_id else : '+room_type_id);
                                                if(room_type_id == 'Single'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="1" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Double'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="2" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Triple'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="3" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                                }
                                                else if(room_type_id == 'Quad'){
                                                    $('.more_hotel_type_select_class_'+id+'').append('<option attr-more_room_quantity="'+more_room_quantity+'" attr-more_room_booked_quantity="'+more_room_booked_quantity+'" attr="4" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_id+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                                }
                                                else{
                                                    $('.more_hotel_type_select_class_'+id+'').append('');
                                                }
                                            }
                                        }
                                    }else{
                                        console.log('price_week_type is empty!')
                                    }
                                }
                                
                                // if(more_room_type_details != null && more_room_type_details != ''){
                                //     var more_room_type_details1 = JSON.parse(more_room_type_details);
                                //     $.each(more_room_type_details1, function(key, value1) {
                                //         var more_room_av_from       = value1.more_room_av_from;
                                //         var more_room_av_to         = value1.more_room_av_to;
                                //         var more_room_supplier_name = value1.more_room_supplier_name
                                //         var more_room_supplier_id   = value1.more_room_supplier_name;
                                //         $.each(supplier_detail, function(key, supplier_detailS) {
                                //             var id = supplier_detailS.id;
                                //             if(id == more_room_supplier_name){
                                //                 more_room_supplier_name  = supplier_detailS.room_supplier_name;
                                //             }
                                //         });
                                        
                                //         var more_hotelRoom_type_id  = value.id;
                                //         var more_hotelRoom_type_idM = value1.room_gen_id;
                                        
                                //         if(value1.more_room_type_name != null && value1.more_room_type_name != ''){
                                //             var more_room_type_cat   = value1.more_room_type_id;
                                //             var more_room_type_name  = value1.more_room_type_name;
                                //         }else{
                                //             var room_type_id    = ''
                                //             var more_room_type_name  = ''
                                //         }
                                        
                                //         if(Date.parse(start_date) >= Date.parse(more_room_av_from) && Date.parse(enddate) <= Date.parse(more_room_av_to)){
                                //             var more_room_meal_type = value1.more_room_meal_type;
                                //             if(more_room_meal_type != null && more_room_meal_type != ''){
                                //                 // var more_room_meal_type1    = JSON.parse(more_room_meal_type);
                                //                 var more_room_meal_type1    = more_room_meal_type;
                                //                 var more_room_meal_type2    = more_room_meal_type1;
                                //             }else{
                                //                 var more_room_meal_type2 = '';
                                //             }
                                            
                                //             var more_week_price_type = value1.more_week_price_type;
                                //             if(more_week_price_type != null && more_week_price_type != ''){
                                //                 // var more_week_price_type1    = JSON.parse(more_week_price_type);
                                //                 var more_week_price_type1    = more_week_price_type;
                                //                 var more_week_price_type2    = more_week_price_type1;
                                //                 if(more_week_price_type2 == 'for_all_days'){
                                //                     var more_price_all_days  = value1.more_price_all_days;
                                //                     var more_room_type = value1.more_room_type;
                                //                     if(more_room_type != null && more_room_type != ''){
                                //                         if(more_room_type == 'Single'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Double'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Triple'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Quad'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else{
                                //                             $('.more_hotel_type_select_class_'+id+'').append('');
                                //                         }
                                //                     }
                                //                 }else{
                                //                     var more_week_end_price     = value1.more_week_end_price
                                //                     var more_week_days_price    = value1.more_week_days_price;
                                                    
                                //                     var more_weekdays = value1.more_weekdays;
                                //                     if(more_weekdays != null && more_weekdays != ''){
                                //                         var more_weekdays1          = JSON.parse(more_weekdays);
                                //                         $.each(more_weekdays1, function(key, more_weekdaysValue) {
                                //                             if(more_weekdaysValue == 'Sunday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Sunday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Sunday_D);
                                //                             }else if(more_weekdaysValue == 'Monday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Monday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Monday_D);
                                //                             }else if(more_weekdaysValue == 'Tuesday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Tuesday_D);
                                //                             }else if(more_weekdaysValue == 'Wednesday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Wednesday_D);
                                //                             }else if(more_weekdaysValue == 'Thursday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Thursday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Thursday_D);
                                //                             }else if(more_weekdaysValue == 'Friday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Friday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Friday_D);
                                //                             }else if(more_weekdaysValue == 'Saturday'){
                                //                                 more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Saturday_D) * parseFloat(more_week_days_price);
                                //                                 more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Saturday_D);
                                //                             }else{
                                //                                 more_price_WeekDays  = more_price_WeekDays;
                                //                                 more_total_WeekDays  = more_total_WeekDays;
                                //                             }
                                //                         });
                                //                     }
                                                    
                                //                     var more_weekend = value1.more_weekend;
                                //                     if(more_weekend != null && more_weekend != ''){
                                //                         var more_weekend1 = JSON.parse(more_weekend);
                                //                         $.each(more_weekend1, function(key, more_weekendValue) {
                                //                             if(more_weekendValue == 'Sunday'){
                                //                                 more_price_WeekEnds  = parseFloat(more_price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Sunday_D);
                                //                             }else if(more_weekendValue == 'Monday'){
                                //                                 more_price_WeekEnds    = parseFloat(more_price_WeekEnds) + parseFloat(Monday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Monday_D);
                                //                             }else if(more_weekendValue == 'Tuesday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Tuesday_D);
                                //                             }else if(more_weekendValue == 'Wednesday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Wednesday_D);
                                //                             }else if(more_weekendValue == 'Thursday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Thursday_D);
                                //                             }else if(more_weekendValue == 'Friday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Friday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Friday_D);
                                //                             }else if(more_weekendValue == 'Saturday'){
                                //                                 more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(more_week_end_price);
                                //                                 more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Saturday_D);
                                //                             }else{
                                //                                 more_price_WeekEnds = more_price_WeekEnds;
                                //                                 more_total_WeekEnds = more_total_WeekEnds;
                                //                             }
                                //                         });
                                //                     }
                                                    
                                //                     var more_room_type    = value1.more_room_type;
                                //                     if(more_room_type != null && more_room_type != ''){
                                //                         if(more_room_type == 'Single'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Double'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Triple'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else if(more_room_type == 'Quad'){
                                //                             $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                //                         }
                                //                         else{
                                //                             $('.more_hotel_type_select_class_'+id+'').append('');
                                //                         }
                                //                     }
                                                    
                                //                     var price_WE_WD = parseFloat(more_price_WeekDays) + parseFloat(more_price_WeekEnds);
                                //                 }
                                //             }else{
                                //                 console.log('more_week_price_type is empty!')
                                //             }
                                //         }
                                //     });
                                    
                                //     // console.log(more_room_type_details1);
                                // }
                                
                            });
                        }else{
                            alert('Select Date First!');
                        }
                        
                    }else{
                         console.log('rooms not fount ');
                  
                        
                        $('.more_hotel_type_select_div_'+id+'').html('<option value="select One">Select One</option>');
                        
                        var roomsTypes = `<option>Select One</option>`;
                            result['rooms_types'].forEach((roomType)=>{
                                var room_data = JSON.stringify(roomType)
                                roomsTypes += `<option value='${room_data}'>${roomType['room_type']}</option>`;
                            })
                            
                               var roomSupplier = ``;
                               var roomSupplierHtml = ``;      
                               
                            result['rooms_supplier'].forEach((roomSupplier)=>{
                                console.log(roomSupplierHtml);
                                roomSupplierHtml += `<option value="${roomSupplier['id']}" >${roomSupplier['room_supplier_name']}</option>`;
                            })
                            
                            
                            console.log(roomSupplierHtml);
                            $('#more_new_rooms_type_'+id+'').html(roomsTypes);
                            $('#more_new_room_supplier_'+id+'').html(roomSupplierHtml);
                            
                            
                        
                        $('.more_hotel_type_select_div_'+id+'').css('display','none');
                        $('#more_new_rooms_type_'+id+'').css('display','block');
                        $('#more_new_room_supplier_div_'+id+'').css('display','block');
                        
                        $('#more_select_add_new_room_type_'+id+'').val(true)
                    }
                    
                    var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
                    var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
                    var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
                    var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
                    var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
                    }
                    var new_MAHN = `${acc_hotel_nameN}`;
                    $('#new_MAHN_'+id+'').html(new_MAHN);
                    var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
                    $('#more_acc_cost_html_'+id+'').html(html_data);
                    
                    $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
                    $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
                    $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
                    $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
                    $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN);    
                
                },
            });
        }
        
        function more_hotel_type_funInvoice(id){
            $('.more_acc_qty_class_'+id+'').val('');
            $('.more_acc_pax_class_'+id+'').val('');
            
            // Price Section
            $('#more_hotel_price_for_week_end_'+id+'').empty();
            $('#more_makkah_acc_room_price_funs_'+id+'').val('');
            $('#more_acc_price_get_'+id+'').val('');
            $('#more_acc_total_amount_'+id+'').val('');
            $('#more_exchange_rate_price_funs_'+id+'').val('');
            $('#more_price_per_room_exchange_rate_'+id+'').val('');
            $('#more_price_per_person_exchange_rate_'+id+'').val('');
            $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            
            var hotel_type = $('.more_hotel_type_select_class_'+id+'').val();
            $('#more_hotel_acc_type_'+id+'').val(hotel_type);
            $('#more_hotel_meal_type_'+id+'').empty();
            
            var hotel_attr_type         = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-type');
            var hotel_price_All         = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-all');
            var hotel_total_days        = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_days');
            var hotel_room_meal_type    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_meal_type');
            var hotel_price_weekdays    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekdays');
            var hotel_total_weekdays    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekdays');
            var hotel_price_weekends    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekends');
            var hotel_total_weekends    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekends');
            var dataMRMT                = `<option value="${hotel_room_meal_type}">${hotel_room_meal_type}</option>`;
            
            // Price Calculations
            var total_Nights_WEWD       = parseFloat(hotel_total_weekdays) + parseFloat(hotel_total_weekends)
            var hotel_type_price        = $('.more_hotel_type_select_class_'+id+'').val();
            var room_price              = $('#more_makkah_acc_room_price_funs_'+id+'').val();
            if(hotel_type_price == 'Double')
            {
                hotel_type_price = 2;
            }
            else if(hotel_type_price == 'Triple')
            {
                hotel_type_price = 3;
            }
            else if(hotel_type_price == 'Quad')
            {
                hotel_type_price = 4;
            }else{
                hotel_type_price = 1;
            }
            
            var attr_room_supplier_name = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_supplier_name');
            var attr_room_type_cat      = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_type_cat');
            var attr_room_type_name     = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_type_name');
            var more_hotelRoom_type_id  = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_hotelRoom_type_id');
            var more_hotelRoom_type_idM  = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_hotelRoom_type_idM');
            
            // Total
            $('#more_room_quantity_'+id+'').css('display','');
            var more_room_quantity    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_quantity');
            $('#more_room_quantity_'+id+'').html('Total : '+more_room_quantity);
            $('.more_room_quantity_'+id+'').val(more_room_quantity);
            
            // Booked
            $('#more_room_booked_quantity_'+id+'').css('display','');
            var more_room_booked_quantity    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_booked_quantity');
            $('#more_room_booked_quantity_'+id+'').html('Booked : '+more_room_booked_quantity);
            $('.more_room_booked_quantity_'+id+'').val(more_room_booked_quantity);
            
            // Available/Over Booked
            if(parseFloat(more_room_booked_quantity) > parseFloat(more_room_quantity)){
                var more_room_over_booked_quantity = parseFloat(more_room_booked_quantity) - parseFloat(more_room_quantity);
                $('#more_room_over_booked_quantity_'+id+'').css('display','');
                $('#more_room_over_booked_quantity_'+id+'').html('Over Booked : '+more_room_over_booked_quantity);
                $('.more_room_over_booked_quantity_'+id+'').val(more_room_over_booked_quantity);
                
                more_room_available = 0;
                $('#more_room_available_'+id+'').css('display','');
                $('#more_room_available_'+id+'').html('Available : '+more_room_available);
                $('.more_room_available_'+id+'').val(more_room_available);
            }else{
                var more_room_available = parseFloat(more_room_quantity) - parseFloat(more_room_booked_quantity);
                $('#more_room_available_'+id+'').css('display','');
                $('#more_room_available_'+id+'').html('Available : '+more_room_available);
                $('.more_room_available_'+id+'').val(more_room_available);
            }
                
            if(hotel_attr_type == 'for_All_Days' || hotel_attr_type == 'more_for_All_Days'){
                var room_price = $('#more_makkah_acc_room_price_funs_'+id+'').val(hotel_price_All);
                $('#more_hotel_meal_type_'+id+'').append(dataMRMT);
                var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For All Days)</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_days}" class="form-control no_of_nights_all_days${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_All}" class="form-control price_per_night_all_days${id}" readonly>
                                                        </div>`;
                $('#more_hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
                
                $('#more_hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
                $('#more_hotel_type_id_'+id+'').val(attr_room_type_cat);
                $('#more_hotel_type_cat_'+id+'').val(attr_room_type_name);
                $('#more_hotelRoom_type_id_'+id+'').val(more_hotelRoom_type_id);
                $('#more_hotelRoom_type_idM_'+id+'').val(more_hotelRoom_type_idM);
                
                var total       = parseFloat(hotel_price_All)/parseFloat(hotel_type_price);
                total           = total.toFixed(2);
                // var grand_total = parseFloat(total) * parseFloat(hotel_total_days);
                var grand_total = parseFloat(hotel_price_All) * parseFloat(hotel_total_days);
                grand_total     = grand_total.toFixed(2);
                $('#more_acc_price_get_'+id+'').val(total);
                $('#more_acc_total_amount_'+id+'').val(grand_total);
                $('#more_makkah_acc_room_price_funs_'+id+'').attr('readonly', true);
                $('#more_acc_price_get_'+id+'').attr('readonly', true);
                $('#more_acc_total_amount_'+id+'').attr('readonly', true);
                
            }else if(hotel_attr_type == 'for_Week_Days' || hotel_attr_type == 'more_for_Week_Days'){
                var price_per_person_weekdays       = parseFloat(hotel_price_weekdays)/parseFloat(hotel_type_price);
                var price_per_person_weekends       = parseFloat(hotel_price_weekends)/parseFloat(hotel_type_price);
                var total_price_per_person_weekdays = parseFloat(price_per_person_weekdays) * parseFloat(hotel_total_weekdays);
                var total_price_per_person_weekends = parseFloat(price_per_person_weekends) * parseFloat(hotel_total_weekends);
                
                var TP_WEWD     = parseFloat(total_price_per_person_weekdays) + parseFloat(total_price_per_person_weekends);
                
                var TP_WEWD1     = parseFloat(hotel_price_weekdays) + parseFloat(hotel_price_weekends);
                var room_price  = $('#more_makkah_acc_room_price_funs_'+id+'').val(TP_WEWD1);
                var total       = parseFloat(TP_WEWD)/parseFloat(hotel_type_price);
                // var grand_total = parseFloat(total) * parseFloat(total_Nights_WEWD);
                var grand_total = parseFloat(TP_WEWD) * parseFloat(total_Nights_WEWD);
                $('#more_acc_price_get_'+id+'').val(total);
                $('#more_acc_total_amount_'+id+'').val(grand_total);
                
                $('#more_hotel_meal_type_'+id+'').append(dataMRMT);
                var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Week Price Details</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_weekdays}" class="form-control no_of_nights_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_weekdays}" class="form-control price_per_night_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Person/Night</label>
                                                            <input type="text" value="${price_per_person_weekdays}" class="form-control price_per_person_weekdays${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Total Amount/Per Person</label>
                                                            <input type="text" value="${total_price_per_person_weekdays}" class="form-control total_price_per_person_weekdays${id}" readonly>
                                                        </div>
                                                        <h4 class="mt-4">WeekEnd Price Details</h4>
                                                        <div class="col-xl-3">
                                                            <label for="">No of Nights</label>
                                                            <input type="text" value="${hotel_total_weekends}" class="form-control no_of_nights_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Room/Night</label>
                                                            <input type="text" value="${hotel_price_weekends}" class="form-control price_per_night_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Price Per Person/Night</label>
                                                            <input type="text" value="${price_per_person_weekends}" class="form-control price_per_person_weekends${id}" readonly>
                                                        </div>
                                                        <div class="col-xl-3">
                                                            <label for="">Total Amount/Per Person</label>
                                                            <input type="text" value="${total_price_per_person_weekends}" class="form-control total_price_per_person_weekends${id}" readonly>
                                                        </div>`;
                $('#more_hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
                $('#more_hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
                $('#more_hotel_type_id_'+id+'').val(attr_room_type_cat);
                $('#more_hotel_type_cat_'+id+'').val(attr_room_type_name);
                $('#more_hotelRoom_type_id_'+id+'').val(more_hotelRoom_type_id);
                $('#more_hotelRoom_type_idM_'+id+'').val(more_hotelRoom_type_idM);
                
            }else{
                alert('Select Room Type');
            }
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var new_MAHN = `${acc_hotel_nameN}`;
            $('#new_MAHN_'+id+'').html(new_MAHN);
            var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
            $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
            $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
            $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN); 
        }
        
        function more_acc_qty_classInvoice(id){
            
            var more_acc_qty_class  = $('.more_acc_qty_class_'+id+'').val();
            var more_room_available = $('.more_room_available_'+id+'').val();
            
            if(parseFloat(more_acc_qty_class) > parseFloat(more_room_available)){
                alert('You Enter Quantity greater then Availability!');
            }else{
                console.log('OK');
            }
            
            var more_switch_hotel_name = $('#more_switch_hotel_name'+id+'').val();
            if(more_switch_hotel_name == 0){
               
                   var room_select_type = $('#more_select_add_new_room_type_'+id+'').val();
                    if(room_select_type == 'false'){
                        var more_hotel_type = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr');
                        var more_mult       = parseFloat(more_acc_qty_class) * parseFloat(more_hotel_type);
                    }else{
                        console.log(room_type_data);
                        var room_type_data = $('#more_new_rooms_type_'+id+'').val();
                        var room_type_Obj = JSON.parse(room_type_data);
                        
                        var more_mult            = parseFloat(more_acc_qty_class) * parseFloat(room_type_Obj['no_of_persons']);
                        
                        console.log(room_type_data);
                    }
            
                $('.more_acc_pax_class_'+id+'').val(more_mult);
                
            }else{
                var more_hotel_type     = $('.more_hotel_type_class_'+id+'').find('option:selected').attr('attr');
                var more_mult           = parseFloat(more_acc_qty_class) * parseFloat(more_hotel_type);
                $('.more_acc_pax_class_'+id+'').val(more_mult);
            }
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var new_MAHN = `${acc_hotel_nameN}`;
            $('#new_MAHN_'+id+'').html(new_MAHN);
            var html_data = `(Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            $("#more_acc_hotel_CheckIn"+id+'').val(start_dateN);
            $("#more_acc_hotel_CheckOut"+id+'').val(enddateN);
            $("#more_acc_hotel_NoOfNights"+id+'').val(acomodation_nightsN);
            $('#more_acc_hotel_Quantity'+id+'').val(acc_qty_classN);
        }
        
        function more_makkah_acc_room_price_funsI(id){
            var more_switch_hotel_name = $('#more_switch_hotel_name'+id+'').val();
            if(more_switch_hotel_name == 1){
                var hotel_type_price = $('#more_hotel_type_'+id+'').val();
            }else{
                var hotel_type_price = $('.more_hotel_type_select_class_'+id+'').val();
            }
            
            var room_price          = $('#more_makkah_acc_room_price_funs_'+id+'').val();
            var acomodation_nights  = $('#more_acomodation_nights_'+id+'').val();
            
            if(hotel_type_price == 'Double')
            {
                hotel_type_price = 2;
            }else if(hotel_type_price == 'Triple')
            {
                hotel_type_price = 3;
            }else if(hotel_type_price == 'Quad')
            {
                hotel_type_price = 4;
            }else{
                hotel_type_price = 1;
            }
            
            var total           = parseFloat(room_price)/parseFloat(hotel_type_price);
            var total1          = total.toFixed(2);
            var grand_total     = parseFloat(room_price) * parseFloat(acomodation_nights);
            var grand_total1    = grand_total.toFixed(2);
            
            $('#more_acc_price_get_'+id+'').val(total1);
            $('#more_acc_total_amount_'+id+'').val(grand_total1);
        }
        
        function more_exchange_rate_price_funsI(id){
            
            $('#more_hotel_markup_'+id+'').val('');
            $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val('');
            $('#more_hotel_markup_total_'+id+'').val('');
            
            var makkah_acc_room_price       = $('#more_makkah_acc_room_price_funs_'+id+'').val();
            var makkah_acc_price            = $('#more_acc_price_get_'+id+'').val();
            var makkah_acc_total_amount     = $('#more_acc_total_amount_'+id+'').val();
            var exchange_rate_price_funs    = $('#more_exchange_rate_price_funs_'+id+'').val();
            $('#more_hotel_exchage_rate_per_night_'+id+'').val(exchange_rate_price_funs);
            
            var currency_conversion         = $("#select_exchange_type").val();
            if(currency_conversion == 'Divided'){
                var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price)/parseFloat(exchange_rate_price_funs);
                var price_per_person_exchangeRate=parseFloat(makkah_acc_price)/parseFloat(exchange_rate_price_funs);
                var price_total_exchangeRate=parseFloat(makkah_acc_total_amount)/parseFloat(exchange_rate_price_funs);  
            }else{
                var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price) * parseFloat(exchange_rate_price_funs);
                var price_per_person_exchangeRate=parseFloat(makkah_acc_price) * parseFloat(exchange_rate_price_funs);
                var price_total_exchangeRate=parseFloat(makkah_acc_total_amount) * parseFloat(exchange_rate_price_funs);  
            }
            
            var price_per_room_exchangeRate     = price_per_room_exchangeRate.toFixed(2);
            var price_per_person_exchangeRate   = price_per_person_exchangeRate.toFixed(2);
            var price_total_exchangeRate        = price_total_exchangeRate.toFixed(2);
            $('#more_price_per_room_exchange_rate_'+id+'').val(price_per_room_exchangeRate);
            $('#more_hotel_acc_price_per_night_'+id+'').val(price_per_room_exchangeRate);
            $('#more_price_per_person_exchange_rate_'+id+'').val(price_per_person_exchangeRate);
            $('#more_price_total_amout_exchange_rate_'+id+'').val(price_total_exchangeRate);
            $('#more_hotel_acc_price_'+id+'').val(price_total_exchangeRate);
            
            
            var double_cost_price   = 0;
            var triple_cost_price   = 0;
            var quad_cost_price     = 0;
            for(var k = 1; k<=20; k++){
                
                var more_price_total_amout_exchange_rate = $('#more_price_total_amout_exchange_rate_'+k+'').val();
                
                if(more_price_total_amout_exchange_rate != null && more_price_total_amout_exchange_rate != '' && more_price_total_amout_exchange_rate != 0){    
                    
                    var more_switch_hotel_name      = $('#more_switch_hotel_name'+k+'').val();
                    if(more_switch_hotel_name != 1){
                        var more_hotel_type_price   = $('.more_hotel_type_select_class_'+k+'').val();
                    }else{
                        var more_hotel_type_price   = $('#more_hotel_type_'+k+'').val();
                    }
                    
                    if(more_hotel_type_price == 'Double'){
                        double_cost_price       = parseFloat(double_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                        var double_cost_price1  = double_cost_price.toFixed(2);
                        $('#double_cost_price').val(double_cost_price1);
                    }else if(more_hotel_type_price == 'Triple'){
                        triple_cost_price       = parseFloat(triple_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                        var triple_cost_price1  = triple_cost_price.toFixed(2);
                        $('#triple_cost_price').val(triple_cost_price1);
                    }else if(more_hotel_type_price == 'Quad'){
                        quad_cost_price         = parseFloat(quad_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                        var quad_cost_price1    = quad_cost_price.toFixed(2);
                        $('#quad_cost_price').val(quad_cost_price1);
                    }else{
                        console.log('Hotel Type Not Found!!');
                    }
                }
                else{
                    console.log('Hotel Type Not Found!!');
                }
            }
        }
        
        function more_hotel_markup_type_accI(id){
            $('#more_hotel_markup_'+id+'').val('');
            $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val('');
            $('#more_hotel_markup_total_'+id+'').val('');
            
            var ids                                 = $('#more_hotel_markup_types_'+id+'').val();
            var prices                              = $('#more_hotel_acc_price_'+id+'').val();
            add_numberElseI();
            if(ids == ''){
                alert('Select markup Type');
                $('.more_markup_value_Div_'+id+'').css('display','none');
                $('.more_exchnage_rate_Div_'+id+'').css('display','none');
                $('.more_markup_price_Div_'+id+'').css('display','none');
                $('.more_markup_total_price_Div_'+id+'').css('display','none');
            }else{
                $('.more_markup_value_Div_'+id+'').css('display','');
                $('.more_exchnage_rate_Div_'+id+'').css('display','');
                $('.more_markup_price_Div_'+id+'').css('display','');
                $('.more_markup_total_price_Div_'+id+'').css('display','');
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                $('#more_hotel_markup_mrk_'+id+'').text(value_1);
            }
            
        }
        
        function get_markup_invoice_price(id){
            var ids                             = $('#more_hotel_markup_types_'+id+'').val();
            var prices                          = $('#more_hotel_acc_price_'+id+'').val();
            var hotel_acc_price_per_night       = $('#more_hotel_acc_price_per_night_'+id+'').val();
            var hotel_exchage_rate_per_night    = $('#more_hotel_exchage_rate_per_night_'+id+'').val();
            var acomodation_nights              = $('#more_acomodation_nights_'+id+'').val();
            add_numberElseI();
            if(ids == '%'){
                var markup_val  =  $('#more_hotel_markup_'+id+'').val();
                var total1      = prices * markup_val/100;
                var total       = total1.toFixed(2);
                $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3      = total2.toFixed(2);
                $('#more_hotel_markup_total_'+id+'').val(total3);
                $('#more_hotel_invoice_markup_'+id+'').val(total3);
                
                add_numberElse_1I();
            }else if(ids == 'per_Night'){
                var markup_val  =  $('#more_hotel_markup_'+id+'').val();
                var total1      = (parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night)) * acomodation_nights;
                var total       = total1.toFixed(2);
                $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3       = total2.toFixed(2);
                $('#more_hotel_markup_total_'+id+'').val(total3);
                $('#more_hotel_invoice_markup_'+id+'').val(total3);
                add_numberElse_1I();
            }else{
                var markup_val  =  $('#more_hotel_markup_'+id+'').val();
                var total1      = parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night);
                var total       = total1.toFixed(2);
                $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
                var total2      = parseFloat(total) + parseFloat(prices);
                var total3       = total2.toFixed(2);
                $('#more_hotel_markup_total_'+id+'').val(total3);
                $('#more_hotel_invoice_markup_'+id+'').val(total3);
                add_numberElse_1I();
            }
        }
        
        // Visa
        $("#currency_conversion1").on('change', function(){
            var value_c                 = $('option:selected', this).val();
            var attr_conversion_type    = $('option:selected', this).attr('attr_conversion_type');
            $("#select_exchange_type").val(attr_conversion_type);
            const usingSplit = value_c.split(' ');
            var value_1 = usingSplit['0'];
            var value_2 = usingSplit['2'];
            
            $('#visa_C_S').empty();
            $('#visa_C_S').html(value_1);
            
            if(value_c != 0){
                $('#invoice_exchage_rate_visa_Div').empty();
                
                var data = `<div class="col-xl-6" style="padding: 10px;">
                                <label for="">Exchange Rate</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                            
                                        </a>
                                    </span>
                                    <input type="text" id="exchange_rate_visaI" onkeyup="exchange_rate_visaI_function()" name="exchange_rate_visaI" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-xl-6" style="padding: 10px;">
                                <label for="">Exchange Visa Fee</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                    <input type="text" id="exchange_rate_visa_total_amountI" name="exchange_rate_visa_fee" class="form-control">
                                </div>
                            </div>`;
                $('#invoice_exchage_rate_visa_Div').append(data);
            }else{
                $('#invoice_exchage_rate_visa_Div').empty();
            }
            exchange_currency_funs(value_1,value_2);
            
        });
        
        function exchange_rate_visaI_function(){
            var visa_fee            = $('#visa_fee').val();
            var exchange_rate_visa  = $('#exchange_rate_visaI').val();
            var currency_conversion = $("#select_exchange_type").val();
            if(currency_conversion == 'Divided'){
                var total_visa = parseFloat(visa_fee) / parseFloat(exchange_rate_visa);
            }
            else{
                var total_visa = parseFloat(visa_fee) * parseFloat(exchange_rate_visa);
            }
            var total_visa = total_visa.toFixed(2);
            $('#exchange_rate_visa_total_amountI').val(total_visa);
            $('#visa_price_select').val(total_visa);
        }
        
        var v_ID = $('#m_VD_count').val();
        $('#add_more_visa_Pax').click(function (){
            var data = `<div id="more_visa_pax_div_${v_ID}" class="row">
                            <div class="col-xl-4" style="padding: 10px;">
                                <label for="">More Visa Type</label>
                                <select name="more_visa_type[]" id="more_visa_type_${v_ID}" class="form-control more_visa_type_class" onchange="more_visa_type_select(${v_ID})"></select>
                             </div>
                        
                            <div class="col-xl-4" style="padding: 10px;">
                                <label for="">More Visa Fee</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up more_visa_C_S">
                                            <?php echo $currency; ?>
                                        </a>
                                    </span>
                                    <input type="text" id="more_visa_fee_${v_ID}" name="more_visa_fee[]" class="form-control" onchange="more_visa_fee_calculation(${v_ID})">
                                </div>
                            </div>
                            <input type="hidden" id="more_total_visa_markup_value_${v_ID}" name="more_total_visa_markup_value[]" class="form-control">
                            <div class="col-xl-3" style="padding: 10px;">
                                <label for="">More Visa Pax</label>
                                <input type="text" id="more_visa_Pax_${v_ID}" name="more_visa_Pax[]" class="form-control">
                            </div>
                            
                            <div class="col-xl-1" style="margin-top: 30px;">
                                <button type="button" onclick="remove_more_visa_pax(${v_ID})" class="btn btn-primary">Remove</button>
                            </div>
                            
                            <div id="more_invoice_exchage_rate_visa_Div_${v_ID}" class="row"></div>
                        </div>`;
            $('#add_more_visa_Pax_Div').append(data);
            
            var data_1 = `<div class="row" id="more_visa_cost_${v_ID}">                        
                                <div class="col-xl-3">
                                    <h4>More Visa Cost</h4>
                                </div>
                                
                                <input type="hidden" name="acc_hotel_CityName[]">
                                <input type="hidden" name="acc_hotel_HotelName[]">
                                <input type="hidden" name="acc_hotel_CheckIn[]">
                                <input type="hidden" name="acc_hotel_CheckOut[]" >
                                <input type="hidden" name="acc_hotel_NoOfNights[]">
                                <input type="hidden" name="acc_hotel_Quantity[]">
                                
                                <div class="col-xl-9">
                                    <input type="hidden" id="more_visa_Type_Costing_${v_ID}" name="markup_Type_Costing[]" value="more_visa_Type_Costing" class="form-control">
                                </div>
                                
                                <div class="col-xl-3">
                                    <input readonly type="text" id="more_visa_type_select_${v_ID}" name="hotel_name_markup[]" class="form-control">
                                </div>
                                
                                <div class="col-xl-2" style="display:none">
                                    <div class="input-group">
                                        <input type="text" id="more_visa_price_per_night_${v_ID}" readonly="" name="more_without_markup_price_single[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                        </span>        
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="input-group">
                                        <input readonly type="text" id="more_visa_price_select_${v_ID}" name="without_markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <select name="markup_type[]" id="more_visa_markup_type_${v_ID}" class="form-control" onchange="more_visa_markup_type_function(${v_ID})">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                    </select>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text" class="form-control" id="more_visa_markup_${v_ID}" name="markup[]" onkeyup="more_visa_markup_function(${v_ID})">
                                        <span class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_visa_mrk_${v_ID}">%</div></button>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-1" style="display:none">
                                    <input type="text" id="more_visa_exchage_rate_per_night_${v_ID}" readonly="" name="more_exchage_rate_single[]" class="form-control">    
                                </div>
                                
                                <div class="col-xl-2" style="display:none">
                                    <div class="input-group">
                                        <input type="text" id="more_visa_exchage_rate_markup_total_per_night_${v_ID}" readonly="" name="more_markup_total_per_night[]" class="form-control"> 
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="input-group">
                                        <input type="text" id="more_total_visa_markup_${v_ID}" name="markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                
                            </div>`;
            $('#more_visa_cost_div').append(data_1);
            
            var value_c                 = $("#currency_conversion1").val();
            var attr_conversion_type    = $("#currency_conversion1").find('option:selected').attr('attr_conversion_type');
            const usingSplit            = value_c.split(' ');
            var value_1                 = usingSplit['0'];
            var value_2                 = usingSplit['2'];
            $("#select_exchange_type").val(attr_conversion_type);
            
            $('.more_visa_C_S').empty();
            $('.more_visa_C_S').html(value_1);
            
            if(value_c != 0){
                $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').empty();
                
                var data = `<div class="col-xl-6" style="padding: 10px;">
                                <label for="">Exchange Rate</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                            
                                        </a>
                                    </span>
                                    <input type="text" id="more_exchange_rate_visaI_${v_ID}" onkeyup="more_exchange_rate_visaI_function(${v_ID})" name="more_exchange_rate_visa[]" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-xl-6" style="padding: 10px;">
                                <label for="">Exchange Visa Fee</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                    <input type="text" id="more_exchange_rate_visa_total_amountI_${v_ID}" name="more_exchange_rate_visa_fee[]" class="form-control">
                                </div>
                            </div>`;
                $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').append(data);
            }else{
                $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').empty();
            }
            exchange_currency_funs(value_1,value_2);
            
            $.ajax({
                url:"{{URL::to('super_admin/get_other_visa_type_detail')}}",
                type: "GET",
                dataType: "html",
                success: function(data){
                    var data1   = JSON.parse(data);
                    var data2   = JSON.parse(data1['visa_type']);
                	$('.more_visa_type_class').empty();
                    $.each(data2['visa_type'], function(key, value) {
                        var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                        $('.more_visa_type_class').append(visa_type_Data);
                    });  
                }
            });
            
            v_ID = parseFloat(v_ID) + 1;
        });
        
        function more_exchange_rate_visaI_function(id){
            var more_visa_fee               =  $('#more_visa_fee_'+id+'').val();
            var more_exchange_rate_visa     =  $('#more_exchange_rate_visaI_'+id+'').val();
            var currency_conversion         = $("#select_exchange_type").val();
            if(currency_conversion == 'Divided'){
                var total_visa = parseFloat(more_visa_fee) / parseFloat(more_exchange_rate_visa);
            }
            else{
                var total_visa = parseFloat(more_visa_fee) * parseFloat(more_exchange_rate_visa);
            }
            var total_visa = total_visa.toFixed(2);
            $('#more_exchange_rate_visa_total_amountI_'+id+'').val(total_visa);
            $('#more_visa_price_select_'+id+'').val(total_visa);    
        }
        
        function remove_more_visa_pax(id){
            $('#more_visa_pax_div_'+id+'').remove();
            $('#more_visa_cost_'+id+'').remove();
        }
        
        function more_visa_type_select(id){
            var more_visa_type = $('#more_visa_type_'+id+'').val();
            $('#more_visa_type_select_'+id+'').val(more_visa_type);
        }
        
        function more_visa_fee_calculation(id){
            var more_visa_fee = $('#more_visa_fee_'+id+'').val();
            $('#more_visa_price_select_'+id+'').val(more_visa_fee);
            $('#more_exchange_rate_visaI_'+id+'').val('');
            $('#more_exchange_rate_visa_total_amountI_'+id+'').val('');
        }
        
        function more_visa_markup_type_function(id){
            var id1 = $('#more_visa_markup_type_'+id+'').find('option:selected').attr('value');
            $('#more_visa_mrk_'+id+'').text(id1);
        }
        
        function more_visa_markup_function(id){
            var id1 = $('#more_visa_markup_type_'+id+'').find('option:selected').attr('value');
            if(id1 == '%')
            {
                var more_visa_markup        =  $('#more_visa_markup_'+id+'').val();
                var more_visa_price_select  =  $('#more_visa_price_select_'+id+'').val();
                var total1                  = (more_visa_price_select * more_visa_markup/100) + parseFloat(more_visa_price_select);
                var total                   = total1.toFixed(2);
                $('#more_total_visa_markup_'+id+'').val(total);
                $('#more_total_visa_markup_value_'+id+'').val(total);
            }
            else
            {
                var more_visa_markup        =  $('#more_visa_markup_'+id+'').val();
                var more_visa_price_select  =  $('#more_visa_price_select_'+id+'').val();
                var total1                  =  parseFloat(more_visa_price_select) +  parseFloat(more_visa_markup);
                var total                   = total1.toFixed(2);
                $('#more_total_visa_markup_'+id+'').val(total);
                $('#more_total_visa_markup_value_'+id+'').val(total);
            }
        }
        
        $('#submitForm_hotel_name_New').on('click',function(e){
            e.preventDefault();
            let form_submit_data = $("#form_submit_data").text($("#form_submit_id").serialize());
            console.log('form_submit_data : '+form_submit_data);
            $.ajax({
                url: "hotel_manger/add_hotel_sub_ajax",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    form_submit_data:form_submit_data,
                },
                success:function(response){
                    if(response){
                        console.log(response);
                        // var data1 = response
                        // console.log(data1);
                        // var data = data1['hotel_Name_get'];
                        // console.log(data);
                        // $(".other_Hotel_Name").empty();
                        // $.each(data, function(key, value) {
                        //     var other_Hotel_Name_Data = `<option attr="${value.other_Hotel_Name}" value="${value.other_Hotel_Name}"> ${value.other_Hotel_Name}</option>`;
                        //     $(".other_Hotel_Name").append(other_Hotel_Name_Data);
                        // });
                        // alert('Other Hotel Name Added SuccessFUl!');
                    }
                    $('#success-message').text(response.success);
                },
            });
        });
    
        $("#accomodation_edit").on('click',function(){
            
            $("#append_accomodation_data_cost1").empty();
            $("#append_accomodation_data_cost").empty();
            $("#append_accomodation").empty();
            var packages_get_city = $('#packages_get_city').val();
            var decodeURI_city = JSON.parse(packages_get_city);
            var city_slc =$(".city_slc").val();
            var count = city_slc.length;
            var j=0;
            for (let i = 1; i <= count; i++) {
                
                var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                                <h4>
                                    City #${i} (${decodeURI_city[i-1]})
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon_${i}"
                                        data-bs-toggle="tooltip" data-bs-placement="right" title="Fill all the information of City ${decodeURI_city[i-1]}">
                                    </i>
                                </h4> 
                                    <div class="row">
                                        
                                        <input type="hidden" name="hotel_city_name[]" id="hotel_city_name" value="${decodeURI_city[i-1]}"/>
                                    <div class="col-xl-3">
                                        <label for="">Hotel Name</label>
                                        <div class="input-group">
                                            <select type="text" onchange="hotel_fun(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control other_Hotel_Name acc_hotel_name_class_${i}">
                                            
                                            </select>
                                            <span title="Add Hotel Name" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-name-modal" type="button">+</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_${i}">
                                    </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})"  class="form-control date makkah_accomodation_check_out_date_class_${i}"></div>
                                    <div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                    
                                    <div class="col-xl-2"><label for="">Room Type</label>
                                        <div class="input-group">
                                            <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option attr="4" value="Quad">Quad</option>
                                                <option attr="3" value="Triple">Triple</option>
                                                <option attr="2" value="Double">Double</option>
                                            </select>
                                            <span title="Add Hotel Type" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                            </span>
                                        </div>
                                        
                                        </div>
                                        <div class="col-xl-2"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                                        
                                        <div class="col-xl-2">
                                        <label for="">Pax</label>
                                        <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                        </div>
                                        <div class="col-xl-3">
                                        <label for="">Price Per Person/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
                                            <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                        </div>
                                        
                                        </div>
                    
                                        <div class="col-xl-3"><label for="">Total Amount/Per Person</label>
                                         <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
                                            <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount[]" class="form-control makkah_acc_total_amount_class_${i}">
                                        </div>
                                        </div>
                                        
                                        <div id="append_add_accomodation_${i}"></div>
                                        <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                        
                                        
                                          <div class="col-xl-12">
                                             <div class="mb-3">
                                             
                                             
                                             
                                             
                                              <label for="simpleinput" class="form-label">Room Amenities</label>
                                              <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                              
                                             </div>
                                          </div>
                                        
                                        <div class="col-xl-12"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                        </div></div>`;
          
            
                var data_cost=`<div class="row" id="${i}">
                                    <input type="text" name="hotel_name_markup[]" hidden>
                                    <div class="col-xl-3">
                                        <h4 class="" id="">Accomodation Cost ${decodeURI_city[i-1]}</h4>
                                    </div>
                                    <div class="col-xl-9"></div>
                                    
                                
                                    <div class="col-xl-3">
                                    
                                    <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                        </div>
                                         <div class="col-xl-3">
                                            <div class="input-group">
                                                <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                                    </a>
                                                </span>
                                            </div>
                                    </div>
                                     <div class="col-xl-2">
                                             
                                              <select name="markup_type[]" onchange="hotel_markup_type(${i})" id="hotel_markup_types_${i}" class="form-control">
                                                    <option value="">Markup Type</option>
                                                    <option value="%">Percentage</option>
                                                    <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                              </select>
                              
                                    </div>
                                    <div class="col-xl-2">
                                         
                                     <input type="hidden" id="" name="" class="form-control">
                                     
                                     <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]">
                                        <span class="input-group-btn input-group-append">
                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}">%</div></button>
                                    </span>
                                    </div>
                     
                     
                     
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="input-group">
                                            <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
                                        </div> 
                                    </div>
                                </div>`;
          
              
                $("#append_accomodation_data_cost").append(data_cost);
                $("#append_accomodation").append(data);
                $('.acc_qty_class_'+i+'').on('change',function(){
                    
                    var acc_qty_class = $(this).val();
                    // console.log(acc_qty_class);
                    var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                    // console.log(hotel_type);
                    var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                    // console.log(mult);
                    $('.acc_pax_class_'+i+'').val(mult);
                    
                });
                
                j = j + 1;
            }
        
            var select_ct =$(".select_ct").val();
            
            var count_1 = select_ct.length;
            
            for (let i = 1; i <= count_1; i++) {
                // console.log(i);
              var data1 = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;"><h4>City #${i} </h4><div class="row"><div class="col-xl-3"><label for="">Hotel Name</label><input type="text" id="simpleinput" name="acc_hotel_name[]" class="form-control">
            </div><div class="col-xl-3"><label for="">Check In</label><input type="date" id="simpleinput" name="acc_check_in[]" class="form-control">
            </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="simpleinput" name="acc_check_out[]" class="form-control"></div><div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="nights" name="acc_no_of_nightst[]" class="form-control"></div><div class="col-xl-3"><label for="">Room Type</label>
            <select name="acc_type[]" id="property_city" class="form-control"  data-placeholder="Choose ..."><option value="">Choose ...</option><option value="Quad">Quad</option><option value="Triple">Triple</option><option value="Double">Double</option></select></div>
            <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control"></div>
            <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="acc_pax[]" class="form-control"></div><div class="col-xl-3"><label for="">Price</label>
            <input type="text" id="simpleinput" name="acc_price[]" class="form-control"></div><div class="col-xl-3"><label for="">Currency</label><select name="acc_currency[]" id="property_city" class="form-control"><option value="">Choose ...</option><option value="SAR">SAR</option><option value="Dollar">Dollar</option><option value="Pound">Pound</option></select></div><div class="col-xl-3"><label for="">Comission</label><input type="text" id="simpleinput" name="acc_commision[]" class="form-control"></div><div class="col-xl-3"><label for="">Sale Price</label><input type="text" id="simpleinput" name="acc_sale_Porice[]" class="form-control"></div><div class="col-xl-3"><label for="">Total Amount</label><input type="text" id="simpleinput" name="acc_total_amount[]" class="form-control"></div>
            <div id="append_add_accomodations_${i}"></div><div class="mt-2"><a href="javascript:;" onclick="add_more_accomodations(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div></div></div>`;
              $("#append_accomodation").append(data1);   
              
            }
        
            $("#select_accomodation").slideToggle();
        });
    
        $('#save_Accomodation').on('click',function(e){
            e.preventDefault();
            
            var select_ct =$("#property_city").val();
            var count_1 = select_ct.length;
            // console.log(count_1);
            for(i=0; i < count_1; i++){
                var acc_hotel_name = [];
                var id  = $(this).val();
                var hotel_city_name = $("#hotel_city_name").val();
                var acc_hotel_name1 = $('.acc_hotel_name_class_'+i+'').val();
                // console.log(acc_hotel_name1);
                acc_hotel_name.push(acc_hotel_name1);
                // console.log(acc_hotel_name);
                var acc_check_in = $('.makkah_accomodation_check_in_class_'+i+'').val();
                var acc_check_out = $('.makkah_accomodation_check_out_date_class_'+i+'').val();
                var acc_no_of_nights = $('.acomodation_nights_class_'+i+'').val();
                var acc_type = $('.hotel_type_class_'+i+'').val();
                var acc_qty = $('.acc_qty_class_'+i+'').val();
                var acc_pax = $('.acc_pax_class_'+i+'').val();
                var acc_price = $('.makkah_acc_price_class_'+i+'').val();
                var acc_total_amount = $('.makkah_acc_total_amount_class_'+i+'').val();
            }
            
            $.ajax({    
                type: 'POST',
                url: 'save_Accomodation/'+id,
                data:{
                    '_token'                    : '{{ csrf_token() }}',
                    'id'                        : id,
                    'hotel_city_name'           : hotel_city_name,
                    'acc_hotel_name'            : acc_hotel_name,
                    'acc_check_in'              : acc_check_in,
                    'acc_check_out'             : acc_check_out,
                    'acc_no_of_nights'          : acc_no_of_nights,
                    'acc_type'                  : acc_type,
                    'acc_qty'                   : acc_qty,
                    'acc_pax'                   : acc_pax,
                    'acc_price'                 : acc_price,
                    'acc_total_amount'          : acc_total_amount,
                },
                success: function(data){
                    // console.log(data);
                    // alert('Accomodation Details Saved SuccessFUl!');
                }
            });
        });
        
        var Agents_detail1 = {!!json_encode($Agents_detail)!!};
        
        $("#agent_Company_Name").on('change', function(){
            var value_c     = $('option:selected', this).val();
            var agent_Id    = $(this).find('option:selected').attr('attr-Id');
            $('#agent_Id').val(agent_Id);
            
            var agent_Name = $(this).find('option:selected').attr('attr-AN');
            $('#agent_Name').val(agent_Name);
            
            $.each(Agents_detail1, function(key, value) {
                var AD_id = value.id;
                if(AD_id == agent_Id){
                    var lead_fname  = value.agent_Name;
                    var lead_lname  = value.agent_Name;
                    var lead_email  = value.agent_Email;
                    var lead_mobile = value.agent_contact_number;
                    var company_name    = value.company_name;
                    
                    $('#lead_fnameI').val(company_name);
                    $('#lead_lnameI').val(company_name);
                    $('#lead_emailI').val(lead_email);
                    $('#lead_mobileI').val(lead_mobile);
                }
            });
            
        });
        
        $('#customer_name').change(function () {
            var customer_data   = $(this).find('option:selected').attr('attr-cusData');
            var customer_data2  = JSON.parse(customer_data);
            var lead_fname      = customer_data2['name'];
            var lead_lname      = customer_data2['name'];
            var lead_email      = customer_data2['email'];
            var lead_mobile     = customer_data2['phone'];
            $('#lead_fnameI').val(lead_fname);
            $('#lead_lnameI').val(lead_lname);
            $('#lead_emailI').val(lead_email);
            $('#lead_mobileI').val(lead_mobile);
        });
        
        
        function add_new_visa_av(){
            $('#new_visa_table').css('display','block')
            $('#exists_visa_table').css('display','none')
            $('#exists_visa_button').css('display','block')
            $('#new_visa_button').css('display','none')
        }
        
        function add_exist_visa_av(){
            $('#new_visa_table').css('display','none')
            $('#exists_visa_table').css('display','block')
            $('#exists_visa_button').css('display','none')
            $('#new_visa_button').css('display','block')
        }
        
        function calculateVisaPrices(id,type){
            if(type == 'exists'){
                console.log('Type is Exist id is '+id);
                
                var visa_price = $('.visa_pur_fee_cl'+id+'').val()
                var exchange_rate = $('.visa_exchange_cl'+id+'').val()
                
                var currency_conversion = $("#currency_conversion1").find('option:selected').attr('attr_conversion_type');
                console.log(currency_conversion);
                if(currency_conversion == 'Divided'){
                    var visaSalePrice = parseFloat(visa_price)/parseFloat(exchange_rate);
                  
                }else{
                    var visaSalePrice = parseFloat(visa_price) * parseFloat(exchange_rate);
                  
                }
                
                visaSalePrice = visaSalePrice.toFixed(2);
                $('.visa_sale_cl'+id+'').val(visaSalePrice);
                
                $('#visa_price_select'+id+'').val(visaSalePrice);
                //  if(id == '1'){
                    
                //      console.log('Enter in if ');
                //      $('#visa_price_select').val(visaSalePrice);
                // }else{
                //     console.log('Enter in else ');
                    
                // }
                 
            }
            
            if(type == 'new'){
                 console.log('Type is Exist id is '+id);
                
                var visa_price = $('.visa_pur_fee_new_cl'+id+'').val()
                var exchange_rate = $('.visa_exchange_new_cl'+id+'').val()
                
                var currency_conversion = $("#currency_conversion1").find('option:selected').attr('attr_conversion_type');
                console.log(currency_conversion);
                if(currency_conversion == 'Divided'){
                    var visaSalePrice = parseFloat(visa_price)/parseFloat(exchange_rate);
                  
                }else{
                    var visaSalePrice = parseFloat(visa_price) * parseFloat(exchange_rate);
                  
                }
                
                visaSalePrice = visaSalePrice.toFixed(2);
                $('.visa_sale_new_cl'+id+'').val(visaSalePrice);
                $('#visa_price_select'+id+'').val(visaSalePrice);
                // if(id == '1'){
                    
                //      console.log('Enter in if ');
                //      $('#visa_price_select').val(visaSalePrice);
                // }else{
                //     console.log('Enter in else ');
                    
                // }
                
            }
            
            // add_numberElse();
            // calculateGrandWithoutAccomodation();
        }
        
        function fetchVisaSupplierVisa(){
            $('#visa_table').html('');
            // $('#visa_occupy_div').html('');
            
            // $('#visa_price_select').val('');
            // $('#visa_type_select').val('');
            var visaSupplierId = $('#visa_supplier').val();
        
          
            $.ajax({
                 url: '{{ URL::to('get_supplier_visas')}}',
                type:"post",
                data:{
                    "_token"    : "{{ csrf_token() }}",
                    'visa_supplier'    :visaSupplierId,
                },
                success:function(response){
                    var visaData = response;
                    
                    var trData = ``;
                    visaData.forEach((item)=>{
                        console.log(item);
                        trData += `<tr>
                                        <td>
                                        <input type="text" id="visa_av${item['id']}" hidden value='${ JSON.stringify(item) }'>
                                        ${item['name']}</td>
                                        <td>${item['other_visa_type']}</td>
                                        <td>${item['availability_from']} - ${item['availability_to']}</td>
                                        <td>${item['visa_qty']}</td>
                                        <td>${item['visa_available']}</td>
                                        <td><input type="number" id="visa_req_seat${item['id']}" max="${item['visa_available']}" class="form-control"></td>
                                        <td><button type="button" class="btn btn-success" onclick="occupyExistsVisa(${item['id']})">Occupy</button></td>
                                   </tr>`
                    })
                    
                    
                     $('#visa_table').html(trData);
                    // console.log(visaData);
                }
            })
        }
        
        <?php 
            if(isset($visa_row_counter)){
        ?>
                var visa_row_counter = {{ $visa_row_counter }};
        <?php
            }else{
        ?>
            var visa_row_counter = 0;
        <?php } ?>
         
    function occupyExistsVisa(id){
        console.log('visa counter is '+visa_row_counter);
        var visaAvlData = $('#visa_av'+id+'').val();
        var visa_req_seat = $('#visa_req_seat'+id+'').val();
        visaAvlData = JSON.parse(visaAvlData);
        console.log(visaAvlData);
        var visaAvl = parseInt(visaAvlData['visa_available']);
        if(visaAvl >= visa_req_seat && visa_req_seat > 0){
            
                var occupyIndex = occupiedVisaArr.indexOf(visaAvlData['id']);
                if(occupyIndex == -1){
                    occupiedVisaArr.push(visaAvlData['id']);
                    
                    
                    var value_c         = $('#currency_conversion1').val();
                                    const usingSplit    = value_c.split(' ');
                                    var value_1         = usingSplit['0'];
                                    var value_2         = usingSplit['2'];
                                    
                                      var visaSupplierId = $('#visa_supplier').val();
                                      var visaSupplierName = $('#visa_supplier').find('option:selected').attr('supplier-name');
                                      
                
                                   
                                    console.log('Value one is '+value_1);
                                    console.log('Value tow is '+value_2);
                    var visa_div =`<div class="row" id="visa${visa_row_counter}">
                                             <div class="col-xl-4" style="padding: 10px;">
                                                    <label for="">Visa Suppplier</label>
                                                    <input type="text" id="" value="${visaSupplierName}" readonly name="" class="form-control">
                                                 
                                                    
                                                 
                                                 </div>
                                                 
                                                <div class="col-xl-4" style="padding: 10px;">
                                                    <label for="">Visa Type</label>
                                                    <input type="text" id="visa_type" value="${visaAvlData['other_visa_type']}" readonly name="visa_type_name[]" class="form-control visa_type_cl${visa_row_counter}">
                                                 
                                                    
                                                    <input type="text" name="visa_add_type_new[]" hidden  value="false">
                                                    <input type="text" name="visa_country_id[]" hidden value="">
                                                    <input type="text" name="visa_supplier_id[]" hidden value="${visaSupplierId}">
                                                    <input type="text" name="visa_av_from[]" hidden value="">
                                                    <input type="text" name="visa_av_to[]" hidden value="">
                                                    <input type="text" name="visa_type_id[]" hidden value="">
                                                    <input type="text" name="visa_avail_id[]" hidden value="${visaAvlData['id']}">
                                                    
                                                    
                                                 </div>
                                                
                                                <div class="col-xl-2" style="padding: 10px;">
                                                    <label for="">Occupied Seats</label>
                                                    <input type="text" id="visa_seat_occupied" value="${visa_req_seat}" readonly name="visa_Pax[]" class="form-control visa_occupied_cl${visa_row_counter}">
                                                 </div>
                                                 
                                                   <div class="col-xl-2" style="padding: 10px;">
                                                    <label for="">Visa Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                ${value_1}
                                                            </a>
                                                        </span>
                                                        <input type="text" id="visa_fee" value="${visaAvlData['visa_price']}" readonly name="visa_fee_purchase[]" class="form-control visa_pur_fee_cl${visa_row_counter}">
                                                    </div>
                                                 </div>
                                                 
                                                 <div class="col-xl-6" style="padding: 10px;">
                                                    <label for="">Exchange Rate</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                 ${value_1}
                                                            </a>
                                                        </span>
                                                        <input type="text"  id="exchange_rate_visa" onkeyup="calculateVisaPrices(${visa_row_counter},'exists')" name="exchange_rate_visa[]" class="form-control visa_exchange_cl${visa_row_counter}">
                                                    </div>
                                                 </div>
                                                 <div class="col-xl-6" style="padding: 10px;">
                                                    <label for="">Exchange Visa Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                                 ${value_2}
                                                            </a>
                                                        </span>
                                                        <input type="text" id="exchange_rate_visa_total_amount" name="visa_fee[]" readonly class="form-control visa_sale_cl${visa_row_counter}">
                                                        <input type="text" id="markup_visa_total_amount" hidden name="markup_visa_total_amount[]" readonly class="form-control markup_visa_total_amount${visa_row_counter}">
                                                    </div>
                                                 </div>
                                                 <div class="col-xl-12">
                                                    <div class="input-group" style="justify-content: end;">
                                                       <button class="btn btn-danger" style="float:right;" type="button" onclick="deleteVisaDiv(${visa_row_counter},${visaAvlData['id']})">Delete</button>
                                                    </div>
                                                </div>
                                                 <div class="col-xl-12" style="padding: 10px;">
                                                    <hr>
                                                 </div>
                                                </div>
                                                 `;
                                                 
                        var visaCosting = `<div class="row" id="visaCost${visa_row_counter}">
                                                <div class="col-xl-3">
                                                    <h4 class="" id="">Visa Cost</h4>
                                                </div>
                                                
                                                <div class="col-xl-9">
                                                    <input type="hidden" id="visa_Type_Costing${visa_row_counter}" name="markup_Type_Costing[]" value="visa_Type_Costing" class="form-control">
                                                </div>
                                                
                                                <div class="col-xl-3">
                                                    <input readonly type="text" id="visa_type_select${visa_row_counter}" value="${visaAvlData['other_visa_type']}" name="hotel_name_markup[]" class="form-control">
                                                </div>
                                                
                                                <div class="col-xl-3">
                                                    <div class="input-group">
                                                        <input readonly type="text" id="visa_price_select${visa_row_counter}" name="without_markup_price[]" class="form-control">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                               ${value_2}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-2">
                                                    <select name="markup_type[]" id="visa_markup_type${visa_row_counter}" onchange="visa_more_markup_calc(${visa_row_counter})" class="form-control">
                                                        <option value="%">Percentage</option>
                                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-xl-2">
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <input type="text"  class="form-control" id="visa_markup${visa_row_counter}" onkeyup="visa_more_markup_calc(${visa_row_counter})" name="markup[]">
                                                        <span class="input-group-btn input-group-append">
                                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="visa_mrk">%</div></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-2">
                                                    <div class="input-group">
                                                        <input type="text" id="total_visa_markup${visa_row_counter}" name="markup_price[]" class="form-control">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                               ${value_2}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>`;
                                                 
                    // $('#child_visa_price').val(visaAvlData['visa_price']);
                    // $('#child_visa_price_infant').val(visaAvlData['visa_price']);
                    
                    $('#visa_cost').append(visaCosting);
                    
                    $('#visa_occupy_div').append(visa_div);
                    // if(visa_row_counter != '1'){
                    //     $('#visa_cost').append(visaCosting);
                    // }else{
                    //     $('#visa_type_select').val(visaAvlData['other_visa_type']);
                    // }
                    
                    
                    
                    visa_row_counter++;
                }else{
                    alert('This Visa is Already Occupied')
                }
                
                
        }else{
            alert('Please Select Visa According to Requirements')
        }
    }
    
    function occupyNewVisa(id){
        
        var visaSupplierId = $('#visa_supplier').val();
         var visaSupplierName = $('#visa_supplier').find('option:selected').attr('supplier-name');
         var add_seats =  $('#new_visa_required_seats').val();
         console.log(visaSupplierId);
         if(visaSupplierId !== 'Select Supplier'){
                console.log('visa counter is '+visa_row_counter);
                var visa_country_id = $('#visa_country').val();
                var visa_country_name = $('#visa_country').find('option:selected').attr('country_name');
                var visa_type_id = $('#new_visa_type_select').val();
                var visa_type_name = $('#new_visa_type_select').find('option:selected').attr('visa_name');
                
                var av_from =  $('#visa_avl_form').val();
                var av_to =  $('#visa_avl_to').val();
                
         
                
                var value_c         = $('#currency_conversion1').val();
                                const usingSplit    = value_c.split(' ');
                                var value_1         = usingSplit['0'];
                                var value_2         = usingSplit['2'];
                                
                                  
                               
                                console.log('Value one is '+value_1);
                                console.log('Value tow is '+value_2);
                var visa_div =`         <div class="row" id="visa${visa_row_counter}">
                                            <div class="col-xl-4" style="padding: 10px;">
                                                <label for="">Visa Suppplier</label>
                                                <input type="text" id="" value="${visaSupplierName}" readonly name="" class="form-control">
                                             </div>
                                            <div class="col-xl-4" style="padding: 10px;">
                                                <label for="">Visa Type</label>
                                                <input type="text" id="visa_type" value="${visa_type_name}" readonly name="visa_type_name[]" class="form-control visa_type_new_cl${visa_row_counter}">
                                                <input type="text" name="visa_add_type_new[]" hidden value="true">
                                                <input type="text" name="visa_country_id[]" hidden value="${visa_country_id}">
                                                <input type="text" name="visa_supplier_id[]" hidden value="${visaSupplierId}">
                                                <input type="text" name="visa_av_from[]" hidden value="${av_from}">
                                                <input type="text" name="visa_av_to[]" hidden value="${av_to}">
                                                <input type="text" name="visa_type_id[]" hidden value="${visa_type_id}">
                                                <input type="text" name="visa_avail_id[]" hidden value="">
                                                
                                               
                                             </div>
                                            
                                            <div class="col-xl-2" style="padding: 10px;">
                                                <label for="">Occupied Seats</label>
                                                <input type="text" id="visa_seat_occupied" value="${add_seats}" readonly name="visa_Pax[]" class="form-control visa_occupied_new_cl${visa_row_counter}">
                                             </div>
                                             
                                               <div class="col-xl-2" style="padding: 10px;">
                                                <label for="">Visa Fee</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                            ${value_1}
                                                        </a>
                                                    </span>
                                                    <input type="text" id="visa_fee" value="" name="visa_fee_purchase[]" class="form-control visa_pur_fee_new_cl${visa_row_counter}">
                                                </div>
                                             </div>
                                             
                                             <div class="col-xl-6" style="padding: 10px;">
                                                <label for="">Exchange Rate</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                             ${value_1}
                                                        </a>
                                                    </span>
                                                    <input type="text"  id="exchange_rate_visa" onkeyup="calculateVisaPrices(${visa_row_counter},'new')" name="exchange_rate_visa[]" class="form-control visa_exchange_new_cl${visa_row_counter}">
                                                </div>
                                             </div>
                                             <div class="col-xl-6" style="padding: 10px;">
                                                <label for="">Exchange Visa Fee</label>
                                                <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                             ${value_2}
                                                        </a>
                                                    </span>
                                                    <input type="text" id="exchange_rate_visa_total_amount" name="visa_fee[]" readonly class="form-control visa_sale_new_cl${visa_row_counter}">
                                                    <input type="text" id="markup_visa_total_amount" hidden name="markup_visa_total_amount[]" readonly class="form-control markup_visa_total_amount${visa_row_counter}">
                                                </div>
                                             </div>
                                             <div class="col-xl-12">
                                                <div class="input-group" style="justify-content: end;">
                                                   <button class="btn btn-danger" style="float:right;" type="button" onclick="deleteVisaDiv(${visa_row_counter},-1)">Delete</button>
                                                </div>
                                            </div>
                                             <div class="col-xl-12" style="padding: 10px;">
                                                <hr>
                                             </div>
                                            </div>
                                            
                                             `;
                                             
                    var visaCosting = `<div class="row" id="visaCost${visa_row_counter}">
                                            <div class="col-xl-3">
                                                <h4 class="" id="">Visa Cost</h4>
                                            </div>
                                            
                                            <div class="col-xl-9">
                                                <input type="hidden" id="visa_Type_Costing${visa_row_counter}" name="markup_Type_Costing[]" value="visa_Type_Costing" class="form-control">
                                            </div>
                                            
                                            <div class="col-xl-3">
                                                <input readonly type="text" id="visa_type_select${visa_row_counter}" value="${visa_type_name}" name="hotel_name_markup[]" class="form-control">
                                            </div>
                                            
                                            <div class="col-xl-3">
                                                <div class="input-group">
                                                    <input readonly type="text" id="visa_price_select${visa_row_counter}" name="without_markup_price[]" class="form-control">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                           ${value_2}
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-2">
                                                <select name="markup_type[]" id="visa_markup_type${visa_row_counter}" onchange="visa_more_markup_calc(${visa_row_counter})" class="form-control">
                                                    <option value="%">Percentage</option>
                                                    <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-xl-2">
                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                    <input type="text"  class="form-control" id="visa_markup${visa_row_counter}" onkeyup="visa_more_markup_calc(${visa_row_counter})" name="markup[]">
                                                    <span class="input-group-btn input-group-append">
                                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="visa_mrk">%</div></button>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-2">
                                                <div class="input-group">
                                                    <input type="text" id="total_visa_markup${visa_row_counter}" name="markup_price[]" class="form-control">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                           ${value_2}
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                        </div>`;
                                             
                $('#visa_occupy_div').append(visa_div);
                $('#visa_cost').append(visaCosting);
               
                // if(visa_row_counter != '1'){
                    
                // }else{
                //     $('#visa_type_select').val(visa_type_name);
                // }
                
                visa_row_counter++;
         }else{
             alert('Select Supplier First');
         }
    }
        
         function deleteVisaDiv(id,avil_id){
            console.log('vai id '+avil_id);
            var occupyIndex = occupiedVisaArr.indexOf(avil_id);
            occupiedVisaArr.splice(occupyIndex,1);
            $('#visa'+id+'').remove();
            $('#visaCost'+id+'').remove();
         }
        
        function visa_more_markup_calc(id){
           var visaMarktype  = $('#visa_markup_type'+id+'').val();
           var CostPrice = $('#visa_price_select'+id+'').val();
           var MarkupPrice = $('#visa_markup'+id+'').val();
           var SalePrice = 0;
           if(visaMarktype == '%'){
               
               var SalePrice = (CostPrice * MarkupPrice/100) + parseFloat(CostPrice);
               console.log('The Markup Type is % '+SalePrice)
           }else{
               
               var SalePrice = +CostPrice + +MarkupPrice;
               console.log('The Markup Type is NUM '+SalePrice)
               
           }
           
           $('#total_visa_markup'+id+'').val(SalePrice);
           $('.markup_visa_total_amount'+id+'').val(SalePrice);
           
           
        
           
       }
            
    </script>
    
@stop
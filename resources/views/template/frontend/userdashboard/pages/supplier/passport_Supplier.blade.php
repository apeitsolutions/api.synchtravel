@extends('template/frontend/userdashboard/layout/default')
@section('content')

                                                                    <h1>Passport Reader</h1>
                                                        <div class="row">
                                                                <div class="col">
                                                                    
                                                                    <form method="post" id="form1" action="https://alhijaztours.net/Uploadpassport" enctype="multipart/form-data">
                                                                        @csrf
                                                                    <div class="form">
                                                                    <input type="file" id="s_s" placeholder="Upload ScreenShot Of Transection." onchange="loadFileactivity(event)" name="file" class="hidden" style="display: none;" /> 
                                                                    <button type="button" class="btn btn-primary"> <label for="s_s">Upload ScreenShot Of Passport. </label>
                                                                    </button>
                                                                    <button type="submit" id="submit_button" class="btn btn-primary "> Submit
                                                                    </button>
                                                                    
                                                                    </form>

                                                                    <span class="setcategory2 mt-5">
                                                                        <img id="imgoutput" width="190" />
                                                                    </span>
                                                                    
                                                                </div>
                                                                <div class="col passport_form">
                                                                   
                                                                   
                                                                   
                                                                </div>
                                                                  
                                                                   
                                                                                                          
                                                        </div>
                                                                 
                                                               <!-- <button class="color2-bg no-shdow-btn btn btn-success">Submit</button> -->
                                                            </div>
                                                           
                                                            
@stop

                                                            
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>
 
    <script>
    $('.passport_form').empty();

    var loadFileactivity = function(event) {
         var image = document.getElementById('imgoutput');
            image.src = URL.createObjectURL(event.target.files[0]);
    




// predefined file types for validation
        var mime_types = [ 'image/jpeg', 'image/png' ];
    
        var submit_button = document.querySelector('#submit_button');
    
        submit_button.addEventListener('click', function() {
    
   
  
    $("#form1").on('submit',(function(e) {
    e.preventDefault();
    $.ajax({
         url: "https://alhijaztours.net/Uploadpassport",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   beforeSend : function()
   {
    //$("#preview").fadeOut();
    $("#err").fadeOut();
   },
   success: function(data)
      {
    console.log(data);
    
    var data = 'urls={{URL::to('')}}/public/images/passportimg/'+data;

    var xhr = new XMLHttpRequest();

    xhr.addEventListener("readystatechange", function () {
    if (this.readyState === this.DONE) {
        var result = this.responseText;
        if(result){
             result = JSON.parse(result);
        
        // console.log(result['result']);
        
        

        
        var result = result['result'][0];
        var prediction = result['prediction'];
    //   console.log(prediction);
        for (var i = 0; i < prediction.length; i++) {
           
            if(prediction[i]['label'] == 'Passport_Number'){
            var passport_no =`<div class="col-4">
                       <label>Passport No: </label>`
                console.log('Passport_Number:' +prediction[i]['ocr_text']);
                passport_no +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control passport_no" />
                            </div>`
            }
            
           
            if(prediction[i]['label'] == 'Surname'){
              var surname =`<div class="col-4">
                 <label>SurName: </label>`

                console.log('Surname:' +prediction[i]['ocr_text']);
                 surname +=`<input type="text" value="${prediction[i]['ocr_text']}" class="form-control " />
                        </div>`
            }  
            
            
            
            if(prediction[i]['label'] == 'Code'){
             var code =`<div class="col-4">
                   <label>Code: </label>`
            
                console.log('Code:' +prediction[i]['ocr_text']);
                  code +=`<input type="text" value="${prediction[i]['ocr_text']}" class="form-control " />
                        </div>`
            }
            
            

             
             if(prediction[i]['label'] == 'First_Name'){
               firstname=`<div class="col-4">
                   <label>First Name: </label>`
            
                console.log('First_Name:' +prediction[i]['ocr_text']);
                 firstname +=`<input type="text" value="${prediction[i]['ocr_text']}" class="form-control" />
                            </div>`
            }
            
            
            
            if(prediction[i]['label'] == 'Nationality'){
             var nationality=`<div class="col-4">
                <label>Nationality: </label>`
            
                console.log('Nationality:' +prediction[i]['ocr_text']);
                nationality +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                </div>`
            }
            
            
            
             if(prediction[i]['label'] == 'Date_of_Birth'){
             var dateofbirth=`<div class="col-4">
                   <label>Date Of Birth: </label>`
           
                console.log('Date_of_Birth:' +prediction[i]['ocr_text']);
                 dateofbirth +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                        </div>`
            }
            
 
            
            if(prediction[i]['label'] == 'Sex'){
            var sex=`<div class="col-4">
                    <label>sex: </label>`
            
                console.log('Sex:' +prediction[i]['ocr_text']);
                 sex +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                        </div>`
            }

            
            if(prediction[i]['label'] == 'Place_of_birth'){
           var placeofbirth =`<div class="col-4">
                   <label>Place Of Birth: </label>`
              
                console.log('Place_of_birth:' +prediction[i]['ocr_text']);
               placeofbirth +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                        </div>`
            }
            
            

           
            if(prediction[i]['label'] == 'Date_of_Issue'){
           var dateofissue=`<div class="col-4">
                   <label>Date Of Issue: </label>`
            
                console.log('Date_of_Issue:' +prediction[i]['ocr_text']);
                dateofissue +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                        </div>`
            }
            
            

             
             if(prediction[i]['label'] == 'Date_of_expiry'){
              var dateofexpiry=`<div class="col-4">
                   <label>Date Of Expiry: </label>`
            
                console.log('Date_of_expiry:' +prediction[i]['ocr_text']);
                 dateofexpiry +=`<input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
                        </div>`
            }
            
    
            
           
            if(prediction[i]['label'] == 'MRZ'){
            passportbar =`<div class="col-7">
                  <label>MZR: </label>
            <input type="text"  value="${prediction[i]['ocr_text']}" class="form-control" />
               
                        </div>`
            };
            
            
 
            
        }
       var finaldata =`<div class="row mt-3">
       
       ${passport_no}
       ${firstname}
       ${sex}
       ${dateofissue}
       ${dateofexpiry}
       ${passportbar}
       
       
                        </div>`;
                       
       $('.passport_form').append(finaldata);
           
       
       
        }
       
    }
});

        xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/95185dca-8387-417e-9ed0-7a6a8751983f/LabelUrls/?async=false");
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("authorization", "Basic " + btoa("iZHOypXDlvw68cOul-q4axnX9iW778mG:"));
        xhr.send(data);
      },
     error: function(e) 
      {
    $('.passport_form').append('Sorry error occur while reading!');
      }          
    });
 }));
});

};



  </script>
  
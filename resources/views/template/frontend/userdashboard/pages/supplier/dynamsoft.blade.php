@extends('template/frontend/userdashboard/layout/default')
@section('content')

<div class="row">
<div class="col">
    
    <input type="file" id="file" onchange="loadFileactivity(event)" accept="image/*" hidden />
    <button type="button" class="btn btn-primary"> <label for="file">Upload ScreenShot Of Transaction. </label></button>
                                                                    <span class="setcategory2 mt-5">
                                                                        <img id="imgoutput" width="200" />
                                                                    </span>
</div>
<div class="col">
    <div class="row">
        
        
        
    <div class="col-4">
                 <label>Issuing Country: </label>

               
                 <input type="text" class="form-control issueancecountry " />
                        </div>
                        <div class="col-4">
                 <label>Passport No: </label>

               
                 <input type="text" class="form-control passportno " />
                        </div>
                        <div class="col-4">
                 <label>Given Name: </label>

               
                 <input type="text" class="form-control givenname " />
                        </div>
                        <div class="col-4">
                 <label>Nationality: </label>

               
                 <input type="text" class="form-control nationality " />
                        </div> 
                        <div class="col-4">
                 <label>sex: </label>

               
                 <input type="text" class="form-control sex " />
                        </div> 
                        <div class="col-4">
                 <label>Date Of Birth: </label>

               
                 <input type="text" class="form-control dateofbirth " />
                        </div>
                        <div class="col-4">
                 <label>Date Of Issue: </label>

               
                 <input type="text" class="form-control dateofexpiry " />
                        </div>
                        <div class="col-7 ">
                            <label>Passport: </label>
                  <input type="text" class="form-control passportbar " />
                        </div>
                        
                        
                        
                        
                        
                        
                        </div>
</div>
</div>



 
<script src="https://cdn.jsdelivr.net/npm/dynamsoft-label-recognizer@2.2.0/dist/dlr.js"></script>
<script>

var loadFileactivity = function(event) {
    var image = document.getElementById('imgoutput');
    image.src = URL.createObjectURL(event.target.files[0]);


};

Dynamsoft.DLR.LabelRecognizer.initLicense("DLS2eyJoYW5kc2hha2VDb2RlIjoiMTAxNTMxNTU5LVRYbFhaV0pRY205cVgyUnNjZyIsIm9yZ2FuaXphdGlvbklEIjoiMTAxNTMxNTU5IiwiY2hlY2tDb2RlIjoxNzIwOTAxNTY3fQ==");
    var recognizer = null; Dynamsoft.DLR.LabelRecognizer.createInstance({         runtimeSettings: "passportMRZ"     }).then(function (obj) {         console.log("recognizer created");         recognizer = obj;     });
    
    document.getElementById("file").addEventListener("change", function () {
        let file = this.files[0];         
        if (recognizer) {            
            recognizer.recognize(file).then(function (results) {                 
                for (let result of results) {
                    console.log(results);
                    if (result.lineResults.length == 2) {                         
                        let lines = result.lineResults;                         
                        let line1 = lines[0].text;                         
                        let line2 = lines[1].text;  
                        console.log(line1+line2);
                        $('.passportbar').val(line1+line2);
                        var data = extractMRZInfo(line1, line2);                     
                         console.log(data);
                        
                    }                 
                    
                }
                
                
                
                
                
                function extractMRZInfo(line1, line2) {     
                    // https://en.wikipedia.org/wiki/Machine-readable_passport     
                    let result = "";     // Type     
                    let tmp = "";     
                    tmp += line1[0];
                    // console.log("type"+tmp);
                    result += tmp + "\n";         // Issuing country     
                    tmp = " ";     
                    tmp += line1.substring(2, 5); 
                     $('.issueancecountry').val(tmp);
                    result += tmp + "\n";         // Surname     
                    let index = 5;     
                    tmp = "Surname: ";     
                    for (; index < 44; index++) {         
                        if (line1[index] != '<') {             
                            tmp += line1[index];         
                            
                        } else {             
                            break;         
                            
                        }     
                        
                    }     
                    result += tmp + "\n";         // Given names     
                    tmp = " ";     
                    index += 2;     
                    for (; index < 44; index++) {         
                        if (line1[index] != '<') {             
                            tmp += line1[index];         
                            $('.givenname').val(tmp);
                        } else {             
                            tmp += ' ';         
                            
                        }    
                        }     
                        result += tmp + "\n";         // Passport number     
                        
                        tmp = " ";     
                        index = 0;     
                        for (; index < 9; index++) {         
                            if (line2[index] != '<') {             
                                tmp += line2[index];         
                                
                            } else {             
                                break; 
                                }
                                
                                }     
                                console.log(tmp);
                                $('.passportno').val(tmp);
                                result += tmp + "\n";         // Nationality     
                                tmp = " ";     
                                tmp += line2.substring(10, 13);
                                $('.nationality').val(tmp);
                                result += tmp + "\n";         // Date of birth     
                                tmp = line2.substring(13, 19);     
                                tmp = tmp.substring(0, 2) +         '/' +         tmp.substring(2, 4) +         '/' +         tmp.substring(4, 6);     tmp = " " + tmp;
                                $('.dateofbirth').val(tmp);
                                result += tmp + "\n";         // Sex     
                                tmp = " ";     
                                tmp += line2[20];     result += tmp + "\n";
                                 $('.sex').val(tmp);
                                // Expiration date of passport     
                                tmp = line2.substring(21, 27);     tmp = tmp.substring(0, 2) +         '/' +         tmp.substring(2, 4) +         '/' +         tmp.substring(4, 6);     tmp = " " + tmp;
                                $('.dateofexpiry').val(tmp);
                                result += tmp + "\n";         // Personal number     
                                if (line2[28] != '<') {         
                                    tmp = "Personal number: ";         
                                    for (index = 28; index < 42; index++) {             
                                        if (line2[index] != '<') {                 
                                            tmp += line2[index];             
                                            
                                        } else {                
                                            break;             
                                            
                                        }         
                                        
                                    }         
                                    result += tmp + "\n";    
                                    }   
                                   
                                    return result; 
                    
                }
                
                
                
                
                
                
                
                
                
                
            });    
            
            
            
            
            
            
            
            
            
            
            
            
        }     
        
    });
    
    
    
</script>


@stop



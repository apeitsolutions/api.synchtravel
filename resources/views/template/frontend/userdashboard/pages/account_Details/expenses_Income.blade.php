@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<!--Accomodation-->
<div class="modal fade" id="exampleModalCenterAccomodation" aria-hidden="true" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-lg" style="margin-right: 50%;">
        <div class="modal-content" style="width: 200%;">
            <div class="modal-header">
                <h1 class="modal-title" id="myLargeModalLabel">Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    
                    <div class="row" id="acc_Div"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exampleModalCenterPayAccomodation" class="modal fade" aria-hidden="true" tabindex="-1" style="display: none; padding-left: 0px;" role="dialog">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Pay Accomodation Fee</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('/sumbit_Accomodation_Pay')}}" method="post">
                        @csrf
                        <!--<input hidden="" readonly="" value="" name="package_id" class="form-control" type="text" id="package_id" required="">-->
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Tour ID</label>
                          <input readonly="" value="" name="tourId" class="form-control" type="text" id="tourIdA" required="">
                        </div>

                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly="" value="{{ date('Y-m-d') }}" name="paying_date" class="form-control" type="date" id="paying_dateA" required="">
                        </div>

                        <div class="mb-2">
                          <label for="customer_name" class="form-label">Customer Name</label>
                          <input readonly="" name="customer_name" value="" class="form-control" type="text" id="customer_nameA" required="">
                        </div>

                        <div class="mb-2">
                          <label for="package_title" class="form-label">Package Title</label>
                          <input readonly="" name="package_title" value="" class="form-control" type="text" id="package_titleA" required="">
                        </div>
                        
                        <div class="mb-2">
                            <label for="" class="form-label">Choose City</label>
                            <select onchange="append_Price()" class="form-control" id="selected_cityID" name="selected_city"></select>
                        </div>


                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Accomodation Amount</label>
                            <input readonly="" name="total_accomodation_amount" value="" class="form-control" type="text" id="total_accomodation_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_amount" class="form-label">Pay Accomodation Amount</label>
                            <input name="recieved_accomodation_amount" class="form-control" type="text" id="recieved_accomodation_amount" required="" placeholder="Recieved Amount">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly="" name="remaining_accomodation_amount" class="form-control" type="text" id="remaining_accomodation_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <input readonly="" value="" name="amount_accomodation_paid" class="form-control" type="text" id="amount_accomodation_paid" required="">
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>PAY</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="modal fade" id="exampleModalCenterATA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
                <div class="col-12">
                    <h3 class="text-center">Total Amount</h3>
                </div>
                <div id="hidden_ATA"></div>
                <div class="row" style="padding: 10px">
                    <div class="col-xl-6">
                        <label for="" class="form-label"><b>Choose City</b></label>
                        <select onchange="append_PriceATA()" class="form-control" id="selected_cityIDATA" name="selected_city"></select>
                    </div>
                </div>
                <table id="tableATA" class="js-table-sections table table-hover js-table-sections-enabled" style="display:none;">
                    <thead>
                        <tr>
                            <th width="15%">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="js-table-sections-header chintu-click">
                        <tr>
                            <td id="accomodation_total_Amount"></td>
                        </tr>
                    </tbody>
                </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterARA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">Recieved Amount</h3>
              </div>
              <div id="hidden_ARA"></div>
                <div class="row" style="padding: 10px">
                    <div class="col-xl-6">
                        <label for="" class="form-label"><b>Choose City</b></label>
                        <select onchange="append_PriceARA()" class="form-control" id="selected_cityIDARA" name="selected_city"></select>
                    </div>
                </div>
              <table id="tableARA" class="js-table-sections table table-hover js-table-sections-enabled" style="display:none;">
                <thead>
                  <tr>
                    <th width="15%">Recieved Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="accomodation_recieve_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterAO" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">Outsatnding</h3>
              </div>
              <div id="hidden_AO"></div>
                <div class="row" style="padding: 10px">
                    <div class="col-xl-6">
                        <label for="" class="form-label"><b>Choose City</b></label>
                        <select onchange="append_PriceAO()" class="form-control" id="selected_cityIDAO" name="selected_city"></select>
                    </div>
                </div>
              <table id="tableAO" class="js-table-sections table table-hover js-table-sections-enabled" style="display:none;">
                <thead>
                  <tr>
                    <th width="15%">Outsatnding</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="accomodation_outsatnding"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<!--Flight-->
<div class="modal fade" id="exampleModalCenterFlight" aria-hidden="true" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-lg" style="margin-right: 50%;">
        <div class="modal-content" style="width: 200%;">
            <div class="modal-header">
                <h1 class="modal-title" id="myLargeModalLabel">Flight Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    
                    <div class="row" id="FD_Div"></div>
                    
                    <div class="row" id="FI_Div"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exampleModalCenterPayFlight" class="modal fade" aria-hidden="true" tabindex="-1" style="display: none; padding-left: 0px;" role="dialog">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Pay Flight Fee</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('/sumbit_Flight_Pay')}}" method="post">
                        @csrf
                        <!--<input hidden="" readonly="" value="" name="package_id" class="form-control" type="text" id="package_id" required="">-->
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Tour ID</label>
                          <input readonly="" value="" name="tourId" class="form-control" type="text" id="tourIdF" required="">
                        </div>

                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly="" value="{{ date('Y-m-d') }}" name="paying_date" class="form-control" type="date" id="paying_dateF" required="">
                        </div>

                        <div class="mb-2">
                          <label for="customer_name" class="form-label">Customer Name</label>
                          <input readonly="" name="customer_name" value="" class="form-control" type="text" id="customer_nameF" required="">
                        </div>

                        <div class="mb-2">
                          <label for="package_title" class="form-label">Package Title</label>
                          <input readonly="" name="package_title" value="" class="form-control" type="text" id="package_titleF" required="">
                        </div>


                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Transportation Amount</label>
                            <input readonly="" name="total_flight_amount" value="" class="form-control" type="text" id="total_flight_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_amount" class="form-label">Pay Transportation Amount</label>
                            <input name="recieved_flight_amount" class="form-control" type="text" id="recieved_flight_amount" required="" placeholder="Recieved Amount">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly="" name="remaining_flight_amount" class="form-control" type="text" id="remaining_flight_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <input readonly="" value="" name="amount_flight_paid" class="form-control" type="text" id="amount_flight_paid" required="">
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>PAY</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="modal fade" id="exampleModalCenterFTA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">FLIGHT DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Total Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="flight_total_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterFRA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">FLIGHT DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Recieved Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="flight_recieve_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterFO" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">FLIGHT DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Outstandings</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="flight_outsatnding"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<!--Transportation-->
<div class="modal fade" id="exampleModalCenterTransportation" aria-hidden="true" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-lg" style="margin-right: 50%;">
        <div class="modal-content" style="width: 200%;">
            <div class="modal-header">
                <h1 class="modal-title" id="myLargeModalLabel">Transportation Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    
                    <div class="row" id="TO_Div"></div>
                    
                    <div class="row" id="TR_Div"></div>
                    
                    <div class="row" id="TA_Div"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exampleModalCenterPayTransportation" class="modal fade" aria-hidden="true" tabindex="-1" style="display: none; padding-left: 0px;" role="dialog">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Pay Transportation Fee</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('/sumbit_Transportation_Pay')}}" method="post">
                        @csrf
                        <!--<input hidden="" readonly="" value="" name="package_id" class="form-control" type="text" id="package_id" required="">-->
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Tour ID</label>
                          <input readonly="" value="" name="tourId" class="form-control" type="text" id="tourIdT" required="">
                        </div>

                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly="" value="{{ date('Y-m-d') }}" name="paying_date" class="form-control" type="date" id="paying_dateT" required="">
                        </div>

                        <div class="mb-2">
                          <label for="customer_name" class="form-label">Customer Name</label>
                          <input readonly="" name="customer_name" value="" class="form-control" type="text" id="customer_nameT" required="">
                        </div>

                        <div class="mb-2">
                          <label for="package_title" class="form-label">Package Title</label>
                          <input readonly="" name="package_title" value="" class="form-control" type="text" id="package_titleT" required="">
                        </div>


                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Transportation Amount</label>
                            <input readonly="" name="total_transportation_amount" value="" class="form-control" type="text" id="total_transportation_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_amount" class="form-label">Pay Transportation Amount</label>
                            <input name="recieved_transportation_amount" class="form-control" type="text" id="recieved_transportation_amount" required="" placeholder="Recieved Amount">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly="" name="remaining_transportation_amount" class="form-control" type="text" id="remaining_transportation_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <input readonly="" value="" name="amount_transportation_paid" class="form-control" type="text" id="amount_transportation_paid" required="">
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>PAY</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="modal fade" id="exampleModalCenterTTA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">TRANSPORTATION DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Total Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="transportation_total_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterTRA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">TRANSPORTATION DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Recieved Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="transportation_recieve_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterTO" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">TRANSPORTATION DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Outstandings</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="transportation_outsatnding"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<!--Visa-->
<div class="modal fade" id="exampleModalCenterVisa" aria-hidden="true" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-lg" style="margin-right: 50%;">
        <div class="modal-content" style="width: 200%;">
            <div class="modal-header">
                <h1 class="modal-title" id="myLargeModalLabel">Visa Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    
                    <div class="row" id="V_Div"></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="exampleModalCenterPayVisa" class="modal fade" aria-hidden="true" tabindex="-1" style="display: none; padding-left: 0px;" role="dialog">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Pay Visa Fee</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('/sumbit_Visa_Pay')}}" method="post">
                        @csrf
                        <!--<input hidden="" readonly="" value="" name="package_id" class="form-control" type="text" id="package_id" required="">-->
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Tour ID</label>
                          <input readonly="" value="" name="tourId" class="form-control" type="text" id="tourIdV" required="">
                        </div>

                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly="" value="{{ date('Y-m-d') }}" name="paying_date" class="form-control" type="date" id="paying_dateV" required="">
                        </div>

                        <div class="mb-2">
                          <label for="customer_name" class="form-label">Customer Name</label>
                          <input readonly="" name="customer_name" value="" class="form-control" type="text" id="customer_nameV" required="">
                        </div>

                        <div class="mb-2">
                          <label for="package_title" class="form-label">Package Title</label>
                          <input readonly="" name="package_title" value="" class="form-control" type="text" id="package_titleV" required="">
                        </div>


                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Visa Amount</label>
                            <input readonly="" name="total_visa_amount" value="" class="form-control" type="text" id="total_visa_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_amount" class="form-label">Pay Visa Amount</label>
                            <input name="recieved_visa_amount" class="form-control" type="text" id="recieved_visa_amount" required="" placeholder="Recieved Amount">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly="" name="remaining_visa_amount" class="form-control" type="text" id="remaining_visa_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <input readonly="" value="" name="amount_visa_paid" class="form-control" type="text" id="amount_visa_paid" required="">
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>PAY</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="modal fade" id="exampleModalCenterVTA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">VISA DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Total Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="visa_total_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterVRA" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">VISA DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Recieved Amount</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="visa_recieve_Amount"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="modal fade" id="exampleModalCenterVO" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
              <div class="col-12">
                <h3 class="text-center">VISA DETAILS</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Outstandings</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="visa_outsatnding"></td>
                  </tr>
                </tbody>
              </table>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="header-title">Expenses</h4>
        <div class="tab-content">
            <div class="tab-pane show active" id="justified-tabs-preview">
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                            <span class="d-none d-md-block">Accomodation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#home2" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Flights Details</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#home3" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Transportation</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#home4" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Visa Details</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    
                    <div class="tab-pane  show active" id="home1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <label><h4><b>Sort Data by Client</b></h4></label>
                                        <select class="form-control" id="client_Name_acc" style="width: 220px;">
                                            <option value="0">Choose Client Name</option>
                                            @foreach ($customer_subcriptions as $value)
                                                <option value="{{ $value->id }}">{{ $value->name.' '.$value->lname }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="row mb-2">
                                            <div class="col-sm-5">
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="text-sm-end">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                        <div class="row">
                                            <table class="table table-centered w-100 dt-responsive nowrap" id="example2">
                                                <thead class="table-light">
                                                    <tr role="row">
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">SR No</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour ID</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Client Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Hotel Name</th>
                                                        <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Room Type</th>-->
                                                        <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Package Type</th>-->
                                                        <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">No of Pax</th>-->
                                                        <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;">Quantity</th>-->
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">No of Nights</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Total</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_all_data_acc" style="text-align: center;">
                                                    <?php $i = 1; ?>
                                                    @foreach($data1 as $value)
                                                        <?php   
                                                                $accomodation_details = json_decode($value->accomodation_details);
                                                                $accomodation_details_more = json_decode($value->accomodation_details_more);
                                                                // print_r($accomodation_details);
                                                        ?>
                                                        <tr role="row" class="odd">
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $value->id }}</td>
                                                            <td>{{ $value->name.' '.$value->lname }}</td>
                                                            <td>{{ $value->title }}</td>
                                                            <td>
                                                                @if(isset($accomodation_details))
                                                                    @foreach($accomodation_details as $value1)
                                                                        <div>{{ $value1->acc_hotel_name ?? '' }}</div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($accomodation_details))
                                                                    @foreach($accomodation_details as $value1)
                                                                        <div>{{ $value1->acc_no_of_nightst ?? '' }}</div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($accomodation_details))
                                                                    <div><b>Total Price :</b></div>
                                                                    @foreach($accomodation_details as $value1)
                                                                        <div><?php echo $currency; ?>{{ $value1->acc_total_amount ?? '' }}</div>
                                                                    @endforeach
                                                                @endif
                                                                @if(isset($accomodation_details_more))
                                                                    <div><b>More Total Price :</b></div>
                                                                    @foreach($accomodation_details_more as $value2)
                                                                        <div><?php echo $currency; ?>{{ $value2->more_acc_total_amount ?? '' }}</div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                <div class="dropdown card-widgets">
                                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3" style="margin-right: 70px;"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAccomodation" data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btn-Accomodation" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Accomodation Details
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayAccomodation" data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btn-PayAccomodation" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Pay Accomodation Fee
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterATA"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnATA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Accomodation Total Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterARA"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnARA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Accomodation Recieved Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAO"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnAO" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Accomodation Outstandings</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++ ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="home2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <label><h4><b>Sort Data by Client</b></h4></label>
                                        <select class="form-control" id="client_Name_flight" style="width: 220px;">
                                            <option value="0">Choose Client Name</option>
                                            @foreach ($customer_subcriptions as $value)
                                                <option value="{{ $value->id }}">{{ $value->name.' '.$value->lname }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="row mb-2">
                                        <div class="col-sm-5">
                                        </div>
                                    </div>
                                        <div class="col-sm-7">
                                        <div class="text-sm-end">
                                        </div>
                                    </div>
                                        <div class="table-responsive">
                                        <div class="row">
                                            <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                                <thead class="table-light">
                                                    <tr role="row">
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">SR NO.</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour ID</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Clienr Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Flight Type</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">Departure</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">Arrival</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;"text-align: center;>Price per person</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_all_data_flight" style="text-align: center;">
                                                    <?php $i = 1; ?>
                                                    @foreach($data1 as $value1)
                                                        <?php 
                                                            $flight_Details       = json_decode($value1->flights_details);
                                                            $flights_details_more = json_decode($value1->flights_details_more);
                                                        ?>
                                                        
                                                        <tr role="row" class="odd">
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $value1->id }}</td>
                                                            <td>{{ $value1->name.' '.$value1->lname }}</td>
                                                            <td>{{ $value1->title }}</td>
                                                            <td>    
                                                                @if(isset($flight_Details))
                                                                    <div>{{ $flight_Details->flight_type }}</div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($flight_Details))
                                                                    <div><b>Flight Details :</b></div>
                                                                    <div>{{ $flight_Details->departure_airport_code }}</div>
                                                                @endif
                                                                @if(isset($flights_details_more))
                                                                    <div><b>More Flight Details :</b></div>
                                                                    @foreach($flights_details_more as $value)
                                                                       <div>{{ $value->more_departure_airport_code }}</div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($flight_Details))
                                                                    <div><b>Flight Details :</b></div>
                                                                    {{ $flight_Details->arrival_airport_code }}
                                                                @endif
                                                                @if(isset($flights_details_more))
                                                                    <div><b>More Flight Details :</b></div>
                                                                    @foreach($flights_details_more as $value)
                                                                       <div>{{ $value->more_arrival_airport_code }}</div>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($flight_Details))
                                                                    <?php echo $currency; ?> {{ $flight_Details->flights_per_person_price }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="dropdown card-widgets">
                                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFlight" data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btn-Flight" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Flight Details
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayFlight" data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btn-PayFlight" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Pay Flight Fee
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFTA"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnFTA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Total Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFRA"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnFRA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Recieved Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFO"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnFO" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Outstandings</a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="home3">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <label><h4><b>Sort Data by Client</b></h4></label>
                                        <select class="form-control" id="client_Name_transportation" style="width: 220px;">
                                            <option value="0">Choose Client Name</option>
                                            @foreach ($customer_subcriptions as $value)
                                                <option value="{{ $value->id }}">{{ $value->name.' '.$value->lname }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="row mb-2">
                                        <div class="col-sm-5">
                                        </div>
                                    </div>
                                        <div class="col-sm-7">
                                        <div class="text-sm-end">
                                        </div>
                                    </div>
                                        <div class="table-responsive">
                                        <div class="row">
                                            <table class="table table-centered w-100 dt-responsive nowrap" id="example3">
                                                <thead class="table-light">
                                                    <tr role="row">
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">SR NO.</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour ID</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Clienr Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Picup Location</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">Drop of Location</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">No of Vehicles</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;text-align: center;">Price per Vehicle</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Price per Person</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_all_data_transportation" style="text-align: center;">
                                                    <?php $i =1; ?>
                                                    @foreach($data1 as $value1)
                                                        <?php 
                                                                $transportation_details = json_decode($value1->transportation_details);
                                                                $transportation_details_more = json_decode($value1->transportation_details_more);
                                                        ?>
                                                           
                                                        <tr role="row" class="odd">
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $value1->id }}</td>
                                                            <td>{{ $value1->name.' '.$value1->lname }}</td>
                                                            <td>{{ $value1->title }}</td>
                                                            <td>
                                                                @if(isset($transportation_details))
                                                                    {{ $transportation_details->transportation_pick_up_location }}
                                                                @endif
                                                                @if(isset($transportation_details_more))
                                                                    @foreach($transportation_details_more as $value)    
                                                                        {{ $value->more_transportation_pick_up_location }}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                @if(isset($transportation_details))
                                                                    {{ $transportation_details->transportation_drop_off_location }}
                                                                @endif
                                                                @if(isset($transportation_details_more))
                                                                    @foreach($transportation_details_more as $value)    
                                                                        {{ $value->more_transportation_drop_off_location }}
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                @if(isset($transportation_details))
                                                                    {{ $transportation_details->transportation_no_of_vehicle }}
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                @if(isset($transportation_details))
                                                                    <?php echo $currency; ?> {{ $transportation_details->transportation_price_per_vehicle ?? '' }}
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                @if(isset($transportation_details))
                                                                    <?php echo $currency; ?> {{ $transportation_details->transportation_price_per_person }}
                                                                @endif
                                                            </td>
                                                            
                                                            <td>
                                                                <div class="dropdown card-widgets">
                                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTransportation" data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btn-Transportation" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Transportation Details
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayTransportation" data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btn-PayTransportation" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Pay Transportation Fee
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTTA"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnTTA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Total Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTRA"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnTRA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Recieved Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTO"  data-id="{{ $value1->id }}" class="dropdown-item fetchorderdetails detail-btnTO" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Outstandings</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="home4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        <label><h4><b>Sort Data by Client</b></h4></label>
                                        <select class="form-control" id="client_Name_visa" style="width: 220px;">
                                            <option value="0">Choose Client Name</option>
                                            @foreach ($customer_subcriptions as $value)
                                                <option value="{{ $value->id }}">{{ $value->name.' '.$value->lname }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <div class="row mb-2">
                                        <div class="col-sm-5">
                                        </div>
                                    </div>
                                        <div class="col-sm-7">
                                        <div class="text-sm-end">
                                        </div>
                                    </div>
                                        <div class="table-responsive">
                                        <div class="row">
                                            <table class="table table-centered w-100 dt-responsive nowrap" id="example4">
                                                <thead class="table-light">
                                                    <tr role="row">
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">SR NO.</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour ID</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Client Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Tour Name</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align: center;">Visa Type</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align: center;">Visa Fee</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;text-align: center;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_all_data_visa" style="text-align: center;">
                                                    <?php $i =1; ?>
                                                    @foreach($data1 as $value)
                                                        <tr role="row" class="odd">
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $value->id }}</td>
                                                            <td>{{ $value->name.' '.$value->lname }}</td>
                                                            <td>{{ $value->title }}</td>
                                                            <td>
                                                                @if(isset($value->visa_type))
                                                                    {{ $value->visa_type }}
                                                                @endif
                                                            </td>
                                                                
                                                            <td>
                                                                @if(isset($value->visa_fee))
                                                                    <?php echo $currency; ?> {{ $value->visa_fee }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="dropdown card-widgets">
                                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3" style="margin-right: 105px;"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVisa" data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btn-Visa" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Visa Details
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayVisa" data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btn-PayVisa" data-uniqid="b5c7a7ced6fda207e232">
                                                                            <i class="mdi mdi-eye me-1"></i>
                                                                            Pay Visa Fee
                                                                        </a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVTA"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnVTA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Total Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVRA"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnVRA" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Recieved Amount</a>
                                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVO"  data-id="{{ $value->id }}" class="dropdown-item fetchorderdetails detail-btnVO" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Outstandings</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div> 
            </div> <!-- end preview-->
        </div> <!-- end tab-content-->
    </div> <!-- end card-body -->
</div> <!-- end card-->

@endsection

@section('scripts')

<script type="text/javascript">
    
    // Accomodation
    $('.detail-btn-Accomodation').click(function() {
        
        $("#acc_Div").empty();
        var acc_Div = ` <div class="col-xl-12" style="text-align:center;">
                            <div class="mb-3">
                              <h1 style="text-decoration: underline;font-size: 50px;">Accomodation Details</h1>
                            </div>
                        </div>`;
        $("#acc_Div").append(acc_Div);
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var accomodation_details = JSON.parse(data['accomodation_details']);
                
                var accomodation_details_more = JSON.parse(data['accomodation_details_more']);
                
                No = 1;
                No2 = 2;
                $.each(accomodation_details, function(key, value) {
                    
                    var hotel_city_name         = value.hotel_city_name;
                    var acc_hotel_name          = value.acc_hotel_name;
                    var acc_check_in            = value.acc_check_in;
                    var acc_check_out           = value.acc_check_out;
                    var acc_no_of_nightst       = value.acc_no_of_nightst;
                    var acc_pax                 = value.acc_pax;
                    var acc_price               = value.acc_price;
                    var acc_qty                 = value.acc_qty;
                    var acc_total_amount        = value.acc_total_amount;
                    var acc_type                = value.acc_type;
                    var total_Price1            = parseFloat(acc_total_amount) * parseFloat(acc_pax);
                    var total_Price             = total_Price1.toFixed(2);
                    
                    var DivCity = `<br><br><div class="row" id='acc_D${No}'></div>`;
                    $("#acc_Div").append(DivCity);
                    var city_Name = `<div class="col-xl-12">
                                        <div class="mb-3">
                                            <h3 style="font-size: 40px;">${hotel_city_name} Details :</h3>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Hotel Name : </b></label>
                                            <span>${acc_hotel_name}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Checked In : </b></label>
                                            <span>${acc_check_in}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Checked Out : </b></label>
                                            <span>${acc_check_out}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>No Of Nights : </b></label>
                                            <span>${acc_no_of_nightst}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Room Type : </b></label>
                                            <span>${acc_type}</span>
                                        </div>
                                    </div>
                        
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>No Of Pax : </b></label>
                                            <span>${acc_pax}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Room Quantity : </b></label>
                                            <span>${acc_qty}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Price per night: </b></label>
                                            <span><?php echo $currency; ?>${acc_price}</span>
                                        </div>
                                    </div>
                        
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Price per person per night : </b></label>
                                            <span><?php echo $currency; ?>${acc_total_amount}</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Total Price : </b></label>
                                            <span><?php echo $currency; ?>${total_Price}</span>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row" id='more_acc_D${No}'></div>
                                    <br><br>
                                    <div class="row" id='more_acc_D${No2}'></div>`;
                    $('#acc_D'+No+'').append(city_Name);
                    No = No + 2;
                    No2 = No2 + 2;
                });
                
                No1 = 1;
                $.each(accomodation_details_more, function(key, value) {
                    var more_hotel_city         = value.more_hotel_city;
                    var more_acc_type           = value.more_acc_type;
                    var more_acc_pax            = value.more_acc_pax;
                    var more_acc_price          = value.more_acc_price;
                    var more_acc_qty            = value.more_acc_qty;
                    var more_acc_total_amount   = value.more_acc_total_amount;
                    var more_total_Price1       = parseFloat(more_acc_total_amount) * parseFloat(more_acc_pax);
                    var more_total_Price        = more_total_Price1.toFixed(2);
                    
                    var more_city_Name  =  `<div class="col-xl-12" style="margin-left: 40px;>
                                                <div class="mb-3">
                                                  <h3 style="font-size: 30px;">${more_acc_type}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>No Of Pax : </b></label>
                                                    <span>${more_acc_pax}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Room Type : </b></label>
                                                    <span>${more_acc_type}</span>
                                                </div>
                                            </div>
                                    
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Quantity : </b></label>
                                                    <span>${more_acc_qty}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Price per night : </b></label>
                                                    <span><?php echo $currency; ?>${more_acc_price}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Price per person per night : </b></label>
                                                    <span><?php echo $currency; ?>${more_acc_total_amount}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Total Price : </b></label>
                                                    <span><?php echo $currency; ?>${more_total_Price}</span>
                                                </div>
                                            </div>`;
                    $('#more_acc_D'+No1+'').append(more_city_Name);
                    No1 = No1 + 1;
                });
            }
        })
    });
    
    $('.detail-btn-PayAccomodation').click(function(){
        $("#selected_cityID").empty();
        $("#total_accomodation_amount").val('0');
        $("#recieved_accomodation_amount").val('0');
        $("#remaining_accomodation_amount").val('0');
        $("#amount_accomodation_paid").val('0');
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                       = data['data'];
                var customer_Data               = data['customer_Data'];
                var paid_accomodation           = data['paid_accomodation'];
                var tourId                      = data1['id'];
                var package_title               = data1['title'];
                var name                        = customer_Data['name'];
                var lname                       = customer_Data['lname'];
                var customer_name               = name +" "+ lname ;
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                $("#tourIdA").val(tourId);
                $("#customer_nameA").val(customer_name);
                $("#package_titleA").val(package_title);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityID").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityID").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        })
    });
        
    function append_Price(id){
        $("total_accomodation_amount").empty();
        $("#recieved_accomodation_amount").empty();
        $("#remaining_accomodation_amount").empty();
        $("#amount_accomodation_paid").empty();
        
        var selected_city = $('#selected_cityID').find('option:selected').attr('attr');
        var t_Id = $('#tourIdA').val();
        $.ajax({
            url:"{{URL::to('/acc_Pay')}}" + '/' + selected_city + '/' + t_Id,
            type: 'GET',
            data: {
                "selected_city": selected_city,
                "t_Id": t_Id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                var accomodation_details_more   = JSON.parse(data1['accomodation_details_more']);
                
                var cityNO = 1;
                var acc_total_amount = 0;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name             = value.hotel_city_name;
                    var acc_pax                     = value.acc_pax;
                    var acc_total_amount_per_person = value.acc_total_amount;
                    
                    if(hotel_city_name == selected_city){
                        console.log("acc_total_amount_per_person : " +acc_total_amount_per_person);
                        var acc_total_amount_all = parseFloat(acc_total_amount_per_person) * parseFloat(acc_pax);
                        acc_total_amount         = parseFloat(acc_total_amount) + parseFloat(acc_total_amount_all);
                    }
                });
                
                var more_acc_total_amount = acc_total_amount;
                $.each(accomodation_details_more, function(key, value) {
                    var more_hotel_city                  = value.more_hotel_city;
                    var more_acc_pax                     = value.more_acc_pax
                    var more_acc_total_amount_per_person = value.more_acc_total_amount;
                    
                    if(more_hotel_city == selected_city){
                        console.log("more_acc_total_amount_per_person : " +more_acc_total_amount_per_person);
                        var more_acc_total_amount_all = parseFloat(more_acc_total_amount_per_person) * parseFloat(more_acc_pax);
                        more_acc_total_amount        = parseFloat(more_acc_total_amount) + parseFloat(more_acc_total_amount_all);
                    }
                });
                console.log("more_acc_total_amount : " +more_acc_total_amount);
                console.log("no_of_pax_days : " +no_of_pax_days);
                
                var total_accomodation_amount = parseFloat(no_of_pax_days) * parseFloat(more_acc_total_amount);
                total_accomodation_amount  = total_accomodation_amount.toFixed(2);
                paid_accomodation = paid_accomodation.toFixed(2);
                $("#total_accomodation_amount").val(total_accomodation_amount);
                $('#amount_accomodation_paid').val(paid_accomodation);
                
                if(paid_accomodation == null || paid_accomodation == 0){
                    $('#remaining_accomodation_amount').val(total_accomodation_amount);
                }
                
                if(paid_accomodation !== null){
                    var remaining_accomodation_amount = parseFloat(total_accomodation_amount) - parseFloat(paid_accomodation);
                    remaining_accomodation_amount = remaining_accomodation_amount.toFixed(2);
                    $('#remaining_accomodation_amount').val(remaining_accomodation_amount);
                }
            }
        })
    }
    
    $('#recieved_accomodation_amount').on('change',function(){
        var recieved_accomodation_amount        = $(this).val();
        var remaining_accomodation_amount       = $('#remaining_accomodation_amount').val();
        var remaining_accomodation_amount_final = parseFloat(remaining_accomodation_amount) - parseFloat(recieved_accomodation_amount);
        remaining_accomodation_amount_final     = remaining_accomodation_amount_final.toFixed(2);
        $('#remaining_accomodation_amount').val(remaining_accomodation_amount_final);
        $('#amount_accomodation_paid').val(recieved_accomodation_amount);
    });
    
    $('.detail-btnATA').click(function() {
        
        $("#accomodation_total_Amount").empty();
        $("#selected_cityIDATA").empty();
        $('#tableATA').css('display','none');
        $("#hidden_ATA").empty();
        
        const id = $(this).attr('data-id');
        
        var hidden_ATA = `<input type="hidden" value="${id}" id="id_ATA">`;
        $("#hidden_ATA").append(hidden_ATA);
        
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDATA").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDATA").append(city_append);
                   
                    cityNO = cityNO + 1;
                });
                
            }
        });
    });
    
    function append_PriceATA(id){
        
        $('#tableATA').css('display','');
        $("#accomodation_total_Amount").empty();
        
        var selected_city = $('#selected_cityIDATA').find('option:selected').attr('attr');
        var t_Id = $('#id_ATA').val();
        
        $.ajax({
            url:"{{URL::to('/acc_Pay')}}" + '/' + selected_city + '/' + t_Id,
            type: 'GET',
            data: {
                "selected_city": selected_city,
                "t_Id": t_Id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                var accomodation_details_more   = JSON.parse(data1['accomodation_details_more']);
                
                var cityNO = 1;
                var acc_total_amount = 0;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name             = value.hotel_city_name;
                    var acc_pax                     = value.acc_pax;
                    var acc_total_amount_per_person = value.acc_total_amount;
                    if(hotel_city_name == selected_city){
                        var acc_total_amount_all = parseFloat(acc_total_amount_per_person) * parseFloat(acc_pax);
                        acc_total_amount         = parseFloat(acc_total_amount) + parseFloat(acc_total_amount_all);
                    }
                });
                
                var more_acc_total_amount = acc_total_amount;
                $.each(accomodation_details_more, function(key, value) {
                    var more_hotel_city                  = value.more_hotel_city;
                    var more_acc_pax                     = value.more_acc_pax
                    var more_acc_total_amount_per_person = value.more_acc_total_amount;
                    
                    if(more_hotel_city == selected_city){
                        var more_acc_total_amount_all = parseFloat(more_acc_total_amount_per_person) * parseFloat(more_acc_pax);
                        more_acc_total_amount        = parseFloat(more_acc_total_amount) + parseFloat(more_acc_total_amount_all);
                    }
                });
                
                var total_accomodation_amount = parseFloat(no_of_pax_days) * parseFloat(more_acc_total_amount);
                total_accomodation_amount     = total_accomodation_amount.toFixed(2)
                $("#accomodation_total_Amount").html(total_accomodation_amount);
                
            }
        });
    }
      
    $('.detail-btnARA').click(function() {
        
        $("#accomodation_recieve_Amount").empty();
        $("#selected_cityIDARA").empty();
        
        $('#tableARA').css('display','none');
        $("#hidden_ARA").empty();
        
        const id = $(this).attr('data-id');
        var hidden_ARA = `<input type="hidden" value="${id}" id="id_ARA">`;
        $("#hidden_ARA").append(hidden_ARA);
        
        
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDARA").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDARA").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        });
    });
    
    function append_PriceARA(id){
        
        $('#tableARA').css('display','');
        $("#accomodation_recieve_Amount").empty();
        
        var selected_city = $('#selected_cityIDARA').find('option:selected').attr('attr');
        var t_Id = $('#id_ARA').val();
        
        $.ajax({
            url:"{{URL::to('/acc_Pay')}}" + '/' + selected_city + '/' + t_Id,
            type: 'GET',
            data: {
                "selected_city": selected_city,
                "t_Id": t_Id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                paid_accomodation               = paid_accomodation.toFixed(2);
                $("#accomodation_recieve_Amount").html(paid_accomodation);
            }
        });
    }
      
    $('.detail-btnAO').click(function() {
        $("#accomodation_recieve_Amount").empty();
        $("#selected_cityIDAO").empty();
        
        $('#tableAO').css('display','none');
        $("#hidden_AO").empty();
        
        const id = $(this).attr('data-id');
        var hidden_AO = `<input type="hidden" value="${id}" id="id_AO">`;
        $("#hidden_AO").append(hidden_AO);
        
        
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDAO").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDAO").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        });
    });
    
    function append_PriceAO(id){
        
        $('#tableAO').css('display','');
        $("#accomodation_outsatnding").empty();
        
        var selected_city = $('#selected_cityIDAO').find('option:selected').attr('attr');
        var t_Id = $('#id_AO').val();
        
        $.ajax({
            url:"{{URL::to('/acc_Pay')}}" + '/' + selected_city + '/' + t_Id,
            type: 'GET',
            data: {
                "selected_city": selected_city,
                "t_Id": t_Id
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                var accomodation_details_more   = JSON.parse(data1['accomodation_details_more']);
                
                var cityNO = 1;
                var acc_total_amount = 0;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name             = value.hotel_city_name;
                    var acc_pax                     = value.acc_pax;
                    var acc_total_amount_per_person = value.acc_total_amount;
                    if(hotel_city_name == selected_city){
                        var acc_total_amount_all = parseFloat(acc_total_amount_per_person) * parseFloat(acc_pax);
                        acc_total_amount         = parseFloat(acc_total_amount) + parseFloat(acc_total_amount_all);
                    }
                });
                
                var more_acc_total_amount = acc_total_amount;
                $.each(accomodation_details_more, function(key, value) {
                    var more_hotel_city                  = value.more_hotel_city;
                    var more_acc_pax                     = value.more_acc_pax
                    var more_acc_total_amount_per_person = value.more_acc_total_amount;
                    
                    if(more_hotel_city == selected_city){
                        var more_acc_total_amount_all = parseFloat(more_acc_total_amount_per_person) * parseFloat(more_acc_pax);
                        more_acc_total_amount        = parseFloat(more_acc_total_amount) + parseFloat(more_acc_total_amount_all);
                    }
                });
                
                var total_accomodation_amount = parseFloat(no_of_pax_days) * parseFloat(more_acc_total_amount);
                var accomodation_outsatnding  = parseFloat(total_accomodation_amount) - parseFloat(paid_accomodation);
                accomodation_outsatnding      = accomodation_outsatnding.toFixed(2)
                $("#accomodation_outsatnding").html(accomodation_outsatnding);
                
            }
        });
    }
    
    // Flight
    $('.detail-btn-Flight').click(function() {
        
        $("#FD_Div").empty();
        $("#FI_Div").empty();
        const id = $(this).attr('data-id');
        console.log(id);
        $.ajax({
            url: 'view_Details_Accomodation/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var flights_details = JSON.parse(data['flights_details']);
                var flights_details_more = JSON.parse(data['flights_details_more']);
                // console.log(flights_details);
                
                var departure_airport_code              = flights_details['departure_airport_code'];
                var arrival_airport_code                = flights_details['arrival_airport_code'];
                var other_Airline_Name2                 = flights_details['other_Airline_Name2'];
                var departure_Flight_Type               = flights_details['departure_Flight_Type'];
                var departure_flight_number             = flights_details['departure_flight_number'];
                var departure_time                      = flights_details['departure_time'];
                var arrival_time                        = flights_details['arrival_time'];
                var total_Time                          = flights_details['total_Time'];
                
                var return_departure_airport_code       = flights_details['return_departure_airport_code'];
                var return_arrival_airport_code         = flights_details['return_arrival_airport_code'];
                var return_other_Airline_Name2          = flights_details['return_other_Airline_Name2'];
                var return_departure_Flight_Type        = flights_details['return_departure_Flight_Type'];
                var return_departure_flight_number      = flights_details['return_departure_flight_number'];
                var return_departure_time               = flights_details['return_departure_time'];
                var return_arrival_time                 = flights_details['return_arrival_time'];
                var return_total_Time                   = flights_details['return_total_Time'];
                
                var flight_type                         = flights_details['flight_type'];
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flights_image                       = flights_details['flights_image'];
                var terms_and_conditions                = flights_details['terms_and_conditions'];
                var connected_flights_duration_details  = flights_details['connected_flights_duration_details'];
                
                if(flight_type == 'Direct'){
                    
                    var FD_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Flight Details</h1>
                                        </div>
                                    </div>`;
                    $("#FD_Div").append(FD_Div);
        
                    var Divflight = `<br><br><div class="row" id="flight_DR"></div>`;
                    $("#FD_Div").append(Divflight);
                    var flight_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Departure Airport : </b></label>
                                                <span>${departure_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Arrival Airport : </b></label>
                                                <span>${arrival_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Airline Name : </b></label>
                                                <span>${other_Airline_Name2}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Departure Time : </b></label>
                                                <span>${departure_time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                <span>${arrival_time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Time : </b></label>
                                                <span>${total_Time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight No : </b></label>
                                                <span>${departure_flight_number}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Price : </b></label>
                                                <span><?php echo $currency; ?>${flights_per_person_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Return Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Departure Airport : </b></label>
                                                <span>${return_departure_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Arrival Airport : </b></label>
                                                <span>${return_arrival_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Airline Name : </b></label>
                                                <span>${return_other_Airline_Name2}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Departure Time : </b></label>
                                                <span>${return_departure_time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                <span>${return_arrival_time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Time : </b></label>
                                                <span>${return_total_Time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight No : </b></label>
                                                <span>${return_departure_flight_number}</span>
                                            </div>
                                        </div>`;
                                    
                    $("#flight_DR").append(flight_Name);
                }
                
                if(flight_type == 'Indirect'){
                    No = 1;
                    
                    var FI_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">More Flight Details</h1>
                                        </div>
                                    </div>`;
                    $("#FI_Div").append(FI_Div);
                    
                    $.each(flights_details_more, function(key, value) {
                        var more_departure_airport_code     = value.more_departure_airport_code;
                        var more_arrival_airport_code       = value.more_arrival_airport_code;
                        var more_other_Airline_Name2        = value.more_other_Airline_Name2;
                        var more_departure_Flight_Type      = value.more_departure_Flight_Type;
                        var more_departure_flight_number    = value.more_departure_flight_number;
                        var more_departure_time             = value.more_departure_time;
                        var more_arrival_time               = value.more_arrival_time;
                        var more_total_Time                 = value.more_total_Time;
                        
                        var return_more_departure_airport_code     = value.return_more_departure_airport_code;
                        var return_more_arrival_airport_code       = value.return_more_arrival_airport_code;
                        var return_more_other_Airline_Name2        = value.return_more_other_Airline_Name2;
                        var return_more_departure_Flight_Type      = value.return_more_departure_Flight_Type;
                        var return_more_departure_flight_number    = value.return_more_departure_flight_number;
                        var return_more_departure_time             = value.return_more_departure_time;
                        var return_more_arrival_time               = value.return_more_arrival_time;
                        var return_more_total_Time                 = value.return_more_total_Time;
                        
                        var FI_DivCity = `<br><br><div class="row" id='FI_acc_D${No}'></div>`;
                        $("#FI_Div").append(FI_DivCity);
                        var FI_city_Name    =   `<div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Details ${No} :</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Departure Airport : </b></label>
                                                        <span>${more_departure_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Arrival Airport : </b></label>
                                                        <span>${more_arrival_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Airline Name : </b></label>
                                                        <span>${more_other_Airline_Name2}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Departure Time : </b></label>
                                                        <span>${more_departure_time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                        <span>${more_arrival_time}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Time : </b></label>
                                                        <span>${more_total_Time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight No : </b></label>
                                                        <span>${more_departure_Flight_Type}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Price : </b></label>
                                                        <span>${more_departure_flight_number}</span>
                                                    </div>
                                                </div>
                                                
                                                <br><br><br>
                                                
                                                <div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Return Details ${No} :</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Departure Airport : </b></label>
                                                        <span>${return_more_departure_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Arrival Airport : </b></label>
                                                        <span>${return_more_arrival_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Airline Name : </b></label>
                                                        <span>${return_more_other_Airline_Name2}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Departure Time : </b></label>
                                                        <span>${return_more_departure_time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                        <span>${return_more_arrival_time}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Time : </b></label>
                                                        <span>${return_more_total_Time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight No : </b></label>
                                                        <span>${return_more_departure_Flight_Type}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Price : </b></label>
                                                        <span>${return_more_departure_flight_number}</span>
                                                    </div>
                                                </div>
                                                <br><br>`;
                        $('#FI_acc_D'+No+'').append(FI_city_Name);
                        No = No + 1;
                    });
                }
            }
        })
    });
    
    $('.detail-btn-PayFlight').click(function(){
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var paid_flights             = data['paid_flights'];
                paid_flights                 = paid_flights.toFixed(2);
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days           = data1['no_of_pax_days'];
                var flights_details          = JSON.parse(data1['flights_details']);
                var flights_per_person_price = flights_details['flights_per_person_price'];
                var total_flight_amount      = parseFloat(flights_per_person_price) * parseFloat(no_of_pax_days);
                
                $("#tourIdF").val(tourId);
                $("#customer_nameF").val(customer_name);
                $("#package_titleF").val(package_title);
                $("#amount_flight_paid").val(paid_flights);
                $("#total_flight_amount").val(total_flight_amount);
                
                if(paid_flights == null || paid_flights == 0){
                    var remaining_flight_amount = parseFloat(total_flight_amount) - parseFloat(paid_flights);
                    remaining_flight_amount     = remaining_flight_amount.toFixed(2);
                    $('#remaining_flight_amount').val(remaining_flight_amount);
                }
                
                if(paid_flights !== null){
                    var remaining_flight_amount = parseFloat(total_flight_amount) - parseFloat(paid_flights);
                    remaining_flight_amount     = remaining_flight_amount.toFixed(2);
                    $('#remaining_flight_amount').val(remaining_flight_amount);
                }
            }
        })
    });
    
    $('#recieved_flight_amount').on('change',function(){
        var recieved_flight_amount                  = $(this).val();
        var remaining_flight_amount                 = $('#remaining_flight_amount').val();
        var remaining_flight_amount_final           = parseFloat(remaining_flight_amount) - parseFloat(recieved_flight_amount);
        remaining_flight_amount_final               = remaining_flight_amount_final.toFixed(2);
        $('#remaining_flight_amount').val(remaining_flight_amount_final);
        $('#amount_flight_paid').val(recieved_flight_amount);
    });
    
    $('.detail-btnFTA').click(function() {
        $("#flight_total_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var flights_details                     = JSON.parse(view_transportation_total_Amount['flights_details']);
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flight_total_Amount                 = parseFloat(no_of_pax_days) * parseFloat(flights_per_person_price);
                flight_total_Amount                     = flight_total_Amount.toFixed(2);
                if(flights_per_person_price != null){
                    $("#flight_total_Amount").html(flight_total_Amount);
                }else{
                    $("#flight_total_Amount").html(0);
                }
            }
        })
    });
      
    $('.detail-btnFRA').click(function() {
        $("#flight_recieve_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var flight_recieve_Amount  = data['view_flight_recieve_Amount'];
                flight_recieve_Amount      = flight_recieve_Amount.toFixed(2);
                $("#flight_recieve_Amount").html(flight_recieve_Amount);
            }
        })
    });
      
    $('.detail-btnFO').click(function() {
        $("#flight_outsatnding").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var flight_recieve_Amount               = data['view_flight_recieve_Amount'];
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var flights_details                     = JSON.parse(view_transportation_total_Amount['flights_details']);
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flight_total_Amount                 = parseFloat(no_of_pax_days) * parseFloat(flights_per_person_price);
                var flight_outsatnding                  = parseFloat(flight_total_Amount) - parseFloat(flight_recieve_Amount);
                flight_outsatnding                      = flight_outsatnding.toFixed(2);
                if(flights_per_person_price != null){
                    $("#flight_outsatnding").html(flight_outsatnding);
                }else{
                    $("#flight_outsatnding").html(0);
                }
            }
        })
    });
    
    // Transportation
    $('.detail-btn-Transportation').click(function() {
        
        $("#TO_Div").empty();
        $("#TR_Div").empty();
        $("#TA_Div").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {    
                var transportation_details      = JSON.parse(data['transportation_details']);
                var transportation_details_more = JSON.parse(data['transportation_details_more']);
                
                var transportation_trip_type            = transportation_details['transportation_trip_type'];
                console.log(transportation_trip_type);
                var transportation_pick_up_location     = transportation_details['transportation_pick_up_location'];
                var transportation_drop_off_location    = transportation_details['transportation_drop_off_location'];
                var transportation_pick_up_date         = transportation_details['transportation_pick_up_date'];
                var transportation_drop_of_date         = transportation_details['transportation_drop_of_date'];
                var transportation_total_Time           = transportation_details['transportation_total_Time'];
                var transportation_vehicle_type         = transportation_details['transportation_vehicle_type'];
                var transportation_no_of_vehicle        = transportation_details['transportation_no_of_vehicle'];
                var transportation_price_per_vehicle    = transportation_details['transportation_price_per_vehicle'];
                var transportation_vehicle_total_price  = transportation_details['transportation_vehicle_total_price'];
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                
                var return_transportation_pick_up_location     = transportation_details['return_transportation_pick_up_location'];
                var return_transportation_drop_off_location    = transportation_details['return_transportation_drop_off_location'];
                var return_transportation_drop_of_date         = transportation_details['return_transportation_drop_of_date'];
                var return_transportation_pick_up_date         = transportation_details['return_transportation_pick_up_date'];
                var return_transportation_total_Time           = transportation_details['return_transportation_total_Time'];
                var return_transportation_vehicle_type         = transportation_details['return_transportation_vehicle_type'];
                var return_transportation_no_of_vehicle        = transportation_details['return_transportation_no_of_vehicle'];
                var return_transportation_price_per_vehicle    = transportation_details['return_transportation_price_per_vehicle'];
                var return_transportation_vehicle_total_price  = transportation_details['return_transportation_vehicle_total_price'];
                var return_transportation_price_per_person     = transportation_details['return_transportation_price_per_person'];
                
                if(transportation_trip_type == 'One-Way'){
                    
                    var TO_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Transportation Details</h1>
                                    </div>
                                </div>`;
                    $("#TO_Div").append(TO_Div);
                    
                    var TO_DR = `<br><br><div class="row" id="TO_DR"></div>`;
                    $("#TO_Div").append(TO_DR);
                    var TO_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        <br><br><br>`;
                                    
                    $("#TO_DR").append(TO_Name);
                }
                
                if(transportation_trip_type == 'Return'){
                    var TR_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Transportation Details</h1>
                                        </div>
                                    </div>`;
                    $("#TR_Div").append(TR_Div);
            
                    var TR_DR = `<br><br><div class="row" id="TR_DR"></div>`;
                    $("#TR_Div").append(TR_DR);
                    var TR_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        
                                        <br><br><br>
                                        
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Return Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${return_transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${return_transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${return_transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${return_transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${return_transportation_total_Time}</span>
                                            </div>
                                        </div>`;
                                    
                    $("#TR_DR").append(TR_Name);
                }
                
                if(transportation_trip_type == 'All_Round'){
                    
                    var TO_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Transportation Details</h1>
                                    </div>
                                </div>`;
                    $("#TO_Div").append(TO_Div);
                    
                    var TO_DR = `<br><br><div class="row" id="TO_DR"></div>`;
                    $("#TO_Div").append(TO_DR);
                    var TO_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        <br><br><br>`;
                                    
                    $("#TO_DR").append(TO_Name);
                    
                    No = 1;
                    var TA_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Transportation Details (${transportation_trip_type})</h1>
                                        </div>
                                    </div>`;
                    $("#TA_Div").append(TA_Div);
                    
                    $.each(transportation_details_more, function(key, value) {
                        var more_transportation_pick_up_location     = value.more_transportation_pick_up_location;
                        var more_transportation_drop_off_location    = value.more_transportation_drop_off_location;
                        var more_transportation_pick_up_date         = value.more_transportation_pick_up_date;
                        
                        var TA_DR = `<br><br><div class="row" id='TA_DR${No}'></div>`;
                        $("#TA_Div").append(TA_DR);
                        var TA_city_Name    =   `<div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details (${transportation_trip_type} ${No}):</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Pick up : </b></label>
                                                        <span>${more_transportation_pick_up_location}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Drop of : </b></label>
                                                        <span>${more_transportation_drop_off_location}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>pick up date : </b></label>
                                                        <span>${more_transportation_pick_up_date}</span>
                                                    </div>
                                                </div>
                                                <br><br>`;
                        $('#TA_DR'+No+'').append(TA_city_Name);
                        No = No + 1;
                    });
                }
            }
        })
    });
    
    $('.detail-btn-PayTransportation').click(function(){
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var paid_transportation      = data['paid_transportation'];
                paid_transportation          = paid_transportation.toFixed(2);
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days                  = data1['no_of_pax_days'];
                var transportation_details          = JSON.parse(data1['transportation_details']);
                var transportation_price_per_person = transportation_details['transportation_price_per_person'];
                var total_transportation_amount     = parseFloat(transportation_price_per_person) * parseFloat(no_of_pax_days);
                
                $("#tourIdT").val(tourId);
                $("#customer_nameT").val(customer_name);
                $("#package_titleT").val(package_title);
                $("#amount_transportation_paid").val(paid_transportation);
                $("#total_transportation_amount").val(total_transportation_amount);
                
                if(paid_transportation == null || paid_transportation == 0){
                    var remaining_Transportation_amount    = parseFloat(total_transportation_amount) - parseFloat(paid_transportation);
                    remaining_Transportation_amount        = remaining_Transportation_amount.toFixed(2);
                    $('#remaining_transportation_amount').val(remaining_Transportation_amount);
                }
                
                if(paid_transportation !== null){
                    var remaining_Transportation_amount = parseFloat(total_transportation_amount) - parseFloat(paid_transportation);
                    remaining_Transportation_amount        = remaining_Transportation_amount.toFixed(2);
                    $('#remaining_transportation_amount').val(remaining_Transportation_amount);
                }
            }
        })
    });
    
    $('#recieved_transportation_amount').on('change',function(){
        var recieved_transportation_amount          = $(this).val();
        var remaining_transportation_amount         = $('#remaining_transportation_amount').val();
        var remaining_transportation_amount_final   = parseFloat(remaining_transportation_amount) - parseFloat(recieved_transportation_amount);
        remaining_transportation_amount_final       = remaining_transportation_amount_final.toFixed(2);
        $('#remaining_transportation_amount').val(remaining_transportation_amount_final);
        $('#amount_transportation_paid').val(recieved_transportation_amount);
    });
    
    $('.detail-btnTTA').click(function() {
        $("#transportation_total_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var transportation_details              = JSON.parse(view_transportation_total_Amount['transportation_details']);
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                var transportation_total_Amount         = parseFloat(no_of_pax_days) * parseFloat(transportation_price_per_person);
                transportation_total_Amount             = transportation_total_Amount.toFixed(2);
                if(transportation_price_per_person != null){
                    $("#transportation_total_Amount").html(transportation_total_Amount);
                }else{
                    $("#transportation_total_Amount").html(0);
                }
            }
        })
    });
      
    $('.detail-btnTRA').click(function() {
        $("#transportation_recieve_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var transportation_recieve_Amount  = data['view_transportation_recieve_Amount'];
                transportation_recieve_Amount      = transportation_recieve_Amount.toFixed(2);
                $("#transportation_recieve_Amount").html(transportation_recieve_Amount);
            }
        })
    });
      
    $('.detail-btnTO').click(function() {
        $("#transportation_outsatnding").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var transportation_recieve_Amount  = data['view_transportation_recieve_Amount'];
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var transportation_details              = JSON.parse(view_transportation_total_Amount['transportation_details']);
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                var transportation_total_Amount         = parseFloat(no_of_pax_days) * parseFloat(transportation_price_per_person);
                var transportation_outsatnding          = parseFloat(transportation_total_Amount) - parseFloat(transportation_recieve_Amount);
                transportation_outsatnding              = transportation_outsatnding.toFixed(2);
                if(transportation_price_per_person != null){
                    $("#transportation_outsatnding").html(transportation_outsatnding);
                }else{
                    $("#transportation_outsatnding").html(0);
                }
            }
        })
    });
    
    // Visa
    $('.detail-btn-Visa').click(function() {
        
        $("#V_Div").empty();
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {    
                // console.log(data);
                var data1                    = data['data'];
                var no_of_pax_days           = data1['no_of_pax_days'];
                var visa_fee                 = data1['visa_fee'];
                var visa_rules_regulations   = data1['visa_rules_regulations'];
                var visa_type                = data1['visa_type'];
                var visa_image               = data1['visa_image'];
                var visa_total_fee           = parseFloat(no_of_pax_days) * parseFloat(visa_fee)
                
                var V_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Visa Details</h1>
                                    </div>
                                </div>`;
                $("#V_Div").append(V_Div);
    
                var V_DR = `<br><br><div class="row" id="V_DR"></div>`;
                $("#V_Div").append(V_DR);
                var V_Name =   `<div class="col-xl-12">
                                        <div class="mb-3">
                                            <h3 style="font-size: 30px;text-decoration: underline;">Visa Details :</h3>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Type : </b></label>
                                            <span>${visa_type}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Fee per Person : </b></label>
                                            <span><?php echo $currency; ?>${visa_fee}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Total Fee : </b></label>
                                            <span><?php echo $currency; ?>${visa_total_fee}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Image : </b></label>
                                            <span>${visa_image}</span>
                                            <img src="/public/uploads/package_imgs/${visa_image}" style="width:433px;height:250px" />
                                        </div>
                                    </div>`;
                                
                $("#V_DR").append(V_Name);
                
            }
        })
    });
    
    $('.detail-btn-PayVisa').click(function(){
        
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'visa_Pay/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {    
                // console.log(data);
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days           = data1['no_of_pax_days'];
                var visa_total_fee           = data1['visa_fee'];
                var paid_visa                = data['paid_visa'];
                paid_visa                    = paid_visa.toFixed(2);
                var total_visa_amount        = parseFloat(no_of_pax_days) * parseFloat(visa_total_fee);
                
                $("#tourIdV").val(tourId);
                $("#customer_nameV").val(customer_name);
                $("#package_titleV").val(package_title);
                $("#total_visa_amount").val(total_visa_amount);
                $("#amount_visa_paid").val(paid_visa);
                
                if(paid_visa == null || paid_visa == 0){
                    var remaining_visa_amount    = parseFloat(total_visa_amount) - parseFloat(paid_visa);
                    remaining_visa_amount        = remaining_visa_amount.toFixed(2);
                    $('#remaining_visa_amount').val(remaining_visa_amount);
                    
                }
                
                if(paid_visa !== null){
                    var remaining_visa_amount    = parseFloat(total_visa_amount) - parseFloat(paid_visa);
                    remaining_visa_amount        = remaining_visa_amount.toFixed(2);
                    $('#remaining_visa_amount').val(remaining_visa_amount);
                }
            }
        })
    });
    
    $('#recieved_visa_amount').on('change',function(){
        var recieved_visa_amount         = $(this).val();
        var remaining_visa_amount        = $('#remaining_visa_amount').val();
        var remaining_visa_amount_final  = parseFloat(remaining_visa_amount) - parseFloat(recieved_visa_amount);
        remaining_visa_amount_final      = remaining_visa_amount_final.toFixed(2);
        $('#remaining_visa_amount').val(remaining_visa_amount_final);
        $('#amount_visa_paid').val(recieved_visa_amount);
    });
    
    $('.detail-btnVTA').click(function() {
        $("#visa_total_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var view_visa_total_Amount      = data['view_visa_total_Amount'];
                var no_of_pax_days              = view_visa_total_Amount['no_of_pax_days'];
                var visa_fee                    = view_visa_total_Amount['visa_fee'];
                
                var visa_total_Amount           = parseFloat(no_of_pax_days) * parseFloat(visa_fee);
                visa_total_Amount               = visa_total_Amount.toFixed(2);
                if(visa_fee != null){
                    $("#visa_total_Amount").html(visa_total_Amount);   
                }else{
                    $("#visa_total_Amount").html(0);
                }
            }
        })
    });
      
    $('.detail-btnVRA').click(function() {
        $("#visa_recieve_Amount").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                // console.log(data);
                var visa_recieve_Amount    = data['view_visa_recieve_Amount'];
                visa_recieve_Amount        = visa_recieve_Amount.toFixed(2);
                $("#visa_recieve_Amount").html(visa_recieve_Amount);
            }
        })
    });
      
    $('.detail-btnVO').click(function() {
        $("#visa_outsatnding").empty();
        const id = $(this).attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+id,
            type: 'GET',
            data: {
                "id": id
            },
            success:function(data) {
                var view_visa_total_Amount      = data['view_visa_total_Amount'];
                var visa_recieve_Amount         = data['view_visa_recieve_Amount'];
                
                var no_of_pax_days              = view_visa_total_Amount['no_of_pax_days'];
                var visa_fee                    = view_visa_total_Amount['visa_fee'];
                var visa_total_Amount           = parseFloat(no_of_pax_days) * parseFloat(visa_fee);
                var visa_outsatnding            = parseFloat(visa_total_Amount) - parseFloat(visa_recieve_Amount);
                visa_outsatnding                = visa_outsatnding.toFixed(2);
                if(visa_fee != null){
                    $("#visa_outsatnding").html(visa_outsatnding);
                }else{
                    $("#visa_outsatnding").html(0);
                }
                
            }
        })
      });

</script>

<script>
    
    $("#client_Name_acc").on('change',function(){
            var id =  $(this).val();
            if(id == 0){
                $("#tbody_all_data_acc").empty();
                $.ajax({
                    url:"{{URL::to('expenses_IncomeAll')}}",
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var accomodation_details = JSON.parse(value.accomodation_details);
                            var accomodation_details_more = JSON.parse(value.accomodation_details_more);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td id="hotel_Name${i}"></td>
                                                    <td id="no_of_Nights${i}"></td>
                                                    <td>
                                                        <div><b>Total Price :</b>
                                                            <div id="total_price${i}"></div>
                                                        </div>
                                                        <div><b >More Total Price :</b>
                                                            <div id="more_total_price${i}"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 70px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAccomodation" onclick="detail_btn_AccomodationID(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Accomodation${i}">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Accomodation Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayAccomodation" onclick="detail_btn_PayAccomodation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayAccomodation${i}">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Accomodation Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterATA" onclick="detail_btnATA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnATA${i}"><i class="mdi mdi-eye me-1"></i>Accomodation Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterARA" onclick="detail_btnARA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnARA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Accomodation Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAO" onclick="detail_btnAO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnAO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Accomodation Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            
                            $("#tbody_all_data_acc").append(data_apppend);
                            
                            $('#hotel_Name'+i+'').empty();
                            $('#no_of_Nights'+i+'').empty();
                            $('#total_price'+i+'').empty();
                            $('#more_total_price'+i+'').empty();
                            
                            $.each(accomodation_details, function(key, value1) {
                                var acc_hotel_name =  `<div>${value1.acc_hotel_name ?? ''}</div>`;
                                $('#hotel_Name'+i+'').append(acc_hotel_name);
                                
                                var acc_no_of_nightst =  `<div>${value1.acc_no_of_nightst ?? ''}</div>`;
                                $('#no_of_Nights'+i+'').append(acc_no_of_nightst);
                                
                                var total_price = `<?php echo $currency; ?>${value1.acc_total_amount ?? ''}<br>`;
                                $('#total_price'+i+'').append(total_price);
                            });
                            
                            $.each(accomodation_details_more, function(key, value1) {
                                var more_total_price = `<?php echo $currency; ?>${value1.more_acc_total_amount ?? ''}<br>`;
                                $('#more_total_price'+i+'').append(more_total_price);
                            });
                            
                            i++;
                            
                        }); 
                    }

                });
            }else if(id !== null){
                $("#tbody_all_data_acc").empty();
                $.ajax({
                    url:"{{URL::to('expenses_Income_client_wise_data')}}" + '/' + id,
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var accomodation_details = JSON.parse(value.accomodation_details);
                            var accomodation_details_more = JSON.parse(value.accomodation_details_more);
                            // console.log(value);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td id="hotel_Name${i}"></td>
                                                    <td id="no_of_Nights${i}"></td>
                                                    <td>
                                                        <div><b>Total Price :</b>
                                                            <div id="total_price${i}"></div>
                                                        </div>
                                                        <div><b >More Total Price :</b>
                                                            <div id="more_total_price${i}"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 70px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAccomodation" onclick="detail_btn_AccomodationID(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Accomodation${i}">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Accomodation Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayAccomodation" onclick="detail_btn_PayAccomodation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayAccomodation${i}">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Accomodation Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterATA" onclick="detail_btnATA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnATA${i}"><i class="mdi mdi-eye me-1"></i>Accomodation Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterARA" onclick="detail_btnARA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnARA${i}"><i class="mdi mdi-eye me-1"></i>Accomodation Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterAO" onclick="detail_btnAO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnAO${i}"><i class="mdi mdi-eye me-1"></i>Accomodation Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            $("#tbody_all_data_acc").append(data_apppend);
                            
                            $('#hotel_Name'+i+'').empty();
                            $('#no_of_Nights'+i+'').empty();
                            $('#total_price'+i+'').empty();
                            $('#more_total_price'+i+'').empty();
                            
                            $.each(accomodation_details, function(key, value1) {
                                var acc_hotel_name =  `<div>${value1.acc_hotel_name ?? ''}</div>`;
                                $('#hotel_Name'+i+'').append(acc_hotel_name);
                                
                                var acc_no_of_nightst =  `<div>${value1.acc_no_of_nightst ?? ''}</div>`;
                                $('#no_of_Nights'+i+'').append(acc_no_of_nightst);
                                
                                var total_price = `<?php echo $currency; ?>${value1.acc_total_amount ?? ''}<br>`;
                                $('#total_price'+i+'').append(total_price);
                            });
                            
                            $.each(accomodation_details_more, function(key, value1) {
                                var more_total_price = `<?php echo $currency; ?>${value1.more_acc_total_amount ?? ''}<br>`;
                                $('#more_total_price'+i+'').append(more_total_price);
                            });
                            
                            i++;
                        }); 
                    }

                });
            }else{
                console.log('else');
            }
        });
        
    $("#client_Name_flight").on('change',function(){
            var id =  $(this).val();
            
            if(id == 0){
                $("#tbody_all_data_flight").empty();
                $.ajax({
                    url:"{{URL::to('expenses_IncomeAll')}}",
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var flights_details = JSON.parse(value.flights_details);
                            var accomodation_details_more = JSON.parse(value.accomodation_details_more);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${flights_details.flight_type}</td>
                                                    <td>
                                                        <div><b>Flight Details :</b></div>
                                                        ${flights_details.departure_airport_code}
                                                    </td>
                                                    <td>
                                                        <div><b>Flight Details :</b></div>
                                                        ${flights_details.arrival_airport_code}
                                                    </td>
                                                    <td><?php echo $currency; ?>${flights_details.flights_per_person_price}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFlight" onclick="detail_btn_Flight(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Flight${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Flight Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayFlight" onclick="detail_btn_PayFlight(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayFlight${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Flight Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFTA" onclick="detail_btnFTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFRA" onclick="detail_btnFRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFO" onclick="detail_btnFO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Outstandings</a>
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            
                            $("#tbody_all_data_flight").append(data_apppend);
                            
                            i++;
                            
                        }); 
                    }

                });
            }else if(id !== null){
                $("#tbody_all_data_flight").empty();
                $.ajax({
                    url:"{{URL::to('expenses_Income_client_wise_data')}}" + '/' + id,
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var flights_details      = JSON.parse(value.flights_details);
                            var flights_details_more = JSON.parse(value.flights_details_more);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${flights_details.flight_type}</td>
                                                    <td>
                                                        <div><b>Flight Details :</b></div>
                                                        ${flights_details.departure_airport_code}
                                                    </td>
                                                    <td>
                                                        <div><b>Flight Details :</b></div>
                                                        ${flights_details.arrival_airport_code}
                                                    </td>
                                                    <td><?php echo $currency; ?>${flights_details.flights_per_person_price}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFlight" onclick="detail_btn_Flight(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Flight${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Flight Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayFlight" onclick="detail_btn_PayFlight(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayFlight${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Flight Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFTA" onclick="detail_btnFTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFRA" onclick="detail_btnFRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterFO" onclick="detail_btnFO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnFO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Flight Outstandings</a>
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            $("#tbody_all_data_flight").append(data_apppend);
                            
                            i++;
                        }); 
                    }

                });
            }else{
                console.log('else');
            }
        });
        
    $("#client_Name_transportation").on('change',function(){
            var id =  $(this).val();
            
            if(id == 0){
                $("#tbody_all_data_transportation").empty();
                $.ajax({
                    url:"{{URL::to('expenses_IncomeAll')}}",
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var transportation_details      = JSON.parse(value.transportation_details);
                            var transportation_details_more = JSON.parse(value.transportation_details_more);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${transportation_details.transportation_pick_up_location}</td>
                                                    <td>${transportation_details.transportation_drop_off_location}</td>
                                                    <td>${transportation_details.transportation_no_of_vehicle}</td>
                                                    <td><?php echo $currency; ?>${transportation_details.transportation_price_per_vehicle}</td>
                                                    <td><?php echo $currency; ?>${transportation_details.transportation_price_per_person}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTransportation" onclick="detail_btn_Transportation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Transportation${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Transportation Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayTransportation" onclick="detail_btn_PayTransportation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayTransportation${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Transportation Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTTA" onclick="detail_btnTTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTRA" onclick="detail_btnTRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTO" onclick="detail_btnTO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            
                            $("#tbody_all_data_transportation").append(data_apppend);
                            
                            i++;
                            
                        }); 
                    }

                });
            }else if(id !== null){
                $("#tbody_all_data_transportation").empty();
                $.ajax({
                    url:"{{URL::to('expenses_Income_client_wise_data')}}" + '/' + id,
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var transportation_details      = JSON.parse(value.transportation_details);
                            var transportation_details_more = JSON.parse(value.transportation_details_more);
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${transportation_details.transportation_pick_up_location}</td>
                                                    <td>${transportation_details.transportation_drop_off_location}</td>
                                                    <td>${transportation_details.transportation_no_of_vehicle}</td>
                                                    <td><?php echo $currency; ?>${transportation_details.transportation_price_per_vehicle}</td>
                                                    <td><?php echo $currency; ?>${transportation_details.transportation_price_per_person}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 60px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTransportation" onclick="detail_btn_Transportation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Transportation${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Transportation Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayTransportation" onclick="detail_btn_PayTransportation(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayTransportation${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Transportation Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTTA" onclick="detail_btnTTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTRA" onclick="detail_btnTRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterTO" onclick="detail_btnTO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnTO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Transportation Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            $("#tbody_all_data_transportation").append(data_apppend);
                            
                            i++;
                        }); 
                    }

                });
            }else{
                console.log('else');
            }
        });
        
    $("#client_Name_visa").on('change',function(){
            var id =  $(this).val();
            
            if(id == 0){
                $("#tbody_all_data_visa").empty();
                $.ajax({
                    url:"{{URL::to('expenses_IncomeAll')}}",
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${value.visa_type}</td>
                                                    <td><?php echo $currency; ?>${value.visa_fee}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 105px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVisa" onclick="detail_btn_Visa(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Visa${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Visa Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayVisa" onclick="detail_btn_PayVisa(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayVisa${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Visa Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVTA" onclick="detail_btnVTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVRA" onclick="detail_btnVRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVO" onclick="detail_btnVO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            
                            $("#tbody_all_data_visa").append(data_apppend);
                            
                            i++;
                            
                        }); 
                    }

                });
            }else if(id !== null){
                $("#tbody_all_data_visa").empty();
                $.ajax({
                    url:"{{URL::to('expenses_Income_client_wise_data')}}" + '/' + id,
                    method: "GET",
                    data: {
                        id : id
                    },
                    success:function(data){
                        var data1 = data['data'];
                        var i  = 1;
                        $.each(data1, function(key, value) {
                            var data_apppend =  `<tr role="row" class="odd">
                                                    <td>${i}</td>
                                                    <td>${value.id}</td>
                                                    <td>${value.name} ${value.lname}</td>
                                                    <td>${value.title}</td>
                                                    <td>${value.visa_type}</td>
                                                    <td><?php echo $currency; ?>${value.visa_fee}</td>
                                                    <td>
                                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="margin-right: 105px;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVisa" onclick="detail_btn_Visa(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-Visa${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Visa Details
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterPayVisa" onclick="detail_btn_PayVisa(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btn-PayVisa${i}" data-uniqid="b5c7a7ced6fda207e232">
                                                                    <i class="mdi mdi-eye me-1"></i>
                                                                    Pay Visa Fee
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVTA" onclick="detail_btnVTA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVTA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Total Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVRA" onclick="detail_btnVRA(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVRA${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Recieved Amount</a>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenterVO" onclick="detail_btnVO(${i})" data-id="${value.id}" class="dropdown-item fetchorderdetails detail-btnVO${i}" data-uniqid="b5c7a7ced6fda207e232"><i class="mdi mdi-eye me-1"></i>Visa Outstandings</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>`;
                            $("#tbody_all_data_visa").append(data_apppend);
                            
                            i++;
                        }); 
                    }

                });
            }else{
                console.log('else');
            }
        });
      
</script>

<script>
    
    // Accomodation Client Wise
    function detail_btn_AccomodationID(id){
        $("#acc_Div").empty();
        var acc_Div = ` <div class="col-xl-12" style="text-align:center;">
                            <div class="mb-3">
                              <h1 style="text-decoration: underline;font-size: 50px;">Accomodation Details</h1>
                            </div>
                        </div>`;
        $("#acc_Div").append(acc_Div);
        
        const ids = $('.detail-btn-Accomodation'+id+'').attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var accomodation_details = JSON.parse(data['accomodation_details']);
                
                var accomodation_details_more = JSON.parse(data['accomodation_details_more']);
                
                No = 1;
                No2 = 2;
                $.each(accomodation_details, function(key, value) {
                    
                    var hotel_city_name         = value.hotel_city_name;
                    var acc_hotel_name          = value.acc_hotel_name;
                    var acc_check_in            = value.acc_check_in;
                    var acc_check_out           = value.acc_check_out;
                    var acc_no_of_nightst       = value.acc_no_of_nightst;
                    var acc_pax                 = value.acc_pax;
                    var acc_price               = value.acc_price;
                    var acc_qty                 = value.acc_qty;
                    var acc_total_amount        = value.acc_total_amount;
                    var acc_type                = value.acc_type;
                    var total_Price1            = parseFloat(acc_total_amount) * parseFloat(acc_pax);
                    var total_Price             = total_Price1.toFixed(2);
                    
                    var DivCity = `<br><br><div class="row" id='acc_D${No}'></div>`;
                    $("#acc_Div").append(DivCity);
                    var city_Name = `<div class="col-xl-12">
                                        <div class="mb-3">
                                            <h3 style="font-size: 40px;">${hotel_city_name} Details :</h3>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Hotel Name : </b></label>
                                            <span>${acc_hotel_name}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Checked In : </b></label>
                                            <span>${acc_check_in}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Checked Out : </b></label>
                                            <span>${acc_check_out}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>No Of Nights : </b></label>
                                            <span>${acc_no_of_nightst}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Room Type : </b></label>
                                            <span>${acc_type}</span>
                                        </div>
                                    </div>
                        
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>No Of Pax : </b></label>
                                            <span>${acc_pax}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Room Quantity : </b></label>
                                            <span>${acc_qty}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Price per night: </b></label>
                                            <span><?php echo $currency; ?>${acc_price}</span>
                                        </div>
                                    </div>
                        
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Price per person per night : </b></label>
                                            <span><?php echo $currency; ?>${acc_total_amount}</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Total Price : </b></label>
                                            <span><?php echo $currency; ?>${total_Price}</span>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row" id='more_acc_D${No}'></div>
                                    <br><br>
                                    <div class="row" id='more_acc_D${No2}'></div>`;
                    $('#acc_D'+No+'').append(city_Name);
                    No = No + 2;
                    No2 = No2 + 2;
                });
                
                No1 = 1;
                $.each(accomodation_details_more, function(key, value) {
                    var more_hotel_city         = value.more_hotel_city;
                    var more_acc_type           = value.more_acc_type;
                    var more_acc_pax            = value.more_acc_pax;
                    var more_acc_price          = value.more_acc_price;
                    var more_acc_qty            = value.more_acc_qty;
                    var more_acc_total_amount   = value.more_acc_total_amount;
                    var more_total_Price1       = parseFloat(more_acc_total_amount) * parseFloat(more_acc_pax);
                    var more_total_Price        = more_total_Price1.toFixed(2);
                    
                    var more_city_Name  =  `<div class="col-xl-12" style="margin-left: 40px;>
                                                <div class="mb-3">
                                                  <h3 style="font-size: 30px;">${more_acc_type}</h3>
                                                </div>
                                            </div>
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>No Of Pax : </b></label>
                                                    <span>${more_acc_pax}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Room Type : </b></label>
                                                    <span>${more_acc_type}</span>
                                                </div>
                                            </div>
                                    
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Quantity : </b></label>
                                                    <span>${more_acc_qty}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Price per night : </b></label>
                                                    <span><?php echo $currency; ?>${more_acc_price}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Price per person per night : </b></label>
                                                    <span><?php echo $currency; ?>${more_acc_total_amount}</span>
                                                </div>
                                            </div>
                                            <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                <div class="mb-3">
                                                    <label class="form-label"><b>Total Price : </b></label>
                                                    <span><?php echo $currency; ?>${more_total_Price}</span>
                                                </div>
                                            </div>`;
                    $('#more_acc_D'+No1+'').append(more_city_Name);
                    No1 = No1 + 1;
                });
            }
        });
    }
    
    function detail_btn_PayAccomodation(id){
        $("#selected_cityID").empty();
        $("#total_accomodation_amount").val('0');
        $("#recieved_accomodation_amount").val('0');
        $("#remaining_accomodation_amount").val('0');
        $("#amount_accomodation_paid").val('0');
        
        const ids = $('.detail-btn-PayAccomodation'+id+'').attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                       = data['data'];
                var customer_Data               = data['customer_Data'];
                var paid_accomodation           = data['paid_accomodation'];
                var tourId                      = data1['id'];
                var package_title               = data1['title'];
                var name                        = customer_Data['name'];
                var lname                       = customer_Data['lname'];
                var customer_name               = name +" "+ lname ;
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                $("#tourIdA").val(tourId);
                $("#customer_nameA").val(customer_name);
                $("#package_titleA").val(package_title);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityID").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityID").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        });
    }
    
    function detail_btnATA(id){
        console.log(id);
        
        $("#accomodation_total_Amount").empty();
        $("#selected_cityIDATA").empty();
        $('#tableATA').css('display','none');
        $("#hidden_ATA").empty();
        
        const ids = $('.detail-btnATA'+id+'').attr('data-id');
        
        var hidden_ATA = `<input type="hidden" value="${ids}" id="id_ATA">`;
        $("#hidden_ATA").append(hidden_ATA);
        
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDATA").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDATA").append(city_append);
                   
                    cityNO = cityNO + 1;
                });
                
            }
        });
    }
    
    function detail_btnARA(id){
        $("#accomodation_recieve_Amount").empty();
        $("#selected_cityIDARA").empty();
        
        $('#tableARA').css('display','none');
        $("#hidden_ARA").empty();
        
        const ids = $('.detail-btnARA'+id+'').attr('data-id');
        var hidden_ARA = `<input type="hidden" value="${ids}" id="id_ARA">`;
        $("#hidden_ARA").append(hidden_ARA);
        
        
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDARA").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDARA").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        });
    }
        
    function detail_btnAO(id){
        $("#accomodation_recieve_Amount").empty();
        $("#selected_cityIDAO").empty();
        
        $('#tableAO').css('display','none');
        $("#hidden_AO").empty();
        
        const ids = $('.detail-btnAO'+id+'').attr('data-id');
        var hidden_AO = `<input type="hidden" value="${ids}" id="id_AO">`;
        $("#hidden_AO").append(hidden_AO);
        
        
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                       = data['data'];
                var paid_accomodation           = data['paid_accomodation'];
                var no_of_pax_days              = data1['no_of_pax_days'];
                var accomodation_details        = JSON.parse(data1['accomodation_details']);
                
                var choose_city = `<option value="Choose City...">Choose City...</option>`;
                $("#selected_cityIDAO").append(choose_city);
                var cityNO = 1;
                $.each(accomodation_details, function(key, value) {
                    var hotel_city_name = value.hotel_city_name;
                    var city_append  = `<option attr="${hotel_city_name}" id="hotel_city_name_${cityNO}" value="${hotel_city_name}">${hotel_city_name}</option>`;
                    $("#selected_cityIDAO").append(city_append);
                    cityNO = cityNO + 1;
                });
                
            }
        });
    }
    
    // Flights Client Wise
    function detail_btn_Flight(id) {
        $("#FD_Div").empty();
        $("#FI_Div").empty();
        const ids = $('.detail-btn-Flight'+id+'').attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var flights_details = JSON.parse(data['flights_details']);
                var flights_details_more = JSON.parse(data['flights_details_more']);
                // console.log(flights_details);
                
                var departure_airport_code              = flights_details['departure_airport_code'];
                var arrival_airport_code                = flights_details['arrival_airport_code'];
                var other_Airline_Name2                 = flights_details['other_Airline_Name2'];
                var departure_Flight_Type               = flights_details['departure_Flight_Type'];
                var departure_flight_number             = flights_details['departure_flight_number'];
                var departure_time                      = flights_details['departure_time'];
                var arrival_time                        = flights_details['arrival_time'];
                var total_Time                          = flights_details['total_Time'];
                
                var return_departure_airport_code       = flights_details['return_departure_airport_code'];
                var return_arrival_airport_code         = flights_details['return_arrival_airport_code'];
                var return_other_Airline_Name2          = flights_details['return_other_Airline_Name2'];
                var return_departure_Flight_Type        = flights_details['return_departure_Flight_Type'];
                var return_departure_flight_number      = flights_details['return_departure_flight_number'];
                var return_departure_time               = flights_details['return_departure_time'];
                var return_arrival_time                 = flights_details['return_arrival_time'];
                var return_total_Time                   = flights_details['return_total_Time'];
                
                var flight_type                         = flights_details['flight_type'];
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flights_image                       = flights_details['flights_image'];
                var terms_and_conditions                = flights_details['terms_and_conditions'];
                var connected_flights_duration_details  = flights_details['connected_flights_duration_details'];
                
                if(flight_type == 'Direct'){
                    
                    var FD_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Flight Details</h1>
                                        </div>
                                    </div>`;
                    $("#FD_Div").append(FD_Div);
        
                    var Divflight = `<br><br><div class="row" id="flight_DR"></div>`;
                    $("#FD_Div").append(Divflight);
                    var flight_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Departure Airport : </b></label>
                                                <span>${departure_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Arrival Airport : </b></label>
                                                <span>${arrival_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Airline Name : </b></label>
                                                <span>${other_Airline_Name2}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Departure Time : </b></label>
                                                <span>${departure_time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                <span>${arrival_time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Time : </b></label>
                                                <span>${total_Time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight No : </b></label>
                                                <span>${departure_flight_number}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Price : </b></label>
                                                <span><?php echo $currency; ?>${flights_per_person_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Return Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Departure Airport : </b></label>
                                                <span>${return_departure_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Arrival Airport : </b></label>
                                                <span>${return_arrival_airport_code}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Airline Name : </b></label>
                                                <span>${return_other_Airline_Name2}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Departure Time : </b></label>
                                                <span>${return_departure_time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                <span>${return_arrival_time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight Total Time : </b></label>
                                                <span>${return_total_Time}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Flight No : </b></label>
                                                <span>${return_departure_flight_number}</span>
                                            </div>
                                        </div>`;
                                    
                    $("#flight_DR").append(flight_Name);
                }
                
                if(flight_type == 'Indirect'){
                    No = 1;
                    
                    var FI_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">More Flight Details</h1>
                                        </div>
                                    </div>`;
                    $("#FI_Div").append(FI_Div);
                    
                    $.each(flights_details_more, function(key, value) {
                        var more_departure_airport_code     = value.more_departure_airport_code;
                        var more_arrival_airport_code       = value.more_arrival_airport_code;
                        var more_other_Airline_Name2        = value.more_other_Airline_Name2;
                        var more_departure_Flight_Type      = value.more_departure_Flight_Type;
                        var more_departure_flight_number    = value.more_departure_flight_number;
                        var more_departure_time             = value.more_departure_time;
                        var more_arrival_time               = value.more_arrival_time;
                        var more_total_Time                 = value.more_total_Time;
                        
                        var return_more_departure_airport_code     = value.return_more_departure_airport_code;
                        var return_more_arrival_airport_code       = value.return_more_arrival_airport_code;
                        var return_more_other_Airline_Name2        = value.return_more_other_Airline_Name2;
                        var return_more_departure_Flight_Type      = value.return_more_departure_Flight_Type;
                        var return_more_departure_flight_number    = value.return_more_departure_flight_number;
                        var return_more_departure_time             = value.return_more_departure_time;
                        var return_more_arrival_time               = value.return_more_arrival_time;
                        var return_more_total_Time                 = value.return_more_total_Time;
                        
                        var FI_DivCity = `<br><br><div class="row" id='FI_acc_D${No}'></div>`;
                        $("#FI_Div").append(FI_DivCity);
                        var FI_city_Name    =   `<div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Details ${No} :</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Departure Airport : </b></label>
                                                        <span>${more_departure_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Arrival Airport : </b></label>
                                                        <span>${more_arrival_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Airline Name : </b></label>
                                                        <span>${more_other_Airline_Name2}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Departure Time : </b></label>
                                                        <span>${more_departure_time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                        <span>${more_arrival_time}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Time : </b></label>
                                                        <span>${more_total_Time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight No : </b></label>
                                                        <span>${more_departure_Flight_Type}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Price : </b></label>
                                                        <span>${more_departure_flight_number}</span>
                                                    </div>
                                                </div>
                                                
                                                <br><br><br>
                                                
                                                <div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">${flight_type} Flight Return Details ${No} :</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Departure Airport : </b></label>
                                                        <span>${return_more_departure_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Arrival Airport : </b></label>
                                                        <span>${return_more_arrival_airport_code}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Airline Name : </b></label>
                                                        <span>${return_more_other_Airline_Name2}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Departure Time : </b></label>
                                                        <span>${return_more_departure_time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Arrival Time : </b></label>
                                                        <span>${return_more_arrival_time}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Time : </b></label>
                                                        <span>${return_more_total_Time}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight No : </b></label>
                                                        <span>${return_more_departure_Flight_Type}</span>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Flight Total Price : </b></label>
                                                        <span>${return_more_departure_flight_number}</span>
                                                    </div>
                                                </div>
                                                <br><br>`;
                        $('#FI_acc_D'+No+'').append(FI_city_Name);
                        No = No + 1;
                    });
                }
            }
        })
    }
    
    function detail_btn_PayFlight(id) {
        const ids = $('.detail-btn-PayFlight'+id+'').attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var paid_flights             = data['paid_flights'];
                paid_flights                 = paid_flights.toFixed(2);
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days           = data1['no_of_pax_days'];
                var flights_details          = JSON.parse(data1['flights_details']);
                var flights_per_person_price = flights_details['flights_per_person_price'];
                var total_flight_amount      = parseFloat(flights_per_person_price) * parseFloat(no_of_pax_days);
                
                $("#tourIdF").val(tourId);
                $("#customer_nameF").val(customer_name);
                $("#package_titleF").val(package_title);
                $("#amount_flight_paid").val(paid_flights);
                $("#total_flight_amount").val(total_flight_amount);
                
                if(paid_flights == null || paid_flights == 0){
                    var remaining_flight_amount = parseFloat(total_flight_amount) - parseFloat(paid_flights);
                    remaining_flight_amount     = remaining_flight_amount.toFixed(2);
                    $('#remaining_flight_amount').val(remaining_flight_amount);
                }
                
                if(paid_flights !== null){
                    var remaining_flight_amount = parseFloat(total_flight_amount) - parseFloat(paid_flights);
                    remaining_flight_amount     = remaining_flight_amount.toFixed(2);
                    $('#remaining_flight_amount').val(remaining_flight_amount);
                }
            }
        })
    }
    
    function detail_btnFTA(id){
        $("#flight_total_Amount").empty();
        const ids = $('.detail-btnFTA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var flights_details                     = JSON.parse(view_transportation_total_Amount['flights_details']);
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flight_total_Amount                 = parseFloat(no_of_pax_days) * parseFloat(flights_per_person_price);
                flight_total_Amount                     = flight_total_Amount.toFixed(2);
                if(flights_per_person_price != null){
                    $("#flight_total_Amount").html(flight_total_Amount);
                }else{
                    $("#flight_total_Amount").html(0);
                }
            }
        })
    }
    
    function detail_btnFRA(id){
        $("#flight_recieve_Amount").empty();
        const ids = $('.detail-btnFRA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var flight_recieve_Amount  = data['view_flight_recieve_Amount'];
                flight_recieve_Amount      = flight_recieve_Amount.toFixed(2);
                $("#flight_recieve_Amount").html(flight_recieve_Amount);
            }
        })
    }
      
    function detail_btnFO(id){
        $("#flight_outsatnding").empty();
        const ids = $('.detail-btnFO'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var flight_recieve_Amount               = data['view_flight_recieve_Amount'];
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var flights_details                     = JSON.parse(view_transportation_total_Amount['flights_details']);
                var flights_per_person_price            = flights_details['flights_per_person_price'];
                var flight_total_Amount                 = parseFloat(no_of_pax_days) * parseFloat(flights_per_person_price);
                var flight_outsatnding                  = parseFloat(flight_total_Amount) - parseFloat(flight_recieve_Amount);
                flight_outsatnding                      = flight_outsatnding.toFixed(2);
                if(flights_per_person_price != null){
                    $("#flight_outsatnding").html(flight_outsatnding);
                }else{
                    $("#flight_outsatnding").html(0);
                }
            }
        })
    }
    
    // Transportation Client Wise
    function detail_btn_Transportation(id){
        $("#TO_Div").empty();
        $("#TR_Div").empty();
        $("#TA_Div").empty();
        const ids = $('.detail-btn-Transportation'+id+'').attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {    
                var transportation_details      = JSON.parse(data['transportation_details']);
                var transportation_details_more = JSON.parse(data['transportation_details_more']);
                
                var transportation_trip_type            = transportation_details['transportation_trip_type'];
                console.log(transportation_trip_type);
                var transportation_pick_up_location     = transportation_details['transportation_pick_up_location'];
                var transportation_drop_off_location    = transportation_details['transportation_drop_off_location'];
                var transportation_pick_up_date         = transportation_details['transportation_pick_up_date'];
                var transportation_drop_of_date         = transportation_details['transportation_drop_of_date'];
                var transportation_total_Time           = transportation_details['transportation_total_Time'];
                var transportation_vehicle_type         = transportation_details['transportation_vehicle_type'];
                var transportation_no_of_vehicle        = transportation_details['transportation_no_of_vehicle'];
                var transportation_price_per_vehicle    = transportation_details['transportation_price_per_vehicle'];
                var transportation_vehicle_total_price  = transportation_details['transportation_vehicle_total_price'];
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                
                var return_transportation_pick_up_location     = transportation_details['return_transportation_pick_up_location'];
                var return_transportation_drop_off_location    = transportation_details['return_transportation_drop_off_location'];
                var return_transportation_drop_of_date         = transportation_details['return_transportation_drop_of_date'];
                var return_transportation_pick_up_date         = transportation_details['return_transportation_pick_up_date'];
                var return_transportation_total_Time           = transportation_details['return_transportation_total_Time'];
                var return_transportation_vehicle_type         = transportation_details['return_transportation_vehicle_type'];
                var return_transportation_no_of_vehicle        = transportation_details['return_transportation_no_of_vehicle'];
                var return_transportation_price_per_vehicle    = transportation_details['return_transportation_price_per_vehicle'];
                var return_transportation_vehicle_total_price  = transportation_details['return_transportation_vehicle_total_price'];
                var return_transportation_price_per_person     = transportation_details['return_transportation_price_per_person'];
                
                if(transportation_trip_type == 'One-Way'){
                    
                    var TO_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Transportation Details</h1>
                                    </div>
                                </div>`;
                    $("#TO_Div").append(TO_Div);
                    
                    var TO_DR = `<br><br><div class="row" id="TO_DR"></div>`;
                    $("#TO_Div").append(TO_DR);
                    var TO_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        <br><br><br>`;
                                    
                    $("#TO_DR").append(TO_Name);
                }
                
                if(transportation_trip_type == 'Return'){
                    var TR_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Transportation Details</h1>
                                        </div>
                                    </div>`;
                    $("#TR_Div").append(TR_Div);
            
                    var TR_DR = `<br><br><div class="row" id="TR_DR"></div>`;
                    $("#TR_Div").append(TR_DR);
                    var TR_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        
                                        <br><br><br>
                                        
                                        <div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Return Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${return_transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${return_transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${return_transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${return_transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${return_transportation_total_Time}</span>
                                            </div>
                                        </div>`;
                                    
                    $("#TR_DR").append(TR_Name);
                }
                
                if(transportation_trip_type == 'All_Round'){
                    
                    var TO_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Transportation Details</h1>
                                    </div>
                                </div>`;
                    $("#TO_Div").append(TO_Div);
                    
                    var TO_DR = `<br><br><div class="row" id="TO_DR"></div>`;
                    $("#TO_Div").append(TO_DR);
                    var TO_Name =   `<div class="col-xl-12">
                                            <div class="mb-3">
                                                <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details :</h3>
                                            </div>
                                        </div>
                                        <br><br>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Pick up : </b></label>
                                                <span>${transportation_pick_up_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of : </b></label>
                                                <span>${transportation_drop_off_location}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>pick up date : </b></label>
                                                <span>${transportation_pick_up_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Drop of date : </b></label>
                                                <span>${transportation_drop_of_date}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Time : </b></label>
                                                <span>${transportation_total_Time}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Vehicle Type : </b></label>
                                                <span>${transportation_vehicle_type}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>No of Vehicle : </b></label>
                                                <span>${transportation_no_of_vehicle}</span>
                                            </div>
                                        </div>
                            
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Price per vehicle : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_vehicle}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price : </b></label>
                                                <span><?php echo $currency ?>${transportation_vehicle_total_price}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                            <div class="mb-3">
                                                <label class="form-label"><b>Total Price per person : </b></label>
                                                <span><?php echo $currency ?>${transportation_price_per_person}</span>
                                            </div>
                                        </div>
                                        <br><br><br>`;
                                    
                    $("#TO_DR").append(TO_Name);
                    
                    No = 1;
                    var TA_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                        <div class="mb-3">
                                          <h1 style="text-decoration: underline;">Transportation Details (${transportation_trip_type})</h1>
                                        </div>
                                    </div>`;
                    $("#TA_Div").append(TA_Div);
                    
                    $.each(transportation_details_more, function(key, value) {
                        var more_transportation_pick_up_location     = value.more_transportation_pick_up_location;
                        var more_transportation_drop_off_location    = value.more_transportation_drop_off_location;
                        var more_transportation_pick_up_date         = value.more_transportation_pick_up_date;
                        
                        var TA_DR = `<br><br><div class="row" id='TA_DR${No}'></div>`;
                        $("#TA_Div").append(TA_DR);
                        var TA_city_Name    =   `<div class="col-xl-12">
                                                    <div class="mb-3">
                                                        <h3 style="font-size: 30px;text-decoration: underline;">Transportation Details (${transportation_trip_type} ${No}):</h3>
                                                    </div>
                                                </div>
                                                <br><br>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Pick up : </b></label>
                                                        <span>${more_transportation_pick_up_location}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>Drop of : </b></label>
                                                        <span>${more_transportation_drop_off_location}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4" style="text-align:center;font-size: 25px;">
                                                    <div class="mb-3">
                                                        <label class="form-label"><b>pick up date : </b></label>
                                                        <span>${more_transportation_pick_up_date}</span>
                                                    </div>
                                                </div>
                                                <br><br>`;
                        $('#TA_DR'+No+'').append(TA_city_Name);
                        No = No + 1;
                    });
                }
            }
        })
    }
    
    function detail_btn_PayTransportation(id){
        const ids = $('.detail-btn-PayTransportation'+id+'').attr('data-id');
        $.ajax({
            url: 'transportation_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var paid_transportation      = data['paid_transportation'];
                paid_transportation          = paid_transportation.toFixed(2);
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days                  = data1['no_of_pax_days'];
                var transportation_details          = JSON.parse(data1['transportation_details']);
                var transportation_price_per_person = transportation_details['transportation_price_per_person'];
                var total_transportation_amount     = parseFloat(transportation_price_per_person) * parseFloat(no_of_pax_days);
                
                $("#tourIdT").val(tourId);
                $("#customer_nameT").val(customer_name);
                $("#package_titleT").val(package_title);
                $("#amount_transportation_paid").val(paid_transportation);
                $("#total_transportation_amount").val(total_transportation_amount);
                
                if(paid_transportation == null || paid_transportation == 0){
                    var remaining_Transportation_amount    = parseFloat(total_transportation_amount) - parseFloat(paid_transportation);
                    remaining_Transportation_amount        = remaining_Transportation_amount.toFixed(2);
                    $('#remaining_transportation_amount').val(remaining_Transportation_amount);
                }
                
                if(paid_transportation !== null){
                    var remaining_Transportation_amount = parseFloat(total_transportation_amount) - parseFloat(paid_transportation);
                    remaining_Transportation_amount        = remaining_Transportation_amount.toFixed(2);
                    $('#remaining_transportation_amount').val(remaining_Transportation_amount);
                }
            }
        })
    }
    
    function detail_btnTTA(id){
        $("#transportation_total_Amount").empty();
        const ids = $('.detail-btnTTA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var transportation_details              = JSON.parse(view_transportation_total_Amount['transportation_details']);
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                var transportation_total_Amount         = parseFloat(no_of_pax_days) * parseFloat(transportation_price_per_person);
                transportation_total_Amount             = transportation_total_Amount.toFixed(2);
                if(transportation_price_per_person != null){
                    $("#transportation_total_Amount").html(transportation_total_Amount);
                }else{
                    $("#transportation_total_Amount").html(0);
                }
            }
        })
    }
    
    function detail_btnTRA(id){
        $("#transportation_recieve_Amount").empty();
        const ids = $('.detail-btnTRA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var transportation_recieve_Amount  = data['view_transportation_recieve_Amount'];
                transportation_recieve_Amount      = transportation_recieve_Amount.toFixed(2);
                $("#transportation_recieve_Amount").html(transportation_recieve_Amount);
            }
        })
    }
    
    function detail_btnTO(id){
        $("#transportation_outsatnding").empty();
        const ids = $('.detail-btnTO'+id+'').attr('data-id');
        $.ajax({
            url: 'view_transportation_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var transportation_recieve_Amount  = data['view_transportation_recieve_Amount'];
                var view_transportation_total_Amount    = data['view_transportation_total_Amount'];
                var no_of_pax_days                      = view_transportation_total_Amount['no_of_pax_days'];
                var transportation_details              = JSON.parse(view_transportation_total_Amount['transportation_details']);
                var transportation_price_per_person     = transportation_details['transportation_price_per_person'];
                var transportation_total_Amount         = parseFloat(no_of_pax_days) * parseFloat(transportation_price_per_person);
                var transportation_outsatnding          = parseFloat(transportation_total_Amount) - parseFloat(transportation_recieve_Amount);
                transportation_outsatnding              = transportation_outsatnding.toFixed(2);
                if(transportation_price_per_person != null){
                    $("#transportation_outsatnding").html(transportation_outsatnding);
                }else{
                    $("#transportation_outsatnding").html(0);
                }
            }
        })
    }
    
    // Visa Client Wise
    function detail_btn_Visa(id){
        $("#V_Div").empty();
        const ids = $('.detail-btn-Visa'+id+'').attr('data-id');
        $.ajax({
            url: 'view_Details_Accomodation/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {    
                // console.log(data);
                var data1                    = data['data'];
                var no_of_pax_days           = data1['no_of_pax_days'];
                var visa_fee                 = data1['visa_fee'];
                var visa_rules_regulations   = data1['visa_rules_regulations'];
                var visa_type                = data1['visa_type'];
                var visa_image               = data1['visa_image'];
                var visa_total_fee           = parseFloat(no_of_pax_days) * parseFloat(visa_fee)
                
                var V_Div  =   `<div class="col-xl-12" style="text-align:center;">
                                    <div class="mb-3">
                                      <h1 style="text-decoration: underline;">Visa Details</h1>
                                    </div>
                                </div>`;
                $("#V_Div").append(V_Div);
    
                var V_DR = `<br><br><div class="row" id="V_DR"></div>`;
                $("#V_Div").append(V_DR);
                var V_Name =   `<div class="col-xl-12">
                                        <div class="mb-3">
                                            <h3 style="font-size: 30px;text-decoration: underline;">Visa Details :</h3>
                                        </div>
                                    </div>
                                    <br><br>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Type : </b></label>
                                            <span>${visa_type}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Fee per Person : </b></label>
                                            <span><?php echo $currency; ?>${visa_fee}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Total Fee : </b></label>
                                            <span><?php echo $currency; ?>${visa_total_fee}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="text-align:center;font-size: 25px;">
                                        <div class="mb-3">
                                            <label class="form-label"><b>Visa Image : </b></label>
                                            <span>${visa_image}</span>
                                            <img src="/public/uploads/package_imgs/${visa_image}" style="width:433px;height:250px" />
                                        </div>
                                    </div>`;
                                
                $("#V_DR").append(V_Name);
                
            }
        })
    }
    
    function detail_btn_PayVisa(id){
        const ids = $('.detail-btn-PayVisa'+id+'').attr('data-id');
        $.ajax({
            url: 'visa_Pay/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {    
                // console.log(data);
                var data1                    = data['data'];
                var customer_Data            = data['customer_Data'];
                var tourId                   = data1['id'];
                var package_title            = data1['title'];
                var name                     = customer_Data['name'];
                var lname                    = customer_Data['lname'];
                var customer_name            = name +" "+ lname ;
                var no_of_pax_days           = data1['no_of_pax_days'];
                var visa_total_fee           = data1['visa_fee'];
                var paid_visa                = data['paid_visa'];
                paid_visa                    = paid_visa.toFixed(2);
                var total_visa_amount        = parseFloat(no_of_pax_days) * parseFloat(visa_total_fee);
                
                $("#tourIdV").val(tourId);
                $("#customer_nameV").val(customer_name);
                $("#package_titleV").val(package_title);
                $("#total_visa_amount").val(total_visa_amount);
                $("#amount_visa_paid").val(paid_visa);
                
                if(paid_visa == null || paid_visa == 0){
                    var remaining_visa_amount    = parseFloat(total_visa_amount) - parseFloat(paid_visa);
                    remaining_visa_amount        = remaining_visa_amount.toFixed(2);
                    $('#remaining_visa_amount').val(remaining_visa_amount);
                    
                }
                
                if(paid_visa !== null){
                    var remaining_visa_amount    = parseFloat(total_visa_amount) - parseFloat(paid_visa);
                    remaining_visa_amount        = remaining_visa_amount.toFixed(2);
                    $('#remaining_visa_amount').val(remaining_visa_amount);
                }
            }
        })
    }
    
    function detail_btnVTA(id){
        $("#visa_total_Amount").empty();
        const ids = $('.detail-btnVTA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var view_visa_total_Amount      = data['view_visa_total_Amount'];
                var no_of_pax_days              = view_visa_total_Amount['no_of_pax_days'];
                var visa_fee                    = view_visa_total_Amount['visa_fee'];
                
                var visa_total_Amount           = parseFloat(no_of_pax_days) * parseFloat(visa_fee);
                visa_total_Amount               = visa_total_Amount.toFixed(2);
                if(visa_fee != null){
                    $("#visa_total_Amount").html(visa_total_Amount);   
                }else{
                    $("#visa_total_Amount").html(0);
                }
            }
        })
    }
    
    function detail_btnVRA(id){
        $("#visa_recieve_Amount").empty();
        const ids = $('.detail-btnVRA'+id+'').attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                // console.log(data);
                var visa_recieve_Amount    = data['view_visa_recieve_Amount'];
                visa_recieve_Amount        = visa_recieve_Amount.toFixed(2);
                $("#visa_recieve_Amount").html(visa_recieve_Amount);
            }
        })
    }
      
    function detail_btnVO(id){
        $("#visa_outsatnding").empty();
        const ids = $('.detail-btnVO'+id+'').attr('data-id');
        $.ajax({
            url: 'view_visa_total_Amount/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var view_visa_total_Amount      = data['view_visa_total_Amount'];
                var visa_recieve_Amount         = data['view_visa_recieve_Amount'];
                
                var no_of_pax_days              = view_visa_total_Amount['no_of_pax_days'];
                var visa_fee                    = view_visa_total_Amount['visa_fee'];
                var visa_total_Amount           = parseFloat(no_of_pax_days) * parseFloat(visa_fee);
                var visa_outsatnding            = parseFloat(visa_total_Amount) - parseFloat(visa_recieve_Amount);
                visa_outsatnding                = visa_outsatnding.toFixed(2);
                if(visa_fee != null){
                    $("#visa_outsatnding").html(visa_outsatnding);
                }else{
                    $("#visa_outsatnding").html(0);
                }
                
            }
        })
      }
    
</script>

@stop

@section('slug')

@stop
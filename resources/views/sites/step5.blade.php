<div id="offer-part" class="content" role="tabpanel" aria-labelledby="offer-part-trigger">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Brokrage Information</label>
                                                <select name="brokrage_type" id="brokrage_type" class="form-control select2" style="width: 100%;">
                                                   <option value="" selected="selected">Select Brokrage Type</option>
                                                   <option @if (isset($Sites->brokrage_type)) {{ $Sites->brokrage_type == 1 ? 'selected' : '' }} @endif value="1"> Salary Fix Pay per Month  </option>
                                                   <option @if (isset($Sites->brokrage_type)) {{ $Sites->brokrage_type == 0 ? 'selected' : '' }} @endif value="0"> Amount and Percentage</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group" @if (isset($Sites->brokrage_type)) style="{{ $Sites->brokrage_type == 1 ? 'display:block' : 'display:none' }}"  @endif  id="fixpay" >
                                                <label>FOR SALARY AND FIX PAY SELECTION </label>
                                                <!-- <input type="text" name="brokrage_amount" class="form-control" placeholder="Enter Salary/Fix Pay"> -->

                                                {!! Form::text('brokrage_amount',  (isset($Sites->brokrage_amount) ? $Sites->brokrage_amount : old('brokrage_amount') ), array('placeholder' => 'Enter Salary/Fix Pay','class' => 'form-control')) !!}


                                             </div>
                                          </div>
                                          <hr /> 
                                          <div class="col-md-12" id="percentage" @if (isset($Sites->brokrage_type)) style="{{ $Sites->brokrage_type == 0 ? 'display:block' : 'display:none' }}"  @endif >   
                                             <div class="col-md-6">
                                                <label>FOR AMOUNT AND PERCENTAGE SELECTION </label>
                                                <div class="form-group">
                                                   <label>Amount </label>
                                                   <!-- <input type="text" name="brokrage_percent" class="form-control" placeholder="Enter Amount"> -->

                                                   {!! Form::text('brokrage_percent_amount',  (isset($Sites->brokrage_percent_amount) ? $Sites->brokrage_percent_amount : old('brokrage_percent_amount') ), array('placeholder' => 'Enter Amount','class' => 'form-control')) !!}


                                                </div>
                                             </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Percentage </label>

                                                   {!! Form::text('brokrage_percent',  (isset($Sites->brokrage_percent) ? $Sites->brokrage_percent : old('brokrage_percent') ), array('placeholder' => 'Enter Amount','class' => 'form-control')) !!}
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                  
                                      <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button> 
                                         <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
<div id="information-part" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                                    <div class="row">
                                          <div class="col-md-3">
                                             <div class="form-group">
                                                <label>Whom to Call ?</label>
                                                
                                             </div>
                                          </div>
                                          <div class="col-md-9">
                                             <div class="form-group">
                                                <select id="whom_to_call" name="whom_to_call" class="form-control">
                                                   <option value="">Select Whom To Call</option>
                                                   <option @if (isset($Sites->whom_to_call)) {{ $Sites->whom_to_call == 0 ? 'selected' : '' }} @endif value="0">Company</option>
                                                   <option @if (isset($Sites->whom_to_call)) {{ $Sites->whom_to_call == 1 ? 'selected' : '' }} @endif value="1">Marketing Person</option>
                                                </select>
                                             </div>
                                          </div>      
                                    </div>            





                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Contact Person Name</label>
                                                 {!! Form::text('contact_person_name', (isset($Sites) ? $Sites->contact_person_name:'' ), array('placeholder' => 'Marketing person name','class' => 'form-control')) !!}
                                             </div>
                                             <div class="form-group">
                                                <label>Contact Person Email</label>
                                                {!! Form::text('contact_person_email', (isset($Sites) ? $Sites->contact_person_email:'' ), array('placeholder' => 'Marketing person email','class' => 'form-control')) !!}
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Contact Person Phone</label>
                                                {!! Form::text('contact_person_phone', (isset($Sites) ? $Sites->contact_person_phone:'' ), array('placeholder' => 'Marketing person no','class' => 'form-control')) !!}
                                             </div>
                                             <div class="form-group">
                                                <label>Website URL</label>
                                               
                                                {!! Form::text('website_url', (isset($Sites) ? $Sites->website_url:'' ), array('placeholder' => 'Enter Website URL','class' => 'form-control')) !!}

                                             </div>
                                          </div>
                                       </div> 
                                       <hr />
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="exampleSelectBorderWidth2">Loan Approval <span style="color: red;"> *</span> </label>
                                                 <select name="loan_approval" id="loanapproval" class="form-control">
                                                      <option value="">Select Approval</option>
                                                      <option @if (isset($Sites->loan_approval)) {{ $Sites->loan_approval == 1 ? 'selected' : '' }} @endif value="1">Available</option>
                                                      <option @if (isset($Sites->loan_approval)) {{ $Sites->loan_approval == 0 ? 'selected' : '' }} @endif value="0">Not Available</option>
                                                 </select>
                                             </div>
                                          </div>
                                          <div class="col-md-6" id="loanBank" @if (isset($Sites->loan_approval)) style="{{ $Sites->loan_approval == 1 ? 'display:block' : '' }}"  @endif>
                                             <div class="form-group">
                                                @php
                                                $selected_banks = array();
                                                if (isset($Sites->siteLoans)) {
                                                    foreach ($Sites->siteLoans as $k => $v) {
                                                        $selected_banks[] = $v->bank_id;
                                                    }
                                                }
                                            @endphp

                                                <label>Select Banks</label>

                                                {!! Form::select('banks[loan][]', $Banks, $selected_banks, ['class' => 'form-control select2', 'multiple' => 'multiple','style'=>'width:100%;']) !!}


                                               
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="exampleSelectBorderWidth2"> Possesion Status <span style="color: red;"> *</span></label>
                                                <select id="possession_status" name="possession_status" class="form-control">
                                                   <option value="">Select Possesion</option>
                                                   <option @if (isset($Sites->possession_status)) {{ $Sites->possession_status == 0 ? 'selected' : '' }} @endif value="0">Ready to move</option>
                                                   <option @if (isset($Sites->possession_status)) {{ $Sites->possession_status == 1 ? 'selected' : '' }} @endif value="1">Under Construction</option>
                                                </select>
                                                 
                                             </div>
                                             <div id="possesion_set" @if (isset($Sites->possession_status)) style="{{ $Sites->possession_status == 1 ? 'display:block' : 'display:none' }}" @endif  class="form-group">
                                                <label>Possesion Date:</label>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      <!-- <select class="form-control select2" style="width: 100%;">
                                                         <option selected="selected">Select Year</option>
                                                         <option>Year</option>
                                                         <option>Year</option>
                                                      </select> -->
                                                      {!! Form::select('possession_month', $possesion_month, $possesion_month, ['class' => 'form-control select2','style'=>'width:100%;']) !!}


                                                   </div>
                                                   <div class="col-md-6">
                                                      {!! Form::select('possession_year', $possesion_year,  null, ['class' => 'form-control select2','style'=>'width:100%;']) !!}
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div   class="form-group">
                                                <label>Sample House available</label>
                                                {!! Form::select('sample_house', $sample_house, (isset($Sites->sample_house) ? $Sites->sample_house :''), ['class' => 'form-control', 'id' => 'sample_house_available','style'=>'width:100%;']) !!} 
                                             </div>

                                          </div>
                                          <div id="is_sample_house_available"  class="col-md-6">
                                             <div class="form-group">
                                                <label>When sample house will be available ?</label>
                                                <div class="row">
                                                   <div class="col-md-6">
                                                      
                                                      {!! Form::select('sample_house_month', $sample_house_month, null, ['class' => 'form-control cselect']) !!}


                                                   </div>
                                                   <div class="col-md-6">
                                                     {!! Form::select('sample_house_year', $sample_house_year, null, ['class' => 'form-control cselect']) !!}
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                   <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
            
                                    </div>
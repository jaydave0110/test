 <div id="primary-part" class="content" role="tabpanel" aria-labelledby="primary-part-trigger">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="exampleInputEmail1"> Site Name <span style="color: red;"> *</span></label>
                                                {!! Form::text('site_name',  (isset($Sites->site_name) ? $Sites->site_name : old('site_name') ), array('placeholder' => 'Enter project or site name','class' => 'form-control')) !!}
                                             </div>
                                             <div class="form-group">
                                                <label class="form-control-label">Youtube Link:</label>
								                    <div class="input-group">
								                        <div class="input-group-addon">https://www.youtube.com/watch?v=</div>
								                        {!! Form::text('video_link', (isset($Sites) ?$Sites->video_link:'' ), array('placeholder' => 'Video','class' => 'form-control')) !!}
								                    </div>
                                             </div>
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Short Description</label>
                                                {!! Form::textarea('description', (isset($Sites) ? $Sites->description:'' ), array('placeholder' => 'Site description','class' => 'form-control', 'rows' => 2)) !!}
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="exampleInputFile">Site Brochure</label>
                                                <div class="custom-file">
              							                      <input type="file" class="custom-file-input" id="customFile" name="brochure">
              							                      <label class="custom-file-label" for="customFile">Choose file</label>

              							                    </div>
                                             </div>
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">RERA Number</label>
                                               {!! Form::text('rera_no', (isset($Sites) ?$Sites->rera_no:'' ), ['placeholder' => 'Enter RERA Number.','class' => 'form-control']) !!}
                                             </div>
                                             <div class="form-group">
                                                <label for="exampleInputFile">RERA Certificate</label>
                                                 <div class="custom-file">
      							                      <input type="file" name="rera_certificate" class="custom-file-input" id="customFile">
      							                      <label class="custom-file-label" for="customFile">Choose file</label>
      							                    </div>
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                       <div class="row">
                                          
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label>State <span style="color: red;"> *</span></label>
                                                    


                                                  {!! Form::select('state_id', $state, (isset($Sites) ?$Sites->state_id:'' ), ['class' => 'form-control select2', 'id' => 'ajaxState' ,'placeholder'=>'Select State','selected'=>'isset($Sites->state_id) ?selected:null'  ]) !!}
                                                 

                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label>City <span style="color: red;"> *</span></label>
                                                <!-- <select id="ajaxCity" name="city_id" class="form-control select2" style="width: 100%;">
                                                   <option selected="selected">Select City</option>
                                                </select> -->

                                                 {!! Form::select('city_id', $city, (isset($Sites) ?$Sites->city_id:'' ), ['class' => 'form-control select2', 'id' => 'ajaxCity' ,'selected'=>'isset($Sites->city_id) ?selected:null'  ]) !!}


                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label>Area <span style="color: red;"> *</span></label>
                                                 
                                             {!! Form::select('area_id', $area, (isset($Sites) ?$Sites->area_id:'' ), ['class' => 'form-control select2', 'id' => 'ajaxArea',  'selected'=>'isset($Sites->area_id) ?selected:null']) !!}



                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Site Address</label>
                                                {!! Form::text('address', (isset($Sites) ? $Sites->address:'' ), array('placeholder' => 'Full Address','class' => 'form-control', 'id' => 'site_address')) !!}
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Site Latitude <span style="color: red;"> *</span></label>
                                                {!! Form::text('latitude', (isset($Sites) ? $Sites->latitude:'' ), array('placeholder' => 'Site latitude','class' => 'form-control', 'id' => 'site_latitude')) !!}
                                             </div>
                                          </div>
                                          <div class="col-md-4">
                                             <div class="form-group">
                                                <label for="exampleInputEmail1">Site Longitude <span style="color: red;"> *</span></label>
                                                {!! Form::text('longitude', (isset($Sites) ? $Sites->longitude:'' ), array('placeholder' => 'Site longitude','class' => 'form-control', 'id' => 'site_longitude')) !!}
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                        <div class="row">
                                          <div class="col-md-12">
                                             <label for="exampleInputEmail1">Price Status <span style="color: red;"> *</span> </label>
                                          {!! Form::select('price_status', $price_status, (isset($Sites) ? $Sites->price_status:'' ), ['class' => 'form-control cselect']) !!}
                                          </div>
                                        </div>
                                         <hr />
                                       <div class="row">
                                           <div class="card col-md-12">
                                        <div class="alert alert-warning">Note: each individual photo size must be less than 4 MB.</div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <div class="file-uploader-container">
                                                        <div class="file-upload-inprogress hide">Uploading...</div>
                                                        <div class="file-uploader">+ Upload Photos</div>
                                                        @if (isset($Sites))
                                                            <input type="file" name="tmp_image[]" multiple="multiple" data-type="buildersites" class="ajaxSiteImageUpload" data-siteid="{{ $Sites->id }}" />
                                                        @else
                                                            <input type="file" name="tmp_image[]" multiple="multiple" data-type="buildersites" class="ajaxQuickImageUpload" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="builderSitesImagePreview"></div>

                                            @foreach ($sitePhotoType as $key => $val)
                                                @if (isset($Sites) && isset($Sites->siteImages))
                                                    @if (count($Sites->siteImages) > 0)
                                                        @if (\Helpers::typePhotoAvailable($Sites->siteImages, $key))
                                                            <h3 class="block-heading">{{ $val }}</h3>
                                                            <hr size="pixels">
                                                            <div class="row">
                                                            @foreach ($Sites->siteImages as $i => $image)
                                                                @if ($image->image_type == $key)
                                                                    {!! \Helpers::displaySiteImages($image, $i, $Sites->id) !!}
                                                                @endif
                                                            @endforeach
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endforeach
                                            
                                        </div>
                                    </div>
                                       </div>
                                       <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                       @if ($mode == 'edit')
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i>&nbsp;&nbsp; Save & Exit
                                            </button>
                                        @endif

                                    </div>
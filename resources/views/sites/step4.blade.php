<div id="specification-part" class="content" role="tabpanel" aria-labelledby="specification-part-trigger">
                                       <div class="row">
                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Water supply</label>
                                                <select name="water_supply" class="form-control select2" style="width: 100%;">
                                                   <option value="" selected="selected">Select Option</option>
                                                   <option @if (isset($Sites->water_supply)) {{ $Sites->water_supply == 1 ? 'selected' : '' }} @endif value="1">Yes, Available</option>
                                                   <option @if (isset($Sites->water_supply)) {{ $Sites->water_supply == 0 ? 'selected' : '' }} @endif value="0">Not Available</option>
                                                </select> 
                                             </div>
                                          </div>

                                          <div class="col-md-6">
                                             <div class="form-group">
                                                <label>Power backup:</label>
                                                <select name="power_backup" class="form-control select2" style="width: 100%;">
                                                   <option selected="selected" value="">Select Option</option>
                                                   <option @if (isset($Sites->power_backup)) {{ $Sites->power_backup == 1 ? 'selected' : '' }} @endif value="1">Yes, Available</option>
                                                   <option @if (isset($Sites->power_backup)) {{ $Sites->power_backup == 0 ? 'selected' : '' }} @endif value="0">Not Available</option>
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                       <div class="row">
                                          <div class="col-12">
                                             <h4>Specifications</small></h4>
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-12 col-sm-12">
                                             <div class="card card-primary card-tabs">
                                                <div class="card-header p-0 pt-1">
                                                   <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                      <li class="nav-item">
                                                         <a class="nav-link active" id="custom-tabs-one-flooring-tab" data-toggle="pill" href="#custom-tabs-one-flooring" role="tab" aria-controls="custom-tabs-one-flooring" aria-selected="true">Flooring details</a>
                                                      </li>
                                                      <li class="nav-item">
                                                         <a class="nav-link" id="custom-tabs-one-fittings-tab" data-toggle="pill" href="#custom-tabs-one-fittings" role="tab" aria-controls="custom-tabs-one-fittings" aria-selected="false">Fittings details</a>
                                                      </li>
                                                      <li class="nav-item">
                                                         <a class="nav-link" id="custom-tabs-one-wall-tab" data-toggle="pill" href="#custom-tabs-one-wall" role="tab" aria-controls="custom-tabs-one-wall" aria-selected="false">Wall Details</a>
                                                      </li>
                                                      <li class="nav-item">
                                                         <a class="nav-link" id="custom-tabs-one-usp-tab" data-toggle="pill" href="#custom-tabs-one-usp" role="tab" aria-controls="custom-tabs-one-usp" aria-selected="false">USP</a>
                                                      </li>
                                                   </ul>
                                                </div>
                                                <div class="card-body">
                                                   <div class="tab-content" id="custom-tabs-one-tabContent">
                                                      <div class="tab-pane fade show active" id="custom-tabs-one-flooring" role="tabpanel" aria-labelledby="custom-tabs-one-flooring-tab">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Balcony</label>
                                                                  {!! Form::text('specs[specification_flooring_balcony]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_balcony') : ''), array('placeholder' => 'Balcony flooring','class' => 'form-control')) !!}

                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Bathroom</label>
                                                                  {!! Form::text('specs[specification_flooring_bathroom]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_bathroom') : ''), array('placeholder' => 'Bathroom flooring','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Living room</label>
                                                                  {!! Form::text('specs[specification_flooring_livingroom]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_livingroom') : ''), array('placeholder' => 'Living room flooring','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Master bedroom:</label>
                                                                  {!! Form::text('specs[specification_flooring_master_bedroom]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_master_bedroom') : ''), array('placeholder' => 'Master bedroom flooring','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Kitchen</label>
                                                                  {!! Form::text('specs[specification_flooring_kitchen]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_kitchen') : ''), array('placeholder' => 'Kitchen flooring','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Bedroom</label>
                                                                  {!! Form::text('specs[specification_flooring_bedroom]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_bedroom') : ''), array('placeholder' => 'Bedroom flooring','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Terrace</label>
                                                                  {!! Form::text('specs[specification_flooring_terrace]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_flooring_terrace') : ''), array('placeholder' => 'Terrace flooring','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="tab-pane fade" id="custom-tabs-one-fittings" role="tabpanel" aria-labelledby="custom-tabs-one-fittings-tab">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Doors</label>
                                                                  {!! Form::text('specs[specification_fitting_doors]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_doors') : ''), array('placeholder' => 'Doors fitting','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Electrical</label>
                                                                   {!! Form::text('specs[specification_fitting_electrical]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_electrical') : ''), array('placeholder' => 'Electrical fitting','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Bathroom</label>
                                                                  {!! Form::text('specs[specification_fitting_bathroom]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_bathroom') : ''), array('placeholder' => 'Bathroom fitting','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Sink</label>
                                                                  {!! Form::text('specs[specification_fitting_sink]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_sink') : ''), array('placeholder' => 'Sink fitting','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Kitchen</label>
                                                                  {!! Form::text('specs[specification_fitting_kitchen_platform]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_kitchen_platform') : ''), array('placeholder' => 'Kitchen platform fitting','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Windows</label>
                                                                  {!! Form::text('specs[specification_fitting_windows]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_windows') : ''), array('placeholder' => 'Windows fitting','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Toilet</label>
                                                                  {!! Form::text('specs[specification_fitting_toilet]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_fitting_toilet') : ''), array('placeholder' => 'Toilet fitting','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="tab-pane fade" id="custom-tabs-one-wall" role="tabpanel" aria-labelledby="custom-tabs-one-wall-tab">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Exterior</label>
                                                                   {!! Form::text('specs[specification_walls_exterior]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_walls_exterior') : ''), array('placeholder' => 'Exterior walls','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Kitchen</label>
                                                                 {!! Form::text('specs[specification_walls_kitchen]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_walls_kitchen') : ''), array('placeholder' => 'Kitchen walls','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Balcony</label>
                                                                    {!! Form::text('specs[specification_walls_balcony]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_walls_balcony') : ''), array('placeholder' => 'Balcony walls','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>Interior</label>
                                                                  {!! Form::text('specs[specification_walls_interior]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_walls_interior') : ''), array('placeholder' => 'Interior walls','class' => 'form-control')) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>Toilet</label>
                                                                  {!! Form::text('specs[specification_walls_toilet]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'specification_walls_toilet') : ''), array('placeholder' => 'Toilet walls','class' => 'form-control')) !!}
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>

                                                      <div class="tab-pane fade" id="custom-tabs-one-usp" role="tabpanel" aria-labelledby="custom-tabs-one-usp-tab">
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>USP One</label>
                                                                  {!! Form::text('specs[usp_one]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_one') : ''), array('placeholder' => 'USP One','class' => 'form-control','maxlength' => 80)) !!}

                                                               </div>
                                                                 
                                                               <div class="form-group">
                                                                  <label>USP two</label>
                                                                  {!! Form::text('specs[usp_two]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_two') : ''), array('placeholder' => 'USP Two','class' => 'form-control','maxlength' => 80)) !!}
                                                                   
                                                               </div>

                                                               <div class="form-group">
                                                                  <label>USP Three</label>
                                                                  {!! Form::text('specs[usp_three]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_three') : ''), array('placeholder' => 'USP Three','class' => 'form-control','maxlength' => 80)) !!}
                                                                   
                                                               </div>

                                                               <div class="form-group">
                                                                  <label>USP Seven</label>
                                                                  {!! Form::text('specs[usp_seven]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_seven') : ''), array('placeholder' => 'USP Seven','class' => 'form-control','maxlength' => 80)) !!}
                                                                   
                                                               </div>

                                                            </div>
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                  <label>USP Four</label>
                                                                  {!! Form::text('specs[usp_four]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_four') : ''), array('placeholder' => 'USP Four','class' => 'form-control','maxlength' => 80)) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>USP Five</label>
                                                                  {!! Form::text('specs[usp_five]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_five') : ''), array('placeholder' => 'USP Five','class' => 'form-control','maxlength' => 80)) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>USP Six</label>
                                                                  {!! Form::text('specs[usp_six]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_six') : ''), array('placeholder' => 'USP Six','class' => 'form-control','maxlength' => 80)) !!}
                                                               </div>
                                                               <div class="form-group">
                                                                  <label>USP Seven</label>
                                                                  {!! Form::text('specs[usp_eight]', (isset($Sites->metas) ? \Helpers::chkSpecs($Sites->metas, 'usp_eight') : ''), array('placeholder' => 'USP Eight','class' => 'form-control','maxlength' => 80)) !!}
                                                                   
                                                               </div>

                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- /.card -->
                                             </div>
                                          </div>
                                       </div>

                                       <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button> 
                                         <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
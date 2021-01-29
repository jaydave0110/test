<div class="card-header">
                        <h3 class="card-title">Add Property Information</h3>&nbsp;&nbsp;&nbsp;&nbsp; <span style="color: red;">* Are Required Fields</span>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Property Code</label>
                                 {!! Form::text('code', (isset($properties->code)?$properties->code:''), array('placeholder' => 'Property Code','class' => 'form-control','required'=>'required')) !!}
                              </div>
                              <div class="form-group">
                                 <label  >Site Name</label>
                                
                                 {!! Form::text('site_name', (isset($Sites->site_name)?$Sites->site_name:''), array('placeholder' => 'Site  Name','class' => 'form-control','readonly'=>'readonly')) !!}
                                 <input type="hidden"  name="site_id" value="{{$Sites->id}}">

                              </div>
                              <div class="form-group">
                                 <label  >Property Type <span style="color: red;">*</span></label>
                                  {!! Form::select('cat_id', $propertyCategory, null, ['class' => 'form-control select2', 'placeholder' => 'Select Option', 'id' => 'ajaxPropertyCategory']) !!}
                              </div>
                              <div class="form-group">
                                 <label  >Property Status <span style="color: red;">*</span></label>
                                 {!! Form::select('status', [''=>'Select','1'=>'Active','2'=>'Soldout'], (isset($properties->status) ? $properties->status : null), ['class' => 'form-control select2']) !!}
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                
                                 <label  >Sub Title <span style="color: red;">*</span></label>
                                  {!! Form::text('sub_title',(isset($properties->sub_title) ? $properties->sub_title : '') , array('placeholder' => 'Sub Title','class' => 'form-control')) !!}
                              </div>
                              <div class="form-group">
                                 @php
                                    if (isset($properties)) {
                                        if (isset($properties->cat_id)) {
                                            if (in_array($properties->cat_id, [2,3])) {
                                                $selected_option = [];
                                                foreach ($properties->propertiesUnitCategory as $category) {
                                                    $selected_option[] = $category->sub_cat_id;
                                                }
                                            } else {
                                                $selected_option[] = $properties->sub_cat_id;
                                            }
                                        } else {
                                            $selected_option = ['' => 'Select Option'];
                                        }
                                        
                                        $multiple = '';
                                        if (in_array($properties->cat_id, [2,3])) {
                                            $multiple = 'multiple';
                                        }
                                    }
                                @endphp 

                                 <label  >Property Category </label>
                                 <div id="ajaxPropertySubCategoryContainer">

                                 

                                        {!! Form::select('sub_cat_id',$propertySubCategory 
                                         , (isset($properties->sub_cat_id) ? $properties->sub_cat_id : null), ['class' => 'form-control select2','id'=>'ajaxPropertySubCategory']) !!} 
                                  
                                </div>
                              </div>
                              <div class="form-group">
                                
                                 <label  >Is Featured Property?  <span style="color: red;">*</span></label>
                                  {!! Form::select('is_featured', [''=>'Select','1'=>'Yes','0'=>'No'], (isset($properties->is_featured) ? $properties->is_featured : null), ['class' => 'form-control select2']) !!} 

 
                              </div>
                              <div class="form-group">
                                
                                 <label  >Is Popular Property?   <span style="color: red;">*</span></label>

                                  

                                 {!! Form::select('is_popular', [''=>'Select','1'=>'Yes','0'=>'No'], (isset($properties->is_popular) ? $properties->is_popular : null), ['class' => 'form-control select2']) !!} 

                                    
                              </div>

                           </div>
                        </div>
                     </div>
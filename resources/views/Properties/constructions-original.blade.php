<div class="card card-default">
                        <div class="card-header">
                           <h3 class="card-title"> Construction layout</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <div class="form-group">
                                       <label  >No of Bedroom</label>
                                       @if (isset($properties->propertyFeatures->bedrooms) && $properties->propertyFeatures->bedrooms != null)
                                           {{ $properties->propertyFeatures->bedrooms }}
                                           <input type="hidden" name="features[bedrooms]" value="{{ $properties->propertyFeatures->bedrooms }}" />
                                       @else
                                           {!! Form::select('features[bedrooms]', $bedrooms, (isset($properties->propertyFeatures->bedrooms) ? $properties->propertyFeatures->bedrooms : ''), ['class' => 'form-control cselect', 'id' => 'no_of_bedrooms']) !!}
                                       @endif
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <div class="form-group">
                                       <label  >No of Bathroom</label>
                                       @if (isset($properties->propertyFeatures->bathrooms) && $properties->propertyFeatures->bathrooms != null)
                                           {{ $properties->propertyFeatures->bathrooms }}
                                           <input type="hidden" name="features[bathrooms]" value="{{ $properties->propertyFeatures->bathrooms }}" />
                                       @else
                                           {!! Form::select('features[bathrooms]', $bathrooms, (isset($properties->propertyFeatures->bathrooms) ? $properties->propertyFeatures->bathrooms : ''), ['class' => 'form-control cselect']) !!}
                                       @endif
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label  >No of Balcony</label>
                                    @if (isset($properties->propertyFeatures->balconies) && $properties->propertyFeatures->balconies != null)
                                        {{ $properties->propertyFeatures->balconies }}
                                        <input type="hidden" name="features[balconies]" value="{{ $properties->propertyFeatures->balconies }}" />
                                    @else
                                        {!! Form::select('features[balconies]', $balconies, (isset($properties->propertyFeatures->balconies) ? $properties->propertyFeatures->balconies : ''), ['class' => 'form-control cselect']) !!}
                                    @endif
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-flats">
                                 <div class="form-group">
                                    <label>No of Towers:</label>
                                    @if (isset($properties->propertyFeatures->no_of_towers) && $properties->propertyFeatures->no_of_towers != null )
                                       <!--  {{ $properties->propertyFeatures->no_of_towers }}
                                        <input type="hidden" name="features[no_of_towers]" value="{{ $properties->propertyFeatures->no_of_towers }}" /> -->
                                         {!! Form::text('features[no_of_towers]', (isset($properties->propertyFeatures->no_of_towers) ? $properties->propertyFeatures->no_of_towers : ''), ['class' => 'form-control','placeholder' => 'No of Towers']) !!}
                                    @else 
                                    {!! Form::text('features[no_of_towers]',null, ['class' => 'form-control','placeholder' => 'No of Towers']) !!}    
                                    
                                    @endif  
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-flats">
                                 <div class="form-group">
                                    <label>No of Flats:</label>
                                    @if (isset($properties->propertyFeatures->no_of_flats) && $properties->propertyFeatures->no_of_flats != null )
                                        <!-- {{ $properties->propertyFeatures->no_of_flats }}
                                        <input type="hidden" name="features[no_of_flats]" value="{{ 
                                        $properties->propertyFeatures->no_of_flats }}" /> -->
                                        {!! Form::text('features[no_of_flats]', (isset($properties->propertyFeatures->no_of_flats) ? $properties->propertyFeatures->no_of_flats : ''), ['class' => 'form-control','placeholder' => 'No of Flats']) !!} 
                                    @else
                                            {!! Form::text('features[no_of_flats]',null, ['class' => 'form-control','placeholder' => 'No of Flats']) !!}    
                                        
                                    @endif
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Foyer area:</label>
                                    {!! Form::text('features[foyer_area]', (isset($properties->propertyFeatures->foyer_area) ? $properties->propertyFeatures->foyer_area : ''), array('class' => 'form-control', 'placeholder' => 'Foyer area')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Store room:</label>
                                    {!! Form::text('features[store_room]', (isset($properties->propertyFeatures->store_room) ? $properties->propertyFeatures->store_room : ''), array('class' => 'form-control', 'placeholder' => 'Store room area')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Pooja room:</label>
                                    {!! Form::text('features[pooja_room]', (isset($properties->propertyFeatures->pooja_room) ? $properties->propertyFeatures->pooja_room : ''), array('class' => 'form-control', 'placeholder' => 'Pooja room area')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Study room:</label>
                                    {!! Form::text('features[study_room]', (isset($properties->propertyFeatures->study_room) ? $properties->propertyFeatures->study_room : ''), array('class' => 'form-control', 'placeholder' => 'Study room area')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all ptype-commercial-all ptype-industrial-all">
                                 <div class="form-group">
                                    <label>Parking area:</label>
                                    {!! Form::text('features[parking_area]', (isset($properties->propertyFeatures->parking_area) ? $properties->propertyFeatures->parking_area : ''), array('class' => 'form-control', 'placeholder' => 'Parking area')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-housevilla">
                                 <div class="form-group">
                                    <label>Total Unit:</label>
                                     {!! Form::text('features[total_unit]', (isset($properties->propertyFeatures->total_unit) ? $properties->propertyFeatures->total_unit : ''), array('class' => 'form-control', 'placeholder' => 'Total Unit')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-housevilla">
                                 <div class="form-group">
                                    <label>Open sides:</label>
                                    {!! Form::text('features[open_sides]', (isset($properties->propertyFeatures->open_sides) ? $properties->propertyFeatures->open_sides : ''), array('class' => 'form-control', 'placeholder' => 'Total open sides')) !!}
                                 </div>
                              </div>
                              <div class="col-md-3 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Servant room:</label>
                                    {!! Form::text('features[servant_room]', (isset($properties->propertyFeatures->servant_room) ? $properties->propertyFeatures->servant_room : ''), array('class' => 'form-control', 'placeholder' => 'Servant room area')) !!}
                                 </div>
                              </div>

                              <div class="col-md-3 ptype-commercial-all">
                                 <div class="form-group">
                                    <label>Total Floors:</label>
                                    {!! Form::select('features[total_floors]', $total_floors, (isset($properties->propertyFeatures->total_floors) ? $properties->propertyFeatures->total_floors : ''), ['class' => 'form-control cselect', 'id' => 'total_floors']) !!}
                                 </div>
                              </div>


                           </div>
                        </div>
                     </div>
                     <div class="card card-default ptype-residential-all">
                        <div class="card-header">
                           <h3 class="card-title">Living room</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Living room area:</label>
                                    {!! Form::text('meta[living_room_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_area') : ''), array('class' => 'form-control', 'placeholder' => "Total area (Sq. Ft.)")) !!}
                                 </div>
                              </div>
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Living room balcony area:</label>
                                    {!! Form::text('meta[living_room_balcony]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_balcony') : ''), array('class' => 'form-control', 'placeholder' => "Balcony area")) !!}
                                 </div>
                              </div>
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Living room bathroom area:</label>
                                    {!! Form::text('meta[living_room_bathroom]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_bathroom') : ''), array('class' => 'form-control', 'placeholder' => "Bathroom area")) !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card card-default ptype-residential-all">
                        <div class="card-header">
                           <h3 class="card-title">Dinning</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Attached with living room:</label><br />
                                    {{ Form::checkbox('meta[dining_attached_with_living_room]', 'yes', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'dining_attached_with_living_room') : ''), ['class' => 'form-check-input']) }} &nbsp; Yes &nbsp;
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label>Attached with Kitchen:</label><br />
                                    {{ Form::checkbox('meta[dining_attached_with_kitchen]', 'yes', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'dining_attached_with_kitchen') : ''), ['class' => 'form-check-input']) }} &nbsp; Yes &nbsp;
                                 </div>
                              </div>
                              <div class="col-md-4 ptype-residential-all">
                                 <div class="form-group">
                                    <label>Seperate Dinning area:</label>
                                    {!! Form::text('meta[seperate_dining]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'seperate_dining') : ''), array('class' => 'form-control', 'placeholder' => "if dinning is seperate then enter dinning area")) !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card card-default ptype-residential-all">
                        <div class="card-header">
                           <h3 class="card-title">Kitchen</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Kitchen total area:</label>
                                    {!! Form::text('meta[kitchen_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'kitchen_area') : ''), array('class' => 'form-control', 'placeholder' => "Total area (Sq. Ft.)")) !!}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Kitchen wash area:</label>
                                    {!! Form::text('meta[kitchen_wash_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'kitchen_wash_area') : ''), array('class' => 'form-control', 'placeholder' => "Wash area")) !!}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="card card-default">
                        <div class="card-header">
                           <h3 class="card-title">Construction Layout for Commercial</h3>
                        </div>
                        <div class="card-body">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Parking area:</label>
                                    <input type="text" class="form-control" placeholder="Parking area:">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label>Total Floors:</label>
                                    <select class="form-control select2" style="width: 100%;">
                                       <option selected="selected">Select Total Floors:</option>
                                       <option>1</option>
                                       <option>2</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     
<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Add Property Information</h3>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">No of Bedrooms:</label>
                    </div>
                    <div class="col-6">
                        @if (isset($properties->propertyFeatures->bedrooms) && $properties->propertyFeatures->bedrooms != null)
                            {{ $properties->propertyFeatures->bedrooms }}
                            <input type="hidden" name="features[bedrooms]" value="{{ $properties->propertyFeatures->bedrooms }}" />
                        @else
                            {!! Form::select('features[bedrooms]', $bedrooms, (isset($properties->propertyFeatures->bedrooms) ? $properties->propertyFeatures->bedrooms : ''), ['class' => 'form-control cselect', 'id' => 'no_of_bedrooms']) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">No of Bathrooms:</label>
                    </div>
                    <div class="col-6">
                        @if (isset($properties->propertyFeatures->bathrooms) && $properties->propertyFeatures->bathrooms != null)
                            {{ $properties->propertyFeatures->bathrooms }}
                            <input type="hidden" name="features[bathrooms]" value="{{ $properties->propertyFeatures->bathrooms }}" />
                        @else
                            {!! Form::select('features[bathrooms]', $bathrooms, (isset($properties->propertyFeatures->bathrooms) ? $properties->propertyFeatures->bathrooms : ''), ['class' => 'form-control cselect']) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">No of Balconies:</label>
                    </div>
                    <div class="col-6">
                        @if (isset($properties->propertyFeatures->balconies) && $properties->propertyFeatures->balconies != null)
                            {{ $properties->propertyFeatures->balconies }}
                            <input type="hidden" name="features[balconies]" value="{{ $properties->propertyFeatures->balconies }}" />
                        @else
                            {!! Form::select('features[balconies]', $balconies, (isset($properties->propertyFeatures->balconies) ? $properties->propertyFeatures->balconies : ''), ['class' => 'form-control cselect']) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-flats">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">No of Towers:</label>
                    </div>
                    <div class="col-6">
                            
                            
                         @if (isset($properties->propertyFeatures->no_of_towers) && $properties->propertyFeatures->no_of_towers != null )
                           <!--  {{ $properties->propertyFeatures->no_of_towers }}
                            <input type="hidden" name="features[no_of_towers]" value="{{ $properties->propertyFeatures->no_of_towers }}" /> -->
                             {!! Form::text('features[no_of_towers]', (isset($properties->propertyFeatures->no_of_towers) ? $properties->propertyFeatures->no_of_towers : ''), ['class' => 'form-control','placeholder' => 'No of Towers']) !!}
                        @else 
                        {!! Form::text('features[no_of_towers]',null, ['class' => 'form-control','placeholder' => 'No of Towers']) !!}    
                        
                        @endif  
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-flats">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">No of Flats:</label>
                    </div>
                    <div class="col-6">
                        
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
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Foyer area:</label>
                    </div>

                    <div class="col-6">
                        {!! Form::text('features[foyer_area]', (isset($properties->propertyFeatures->foyer_area) ? $properties->propertyFeatures->foyer_area : ''), array('class' => 'form-control', 'placeholder' => 'Foyer area')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Store room:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[store_room]', (isset($properties->propertyFeatures->store_room) ? $properties->propertyFeatures->store_room : ''), array('class' => 'form-control', 'placeholder' => 'Store room area')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Pooja room:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[pooja_room]', (isset($properties->propertyFeatures->pooja_room) ? $properties->propertyFeatures->pooja_room : ''), array('class' => 'form-control', 'placeholder' => 'Pooja room area')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Study room:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[study_room]', (isset($properties->propertyFeatures->study_room) ? $properties->propertyFeatures->study_room : ''), array('class' => 'form-control', 'placeholder' => 'Study room area')) !!}
                    </div>
                </div>
            </div>


            <div class="form-group col-4 ptype-residential-all ptype-commercial-all ptype-industrial-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Parking area:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[parking_area]', (isset($properties->propertyFeatures->parking_area) ? $properties->propertyFeatures->parking_area : ''), array('class' => 'form-control', 'placeholder' => 'Parking area')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-housevilla">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Total Unit:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[total_unit]', (isset($properties->propertyFeatures->total_unit) ? $properties->propertyFeatures->total_unit : ''), array('class' => 'form-control', 'placeholder' => 'Total Unit')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-housevilla">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Open sides:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[open_sides]', (isset($properties->propertyFeatures->open_sides) ? $properties->propertyFeatures->open_sides : ''), array('class' => 'form-control', 'placeholder' => 'Total open sides')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Servant room:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('features[servant_room]', (isset($properties->propertyFeatures->servant_room) ? $properties->propertyFeatures->servant_room : ''), array('class' => 'form-control', 'placeholder' => 'Servant room area')) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-commercial-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Total Floors:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::select('features[total_floors]', $total_floors, (isset($properties->propertyFeatures->total_floors) ? $properties->propertyFeatures->total_floors : ''), ['class' => 'form-control cselect', 'id' => 'total_floors']) !!}
                    </div>
                </div>
            </div>


            <div class="form-group col-12 ptype-residential-all">
                <h3 class="block-heading">Living room</h3>
                <hr size="pixels">
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Living room area:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('meta[living_room_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_area') : ''), array('class' => 'form-control', 'placeholder' => "Total area (Sq. Ft.)")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Living room balcony area:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('meta[living_room_balcony]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_balcony') : ''), array('class' => 'form-control', 'placeholder' => "Balcony area")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Living room bathroom area:</label>
                    </div>
                    <div class="col-6">
                        {!! Form::text('meta[living_room_bathroom]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'living_room_bathroom') : ''), array('class' => 'form-control', 'placeholder' => "Bathroom area")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-12 ptype-residential-all">
                <h3 class="block-heading">Dinning</h3>
                <hr size="pixels">
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Attached with living room: </label>
                    </div>
                    <div class="col-6">
                        {{ Form::checkbox('meta[dining_attached_with_living_room]', 'yes', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'dining_attached_with_living_room') : ''), ['class' => 'form-check-input']) }} &nbsp; Yes &nbsp;
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-6">
                        <label class="form-control-label">Attached with Kitchen: </label>
                    </div>
                    <div class="col-6">
                        {{ Form::checkbox('meta[dining_attached_with_kitchen]', 'yes', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'dining_attached_with_kitchen') : ''), ['class' => 'form-check-input']) }} &nbsp; Yes &nbsp;
                    </div>
                </div>
            </div>

            <div class="form-group col-4 ptype-residential-all">
                <div class="row">
                    <div class="col-3">
                        <label class="form-control-label">Seperate Dinning area: </label>
                    </div>
                    <div class="col-9">
                        {!! Form::text('meta[seperate_dining]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'seperate_dining') : ''), array('class' => 'form-control', 'placeholder' => "if dinning is seperate then enter dinning area")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-12 ptype-residential-all">
                <h3 class="block-heading">Kitchen</h3>
                <hr size="pixels">
            </div>

            <div class="form-group col-6 ptype-residential-all">
                <div class="row">
                    <div class="col-5">
                        <label class="form-control-label">Kitchen total area:</label>
                    </div>
                    <div class="col-7">
                        {!! Form::text('meta[kitchen_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'kitchen_area') : ''), array('class' => 'form-control', 'placeholder' => "Total area (Sq. Ft.)")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-6 ptype-residential-all">
                <div class="row">
                    <div class="col-5">
                        <label class="form-control-label">Kitchen wash area:</label>
                    </div>
                    <div class="col-7">
                        {!! Form::text('meta[kitchen_wash_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'kitchen_wash_area') : ''), array('class' => 'form-control', 'placeholder' => "Wash area")) !!}
                    </div>
                </div>
            </div>

            <div class="form-group col-12 ptype-residential-all" id="bedrooms_details_injector">
            @if (isset($properties->propertyFeatures->bedrooms) && $properties->propertyFeatures->bedrooms > 0)
                @for ($i = 1; $i <= $properties->propertyFeatures->bedrooms; $i++)

                    <h3 class="block-heading">{{ \Helpers::NumSuffix($i) }} Bedroom</h3>
                    <hr size="pixels">

                    <div class="row">

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Bedroom area:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[bedrooms]['.$i.'][bedroom_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'bedroom_area_'.$i) : ''), array('class' => 'form-control', 'placeholder' => "Bedroom area (Sq. Ft.)")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Balcony Area:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[bedrooms]['.$i.'][bedroom_balcony]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'bedroom_balcony_'.$i) : ''), array('class' => 'form-control', 'placeholder' => 'Attached Balcony area')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Attached Bathroom area:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[bedrooms]['.$i.'][bedroom_bathroom]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'bedroom_bathroom_'.$i) : ''), array('class' => 'form-control', 'placeholder' => "Attached Bathroom area")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Dressing space area in attach bathroom:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[bedrooms]['.$i.'][bedroom_bathroom_dressing_space]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'bedroom_bathroom_dressing_space_'.$i) : ''), array('class' => 'form-control', 'placeholder' => "Dressing area")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Is it Master bedroom ?</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::radio('meta[bedrooms][master_bedroom]', $i, (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'master_bedroom', $i) : ''), ['class' => 'form-control']) !!} &nbsp; Yes &nbsp;
                                </div>
                            </div>
                        </div>

                    </div>
                @endfor
            @endif
            </div>

            <div class="form-group col-12 ptype-commercial-all" id="floor_details_injector">
            @if (isset($properties->propertyFeatures->total_floors) && $properties->propertyFeatures->total_floors >= 0)
                @for ($i = 0; $i <= $properties->propertyFeatures->total_floors; $i++)
                    <h3 class="block-heading">{{ \Helpers::NumSuffix($i) }} Floor plan</h3>
                    <hr size="pixels">

                    <div class="row main_row">

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label"><span class="text-danger">*</span> Minimum Varient In Sq.Ft.:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][min_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_min_area', $i) : ''), array('class' => 'form-control number min_area', 'placeholder' => "Smallest varient in Sq.Ft.")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label"><span class="text-danger">*</span> Maximum Varient In Sq.Ft.:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][max_area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_max_area', $i) : ''), array('class' => 'form-control number', 'placeholder' => "Largest varient in Sq.Ft.")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Total Units:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][total_units]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_total_units', $i) : ''), array('class' => 'form-control number', 'placeholder' => "Total units")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Booked Units:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][booked]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_booked', $i) : ''), array('class' => 'form-control number', 'placeholder' => "Booked units")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Available units:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][available]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_available', $i) : ''), array('class' => 'form-control number', 'placeholder' => "Available units")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Total Sq.Ft.:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][area]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_area', $i) : ''), array('class' => 'form-control number', 'placeholder' => "Total Sq.Ft.")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label"><span class="text-danger">*</span>Price per Sq.Ft.:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][price_sq_ft]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_price_sq_ft', $i) : ''), array('class' => 'form-control number sqft_price', 'placeholder' => "Price per Sq.Ft.")) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-control-label">Starting Price:</label>
                                </div>
                                <div class="col-7">
                                    {!! Form::text('meta[floors]['.$i.'][price]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'floor_'.$i.'_price', $i) : ''), array('class' => 'form-control number starting_price', 'placeholder' => 'Price','readonly'=>'readonly')) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                @endfor
            @endif
            </div>
        </div>
    </div>    
</div>
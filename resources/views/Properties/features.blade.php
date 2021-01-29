 
<div class="card card-default">
   <div class="card-header">
                           <h3 class="card-title">Property Features</h3>
                        </div>
    <div class="card-body" id="commerical-block-none">
        <div class="row">


            <div class="col-6">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Total Area:</label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[area_covered]', (isset($properties->propertyFeatures->area_covered) ? $properties->propertyFeatures->area_covered : ''), array('placeholder' => 'Enter area', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[area_covered_unit]', $area_unit, (isset($properties->propertyFeatures->area_covered_unit) ? $properties->propertyFeatures->area_covered_unit : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Shed Area:</label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[shed_area]', (isset($properties->propertyFeatures->shed_area) ? $properties->propertyFeatures->shed_area : ''), array('placeholder' => 'Shed area', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[shed_area_unit]', $area_unit, (isset($properties->propertyFeatures->shed_area_unit) ? $properties->propertyFeatures->shed_area_unit : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Shed Height:</label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[shed_height]', (isset($properties->propertyFeatures->shed_height) ? $properties->propertyFeatures->shed_height : ''), array('placeholder' => 'Shed Height', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[shed_height_unit]', $area_unit, (isset($properties->propertyFeatures->shed_height_unit) ? $properties->propertyFeatures->shed_height_unit : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-all ptype-commercial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">SB Area: </label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[sb_area]', (isset($properties->propertyFeatures->sb_area) ? $properties->propertyFeatures->sb_area : ''), array('placeholder' => 'S.B. area', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[sb_area_unit]', $area_unit, (isset($properties->propertyFeatures->sb_area_unit) ? $properties->propertyFeatures->sb_area_unit : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-all ptype-commercial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Carpet Area: </label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[carpet_area]', (isset($properties->propertyFeatures->carpet_area) ? $properties->propertyFeatures->carpet_area : ''), array('placeholder' => 'Carpet area', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[carpet_area_unit]', $area_unit, (isset($properties->propertyFeatures->carpet_area_unit) ? $properties->propertyFeatures->carpet_area_unit : ''), ['class' => 'form-control select2', ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-all ptype-commercial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Built-Up Area: </label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[built_area]', (isset($properties->propertyFeatures->built_area) ? $properties->propertyFeatures->built_area : ''), array('placeholder' => 'Built-up area', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[built_area_unit]', $area_unit, (isset($properties->propertyFeatures->built_area_unit) ? $properties->propertyFeatures->built_area_unit : ''), ['class' => 'form-control select2', ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-housevilla ptype-residential-openplots ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Plot Area (House): </label>
                        </div>
                        <div class="col-5">
                            {!! Form::text('features[plot_area]', (isset($properties->propertyFeatures->plot_area) ? $properties->propertyFeatures->plot_area : ''), array('placeholder' => 'Plot area (House)', 'class' => 'form-control')) !!}
                        </div>
                        <div class="col-4">
                            {!! Form::select('features[plot_area_unit]', $area_unit, (isset($properties->propertyFeatures->plot_area_unit) ? $properties->propertyFeatures->plot_area_unit : ''), ['class' => 'form-control select2', ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-flat ptype-land-all ptype-residential-openplots">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Price/Sq.Ft.</label>
                        </div>
                        <div class="col-9">
                            {!! Form::text('features[price_sq_ft]', (isset($properties->propertyFeatures->price_sq_ft) ? $properties->propertyFeatures->price_sq_ft : ''), array('placeholder' => 'Price Sq. Ft.', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-land-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Declared Zone:</label>
                        </div>
                        <div class="col-9">
                            {!! Form::select('meta[land_zone]', $land_zone, (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'land_zone') : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-land-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">No of owners:</label>
                        </div>
                        <div class="col-9">
                            {!! Form::text('meta[no_of_owners]', (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'no_of_owners') : ''), array('class' => 'form-control', 'placeholder' => "No of owners")) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-land-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Land Location: </label>
                        </div>
                        <div class="col-9">
                            {!! Form::select('meta[land_location]', $land_location, (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'land_location') : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-land-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Good for:</label>
                        </div>
                        <div class="col-9">
                            {!! Form::select('meta[good_for]', $land_zone, (isset($properties->propertyMetas) ? \Helpers::chkMetas($properties->propertyMetas, 'good_for') : ''), ['class' => 'form-control select2']) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Electricity Connection: </label>
                        </div>
                        <div class="col-9">
                            {{ Form::radio('features[electricity_connection]', 'yes', (isset($properties->propertyFeatures->electricity_connection) && $properties->propertyFeatures->electricity_connection == 'yes' ? true : false), ['class' => 'form-checkwput']) }} &nbsp; Yes, Available &nbsp;

                            {{ Form::radio('features[electricity_connection]', 'no', (isset($properties->propertyFeatures->electricity_connection) && $properties->propertyFeatures->electricity_connection == 'no' ? true : false), ['class' => 'form-check-input']) }} &nbsp; Not available
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Crane Facility : </label>
                        </div>
                        <div class="col-9">
                            {{ Form::radio('features[crane_facility]', 'yes', (isset($properties->propertyFeatures->crane_facility) && $properties->propertyFeatures->crane_facility == 'yes' ? true : false), ['class' => 'form-check-input']) }} &nbsp; Yes, Available &nbsp;

                            {{ Form::radio('features[crane_facility]', 'no', (isset($properties->propertyFeatures->crane_facility) && $properties->propertyFeatures->crane_facility == 'no' ? true : false), ['class' => 'form-check-input']) }} &nbsp; Not available
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 ptype-residential-all ptype-commercial-all ptype-industrial-all">
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label">Vastu: </label>
                        </div>
                        <div class="col-9">
                            {{ Form::radio('features[vastu]', 'yes', (isset($properties->propertyFeatures->vastu) ? ($properties->propertyFeatures->vastu == 'yes' ? true : false) : ''), ['class' => 'form-check-input']) }} Yes, Compliance

                            {{ Form::radio('features[vastu]', 'no', (isset($properties->propertyFeatures->vastu) ? ($properties->propertyFeatures->vastu == 'no' ? true : false) : ''), ['class' => 'form-check-input']) }} Not Compliance
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
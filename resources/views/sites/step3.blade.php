<div id="amenities-part" class="content" role="tabpanel" aria-labelledby="amenities-part-trigger">
                                       <div class="row">
                                          <label style="padding-left: 10px;">Basic amenities</label>
                                          <div class="col-md-12" style="display: flex;">
                                             

                                             <div class="col-md-2" >
                                                <div class="form-check">
                                                    <input class="form-check-input"  type="checkbox"  name="amenity[lift]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'lift', '1') : '' }} value="1">
                                                    <label class="form-check-label">Elevator/Lift</label>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                   <input class="form-check-input"  type="checkbox"  name="amenity[garden]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'garden', '1') : '' }} value="1">
                                                   <label class="form-check-label">Garden</label>
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[security_facility]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'security_facility', '1') : '' }} value="1">
                                                <label class="form-check-label">Security facility</label>
                                            </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[parking_area]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'parking_area', '1') : '' }} value="1">
                                                <label class="form-check-label">Parking Area</label>  

                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[children_play_area]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'children_play_area', '1') : '' }} value="1" >
                                                <label class="form-check-label">Children Play Area</label>  
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[restaurant]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'restaurant', '1') : '' }} value="1" >
                                                <label class="form-check-label">Restaurant</label> 
                                             </div>
                                          </div>
                                          <div class="col-md-12" style="display: flex;">
                                             
                                             <div class="col-md-2" style="left:19px;">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[gas_line]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'gas_line', '1') : '' }} value="1" >
                                                <label class="form-check-label">Gas Line</label>   
                                             </div>
                                             <div class="col-md-2" >
                                                <input class="form-check-input"  type="checkbox" name="amenity[cctv]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'cctv', '1') : '' }} value="1" >
                                                <label class="form-check-label">CCTV</label> 
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[internal_road]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'internal_road', '1') : '' }} value="1" >
                                                <label class="form-check-label">Internal Road</label> 
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[video_door_phone]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'video_door_phone', '1') : '' }} value="1" >
                                                <label class="form-check-label">Video Door Phone</label> 
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[washing_machine_area]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'washing_machine_area', '1') : '' }} value="1" >
                                                <label class="form-check-label">Washing Machine Area</label>   
                                             </div>
                                          </div>   
                                       </div>
                                       <hr />
                                       <div class="row">
                                          <label style="padding-left: 10px;">Flat amenities</label>
                                          <div class="col-md-12" style="display: flex;">
                                             

                                             <div class="col-md-2" >
                                                <div class="form-check">
                                                    <input class="form-check-input"  type="checkbox"  name="amenity[library]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'library', '1') : '' }} value="1">
                                                    <label class="form-check-label">Library</label>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                   <input class="form-check-input"  type="checkbox"  name="amenity[internet]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'internet', '1') : '' }} value="1">
                                                   <label class="form-check-label">Internet</label>
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"   name="amenity[intercom]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'intercom', '1') : '' }} value="1">
                                                <label class="form-check-label">Intercom</label>
                                            </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[rainwater_harvest]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'rainwater_harvest', '1') : '' }} value="1">
                                                <label class="form-check-label">Rainwater Harvest</label>   

                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"   name="amenity[unity_stores]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'unity_stores', '1') : '' }} value="1"  >
                                                <label class="form-check-label">Unity Stores</label>  
                                             </div>
                                             <div class="col-md-2">
                                                   
                                             </div>
                                          </div>
                                       </div>
                                       <hr />
                                       <div class="row">
                                          <label style="padding-left: 10px;">Luxury amenities</label>
                                          <div class="col-md-12" style="display: flex;">
                                             <div class="col-md-2" >
                                                <div class="form-check">
                                                    <input class="form-check-input"  type="checkbox"   name="amenity[infinity_swimming_pool]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'infinity_swimming_pool', '1') : '' }} value="1">
                                                    <label class="form-check-label">Infinity Swimming Pool</label>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                   <input class="form-check-input"  type="checkbox"  name="amenity[volleyball]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'volleyball', '1') : '' }} value="1">
                                                   <label class="form-check-label">Volleyball</label>
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[badminton]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'badminton', '1') : '' }} value="1">
                                                <label class="form-check-label">Badminton</label>
                                            </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"   name="amenity[golf]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'golf', '1') : '' }} value="1">
                                                <label class="form-check-label">Golf</label> 

                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[tennis]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'tennis', '1') : '' }} value="1" >
                                                <label class="form-check-label">Tennis</label>  
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[squash]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'squash', '1') : '' }} value="1" >
                                                <label class="form-check-label">Squash</label>  
                                             </div>
                                          </div>
                                          <div class="col-md-12" style="display: flex;">
                                             
                                             <div class="col-md-2" style="left:19px;">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[yoga]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'yoga', '1') : '' }} value="1" >
                                                <label class="form-check-label">Yoga</label> 
                                             </div>
                                             <div class="col-md-2" >
                                                <input class="form-check-input"  type="checkbox" name="amenity[gazebo]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'gazebo', '1') : '' }} value="1" >
                                                <label class="form-check-label">Gazebo</label>  
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[banquet_hall]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'banquet_hall', '1') : '' }} value="1"  >
                                                <label class="form-check-label">Banquet Hall</label>  
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[amphi_theatre]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'amphi_theatre', '1') : '' }} value="1" >
                                                <label class="form-check-label">Amphitheatre</label>  
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[gymasium]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'gymasium', '1') : '' }} value="1" >
                                                <label class="form-check-label">Gymnasium</label>  
                                             </div>
                                          </div>   
                                          <div class="col-md-12" style="display: flex;">
                                             <div class="col-md-2" style="left:19px;">
                                                <input class="form-check-input"  type="checkbox" name="amenity[joggers_park]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'joggers_park', '1') : '' }} value="1"  >
                                                <label class="form-check-label">Jogger's Park</label>
                                             </div>
                                             <div class="col-md-2" >
                                                <input class="form-check-input"  type="checkbox" name="amenity[outdoor_game_court]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'outdoor_game_court', '1') : '' }} value="1" >
                                                <label class="form-check-label">Outdoor Games Court</label> 
                                             </div>
                                             <div class="col-md-2" >
                                                <input class="form-check-input"  type="checkbox" name="amenity[indoor_game_court]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'indoor_game_court', '1') : '' }} value="1">
                                                <label class="form-check-label">Indoor Games Court</label>  
                                                   
                                             </div>
                                          </div>
                                       </div>
                                        <hr />
                                        <div class="row" style="margin-bottom: 10px;">
                                          <label style="padding-left: 10px;">Innovative Amenities</label>
                                          <div class="col-md-12" style="display: flex;">
                                             <div class="col-md-2" >
                                                <div class="form-check">
                                                    <input class="form-check-input"  type="checkbox"   name="amenity[butterfly_park]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'butterfly_park', '1') : '' }} value="1">
                                                    <label class="form-check-label">Butterfly Park</label>
                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                   <input class="form-check-input"  type="checkbox"  name="amenity[temple]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'temple', '1') : '' }} value="1">
                                                   <label class="form-check-label">Temple</label>
                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[senior_citizen_garden]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'senior_citizen_garden', '1') : '' }} value="1">
                                                <label class="form-check-label">Senior Citizen Garden</label>
                                            </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox"  name="amenity[wifi]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'wifi', '1') : '' }} value="1">
                                                <label class="form-check-label">Wi-Fi</label>   

                                             </div>
                                             <div class="col-md-2">
                                                <input class="form-check-input"  type="checkbox" name="amenity[relaxation_room]" {{ isset($Sites->metas) ? \Helpers::chkAmenity($Sites->metas, 'relaxation_room', '1') : '' }} value="1" >
                                                <label class="form-check-label">Relaxation Room</label>  
                                             </div>
                                             <div class="col-md-2"></div>
                                          </div>
                                           
                                       </div>
                                        
                                       
                                   <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                   <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button> 
                                    </div>
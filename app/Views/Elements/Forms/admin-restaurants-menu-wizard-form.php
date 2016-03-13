<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
  <div class="page animsition">
   <div class="page-header">
      <h1 class="page-title">Form Wizard</h1>
      <ol class="breadcrumb">
        <li><a href="/gadmin/restaurants">Restaurants</a></li>
        <li><a href="/gadmin/restaurant/add">Add</a></li>
        <li class="active">Wizard</li>
      </ol>
      <div class="page-header-actions">
        <a class="btn btn-sm btn-default btn-outline btn-round" href="https://github.com/amazingSurge/jquery-wizard"
        target="_blank">
          <i class="icon wb-link" aria-hidden="true"></i>
          <span class="hidden-xs">Official Website</span>
        </a>
      </div>
    </div>
    <div class="page-content container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- Panel Wizard Form -->
          <div class="panel" id="restaurantWizardForm">
            <div class="panel-heading">
              <h3 class="panel-title">Restaurants/Menu Form</h3>
            </div>
            <div class="panel-body">
              <!-- Steps -->
              <div class="steps steps-sm row" data-plugin="matchHeight" data-by-row="true" role="tablist">
                <div class="step col-md-4 current" data-target="#restaurantStep" role="tab">
                  <span class="step-number">1</span>
                  <div class="step-desc">
                    <span class="step-title">Add/Edit Restaurants</span>
                    <p>Menu</p>
                  </div>
                </div>
                <div class="step col-md-4" data-target="#AddMenuItemStep" role="tab">
                  <span class="step-number">2</span>
                  <div class="step-desc">
                    <span class="step-title">Add Menu Item</span>
                    <p>Add/Edit Menu Item</p>
                  </div>
                </div>
                <div class="step col-md-4" data-target="#exampleGetting" role="tab">
                  <span class="step-number">3</span>
                  <div class="step-desc">
                    <span class="step-title">Menu-Item Image</span>
                    <p>Crop Menu Image</p>
                  </div>
                </div>
              </div>
              <!-- End Steps -->
              <!-- Wizard Content -->
              <div class="wizard-content">
                <div class="wizard-pane active" id="restaurantStep" role="tabpanel">
                  <form id="restaurantForm" method="post" action="<?=isset($restaurant['id'])?'/gadmin/restaurant/edit/'.$restaurant['id']:'/gadmin/restaurant/add'?>">
                    <div class="form-group">
                      <label class="control-label" for="restaurant">restaurant's name</label>
                      <input type="text" class="form-control" id="restaurant" name="restaurant" required="required" placeholder="Enter Restaurant's Name" value="<?=isset($restaurant['restaurant'])?$restaurant['restaurant']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="phone">Phone Number</label>
                      <input type="text" class="form-control" id="phone" name="phone"
                      required="required" placeholder="Please Enter Restaurant's Phone" value="<?=isset($restaurant['phone'])?$restaurant['phone']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="fax">Fax Number</label>
                      <input type="text" class="form-control" id="fax" name="fax" required="required" placeholder="Please Enter Restaurant's Fax Number" value="<?=isset($restaurant['fax'])?$restaurant['fax']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="address">address</label>
                      <input type="address" class="form-control" id="address" name="address"
                      required="required" placeholder="Please Enter Restaurant's Address" value="<?=isset($restaurant['address'])?$restaurant['address']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="city">City</label>
                      <select class="form-control" id="city_id" name="city_id" required="required">
                          <option value="" >--SELECT CITY--</option>
                          <?php foreach( $cities as $city): ?>
                          <option value="<?=$city['id']?>" ><?=$city['name']?></option>
                          <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label class="control-label" for="state_id">State</label>
                      <select class="form-control" id="state_id" name="state_id" required="required">
                        <?php foreach( $states as $state): ?>
                          <option value="<?=$state['id']?>"  ><?=$state['name']?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="zipcode">Zip Code</label>
                      <input type="text" class="form-control" id="zipcode" name="zipcode"
                      required="required" placeholder="Please Enter Restaurant's Zip Code" value="<?=isset($restaurant['zipcode'])?$restaurant['zipcode']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="opens">Opens</label>
                      <input type="text" class="form-control" id="opens" name="opens" required="required" placeholder="Please Enter The Restaurant's Open Time" value="<?=isset($restaurant['opens'])?$restaurant['opens']:''?>">
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="closes">closes</label>
                      <input type="text" class="form-control" id="closes" name="closes"
                      required="required" placeholder="Please Enter Restaurant's Close time" value="<?=isset($restaurant['closes'])?$restaurant['closes']:''?>">
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="closes">Delivery Radius</label>
                      <input type="text" class="form-control" id="delivery_radius" name="delivery_radius"
                      required="required" placeholder="Please Enter The Restaurant's Delivery Radius" value="<?=isset($restaurant['delivery_radius'])?$restaurant['delivery_radius']:''?>">
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="closes">Rating</label>
                      <select  class="form-control" id="rating" name="rating"
                      required="required">
                          <option value="">--SELECT RATING--</option>
                          <option value="1" <?=isset($restaurant['rating']) && $restaurant['rating'] =='1'?'selected':''?>>1 STAR</option>
                          <option value="2" <?=isset($restaurant['rating']) && $restaurant['rating'] =='2'?'selected':''?>>2 STARS</option>
                          <option value="3" <?=isset($restaurant['rating']) && $restaurant['rating'] =='3'?'selected':''?>>3 STARS</option>
                          <option value="4" <?=isset($restaurant['rating']) && $restaurant['rating'] =='4'?'selected':''?>>4 STARS</option>
                          <option value="5" <?=isset($restaurant['rating']) && $restaurant['rating'] =='5'?'selected':''?>>5 STARS</option>
                                
                      </select>    
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="closes">Price</label>
                      <select  class="form-control" id="price" name="price" required="required" >
                          <option value="">--SELECT PRICE RANGE--</option>
                          <option value="1" <?=isset($restaurant['price']) && $restaurant['price'] =='1'?'selected':''?>>$</option>
                          <option value="2" <?=isset($restaurant['price']) && $restaurant['price'] =='2'?'selected':''?>>$$</option>
                          <option value="3" <?=isset($restaurant['price']) && $restaurant['price'] =='3'?'selected':''?>>$$$</option>
                          <option value="4" <?=isset($restaurant['price']) && $restaurant['price'] =='4'?'selected':''?>>$$$$</option>
                          <option value="5" <?=isset($restaurant['price']) && $restaurant['price'] =='5'?'selected':''?>>$$$$$</option>
                                                 
                      </select>
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="full_address">Full Address</label>
                      <input type="text" class="form-control" id="full_address" name="full_address"
                      required="required" placeholder="Please Enter Restaurant's Full Address ( address, city,state, zip )" value="<?=isset($restaurant['full_address'])?$restaurant['full_address']:''?>">
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="Longitude">Longitude</label>
                      <input type="text" class="form-control" id="longitude" name="longitude" required="required" placeholder="Please Enter Restaurant's Longitude" value="<?=isset($restaurant['longitude'])?$restaurant['longitude']:''?>">
                    </div> 
                    <div class="form-group">
                      <label class="control-label" for="Latitude">Latitude</label>
                      <input type="text" class="form-control" id="latitude" name="latitude" required="required" placeholder="Please Enter Restaurant's latitude" value="<?=isset($restaurant['latitude'])?$restaurant['latitude']:''?>">
                    </div>                       
                  </form>
                </div>
                <div class="wizard-pane" id="AddMenuItemStep" role="tabpanel">
                    <div class="row row-lg">

                      <div class="clearfix visible-sm-block visible-md-block"></div>
                      <div class="col-lg-4 col-sm-6">
                        <!-- Example height -->
                        <div class="example-wrap">
                          <h4 class="example-title"></h4>
                          <div class="example">
                            <input type="file" id="foodImage" data-plugin="dropify" data-height="364" data-width="364"
                            />
                          </div>
                        </div>
                        <!-- End Example height -->
                      </div>
                      <div class="col-lg-4 col-sm-6">
                        <!-- Example height -->
                        <div class="example-wrap">
                          <h4 class="example-title"></h4>
                          <div class="example">
                            <input type="file" id="input-file-now-custom-2" data-plugin="dropify" data-height="364" data-width="364"
                            />
                          </div>
                        </div>
                        <!-- End Example height -->
                      </div>
                        <div class="col-lg-4 col-sm-6">
                        <!-- Example height -->
                        <div class="example-wrap">
                          <h4 class="example-title"></h4>
                          <div class="example">
                            <input type="file" id="input-file-now-custom-2" data-plugin="dropify" data-height="364" data-width="364"
                            />
                          </div>
                        </div>
                        <!-- End Example height -->
                      </div>                    
                    </div>
                  </div>

<!--                <div class="wizard-pane" id="exampleGetting" role="tabpanel">
                  <div class="text-center margin-vertical-20">
                    <i class="icon wb-check font-size-40" aria-hidden="true"></i>
                    <h4>We got your order. Your product will be shipping soon.</h4>
                  </div>
                </div>-->
              </div>
              <!-- End Wizard Content -->
            </div>
          </div>
          <!-- End Panel Wizard One Form -->
        </div>

      </div>
    </div>
</div>
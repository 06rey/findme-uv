<?php $this->load->view('partials/header');?>


        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mt-3 text-gray-800"><?php echo $pageTitle?></h1>

          <hr>

            <?php if (!empty($message)):?>
              <div class="alert alert-<?php echo $message['type'] ?>">
                <?php echo $message['message'] ?>
              </div>
            <?php endif ?>

          <!-- DataTales Example -->
          <div class="card mb-4">
            <div class="card-header py-3">
              <p class="m-0 text-primary">Fill All Info</p>
            </div>
            <div class="card-body">

                <div class="alert alert-danger" id="msg" hidden>
                    
                </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="p-5">
              
              <form method="post" action="<?php echo base_url('route/add_validation');?>" class="form-horizontal">

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Origin : </label>
                            <div class="col-sm-8">
                              <input type="text" name="origin" id="origin"  class="form-control" required>

                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Destination : </label>
                            <div class="col-sm-8">
                                <input type="text" name="destination" id="destination"  class="form-control" required disabled>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Via : </label>
                            <div class="col-sm-8">
                              <input type="text" name="via" id="via"  class="form-control" required>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-sm-2 control-label">Fare : </label>
                            <div class="col-sm-8">
                              <input type="number" step="0.01" name="fare" id="fare"  class="form-control" required>
                            </div>
                          </div>

                          <!-- Hidden input -->
                          <div class="form-group" >
                            <div class="col-sm-8">
                              <input type="text" name="origin_lat_lng" id="origin_lat_lng"  class="form-control" hidden required>
                            </div>
                          </div>

                          <div class="form-group" >
                            <div class="col-sm-8">
                              <input type="text" name="destination_lat_lng" id="destination_lat_lng"  class="form-control" hidden required>
                            </div>
                          </div>

                          <div class="form-group" >
                            <div class="col-sm-8">
                              <textarea type="text" name="way_point" id="way_point"  class="form-control" hidden required></textarea>
                            </div>
                          </div>

                          <div class="form-group">
                             <div class="col-sm-8">
                              <div class="btn btn-info" style="width: 100%; color: white" id="btn-map">ADD ROUTE MAP DIRECTION</div>
                            </div>
                          </div>

                    

                          <br>
                          <div class="col-sm-6 col-sm-offset-4">
                            <a class="btn btn-secondary" href="<?php echo base_url();?>route/all">Cancel</a>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary" id="submit">
                          </div>
                        </form>

            </div>
          </div>
        </div>
              
            </div>
          </div>



        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


     

        <!-- MAP MODAL -->
        <div class="container">
            <!-- Modal -->
            <div class="modal fade" id="mapModal" role="dialog" style="z-index: 1400;" data-keyboard="false" data-backdrop="static">
              <div class="modal-dialog modal-xl">s
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="title"></h4>
                    
                    
                  </div>
                  <div class="modal-body" id="googleMap">
                

                  </div>
                  <div class="modal-footer">

                      <div class="mr-auto">
                        <button class="btn btn-secondary" id="map-edit" title="Draw lines"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-secondary" id="map-undo" title="Undo"><i class="fas fa-undo"></i></button>
                        <button class="btn btn-secondary" id="map-redo" title="Redo"><i class="fas fa-redo"></i></button>
                        <button class="btn btn-secondary" id="map-backspace" title="Backspace"><i class="fas fa-backspace"></i></button>
                      </div>
        
                    <button type="button" class="btn btn-danger" id="map-close">Close</button>
                    <button type="button" class="btn btn-success" id="map-save">Done</button>
                  </div>
                </div>
                
              </div>
            </div>
            
          </div>

          <!--END MAP MODAL -->

          <!-- MAP INFO MODAL -->

                    <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="info-modal" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1600; top: 20%">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">Information</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <div class="modal-body" style="background: white"><br>
                                <div style="padding-left: 50px; padding-right: 50px">
                                    <center>
                                      <p id="info-msg"></p>
                                    </center>
                                </div>
                              </div>

                              <div class="modal-footer" style="width: 100%">
                                <button type="submit" class="btn btn-success" id="info-ok">Ok</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- END OF MAP SUB MODAL -->

                      <!-- CONFIRM SUB MODAL -->

                    <div class="container">
                        <!-- Modal -->
                        <div class="modal fade" id="confirm-modal" role="dialog" data-keyboard="false" data-backdrop="static" style="z-index: 1600; top: 20%">
                          <div class="modal-dialog modal-md">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="title">Confirm</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <div class="modal-body" style="background: white"><br>
                                <div style="padding-left: 50px; padding-right: 50px">
                                    <center>
                                      <p id="confirm-msg"></p>
                                    </center>
                                </div>
                              </div>

                              <div class="modal-footer" style="width: 100%">
                                <button type="button" class="btn btn-danger" id="confirm-no">No</button>
                                <button type="button" class="btn btn-success" id="confirm-yes">Yes</button>
                                <button type="button" class="btn btn-info" id="confirm-cancel">Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- END OF CONFIRM SUB MODAL -->


        <style type="text/css">
          
          .map-frame{
            width: 100%;
          }

          #googleMap {
            height: 390px;
          }

        </style>
      <!-- End of Main Content -->

      <?php $this->load->view('partials/footer');?>

  <script type="text/javascript">

disabledForm();

$(document).ready(function(){

    $('#origin').change(function(){
        if ($('#origin').val().trim() != "") {
            $('#destination').prop('disabled', false);
        } else {
            $('#destination').prop('disabled', true);
        }
    });
        
    $('#destination').change(function(){

        var origin = $('#origin').val();
        var destination = $('#destination').val();
        if (origin.includes(' ')) {
            origin = origin.substr(0, origin.indexOf(' '));
        }
        if (destination.includes(' ')) {
            destination = destination.substr(0, destination.indexOf(' '));
        }

        if (origin == destination) {
            $('#msg').prop('hidden', false);
            $('#msg').text('Origin and destination must be unique.');
        } else {
            $('#msg').prop('hidden', true);
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url("route/validate_route"); ?>/'+origin+'/'+destination,
                dataType: 'json',
                success: function(response){
                    if (response.length > 0) {
                         $('#msg').prop('hidden', false);
                         $('#msg').text(origin +' to ' + destination + ' already exists.');
                    } else {
                         $('#msg').prop('hidden', true);
                         enabledForm();
                    }
                }
            });
        }
    });
});

function disabledForm() {
    $('#via').prop('disabled', true);
    $('#fare').prop('disabled', true);
    $('#btn-map').prop('disabled', true);
}

function enabledForm() {
    $('#via').prop('disabled', false);
    $('#fare').prop('disabled', false);
    $('#btn-map').prop('disabled', false);
}
        
  

    // MAP START HERE
   
    function myMap() {

        // MAP MODAL PROPERTIES


        var lat = 1, lng = 2;
        var lats, lngs;

        var confirm = 'cancel';
        var hasChanged = false;
        var hasSaved = false;
        var action = null;
        var write = false;

        var originLatLng, destinationLatLng, lineLatLng;
        var lineIndex = 0, currentIndex;
        var origin, destination, routeIndex = null;


        //MAP MODAL PROPERTIES END HERE


        // GOOGLE MAP PROPERTIES

        var lt = 11.240988, lg = 125.002026;
        var mapProp= {
            center:new google.maps.LatLng(lt,lg),
            zoom:10,
            mapTypeId: "OSM",
            mapTypeControl: false,
            streetViewControl: false
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        var mainTerminalLocation, mainTerminalMarker = null;
        var allRoute = null;
        var doIndex = null, doStatus = false;
        var routeOriginLatlLng = '', routeDestinationLatLng = '', routreWayPoint = '';

        var geoPoints = new Array(), flightPathArr = new Array();
        var redoGeoPoints = new Array();



        flightPlanCoordinates = [
            {lat: 11.185704, lng: 125.534767},
            {lat: 11.262500, lng: 124.966745}
        ];
        flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 20
        });


        // Coat google map with OSM map
        map.mapTypes.set("OSM", new google.maps.ImageMapType({
            getTileUrl: function(coord, zoom) {
            return "https://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
            tileSize: new google.maps.Size(256, 256),
            name: "OpenStreetMap",
            maxZoom: 18
        }));

        // Adding main terminal marker
        function addMarker(location, place) {
            var mainMarker = '';

            mainMarker = '<?php echo base_url("assets/img/origin.png");?>';
            showInfoModal('Origin has been set.');

            if (mainTerminalMarker != null) {
              mainTerminalMarker.setMap(null);
            }
            var info = '<div>' +
                        '<h4>Origin: '+place+' Duptours terminal</h4>' +
                        '</div>';
            var infowindow = new google.maps.InfoWindow({
              content: info
            });
            mainTerminalMarker = new google.maps.Marker({
                position: location,
                map: map,
                title: place+' Duptours terminal',
                icon: mainMarker
            });

          mainTerminalMarker.addListener('click', function() {
            infowindow.open(map, mainTerminalMarker);
          });


        }

        google.maps.event.addListener(map, "click", function (e) {

            if (write) {
                if (geoPoints.length < 1) {
                    geoPoints.push(e.latLng);
                    addMarker(geoPoints[0], $('#origin').val());
                } else {

                    var geoPointsIndex = geoPoints.length;
                    var distanceInMeters = google.maps.geometry.spherical.computeDistanceBetween(
                        new google.maps.LatLng(geoPoints[geoPointsIndex-1].lat(), geoPoints[geoPointsIndex-1].lng()),
                        new google.maps.LatLng(e.latLng.lat(), e.latLng.lng())
                    );

                    if (distanceInMeters > 100) {
                        showInfoModal('Line distance from previous point is too long. Try again.');
                    } else {
                        redoGeoPoints = new Array();

                        geoPoints.push(e.latLng);
                        flightPlanCoordinates = [
                            {lat: geoPoints[geoPointsIndex-1].lat(), lng: geoPoints[geoPointsIndex-1].lng()},
                            {lat: e.latLng.lat(), lng: e.latLng.lng()}
                        ];
                        setPolyline(flightPlanCoordinates);
                    }
                }
            }

        });

        function setPolyline(coordinates) {
            flightPath = new google.maps.Polyline({
                            path: coordinates,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 4
                        });

                        flightPath.setMap(map);
                        flightPathArr.push(flightPath);
        }
        
        google.maps.event.addDomListener(window, "load", null);
        getPolyPath(flightPath);

        function redo() {
            var redoGeoPointsLen = redoGeoPoints.length - 1;
            var geoPointsLen = geoPoints.length - 1;
            flightPlanCoordinates = [
                {lat: geoPoints[geoPointsLen].lat(), lng: geoPoints[geoPointsLen].lng()},
                {lat: redoGeoPoints[redoGeoPointsLen].lat(), lng: redoGeoPoints[redoGeoPointsLen].lng()}
            ];
            setPolyline(flightPlanCoordinates);
            geoPoints.push(redoGeoPoints[redoGeoPointsLen]);
            redoGeoPoints.pop();
        }

        function undo() {
            redoGeoPoints.push(geoPoints[geoPoints.length - 1]);
            geoPoints.pop();
            flightPathArr[flightPathArr.length - 1].setMap(null);
            flightPathArr.pop();
        }

        function backSpace() {
            geoPoints.pop();
            flightPathArr[flightPathArr.length-1].setMap(null);
            flightPathArr.pop();
            redoGeoPoints =  new Array();
        }

        // Parse coordiantes to json
        function setRouteWayPoint() {
              var len = geoPoints.length;
              routreWayPoint = '[';
              for (var i=0; i<len; i++) {
                  routreWayPoint = routreWayPoint + '{"lat":"'+geoPoints[i].lat()+'","lng":"'+geoPoints[i].lng()+'"}';
                  if (i<len-1) {
                      routreWayPoint = routreWayPoint + ',';
                  }
              }
              routreWayPoint = routreWayPoint + ']';
              $('#way_point').val('');
              $('#way_point').val(routreWayPoint);
        }

        function setRouteOriginLatLng() {
            routeOriginLatlLng = '{"lat":"'+geoPoints[0].lat()+'","lng":"'+geoPoints[0].lng()+'"}';
             $('#origin_lat_lng').val(routeOriginLatlLng);
        }

        function setRouteDestinationLatLng() {
            routeDestinationLatLng = '{"lat":"'+geoPoints[geoPoints.length-1].lat()+'","lng":"'+geoPoints[geoPoints.length-1].lng()+'"}';
            $('#destination_lat_lng').val(routeDestinationLatLng);
        }


        // Set marker

        function setOriginMarker() {
            var allRouteLen = allRoute.length;
            var routeOrigin = origin;
            var routeDestination;
            if (origin.includes(' ')) {
                routeOrigin = origin.substr(0, origin.indexOf(' '));
            }
            for (var i=0; i<allRouteLen; i++) {
                if (allRoute[i]['origin'].includes(routeOrigin)) {
                    console.log('INCLUDE: ' + allRoute[i]['origin'].includes(routeOrigin));
                    routeIndex = i;
                    var latTemp = allRoute[i]['origin_lat_lng']['lat'];
                    var lngTemp = allRoute[i]['origin_lat_lng']['lng'];
                    var routeLocation = new google.maps.LatLng(latTemp, lngTemp);
                    addMarker(routeLocation, allRoute[routeIndex]['origin']);
                    geoPoints.push(routeLocation);
                    break;
                }
            }
            if (i == allRouteLen) {
                showInfoModal('Select a point on the map to be the origin.');
            }
        }

        // AJAX REQUEST

        function getAllRoute() {
            $.ajax({
                type: 'GET',
                url: '<?php echo base_url("route/route_path"); ?>',
                dataType: 'json',
                success: function(response){
                    if (response[0]['status'] == 'has_record') {
                        allRoute = response;
                        setOriginMarker();
                    }
                }
            });
        }


        // MAP MODAL FUNCTIONS

        // Jquery click funtion
        var isMapModalFirstOpen = true;
        $(document).ready(function(){

          $('#btn-map').click(function(){
            
            origin = $('#origin').val();
            destination = $('#destination').val();

            if (origin.trim() == '' || destination.trim() == '') {
              alert('Please enter origin and destination first.')
            } else {
                if (isMapModalFirstOpen) {
                    getAllRoute();
                    $('#title').text(origin + ' to ' + destination + ' map direction');
                    isMapModalFirstOpen = false;
                }
                $('#mapModal').modal('show');
            }
          });

          $('#submit').click(function(){
                if (geoPoints.length < 1) {
                    showInfoModal("Please set map direction.");
                }
          });


          // Map button click function

           $('#map-edit').click(function(){
            if (write) {
              $('#map-edit').css("background-color","#8c8c8c");
              write = false;
            } else {
              $('#map-edit').css("background-color","green");
              write = true; 
            }
          });

            

          $('#map-undo').click(function(){
              if (geoPoints.length > 1) {
                  undo();
              }
          });

          $('#map-redo').click(function(){ 
              if (redoGeoPoints.length > 0) {
                  redo();
              }
          });

           $('#map-backspace').click(function(){
              if (geoPoints.length > 1) {
                  backSpace();
              }
          });

          $('#map-save').click(function(){
                if (geoPoints.length > 0) {
                    setRouteWayPoint();
                    setRouteOriginLatLng();
                    setRouteDestinationLatLng();
                }
                $('#mapModal').modal('toggle');
          });

          $('#map-close').click(function(){
                if (geoPoints.length > 0) {
                    setRouteWayPoint();
                    setRouteOriginLatLng();
                    setRouteDestinationLatLng();
                }
                $('#mapModal').modal('toggle');
          });

          // Confirm modal button clcik event

          $('#confirm-yes').click(function(){
            confirm = 'yes';
            hideConfirmModal();
            checkAction();
          });

          $('#confirm-no').click(function(){
            confirm = 'no';
            hideConfirmModal();
          });

          $('#confirm-cancel').click(function(){
            confirm = 'cancel';
            hideConfirmModal();
          });

          // Info modal button ok click

          $('#info-ok').click(function(){
            $('#info-modal').modal('toggle');
          });

          //READY EVENT CLOSING BRACKET
        });

        // Main terminal marker button click
        $(document).on('click', '#btn-set-origin', function(){
          
        });

        function showInfoModal(msg) {
            $('#info-msg').text(msg);
            $('#info-modal').modal('show');
        }

        function hideInfoModal() {
          $('#info-modal').modal('toggle');
        }

        function showConfirmModal(msg) {
            $('#confirm-msg').text(msg);
            $('#confirm-modal').modal('show');
        }

        function hideConfirmModal() {
          $('#confirm-modal').modal('toggle');
        }
      
        getLocation();

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        }

        function showPosition(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude;
        }
}

  //END MAP 





  function getPolyPath(flightPath) {
    var polygonBounds = flightPath.getPath();
            var bounds = [];
            for (var i = 0; i < polygonBounds.length; i++) {
                  var point = {
                    lat: polygonBounds.getAt(i).lat(),
                    lng: polygonBounds.getAt(i).lng()
                  };
                  bounds.push(point);
                  console.log(point.lat + " " + point.lng);
             }
  }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDniqAbGD4phpzXC4owCA9bkJK5PdnUdvA&callback=myMap"></script>


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
              <a class="btn btn-info" href="<?php echo base_url('logs/contact_list');?>">Accident Alert Contact List</a>
             
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped text-gray-800 filtered"  id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Trip No.</th>
                      <th>Driver Name</th>
                      <th>Plate No</th>
                      <th>Speed</th>
                      <th>Acceleration</th>
                      <th>Date of Accident</th>
                      <th>Location</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                     <th>Trip No.</th>
                      <th>Driver Name</th>
                      <th>Plate No</th>
                      <th>Speed</th>
                      <th>Acceleration</th>
                      <th>Date of Accident</th>
                      <th>Location</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach($all_logs as $logs ):?>
                      <tr>
                        <td><?php echo $logs->trip_id?></td>
                        <td><?php echo $logs->f_name.' '.$logs->l_name; ?></td>
                        <td><?php echo $logs->plate_no; ?></td>
                        <td><?php echo $logs->speed.' kph' ?></td>
                        <td><?php echo $logs->g_force; ?></td>
                        <td><?php echo $logs->date_added; ?></td>
                        <td><a href="#" onclick="showMap(<?=$logs->lat?>, <?=$logs->lng?>)"><i class="fa fa-map"></i> Location</a></td>
                      </tr>
                    <?php endforeach;?>
                      
                    
                  </tbody>
                </table>
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
                    <h4 class="modal-title" id="title">UV Express Accident Location</h4>
                    
                  </div>
                  <div class="modal-body" id="googleMap" style="height: 400px">
                

                  </div>
                  <div class="modal-footer">
        
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="map-close">Close</button>
                  </div>
                </div>
                
              </div>
            </div>
            
          </div>

          <!--END MAP MODAL -->

  <?php $this->load->view('partials/footer');?>


  <script type="text/javascript">

    var lt = 11.240988, lg = 125.002026;
    function showMap(lat, lng) {
            $('#mapModal').modal('show');
            addMarker(lat, lng);
      }

    function myMap() {
      
        // GOOGLE MAP PROPERTIES
        var mapProp= {
            center:new google.maps.LatLng(lt,lg),
            zoom:10,
            mapTypeId: "OSM",
            mapTypeControl: false,
            streetViewControl: false
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);


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
        function addMarker(lat, lng) {

          location = new google.maps.LatLng(lat, lng);
    
            mainTerminalMarker = new google.maps.Marker({
                position: location,
                map: map,
                title: place+' UV Express Accident Location'
            });

        }
        
        google.maps.event.addDomListener(window, "load", null);
  }

  </script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDniqAbGD4phpzXC4owCA9bkJK5PdnUdvA&callback=myMap"></script>







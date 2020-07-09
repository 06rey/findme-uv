      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <h6>Copyright &copy; FIND ME UV 2019</h6>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

   <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <!-- accident alert modal -->
  <div class="modal fade" id="accident-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="width: 80%">
        <div class="modal-header">
          <h5 class="red-header">ACCIDENT ALERT!</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body b-white">
          <p class="accident-msg"></p>
          <div>
            <label class="lbl">Driver: </label>&nbsp;&nbsp;&nbsp;<label class="driver-name"></label>
            <label class="lbl">Plate: </label>&nbsp;&nbsp;&nbsp;<label class="plate-no"></label>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-info" type="button" data-dismiss="modal">Location</button>
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End accident alert modal -->
</body>
</html>


<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url();?>assets/js/sb-admin-2.js"></script>
<script type="text/javascript" src="<?=base_url('assets/js/bootstrap-select.min.js')?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url();?>assets/js/ddtf.js"></script>
<script src="<?php echo base_url();?>assets/js/toast.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-confirm.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-dateformat.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>

<!-- DataTables -->
<script type="text/javascript" src="<?=base_url('assets/js/datatables/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/datatables/dataTables.bootstrap4.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/datatables/dataTables.buttons.min.js')?>"></script> <!-- buttons -->
<script type="text/javascript" src="<?=base_url('assets/js/datatables/jszip.min.js')?>"></script> <!-- excel -->
<script type="text/javascript" src="<?=base_url('assets/js/datatables/pdfmake.min.js')?>"></script> <!-- pdf -->
<script type="text/javascript" src="<?=base_url('assets/js/datatables/vfs_fonts.js')?>"></script> <!-- pdf -->
<script type="text/javascript" src="<?=base_url('assets/js/datatables/buttons.html5.min.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/datatables/buttons.print.min.js')?>"></script>

<!-- Vue JS -->
<script src="<?php echo base_url();?>assets/js/vue.js"></script>
<!-- App JS -->
<script src="<?php echo base_url();?>assets/js/app/app.js"></script>
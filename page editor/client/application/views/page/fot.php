<script src="<?php echo base_url();?>assets/plugins/horizontal-menu/horizontal-menu-2/horizontal-menu.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap Bundle js -->
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Ionicons js -->
<script src="<?php echo base_url();?>assets/plugins/ionicons/ionicons.js"></script>
<!-- Moment js -->
<script src="<?php echo base_url();?>assets/plugins/moment/moment.js"></script>

<!-- Rating js-->
<script src="<?php echo base_url();?>assets/plugins/rating/jquery.rating-stars.js"></script>
<script src="<?php echo base_url();?>assets/plugins/rating/jquery.barrating.js"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="<?php echo base_url();?>assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/perfect-scrollbar/p-scroll.js"></script>
<!--Internal Sparkline js -->
<script src="<?php echo base_url();?>assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<!-- Custom Scroll bar Js-->
<script src="<?php echo base_url();?>assets/plugins/mscrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- right-sidebar js -->
<script src="<?php echo base_url();?>assets/plugins/sidebar/sidebar-rtl.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sidebar/sidebar-custom.js"></script>
<!-- Eva-icons js -->
<script src="<?php echo base_url();?>assets/js/eva-icons.min.js"></script>
<!--Internal  Chart.bundle js -->
<script src="<?php echo base_url();?>assets/plugins/chart.js/Chart.bundle.min.js"></script>
<!-- Moment js -->
<script src="<?php echo base_url();?>assets/plugins/raphael/raphael.min.js"></script>
<!--Internal  Flot js-->
<script src="<?php echo base_url();?>assets/plugins/jquery.flot/jquery.flot.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.flot/jquery.flot.pie.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.flot/jquery.flot.resize.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.flot/jquery.flot.categories.js"></script>
<script src="<?php echo base_url();?>assets/js/dashboard.sampledata.js"></script>
<script src="<?php echo base_url();?>assets/js/chart.flot.sampledata.js"></script>
<!--Internal Apexchart js-->
<script src="<?php echo base_url();?>assets/js/apexcharts.js"></script>
<!-- Internal Map -->
<script src="<?php echo base_url();?>assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="<?php echo base_url();?>assets/js/modal-popup.js"></script>
<!--Internal  index js -->
<script src="<?php echo base_url();?>assets/js/index.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.vmap.sampledata.js"></script>
<!-- Sticky js -->
<script src="<?php echo base_url();?>assets/js/sticky.js"></script>
<!-- custom js -->
<script src="<?php echo base_url();?>assets/js/custom.js"></script><!-- Left-menu js-->
<script src="<?php echo base_url();?>assets/plugins/side-menu/sidemenu.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sidebar-rtl.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sidebar-custom.js"></script>
<!-- Switcher js -->
<script src="<?php echo base_url();?>assets/switcher/js/switcher-rtl.js"></script>
<script>
	$(document).ready(function () {
		$("#themeL").click(function (e) {
			e.preventDefault();
			$("#tmMode").addClass('light-theme');
			$("#tmMode").removeClass('dark-theme');
		})
		$("#themeD").click(function (e) {
			e.preventDefault();
			$("#tmMode").addClass('dark-theme');
			$("#tmMode").removeClass('light-theme');
		})
	})
</script>
</body>
</html>
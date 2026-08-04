</div>
<!-- END Wrapper -->

<!-- Vendor Javascript (Require in all Page) -->
<script src="/Duan1-main/public/admin/assets_admin/js/vendor.js"></script>

<!-- App Javascript (Require in all Page) -->
<script src="/Duan1-main/public/admin/assets_admin/js/app.js"></script>

<?php 
// Only load dashboard-specific scripts on the dashboard page
if (isset($view) && $view === 'dashboard'): 
?>
<!-- Vector Map Js -->
<script src="/Duan1-main/public/admin/assets_admin/vendor/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/Duan1-main/public/admin/assets_admin/vendor/jsvectormap/maps/world-merc.js"></script>
<script src="/Duan1-main/public/admin/assets_admin/vendor/jsvectormap/maps/world.js"></script>

<!-- Dashboard Js -->
<script src="/Duan1-main/public/admin/assets_admin/js/pages/dashboard.js"></script>
<?php endif; ?>

</body>


<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Mar 2025 17:42:50 GMT -->

</html>
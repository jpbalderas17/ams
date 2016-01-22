
    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="js/datatables.buttons.js"></script>
    <script src="js/datatables.buttons.print.js"></script>
	<script src="bower_components/select2/js/select2.full.js" ></script>

    <!--<script src="bower_components/datatables-responsive/js/dataTables.responsive.js" ></script>-->


    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

	<script type="text/javascript">
  $('select').select2({
        placeholder:$(this).data("placeholder")
  });
  $('select').each(function(index,element){
    if(typeof $(element).data("selected") !== "undefined"){
    $(element).val($(element).data("selected")).trigger("change");
    }
  });
</script>
</body>
</html>
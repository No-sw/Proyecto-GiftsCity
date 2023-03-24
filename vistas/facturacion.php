<?php
if (strlen(session_id()) < 1) 
session_start();

    require('header.php');
?>
<div class="content-wrapper">

<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="../files/empresa/logo.jpg" alt="AdminLTELogo" height="120" width="120">
  </div>
      
<!-- Input addon -->
<div class="card card-info">
<div class="row">
    <div class="col-4">
<?php
if ($_SESSION['prodinsert']==1){
  echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
    Registrar Factura
  </button>';
  }
?>
</div>
<div class="col-4">
        <button type="button" onclick="verreporte();" class="btn btn-primary ">Ver reporte</button>
            </div>
        </div>

              <div class="card-header">
                <h3 class="card-title">Registro de Factura</h3>
              </div>
              <div class="card-body">
                
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Producto</th>
                    <th>Cliente</th>
                    <th>Evento</th>
                    <th>Subtotal</th>
                    <th>Impuesto</th>
                    <th>Codicion</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Opciones</th>
                    <th>Producto</th>
                    <th>Evento</th>
                    <th>Cliente</th>
                    <th>Subtotal</th>
                    <th>Impuesto</th>
                    <th>Codicion</th>
                  </tfoot>
                </table>
                <!-- /input-group -->
              </div>
              <!-- /.card-body -->
<div class="form-group">
<a href="index.php"><i class="fa-solid fa-floppy-disk-circle-arrow-right"></i>Salir</a>
</div>

            </div>
            


</div>


<?php
    require('footer.php');
?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Factura</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="" method="post" name="formulario" id="formulario">

        <div class="form-group row">
            <div class="col-md-5">
              <label for="">Producto:</label>
              
              <input type="hidden" name="idfacturacion" id="idfacturacion" class="form-control" value="">
              
              <input type="text" class="form-control" id="producto" name="producto"  aria-hidden="true"></i>
          </div>
          <div class="col-md-5">
              <label for="">Cliente:</label>
              
              
              <input type="text" class="form-control" id="cliente" name="cliente"  aria-hidden="true"></i>
          </div>
          </div>
          <div class="form-group row">
          <div class="col-md-4">
              <label for="">Evento:</label>
              <select class="form-control form-control-sm-6" name="evento" id="evento">
                </select>
          </div>
          <div class="col-md-4">
              <label for="">Subtotal:</label>
              
              
              <input type="text" class="form-control" id="venta" name="venta"  aria-hidden="true">
          </div>
          <div class="col-md-4">
              <label for="">Impuesto:</label>
               <select class="form-control form-control-sm-3" name="impuesto" id="impuesto">
                  <option select value=0>0</option>
                  <option value=15>15</option>
                  <option value=18>18</option>
                </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="guardaryEditar();" class="btn btn-primary">Registrar Factura</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript" src="script/facturacion.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
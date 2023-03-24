<?php
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
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Registrar Evento
</button>
</div>
<div class="col-4">
<button type="button" onclick="reporte();" class="btn btn-primary ">Ver reporte</button>
    </div>
</div>

              <div class="card-header">
                <h3 class="card-title">Registro de Evento</h3>
              </div>
              <div class="card-body">
                
              <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Opciones</th>
                    <th>Evento</th>
                    <th>fecha de Inicio</th>
                    <th>fecha Final</th>
                    <th>Descripcion</th>
                    <th>Condicion</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Opciones</th>
                    <th>Evento</th>
                    <th>fecha de Inicio</th>
                    <th>fecha Final</th>
                    <th>Descripcion</th>
                    <th>Condicion</th>
                  </tr>
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
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form action="" method="post" name="formulario" id="formulario">

        <div class="form-group row">
            <div class="col-md-6">
              <label for="">Evento:</label>
              
              <input type="hidden" name="idinventario" id="idinventario" class="form-control" value="">
              
              <input type="text" class="form-control" id="evento" name="evento"  aria-hidden="true"></i>
          </div>
          <div class="col-md-6">
              <label for="">fechaInicio:</label>
              
              
             <input type="date" class="form-control" id="inicio" name="inicio"  aria-hidden="true">
          </div>
          <div class="col-md-6">
              <label for="">fechaFinal:</label>
              
              
              <input type="date" class="form-control" id="final" name="final"  aria-hidden="true">
          </div>
          <div class="col-md-6">
              <label for="">Descripcion:</label>
              
              
              <input type="text" class="form-control" id="descripcion" name="descripcion"  aria-hidden="true">
          </div>
        <div>


            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="guardaryeditar();" class="btn btn-primary">Registrar Evento</button>
      </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="script/inventario.js"></script>
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
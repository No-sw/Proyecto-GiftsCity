var tabla;
function init(){
    limpiar();
    listar();
}
function limpiar(){
        $("#idinventario").val(""); 
        $("#evento").val("");
        $("#inicio").val("");
        $("#final").val("");
        $("#descripcion").val("");
        
}

function reporte(){
    alert('Entro a ver el reporte');

    window.open('../reportes/rptinventario.php','_blank');
}

function guardaryeditar(){

    var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/inventario.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false, 

	    success: function(datos)
	    {                    
	         alert(datos);	          
	          
	         
	          tabla.ajax.reload();
              limpiar();
              $("#exampleModal").modal('hide');
	    }

	});

}
function activar(idinventario){
    
    
    Swal.fire({
        title: 'Esta seguro de activar el registro?',
        text: "Se activara el registro",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Activar!'
      }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/inventario.php?op=activar", {idinventario : idinventario}, function(e){
    //            alert(e);
    Swal.fire(
        'Activar',
        'El registro a sido activado',
        'success'
      )
                tabla.ajax.reload();
            });	
         
        }
      })
            
}
function desactivar(idinventario){

    Swal.fire({
        title: 'Esta seguro de anular el registro?',
        text: "Se anulara el registro",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Anular!'
      }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/inventario.php?op=desactivar", {idinventario : idinventario}, function(e){
                alert(e);
                tabla.ajax.reload();
            });	
          Swal.fire(
            'Anular',
            'El registro a sido anulado',
            'success'
          )
        }
      })

        
    }


	
function mostrar(idinventario){

    $("#exampleModal").modal('show');

    $.post("../ajax/inventario.php?op=mostrar",{idinventario : idinventario}, function(data, status)
    {
        data = JSON.parse(data);
        $("#idinventario").val(data.idinventario);
        $("#evento").val(data.Evento);
        $("#inicio").val(data.fechaInicio);
        $("#final").val(data.fechafinal);
        $("#descripcion").val(data.Descripcion);
        
        
    });
}      

function listar(){
    
    tabla=$('#example1').dataTable(
        {
            "aProcessing": true,//Activamos el procesamiento del datatables
            "aServerSide": true,//Paginación y filtrado realizados por el servidor
            dom: 'Bfrtip',//Definimos los elementos del control de tabla
            buttons: [		          
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'pdf'
                    ],
            "ajax":
                    {
                        url: '../ajax/inventario.php?op=listar',
                        type : "get",
                        dataType : "json",						
                        error: function(e){
                            console.log(e.responseText);	
                        }
                    },
            "bDestroy": true,
            "iDisplayLength": 25,//Paginación
            "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
        }).DataTable();
   //
}
init();
var tabla;
function init(){
    limpiar();
    listar();

    
    $.post("../ajax/facturacion.php?op=selectevento", function (e){
            $("#evento").html(e);
            $("#evento").selectpicker('refresh');


    } );
}
function verreporte(){
    alert('Entro a ver el reporte');

    window.open('../reportes/rptfacturacion.php','_blank');
}
function limpiar(){
        $("#idfacturacion").val("");
        $("#producto").val("");
        $("#cliente").val("");
        $("#evento").val("");
        $("#venta").val("");
        $("#impuesto").val("");
        
}

function guardaryEditar(){

    var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/facturacion.php?op=guardaryeditar",
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
function activar(idfacturacion){
    
    
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
            $.post("../ajax/facturacion.php?op=activar", {idfacturacion : idfacturacion}, function(e){
    //            alert(e);
    Swal.fire(
        'Activar',
        e,
        'success'
      )
                tabla.ajax.reload();
            });	
         
        }
      })
            
}
function desactivar(idfacturacion){

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
            $.post("../ajax/facturacion.php?op=desactivar", {idfacturacion : idfacturacion}, function(e){
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


	
function mostrar(idfacturacion){

    $("#exampleModal").modal('show');

    $.post("../ajax/facturacion.php?op=mostrar",{idfacturacion : idfacturacion}, function(data, status)
    {
        data = JSON.parse(data);
        $("#idfacturacion").val(data.idfacturacion);
        $("#producto").val(data.producto);
        $("#cliente").val(data.Cliente);
        $("#evento").val(data.idEvento);
        $("#venta").val(data.venta);
        $("#impuesto").val(data.impuesto);
        
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
                        url: '../ajax/facturacion.php?op=listar',
                        type : "get",
                        dataType : "json",						
                        error: function(e){
                            console.log(e.responseText);	
                        }
                    },
            "bDestroy": true,
            "iDisplayLength": 50,//Paginación
            "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
        }).DataTable();
   //
}
init();
var tabla;
function init(){
    limpiar();
    listar(); 

    
    $.post("../ajax/usuario.php?op=permisos&id=0",function(r){
        $("#permisos").html(r);
        $("#permisos1").html(r);
});


}

function limpiar(){
    
        $("#idusuario").val("");
        $("#login").val("");
        $("#usuario").val("");
        $("#password").val("");
        $("#correo").val("");
        $("#imagen").val("");
        
}

function guardaryeditar(){

    var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
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
function activar(idusuario){
    
    
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
            $.post("../ajax/usuario.php?op=activar", {idusuario : idusuario}, function(e){
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
function desactivar(idusuario){

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
            $.post("../ajax/usuario.php?op=desactivar", {idusuario : idusuario}, function(e){
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


	
function mostrar(idusuario){

    $("#exampleModal").modal('show');

    $.post("../ajax/usuario.php?op=mostrar",{idusuario : idusuario}, function(data, status)
    {
        data = JSON.parse(data);
        $("#idusuario").val(data.idusuario);
        $("#login").val(data.login);
        $("#usuario").val(data.usuario);
        $("#password").val(data.clave); 
        $("#correo").val(data.correo);
        $("#imagen").val(data.imagen);
        
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
                        url: '../ajax/usuario.php?op=listar',
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
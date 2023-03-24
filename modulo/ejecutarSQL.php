<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class ejecutarSQL
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros insert, update, delete
	public function insertar($sql)
	{
		
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($sql)
	{
		
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($sql)
	{
		return ejecutarConsulta($sql);
	}
    function limpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion,trim($str));
        return htmlspecialchars($str);
    }

	//Implementamos un método para activar categorías
	public function activar($sql)
	{
		
		return ejecutarConsulta($sql);
	}

public function insertarfactura($idfactura,$idarticulo,$cantidad,$precio_venta,$descuento)
    {
        
        
        $num_elementos=0;
        $sw=true;
        $l=0;
     
        while ($num_elementos < count($idarticulo))
        {
        
            $sub_=$precio_venta[$num_elementos]*$cantidad[$num_elementos];
            $sql_detalle = "INSERT INTO  detallefactura VALUES ('$idfactura', '$idarticulo[$num_elementos]','','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$sub_','$descuento[$num_elementos]','$l')";
            
            ejecutarConsulta($sql_detalle);
            $num_elementos=$num_elementos + 1;
        }
        //INSERT INTO detallefactura VALUES ('17', 'AG-03','',1','100','100','0','0')

        return $sw;
    }


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($sql)
	{
		
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($sql)
	{
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categoria where condicion=1";
		return ejecutarConsulta($sql);		
	}
    
	

}

?>
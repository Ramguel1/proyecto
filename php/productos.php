<?php

    require_once "config.php";
    header('Content-Type: application/json; charset=utf-8');

    $respuesta = ["success" => false, "mensaje" => ""];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        if ($action === 'add') {
            $valido['success']=array('success'=>false, 'mensaje'=>"");

if($_POST){
        $a=$_POST['nombre'];
        $b=$_POST['precio'];
        $c=$_POST['categoria'];
  
        $sql="INSERT INTO productos VALUES (null,'$a','$b','$c')";
        if($cx->query($sql)){
            $valido['success']=true;
        }else{
            $valido['success']=false;
        }
}else{
    $valido['success']=false;
}
 echo json_encode($valido);

    
        } elseif ($action === 'update') {
           
if($_POST){
    $id=$_POST['id'];
    $a=$_POST['nombre'];
    $b=$_POST['precio'];
    $c=$_POST['categoria'];
    $d=$_POST['boleto'];
    $e=$_POST['fecha'];

    $sql = "UPDATE productos SET nombre='$a', precio='$b', categoria='$c',  WHERE id=$id";

    if($cx->query($sql)){
       $valido['success']=true;
       $valido['mensaje']="SE ACTUALIZO CORRECTAMENTE";
    }else{
        $valido['success']=false;
       $valido['mensaje']="ERROR AL ACTUALIZAR EN BD"; 
    }
    
}else{
$valido['success']=false;
$valido['mensaje']="ERROR AL GUARDAR";
}

echo json_encode($valido);
  
        } elseif ($action === 'cargar') {
            
            header('Content-Type: text/html; charset=utf-8');

$sql="SELECT * FROM productos";
$registro=array('data'=>array());
$res=$cx->query($sql);
if($res->num_rows>0){
    while($row=$res->fetch_array()){
        $registro['data'][]=array($row[0],$row[1],$row[2],$row[3]);
    }
}

echo json_encode($registro);

        } elseif ($action === 'eliminar') {
            $valido['success']=array('success'=>false,'mensaje'=>"");

if($_POST){
    $id=$_POST['id'];
    $sqle="DELETE FROM productos WHERE  id=$id";
    if($cx->query($sqle)){
       $valido['success']=true;
       $valido['mensaje']="SE ELIMINO CORRECTAMENTE";
    }else{
        $valido['success']=false;
       $valido['mensaje']="ERROR AL ELIMINAR EN BD"; 
    }
    
}else{
$valido['success']=false;
$valido['mensaje']="ERROR AL ELIMINAR";

}

echo json_encode($valido);
      
        } elseif ($action === 'consul'){
            header('Content-Type: text/html; charset=utf-8');
            $valido['success']=array('success'=>false,
            'mensaje'=>"",
            'productosid'=>"",
            'nombre'=>"",
            'precio'=>"",
            'categoria'=>"",
            
           );
            if ($_POST) {
            $id=$_POST['id'];
            $sql="SELECT * FROM productos WHERE id=$id";
            $res=$cx->query($sql);
            $row=$res->fetch_array();
            $valido['success']=true;
            $valido['mensaje']="SE ENCONTRO REGISTRO";
            $valido['id']=$row[0];
            $valido['nombre']=$row[1];
            $valido['precio']=$row[2];
            $valido['categoria']=$row[3];
            }else{
                $valido['success']=false;
                $valido['mensaje']="NO SE ENCONTRO EL productos";
            
            }
            
            echo json_encode($valido);
        }else{

        echo json_encode($respuesta);
    } 
    
}else {
        echo json_encode(["error" => "Método no permitido"]);
    }
?>



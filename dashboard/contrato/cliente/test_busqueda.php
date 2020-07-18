<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body>


<script src="http://localhost/crmcreditos.git/trunk/AdminLTE/plugins/jquery/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function () {

		dos();

function dos(){
	var url = 'https://www.prevenciondelavado.com/listas/api/busqueda';
  var data = { Apellido: 'LOPEZ OBRADOR',  Nombre: 'ANDRES MANUEL',  Usuario: 'anal2', Password: '7D434594'};

fetch(url, {
  method: 'POST', // or 'PUT'
  credentials: 'omit',
  body: JSON.stringify(data), // data can be `string` or {object}!
  headers:{
    'Content-Type': 'application/json'
  }
}).then(res => res.json())
.catch(error => console.error('Error:', error))
.then(response => console.log('Success:', response));

}
		function buscaPPE(){ 
  $.ajax({
    "method":"POST",
    "url":"https://www.prevenciondelavado.com/listas/api/busqueda",
    "cache":false,
    "dataType": "json",
    "contentType": "application/json;",
    "data": {'Apellido':'ioioioioioi', 'Nombre':'oioioioioiio', 'Identificacion':'nnnnnnnnnn', 'SATxDenominacion':'N','Usuario':'uuuuuuu','Password':'pppppppp'},
    success: function(data){

          if (data == '') {
            swal({
                title: "Respuesta",
                text: "Registro Eliminado con exito!!",
                type: "success",
                timer: 1500,
                showConfirmButton: false
            });
            $('#lgmEliminar').modal('toggle');
            table.ajax.reload();
          } else {
            swal({
                title: "Respuesta",
                text: data,
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });

          }
        },
  });
}


	});

</script>
</body>
</html>
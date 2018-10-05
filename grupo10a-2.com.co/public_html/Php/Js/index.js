$(document).ready(function () {
    show(); //METODO UTILIZADO PARA MOSTRAR LUS USUARIOS QUE FUERON REGISTRADOS

    function show() {
        $.ajax({
            type: 'POST',
            url: '../Php/Register.php',
            data:
            {
                "action": "show"
            }
        })
            .done(function (data) {
                $("table#show_users tbody tr").remove();
                var array = JSON.parse(data);
                var n = 1;

                array.forEach(element => { 
                    var usr = "";
                    usr = usr.concat(
                        "<tr>",
                        "<td>" + n + "</td>",
                        "<td>" + element.document + "</td>",
                        "<td>" + element.name + "</td>",
                        "<td>" + element.lastname + "</td>",
                        "<td>" + element.phone + "</td>",
                        "<td>" + element.address + "</td>",
                        "<td><button type='button' value='"+ element.id +"' class='btn btn-danger' id='delete'>Delete</button></td>",
                        "<td><button type='button' value='"+ element.id +"' class='btn btn-success' id='update'>Update</button></td>",
                        "</tr>"
                    );
                    n++;
                    $("table#show_users tbody").append(usr).closest("table#show_users");
                });
            })
    }

    $("#save_user").on("click", function () { //ACCION QUE SE EJECUTA PARA HACER EL REGISTRO DE USUARIOS
        registerUser();  

    });

    function registerUser() {
        var name = $("#name").val();
        var lastname = $("#last_name").val();
        var address = $("#address").val();
        var phone = $("#phone").val();
        var document = $("#document").val();

        $.ajax({
            type: 'POST',
            url: '../Php/Register.php',
            data:
            {
                "name": name,
                "lastname": lastname,
                "address": address,
                "phone": phone,
                "document": document,
                "action": "register"
            }
        })
        .done(function(params) {
            show();
            clearFormRegister();
        })

    }

    function clearFormRegister() {  //FUNCION UTILIZADA PARA LIMPIAR EL FORMULARIO 
                                    //DE REGISTRO UNA VES QUE SE AH TERMINADO DE REGISTRAR UN NUEVO USUARIO
        $("#name").val('');
        $("#last_name").val('');
        $("#address").val('');
        $("#phone").val('');
        $("#document").val('');
    }





    $("#show_users").on("click", "#delete", function () { // ACCION QUE NOS PERMITE ELIMINAR UN USUARIO DE LA BASE DE DATOS
        
        delete_user(this.value);

    });

    function delete_user(id){//FUNCION PARA ELIMINAR USUARIOS
        var option = confirm('desea eliminar este usuario?');
        if(option){
            $.ajax({
                type: 'POST',
                url: '../Php/Register.php',
                data:
                {
                    "id": id,
                    "action": "delete"
                }
            })
            .done(function(msg){
                console.log(msg)
                show();
            })
        }
    }

})
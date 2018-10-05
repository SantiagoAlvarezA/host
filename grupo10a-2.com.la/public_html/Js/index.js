$(document).ready(function () {
    show();

    function show() { //mostrar los paises registrados
        $.ajax({
            type: 'POST',
            url: '../Php/index.php',
            data:
            {
                "action": "show"
            }
        })
            .done(function (data) {
                document.getElementById("show").innerHTML="";
                var array = JSON.parse(data);
                array.forEach(element => {
                    var usr = '';
                    usr = usr.concat(
                        '<div class="m-3">',
                            '<div class="card border-info" style="width: 20rem;">',
                                '<div class="card-header text-white text-center bg-info"> ' + element.country_name + '</div>',
                                    '<ul class="list-group list-group-flush">',
                                        '<li class="list-group-item">Capital: ' + element.capital + '</li>',
                                        '<li class="list-group-item">Continente: ' + element.continent + '</li>',
                                        '<li class="list-group-item">N° Habitantes: ' + element.number_habitants + '</li>',
                                        '<li class="list-group-item">Area: ' + element.area + '</li>',
                                    '</ul>',                                
                            '</div>',
                        '</div>'
                    );
                    $("#show").append(usr).closest("#show");
                });
            })
    }








    $("#insert").on("click", function () { //ACCION QUE SE EJECUTA PARA HACER EL REGISTRO DE USUARIOS
        var country_name = $('#country_name').val();
        var continent = $('#continent').val();
        var capital = $('#capital').val();
        var area = $('#area').val();
        var number_habitants = $('#number_habitants').val();

        if (country_name != '' && continent != '' && capital != '' && area != '' && number_habitants != '') {

            insert_country(country_name, continent, capital, area, number_habitants);

        } else {
            alert("Uno de los campos esta vacio");
        }


    });









    //Funcion utilizada para insertar paises

    function insert_country(country_name, continent, capital, area, number_habitants) {


        $.ajax({
            type: 'POST',
            url: '../Php/index.php',
            data:
            {
                "country_name": country_name,
                "continent": continent,
                "capital": capital,
                "area": area,
                "number_habitants": number_habitants,
                "action": "insert"
            }
        })
            .done(function (params) {
                show();
                clearFormRegister();
                
            })
    }





    function clearFormRegister() { //funcion para limpiar formulario
        $('#country_name').val('');
        $('#continent').val('');
        $('#capital').val('');
        $('#area').val('');
        $('#number_habitants').val('');
    }


});
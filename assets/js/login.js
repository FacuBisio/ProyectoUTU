document.getElementById('btnAgregar').onclick = function () {

    document.getElementById('comentario').value +=
        "Usuario: " + document.getElementById('usuario').value + "\n" +
        "Contraseña: " + document.getElementById('password').value + "\n" +
        "Correo: " + document.getElementById('correo').value + "\n" +
        "Contacto: " + document.getElementById('contacto').value + "\n" +
        "Departamento: " + document.getElementById('departamento').value + "\n";

    document.getElementById('usuario').value = "";
    document.getElementById('password').value = "";
    document.getElementById('correo').value = "";
    document.getElementById('contacto').value = "";
    document.getElementById('departamento').value = "";
};
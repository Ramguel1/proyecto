var sesion = localStorage.getItem('usuario') || "null";
if (sesion === "null") {
    window.location.href = "index.html";
}


var nombre = document.getElementById("nombre");
var apellido = document.getElementById("ap");

const cargarNombre = async () => {
    let datos = new FormData();
    datos.append("usuario", sesion);
    datos.append("action", "select");

    try {
        let respuesta = await fetch("php/usuario.php", { method: 'POST', body: datos });
        if (!respuesta.ok) {
            throw new Error('Network response was not ok');
        }
        let json = await respuesta.json();

        if (json.success === true) {
            document.getElementById("user").innerHTML = `${json.mensaje}`;

            // Cargar la imagen de perfil
            if (json.foto && json.foto.trim() !== '') {
                document.getElementById("foto_perfil").src = `php/${json.foto.trim()}`;
            } else {
                document.getElementById("foto_perfil").src = 'assets/img_profile/icono3.jpeg'; // Imagen por defecto
            }
        } else {
            Swal.fire({ title: "ERROR", text: json.mensaje, icon: "error" });
        }
    } catch (error) {

    }
}

document.getElementById("btnSalir").onclick = async () => {
    Swal.fire({
        title: "cerrar sesion",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.clear();
            window.location.href = "index.html";
        }
    });
}

const cargarPerfil = async () => {
    let datos = new FormData();
    datos.append("usuario", sesion);
    datos.append("action", "perfil");
 
    try {
        let respuesta = await fetch("php/usuario.php", { method: 'POST', body: datos });
        if (!respuesta.ok) {
            throw new Error('Network response was not ok');
           
        }
        let json = await respuesta.json();

        if (json.success === true) {
            document.getElementById("email").innerHTML = json.usuario;
            document.getElementById("nombre").value = json.nombre;
            document.getElementById("ap").value = json.apellido;
            if (json.foto.trim() !== '') {
                document.getElementById("foto-preview").innerHTML = `<img src="php/${json.foto.trim()}" class="foto-perfil">`;
                document.getElementById("foto-preview1").innerHTML = `<img src="php/${json.foto.trim()}" class="profile-photo">`;
            } else {
                document.getElementById("foto-preview").innerHTML = '';
                document.getElementById("foto-preview1").innerHTML = '';
            }
        } else {
            Swal.fire({ title: "ERROR", text: json.mensaje, icon: "error" });
        }
    } catch (error) {

    }
}

const guardarPerfil = async () => {
    let formPerfil = document.getElementById("formPerfil");
    let datos = new FormData(formPerfil);
    datos.append("usuario", sesion);
    datos.set("nombre", nombre.value);
    datos.set("apellido", apellido.value);
    datos.append("action", "saveperfil");

    try {
        let respuesta = await fetch("php/usuario.php", { method: 'POST', body: datos });
        if (!respuesta.ok) {
            throw new Error('Network response was not ok');
           
        }
        let json = await respuesta.json();
 
        if (json.success === true) {
            Swal.fire({
                icon: "success",
                title: "sisi",
                text: json.mensaje,
                


            })
            cargarPerfil();
            
            location.reload(true)
        } else {
            Swal.fire({ title: "mal", text: json.mensaje, icon: "error" });
        }
    } catch (error) {
        Swal.fire({ title: "mal", text: "Error al guardar el perfil", icon: "error" });
    }
}



document.getElementById("btnGuardar").onclick = guardarPerfil,recargar;

function recargar () {
    window.location.href = window.location.href;
}
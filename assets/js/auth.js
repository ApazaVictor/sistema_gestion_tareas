async function login(event) {
    event.preventDefault();

    const correo = document.getElementById("correo").value;
    const contrasena = document.getElementById("contrasena").value;

    try {
        const respuesta = await fetch("login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                correo,
                contrasena,
            }),
        });

        const respuestaJson = await respuesta.json();

        if (respuestaJson.status === "error") {
            showAlertAuth("loginAlert", "error", respuestaJson.message);
            return false;
        }

        window.location.href = "../tareas";
    } catch (error) {
        showAlertAuth("loginAlert", "error", "Error al iniciar sesión: " + error);
        return false;
    }
}

async function register(event) {
    event.preventDefault();

    const nombre = document.getElementById("nombre").value;
    const correo = document.getElementById("correo").value;
    const contrasena = document.getElementById("contrasena").value;
    const confirmar_contrasena = document.getElementById("confirmar_contrasena").value;

    if (!nombre || !correo || !contrasena || !confirmar_contrasena) {
        showAlertAuth("registerAlert", "error", "Todos los campos son obligatorios");
        return false;
    }

    if (contrasena !== confirmar_contrasena) {
        showAlertAuth("registerAlert", "error", "Las contraseñas no coinciden");
        return false;
    }

    try {
        const respuesta = await fetch("register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                nombre,
                correo,
                contrasena
            }),
        });

        const respuestaJson = await respuesta.json();

        if (respuestaJson.status === "error") {
            showAlertAuth("registerAlert", "error", respuestaJson.message);
            return false;
        }

        showAlertAuth("registerAlert", "success", "Registro exitoso. Redirigiendo...");
        setTimeout(() => {
            window.location.href = "login";
        }, 2000);
    } catch (error) {
        showAlertAuth("registerAlert", "error", "Error al registrar: " + error);
        return false;
    }
}

function showAlertAuth(containerId, type, message) {
    const container = document.getElementById(containerId);
    const alertClass = type === "error" ? "alert-danger" : "alert-success";
    container.innerHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
}

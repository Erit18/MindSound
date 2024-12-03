const inputs = document.querySelectorAll(".input");
const form = document.querySelector("form");

// Función mejorada para el foco
function focusFunc() {
    let parent = this.parentNode;
    parent.classList.add("focus");
    this.classList.add("active");
}

// Función mejorada para cuando pierde el foco
function blurFunc() {
    let parent = this.parentNode;
    if (this.value == "") {
        parent.classList.remove("focus");
        this.classList.remove("active");
    }
}

// Validación básica del formulario
form.addEventListener("submit", function(e) {
    e.preventDefault();
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add("error");
        } else {
            input.classList.remove("error");
        }
    });

    if (isValid) {
        // Aquí puedes agregar el código para enviar el formulario
        alert("Mensaje enviado correctamente");
        form.reset();
    }
});

inputs.forEach(input => {
    input.addEventListener("focus", focusFunc);
    input.addEventListener("blur", blurFunc);
    
    // Eliminar clase de error al escribir
    input.addEventListener("input", function() {
        this.classList.remove("error");
    });
});



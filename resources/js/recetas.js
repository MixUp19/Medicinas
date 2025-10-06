document.addEventListener("DOMContentLoaded", () => {
    const medicamentoSelect = document.getElementById("medicamento-select");
    const unidadesInput = document.getElementById("unidades-input");
    const agregarBtn = document.getElementById("agregar-btn");
    const listaContainer = document.getElementById("lista-agregados-container");
    const enviarBtn = document.getElementById("enviar-btn");
    const mensajeVacio = document.getElementById("mensaje-vacio");

    if (
        !medicamentoSelect ||
        !unidadesInput ||
        !agregarBtn ||
        !listaContainer ||
        !enviarBtn ||
        !mensajeVacio
    ) {
        console.error(
            "No se encontraron todos los elementos necesarios en el DOM."
        );
        return;
    }

    let medicamentosAgregados = new Map();

    const actualizarBotonEnviar = () => {
        enviarBtn.disabled = medicamentosAgregados.size === 0;
    };

    const renderizarLista = () => {
        listaContainer.innerHTML = "";

        if (medicamentosAgregados.size === 0) {
            listaContainer.appendChild(mensajeVacio);
        } else {
            medicamentosAgregados.forEach((medicamento, id) => {
                const itemDiv = document.createElement("div");
                itemDiv.className =
                    "flex justify-between items-center bg-gray-50 p-3 rounded-md border";
                itemDiv.setAttribute("data-id", id);

                itemDiv.innerHTML = `
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 w-full">
                        <div class="flex flex-col gap-2 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <h3 class="text-lg font-semibold text-gray-900">${medicamento.nombre}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    $${medicamento.precio}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Cantidad: ${medicamento.unidades} </span>
                            </div>
                        </div>
                        <button class="eliminar-btn inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                `;
                listaContainer.appendChild(itemDiv);
            });
        }
        actualizarBotonEnviar();
    };

    agregarBtn.addEventListener("click", () => {
        const selectedOption =
            medicamentoSelect.options[medicamentoSelect.selectedIndex];
        const id = selectedOption.value;
        const nombre = selectedOption.getAttribute("data-nombre");
        const precio = selectedOption.getAttribute("data-precio");
        const unidades = parseInt(unidadesInput.value, 10);

        if (!id || unidades < 1) {
            alert(
                "Por favor, selecciona un medicamento y especifica al menos una unidad."
            );
            return;
        }

        if (medicamentosAgregados.has(id)) {
            alert("Este medicamento ya ha sido agregado.");
        } else {
            medicamentosAgregados.set(id, { nombre, unidades, precio });
        }

        renderizarLista();
        unidadesInput.value = "1";
    });

    listaContainer.addEventListener("click", (e) => {
        if (e.target.classList.contains("eliminar-btn")) {
            const itemDiv = e.target.closest("[data-id]");
            const id = itemDiv.getAttribute("data-id");
            medicamentosAgregados.delete(id);
            renderizarLista();
        }
    });

    enviarBtn.addEventListener("click", () => {
        if (medicamentosAgregados.size === 0) {
            alert("No hay medicamentos en la lista.");
            return;
        }

        const datosAEnviar = {
            sucursal_id: document.getElementById("sucursal-select").value,
            medicamentos: Array.from(medicamentosAgregados.entries()).map(
                ([id, data]) => ({
                    medicamento_id: id,
                    unidades: data.unidades,
                })
            ),
        };

        // Deshabilitar el botón mientras se procesa
        enviarBtn.disabled = true;
        enviarBtn.textContent = "Procesando...";

        fetch("/recetas", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "X-Requested-With": "XMLHttpRequest", // Indica que es AJAX
            },
            body: JSON.stringify(datosAEnviar),
        })
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    // Éxito: Reemplazar el contenido de la página con la nueva vista
                    document.documentElement.innerHTML = data.html;

                    // Opcional: Actualizar la URL del navegador
                    window.history.pushState({}, "Receta", "/receta");
                } else {
                    // Error: Mostrar mensaje sin recargar
                    mostrarError(data.message || "Ocurrió un error inesperado");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                mostrarError(
                    "Error de conexión. Por favor, inténtalo de nuevo."
                );
            })
            .finally(() => {
                // Rehabilitar el botón
                enviarBtn.disabled = false;
                enviarBtn.textContent = "Enviar";
            });
    });

    // Función para mostrar errores
    function mostrarError(mensaje) {
        // Crear o actualizar elemento de error
        let errorDiv = document.getElementById("error-message");

        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.id = "error-message";
            errorDiv.className =
                "fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50 max-w-md";
            document.body.appendChild(errorDiv);
        }

        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>${mensaje}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    ×
                </button>
            </div>
        `;

        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            if (errorDiv && errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    actualizarBotonEnviar();
});

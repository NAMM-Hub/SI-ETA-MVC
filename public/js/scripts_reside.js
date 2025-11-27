const BASE_URL_JS = BASE_URL_JS_GLOBAL;
console.log('✅ scripts_reside.js se está ejecutando desde el inicio.');
console.log('BASE_URL_JS:', BASE_URL_JS);
function toggleLocationInputs(selectContainerId, textContainerId, showSelect) {
    const selectContainer = document.getElementById(selectContainerId);
    const textContainer = document.getElementById(textContainerId);

    if (selectContainer) {
        // Si showSelect es TRUE, muestra el select (''), si es FALSE, lo oculta ('none')
        selectContainer.style.display = showSelect ? '' : 'none';
    }
    if (textContainer) {
        // Si showSelect es TRUE, OCULTA el texto libre ('none'). 
        // Si showSelect es FALSE, MUESTRA el texto libre ('').
        textContainer.style.display = showSelect ? 'none' : '';
    }
    console.log('Hola (Reset)');
}
function limpiarSelect(selectElement) {
    if (selectElement) {
        selectElement.innerHTML = '<option value="">Seleccione</option>';
    }
}

function cargarMunicipios(
    estadoId,
    municipioSeleccionadoId = null,
    municipioTextoGuardado = null,
    comunidadSeleccionadaId = null,
    comunidadTextoGuardada = null) {
console.log('--- DEBUG: Llamada a cargarMunicipios ---');
    const municipioSelect = document.getElementById('municipio');
    const municipioTextInput = document.getElementById('municipio_texto');
    const comunidadSelect = document.getElementById('comunidad'); 
    const comunidadTextInput = document.getElementById('ciudad_texto'); 


    limpiarSelect(municipioSelect);
    if (municipioTextInput) municipioTextInput.value = '';
    toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', false); // Oculta ambos

    limpiarSelect(comunidadSelect);
    if (comunidadTextInput) comunidadTextInput.value = '';
    toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', false); // Oculta ambos

    if (!estadoId) return; 

    // Usa BASE_URL de tu PHP para construir la ruta correcta
    let responseClone;
    fetch(BASE_URL_JS + 'Location/getMunicipios?estado_id=' + estadoId)
        .then(response => {
            responseClone = response.clone();
            return response.json()})
        .then(data => {
            console.log('Hola! Datos de municipios recibidos y procesados.');
            if (data.length > 0) {
                toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', true);
                data.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio.id_municipio;
                    option.textContent = municipio.nombre_municipio;
                    if (municipio.id_municipio == municipioSeleccionadoId) {
                        option.selected = true;
                    }
                    municipioSelect.appendChild(option);
                });

                const currentMunicipioId = municipioSelect.value || (data.length > 0 ? data[0].id_municipio : null);

                if (currentMunicipioId) {
                    cargarComunidades(currentMunicipioId, comunidadSeleccionadaId, comunidadTextoGuardada);
                }

            } else {
                
                toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', false);
                if (municipioTextInput) municipioTextInput.value = municipioTextoGuardado || '';
            }
        })
        .catch(error => {
            console.error('Error cargando municipios:', error, responseClone);
            responseClone.text()
            .then(text => console.log('Recivido los siguientes instancias validos de JSON: ', text))
            toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', false);
            if (municipioTextInput) municipioTextInput.value = municipioTextoGuardado || '';
        });
}

function cargarComunidades(
    municipioId,
    comunidadSeleccionadaId = null,
    comunidadTextoGuardada = null
) {
    const comunidadSelect = document.getElementById('comunidad');
    const comunidadTextInput = document.getElementById('ciudad_texto');

    limpiarSelect(comunidadSelect);
    if (comunidadTextInput) comunidadTextInput.value = '';
    toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', false); // Oculta ambos

    if (!municipioId) return;

    fetch(BASE_URL_JS + 'Location/getComunidades?municipio_id=' + municipioId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', true);
                data.forEach(comunidad => {
                    const option = document.createElement('option');
                    option.value = comunidad.id_comunidad;
                    option.textContent = comunidad.nombre_comunidad;
                    if (comunidad.id_comunidad == comunidadSeleccionadaId) {
                        option.selected = true;
                    }
                    comunidadSelect.appendChild(option);
                });
            } else {
                toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', false);
                if (comunidadTextInput) comunidadTextInput.value = comunidadTextoGuardada || ''; // Pre-llena si hay texto guardado
            }
        })
        .catch(error => {
            console.error('Error cargando comunidades:', error);
            toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', false);
            if (comunidadTextInput) comunidadTextInput.value = comunidadTextoGuardada || '';
        });
}

function resetDynamicLocation() {
    console.log('🔴 Ejecutando resetDynamicLocation()');
    const estadoSelect = document.getElementById('estado');
    const municipioSelect = document.getElementById('municipio');

    // --- A. Limpieza de Selects Dependientes ---
    // Limpiamos los selects dependientes y ocultamos los campos de texto libre
    // ¡La función limpiarSelect() y toggleLocationInputs() ya hacen gran parte del trabajo!
    
    // Limpiamos Municipio y Ciudad
    limpiarSelect(municipioSelect);
    limpiarSelect(document.getElementById('comunidad'));
    
    // Ocultamos los contenedores de texto libre y select de las opciones dependientes
    toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', true);
    toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', true);

    // --- B. Restaurar Estado y Recargar ---
    if (estadoSelect) {
        // 1. Restaurar el select de Estado a su valor inicial (idEstadoGuardado)
        // El valor de idEstadoGuardado debe venir del PHP.
        const initialEstadoId = typeof idEstadoGuardado !== 'undefined' ? idEstadoGuardado : '';
        estadoSelect.value = initialEstadoId;
         
        if (initialEstadoId) {
            // Si hay un ID de estado, significa que debe cargar los selects dinámicos
            cargarMunicipios(
                initialEstadoId,
                typeof idMunicipioGuardado !== 'undefined' ? idMunicipioGuardado : null,
                typeof municipioTextoGuardado !== 'undefined' ? municipioTextoGuardado : null,
                typeof idComunidadGuardada !== 'undefined' ? idComunidadGuardada : null,
                typeof comunidadTextoGuardada !== 'undefined' ? comunidadTextoGuardada : null
            );
        } else {
            // 💡 ESTA ES LA CLAVE: Si el valor inicial de Estado es NULO, 
            // el comportamiento por defecto es mostrar los campos de TEXTO LIBRE 
            // y ocultar los SELECTS dinámicos. Replicamos esa lógica de inicio.
            
            // Revertir a la configuración por defecto (Selects ocultos, Texto libre visible)
            toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', true);
            toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', true);
            
            // Pero debemos asegurarnos de mostrar el campo de texto libre
            // (La función toggleLocationInputs() con `false` en el tercer argumento
            // oculta el select y MUESTRA el texto libre).
            
        }

    }
    console.log('Hola (Reset)');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('--- DOMContentLoaded Evento Disparado ---');
    const estadoSelect = document.getElementById('estado');
    const municipioSelect = document.getElementById('municipio');
    const myForm = document.getElementById('myForm');

    
    if (typeof idEstadoGuardado !== 'undefined' && idEstadoGuardado) {
        
        cargarMunicipios(
            idEstadoGuardado,
            idMunicipioGuardado,
            municipioTextoGuardado,
            idComunidadGuardada,
            comunidadTextoGuardada
        );
    } else {
        const municipioSelect = document.getElementById('municipio');
        const comunidadSelect = document.getElementById('comunidad');
        if (municipioSelect) {
            toggleLocationInputs('contenedor_municipio_select', 'contenedor_municipio_texto', true);
        }
        if (comunidadSelect) {
             toggleLocationInputs('contenedor_comunidad_select', 'contenedor_comunidad_texto', true);
        }
    }
    
    if (estadoSelect) {
        estadoSelect.addEventListener('change', function() {
            const estadoId = this.value;
           
            cargarMunicipios(estadoId, null, null, null, null);
        });
    }

    if (municipioSelect) {
        municipioSelect.addEventListener('change', function() {
            const municipioId = this.value;
            
            cargarComunidades(municipioId, null, null);
        });
    }
    if (myForm) {
        myForm.addEventListener('reset', function(e) {
            console.log('✅ Evento "reset" del formulario capturado. Llamando a resetDynamicLocation en 50ms.');
            // Aseguramos que la función de corrección se ejecute
            // después de que el navegador haya aplicado el reset nativo.
            setTimeout(resetDynamicLocation, 50); 
        });
    }
    console.log('Hola (Carga Inicial)');
});
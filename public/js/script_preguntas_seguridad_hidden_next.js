
    const preguntasDisponibles = [
        { value: "¿Cuál era el nombre de tu primera mascota?", text: "¿Cuál era el nombre de tu primera mascota?" },
        { value: "¿Cuál es el segundo nombre de tu madre?", text: "¿Cuál es el segundo nombre de tu madre?" },
        { value: "¿Cuál es tu comida favorita?", text: "¿Cuál es tu comida favorita?" },
        { value: "¿Nombre del hermano de tú padre?", text: "¿Nombre del hermano de tú padre?" },
        { value: "¿Cuál es el nombre de la calle donde creciste?", text: "¿Cuál es el nombre de la calle donde creciste?" }
    ];

    const select1 = document.getElementById('pregunta_seguridad_1');
    const select2 = document.getElementById('pregunta_seguridad_2');
    const select3 = document.getElementById('pregunta_seguridad_3');
    const selects = [select1, select2, select3];

    function poblarSelects() {
        selects.forEach(select => {
            const valorSeleccionado = select.value;
            while (select.options.length > 1) {
                select.remove(1);
            }
            preguntasDisponibles.forEach(pregunta => {
                const option = document.createElement('option');
                option.value = pregunta.value;
                option.textContent = pregunta.text;
                select.appendChild(option);
            });
            select.value = valorSeleccionado;
        });
    }

    function filtrarOpciones() {
        const valoresSeleccionados = selects.map(select => select.value).filter(value => value !== "");

        selects.forEach(currentSelect => {
            const valorActual = currentSelect.value;
            for (let i = 0; i < currentSelect.options.length; i++) {
                const option = currentSelect.options[i];
                if (valoresSeleccionados.includes(option.value) && option.value !== valorActual) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            }
        });
    }

    function manejarCambio() {
        poblarSelects();
        filtrarOpciones();
    }

    selects.forEach(select => {
        select.addEventListener('change', manejarCambio);
    });
    manejarCambio();
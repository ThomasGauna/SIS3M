<style>
    /* Contenedor del formulario */
    .form-container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
    }

    /* Estilos generales del formulario */
    .form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .form label {
        font-weight: bold;
        color: #333;
    }

    .form input,
    .form select,
    .form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .form textarea {
        resize: vertical;
        height: 80px;
    }

    .form button {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 15px;
        width: 100%;
        transition: background 0.3s ease;
    }

    .form button:hover {
        background-color: #0056b3;
    }

    /* Estilo especial para los tipos de servicio */
    .form select option[value="Reparación"] {
        background-color: red;
        color: white;
    }

    .form select option[value="Service anual"] {
        background-color: green;
        color: black;
    }

    /* Contenedor de datos organizados */
    .datos-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
    }

    /* Responsividad */
    @media (max-width: 600px) {
        .form-container {
            padding: 15px;
        }
    }
</style>

<div class="form-container">
    <form id="formReparacion" class="form">
        <div class="datos-container">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="numero_serie">Número de Serie:</label>
                <input type="text" id="numero_serie" name="numero_serie" required>
            </div>

            <div>
                <label for="fecha_instalacion">Fecha de Instalación:</label>
                <input type="date" id="fecha_instalacion" name="fecha_instalacion">
            </div>

            <div>
                <label for="fecha_vencimiento_garantia">Fecha de Vencimiento de Garantía:</label>
                <input type="date" id="fecha_vencimiento_garantia" name="fecha_vencimiento_garantia">
            </div>

            <div>
                <label for="estado_garantia">Estado de Garantía:</label>
                <input type="text" id="estado_garantia" name="estado_garantia">
            </div>

            <div>
                <label for="horas_uso">Horas de Uso del Motor:</label>
                <input type="number" id="horas_uso" name="horas_uso">
            </div>

            <div>
                <label for="fecha_proximo_service">Próximo Servicio:</label>
                <input type="date" id="fecha_proximo_service" name="fecha_proximo_service">
            </div>
        </div>

        <label for="fecha_reparacion">Fecha de Reparación:</label>
        <input type="date" id="fecha_reparacion" name="fecha_reparacion" required>

        <label for="descripcion_reparacion">Descripción de Reparación:</label>
        <textarea id="descripcion_reparacion" name="descripcion_reparacion" required></textarea>

        <label for="tecnico">Técnico:</label>
        <input type="text" id="tecnico" name="tecnico" required>

        <label for="tipo_servicio">Tipo de Servicio:</label>
        <select id="tipo_servicio" name="tipo_servicio" required>
            <option value="Reparación">Reparación</option>
            <option value="Service anual">Service anual</option>
        </select>

        <label for="costo">Costo:</label>
        <input type="number" step="0.01" id="costo" name="costo" required>

        <button type="submit">Agregar Reparación</button>
    </form>
</div>

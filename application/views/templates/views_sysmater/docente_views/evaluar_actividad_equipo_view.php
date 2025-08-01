<div class="container mt-5">
  <h3>Evaluar Actividad en Equipo</h3>

  <!-- Datos generales -->
  <input type="hidden" id="id_actividad_equipo" value="7">
  <input type="hidden" id="id_equipo" value="3">

  <form id="formEvaluacionEquipo">
    <table class="table table-bordered mt-4">
      <thead class="table-light">
        <tr>
          <th>Criterio</th>
          <th>Calificación (0.0 - 2.0)</th>
        </tr>
      </thead>
      <tbody id="criteriosTabla">
        <!-- Estos criterios deberían generarse dinámicamente desde PHP o JS -->
        <tr>
          <td>Documentación</td>
          <td><input type="number" step="0.1" min="0" max="2" class="form-control" name="criterio_1" required></td>
        </tr>
        <tr>
          <td>Investigación</td>
          <td><input type="number" step="0.1" min="0" max="2" class="form-control" name="criterio_2" required></td>
        </tr>
        <tr>
          <td>Presentación</td>
          <td><input type="number" step="0.1" min="0" max="2" class="form-control" name="criterio_3" required></td>
        </tr>
        <!-- Agrega más criterios si los hay -->
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Guardar Evaluación</button>
  </form>

  <div id="mensaje" class="mt-3"></div>
</div>


<script>
  document.getElementById('formEvaluacionEquipo').addEventListener('submit', async function(e) {
    e.preventDefault();

    const idActividadEquipo = document.getElementById('id_actividad_equipo').value;
    const idEquipo = document.getElementById('id_equipo').value;

    const criterios = [];

    // Recorremos los inputs del formulario
    document.querySelectorAll('#criteriosTabla input').forEach((input, index) => {
      const idCriterio = index + 1; // Asegúrate de que el orden coincide con el id real
      const calificacion = parseFloat(input.value);
      criterios.push({
        id_criterio: idCriterio,
        calificacion: calificacion
      });
    });

    const payload = {
      id_actividad_equipo: parseInt(idActividadEquipo),
      id_equipo: parseInt(idEquipo),
      criterios: criterios
    };

    try {
      const response = await fetch('<?= base_url("actividades/guardar_calificaciones_equipo") ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const result = await response.json();
      const mensajeDiv = document.getElementById('mensaje');

      if (result.success) {
        mensajeDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
      } else {
        mensajeDiv.innerHTML = `<div class="alert alert-danger">${result.message || 'Error al guardar'}</div>`;
      }
    } catch (error) {
      console.error('Error:', error);
      document.getElementById('mensaje').innerHTML = `<div class="alert alert-danger">Error de red o servidor</div>`;
    }
  });
</script>
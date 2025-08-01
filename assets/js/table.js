document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('buscador');
    const table = document.getElementById('tabla_id');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    const rowsPerPage = 10;
    const maxVisiblePages = 10;
    let currentPage = 1;
    let filteredRows = rows;

    function filterTable() {
      const search = searchInput.value.toLowerCase();
      filteredRows = rows.filter(row => row.textContent.toLowerCase().includes(search));
      currentPage = 1;
      renderTable();
    }

    function renderTable() {
      tbody.innerHTML = "";
      const start = (currentPage - 1) * rowsPerPage;
      const end = start + rowsPerPage;
      const paginatedRows = filteredRows.slice(start, end);

      paginatedRows.forEach(row => tbody.appendChild(row));
      renderPagination();
      updatePaginationInfo();
    }

    function renderPagination() {
      let pagination = document.getElementById('pagination');
      pagination.innerHTML = "";

      const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

      function createButton(label, targetPage, disabled = false, isActive = false, tipo = 'pagina') {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.disabled = disabled;
        btn.classList.add('pagination-button');

        // Asignar clase según tipo
        if (tipo === 'inicio') btn.classList.add('inicio');
        else if (tipo === 'fin') btn.classList.add('fin');
        else if (tipo === 'anterior') btn.classList.add('anterior');
        else if (tipo === 'siguiente') btn.classList.add('siguiente');
        else btn.classList.add('pagina');

        if (isActive) btn.classList.add('active');

        btn.onclick = () => {
          currentPage = targetPage;
          renderTable();
        };

        pagination.appendChild(btn);
      }

      // Botones Inicio y Anterior
      createButton('⏮ INICIO', 1, currentPage === 1, false, 'inicio');
      createButton('◀ ANT', currentPage - 1, currentPage === 1, false, 'anterior');

      // Botones de páginas
      let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
      let endPage = Math.min(pageCount, startPage + maxVisiblePages - 1);
      if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
      }

      for (let i = startPage; i <= endPage; i++) {
        createButton(i, i, false, i === currentPage, 'pagina');
      }

      createButton('SIG ▶', currentPage + 1, currentPage === pageCount || pageCount === 0, false, 'siguiente');
      createButton('FIN ⏭', pageCount, currentPage === pageCount || pageCount === 0, false, 'fin');
    }
        function updatePaginationInfo() {
        const paginationInfo = document.getElementById('paginationInfo');
        const totalRecords = filteredRows.length;
        const start = totalRecords === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, totalRecords);
        
        paginationInfo.textContent = `Mostrando ${start} a ${end} de ${totalRecords} registros`;
    }

    searchInput.addEventListener('input', filterTable);

    renderTable();
  });
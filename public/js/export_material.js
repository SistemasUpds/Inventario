
    document.getElementById('export-material').onclick = function () {
        var tableId = document.getElementById('tabla-material').id;
        htmlTableToExcel(tableId, '');
    }

    var htmlTableToExcel = function (tableId, fileName = '') {
        var excelFileName = 'datos_de_tabla_excel';
        var TableDataType = 'application/vnd.ms-excel';
        var selectTable = document.getElementById(tableId);
        // Obtener todas las celdas de la tabla
        var cells = selectTable.querySelectorAll('td');
        // Reemplazar el contenido de las celdas con la versión completa de la descripción
        for (var i = 0; i < cells.length; i++) {
            var cell = cells[i];
            if (cell.classList.contains('descripcion-column')) { // Asegurarse de que se aplique solo a la columna de descripción
                var truncatedText = cell.textContent;
                var fullText = cell.getAttribute('data-full-text');
                cell.textContent = fullText;
            }
        }
        // Eliminar las celdas con la clase "export-ignore" antes de codificar la tabla
        var cellsToExclude = selectTable.querySelectorAll('.export-ignore');
        for (var i = 0; i < cellsToExclude.length; i++) {
            var cell = cellsToExclude[i];
            cell.parentNode.removeChild(cell);
        }
        
        // Codificar la tabla y agregar el BOM para UTF-8
        var htmlTable = '\uFEFF' + encodeURIComponent(selectTable.outerHTML);

        // Restaurar el contenido truncado en las celdas de descripción
        for (var i = 0; i < cells.length; i++) {
            var cell = cells[i];
            if (cell.classList.contains('descripcion-column')) {
                cell.textContent = truncatedText;
            }
        }
        
        fileName = fileName ? fileName + '.xls' : excelFileName + '.xls';
        var excelFileURL = document.createElement("a");
        document.body.appendChild(excelFileURL);

        if (navigator.msSaveOrOpenBlob) {
            var blob = new Blob([htmlTable], {
                type: TableDataType
            });
            navigator.msSaveOrOpenBlob(blob, fileName);
        } else {
            excelFileURL.href = 'data:' + TableDataType + ', ' + htmlTable;
            excelFileURL.download = fileName;
            excelFileURL.click();
        }
    }
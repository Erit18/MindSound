document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const booksContainer = document.getElementById('container');
    let timeoutId;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const searchTerm = this.value.trim();

        if (searchTerm.length < 2) {
            searchResults.classList.remove('active');
            return;
        }

        timeoutId = setTimeout(() => {
            realizarBusqueda(searchTerm);
        }, 300);
    });

    async function realizarBusqueda(termino) {
        try {
            const formData = new FormData();
            formData.append('accion', 'buscar');
            formData.append('termino', termino);

            const response = await fetch('Controlador/CLibros.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                mostrarResultados(data.data);
            }
        } catch (error) {
            console.error('Error en la búsqueda:', error);
        }
    }

    function mostrarResultados(resultados) {
        if (resultados.length === 0) {
            searchResults.innerHTML = '<div class="search-result-item"><div class="search-result-info"><div class="search-result-title">No se encontraron resultados</div></div></div>';
        } else {
            searchResults.innerHTML = resultados.map(libro => `
                <a href="detalleLibro.php?id=${libro.IDLibro}" class="search-result-item">
                    <img src="${libro.RutaPortada ? libro.RutaPortada : 'img/default-cover.jpg'}" alt="${libro.Titulo}">
                    <div class="search-result-info">
                        <div class="search-result-title">${libro.Titulo}</div>
                        <div class="search-result-author">${libro.Autor}</div>
                    </div>
                </a>
            `).join('');
        }
        searchResults.classList.add('active');
    }

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.remove('active');
        }
    });

    // Función para filtrar los libros mostrados en la página
    function filtrarLibrosEnPagina(termino) {
        const cards = booksContainer.getElementsByClassName('card');
        termino = termino.toLowerCase();

        Array.from(cards).forEach(card => {
            const titulo = card.querySelector('.card__title').textContent.toLowerCase();
            const descripcion = card.querySelector('.card__description').textContent.toLowerCase();
            
            if (titulo.includes(termino) || descripcion.includes(termino)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Agregar evento de input para filtrado en tiempo real
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        if (searchTerm.length >= 2) {
            filtrarLibrosEnPagina(searchTerm);
        } else {
            // Mostrar todos los libros si el término de búsqueda es muy corto
            Array.from(booksContainer.getElementsByClassName('card')).forEach(card => {
                card.style.display = '';
            });
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = document.body.getAttribute('data-user-logged-in') === 'true';
    const userId = document.body.getAttribute('data-user-id');
    
    console.log('Login status:', isLoggedIn);
    console.log('User ID:', userId);

    const likeButtons = document.querySelectorAll('.like-button, .heart');
    console.log('Found like buttons:', likeButtons.length);
    
    likeButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Like button clicked');
            
            if (!isLoggedIn) {
                Swal.fire({
                    title: 'Inicia sesión',
                    text: 'Debes iniciar sesión para guardar libros',
                    icon: 'warning',
                    confirmButtonColor: '#730F16',
                    confirmButtonText: 'Iniciar sesión'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Obtener la ruta base del proyecto
                        const currentPath = window.location.pathname;
                        const isInProjectRoot = currentPath.includes('/Project/') && !currentPath.includes('/category/');
                        const redirectPath = isInProjectRoot ? 'intranet.php' : '../intranet.php';
                        window.location.href = redirectPath;
                    }
                });
                return;
            }

            const bookId = this.getAttribute('data-book-id');
            const icon = this.querySelector('i') || this;
            const isLiked = icon.classList.contains('liked');
            
            try {
                const response = await fetch('Controlador/manejarLike.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        userId: userId,
                        bookId: bookId,
                        action: isLiked ? 'unlike' : 'like'
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        icon.classList.toggle('liked');
                        if (icon.classList.contains('liked')) {
                            icon.style.color = '#fff';
                            // Mostrar notificación de éxito al agregar
                            Swal.fire({
                                title: '¡Guardado!',
                                text: 'Libro agregado a tus favoritos',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500,
                                position: 'top-end',
                                toast: true,
                                background: '#4CAF50',
                                color: '#fff',
                                iconColor: '#fff'
                            });
                        } else {
                            icon.style.color = '#730F16';
                            // Mostrar notificación al quitar de favoritos
                            Swal.fire({
                                title: 'Eliminado',
                                text: 'Libro eliminado de tus favoritos',
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 1500,
                                position: 'top-end',
                                toast: true,
                                background: '#730F16',
                                color: '#fff',
                                iconColor: '#fff'
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                // Mostrar notificación de error
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al procesar tu solicitud',
                    icon: 'error',
                    confirmButtonColor: '#730F16'
                });
            }
        });
    });

    // Si estamos en la página de likes, cargar los libros guardados
    if (window.location.pathname.includes('likes.php')) {
        loadSavedBooks();
    }
});

async function loadSavedBooks() {
    try {
        const response = await fetch(`Controlador/obtenerLibrosGuardados.php`);
        const books = await response.json();
        
        const container = document.getElementById('container');
        container.innerHTML = ''; // Limpiar el contenedor

        books.forEach(book => {
            const card = createBookCard(book);
            container.appendChild(card);
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

function createBookCard(book) {
    const card = document.createElement('div');
    card.classList.add('card');
    card.innerHTML = `
        <img class="card__background" src="${book.RutaPortada}" alt="${book.Titulo}">
        <div class="card__content">
            <div class="card__content--container">
                <h3 class="card__title">${book.Titulo}</h3>
                <p class="card__description">${book.Descripcion}</p>
            </div>
            <div class="liked_books">
                <button class="card__button">
                    <a href="detalleLibro.php?id=${book.IDLibro}">LEER MÁS</a>
                </button>
                <button class="like-button" data-book-id="${book.IDLibro}">
                    <i class="fas fa-heart liked"></i>
                </button>
            </div>
        </div>
    `;

    // Agregar el evento de like específicamente para este botón
    const likeButton = card.querySelector('.like-button');
    likeButton.addEventListener('click', async function(e) {
        e.preventDefault();
        const icon = this.querySelector('i');
        const bookId = this.getAttribute('data-book-id');
        
        try {
            const response = await fetch('Controlador/manejarLike.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    bookId: bookId,
                    action: 'unlike'
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Eliminar la tarjeta con una animación
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        card.remove();
                        // Si no hay más libros, mostrar un mensaje
                        if (document.querySelectorAll('.card').length === 0) {
                            const container = document.getElementById('container');
                            container.innerHTML = '<p style="color: white; text-align: center; width: 100%;">No tienes libros guardados</p>';
                        }
                    }, 300);

                    // Mostrar notificación
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'Libro eliminado de tus favoritos',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'top-end',
                        toast: true,
                        background: '#730F16',
                        color: '#fff',
                        iconColor: '#fff'
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al eliminar el libro',
                icon: 'error',
                confirmButtonColor: '#730F16'
            });
        }
    });

    return card;
}


 
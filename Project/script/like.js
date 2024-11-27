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
                alert('Debes iniciar sesión para guardar libros');
                window.location.href = 'login.php';
                return;
            }

            const bookId = this.getAttribute('data-book-id');
            const icon = this.querySelector('i') || this;
            const isLiked = icon.classList.contains('liked');
            
            console.log('Book ID:', bookId);
            console.log('Is liked:', isLiked);

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

                console.log('Response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        icon.classList.toggle('liked');
                        if (icon.classList.contains('liked')) {
                            icon.style.color = '#fff';
                        } else {
                            icon.style.color = '#730F16';
                        }
                    }
                }
            } catch (error) {
                console.error('Error:', error);
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
        <div class="card__content | flow">
            <div class="card__content--container | flow">
                <h3 class="card__title">${book.Titulo}</h3>
                <p class="card__description">${book.Descripcion}</p>
            </div>
            <div class="liked_books">
                <button class="card__button">
                    <a href="detalleLibro.php?id=${book.IDLibro}">LEER MÁS</a>
                </button>
                <i class="fas fa-thumbs-up heart liked" data-book-id="${book.IDLibro}"></i>
            </div>
        </div>
    `;
    return card;
}


 
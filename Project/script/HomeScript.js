(function() {
  var autoUpdate = false,
      timeTrans = 4000;
  
  var cdSlider = document.querySelector('.cd-slider'),
      item = cdSlider.querySelectorAll("li"),
      nav = cdSlider.querySelector("nav");

  item[0].className = "current_slide";

  // Detect IE
  // hide ripple effect on IE9
  var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE");
      if ( msie > 0 ) {
          var version = parseInt(ua.substring(msie+ 5, ua.indexOf(".", msie)));
          if (version === 9) { cdSlider.className = "cd-slider ie9";}
  }

  if (item.length <= 1) {
      nav.style.display = "none";
  }

  function prevSlide() {
      var currentSlide = cdSlider.querySelector("li.current_slide"),
          prevElement = currentSlide.previousElementSibling,
          prevSlide = ( prevElement !== null) ? prevElement : item[item.length-1],
          prevColor = prevSlide.getAttribute("data-color"),
          el = document.createElement('span');

      currentSlide.className = "";
      prevSlide.className = "current_slide";

      nav.children[0].appendChild(el);

      var size = ( cdSlider.clientWidth >= cdSlider.clientHeight ) ? cdSlider.clientWidth*2 : cdSlider.clientHeight*2,
          ripple = nav.children[0].querySelector("span");

      ripple.style.height = size + 'px';
      ripple.style.width = size + 'px';
      ripple.style.backgroundColor = prevColor;

      ripple.addEventListener("webkitTransitionEnd", function() {
          if (this.parentNode) {
              this.parentNode.removeChild(this);
          }
      });

      ripple.addEventListener("transitionend", function() {
          if (this.parentNode) {
              this.parentNode.removeChild(this);
          }
      });

  }

  function nextSlide() {
      var currentSlide = cdSlider.querySelector("li.current_slide"),
          nextElement = currentSlide.nextElementSibling,
          nextSlide = ( nextElement !== null ) ? nextElement : item[0],
          nextColor = nextSlide.getAttribute("data-color"),
          el = document.createElement('span');

      currentSlide.className = "";
      nextSlide.className = "current_slide";

      nav.children[1].appendChild(el);

      var size = ( cdSlider.clientWidth >= cdSlider.clientHeight ) ? cdSlider.clientWidth*2 : cdSlider.clientHeight*2,
            ripple = nav.children[1].querySelector("span");

      ripple.style.height = size + 'px';
      ripple.style.width = size + 'px';
      ripple.style.backgroundColor = nextColor;

      ripple.addEventListener("webkitTransitionEnd", function() {
          if (this.parentNode) {
              this.parentNode.removeChild(this);
          }
      });

      ripple.addEventListener("transitionend", function() {
          if (this.parentNode) {
              this.parentNode.removeChild(this);
          }
      });

  }

  updateNavColor();

  function updateNavColor () {
      var currentSlide = cdSlider.querySelector("li.current_slide");

      var nextColor = ( currentSlide.nextElementSibling !== null ) ? currentSlide.nextElementSibling.getAttribute("data-color") : item[0].getAttribute("data-color");
      var	prevColor = ( currentSlide.previousElementSibling !== null ) ? currentSlide.previousElementSibling.getAttribute("data-color") : item[item.length-1].getAttribute("data-color");

      if (item.length > 2) {
          nav.querySelector(".prev").style.backgroundColor = prevColor;
          nav.querySelector(".next").style.backgroundColor = nextColor;
      }
  }

  nav.querySelector(".next").addEventListener('click', function(event) {
      event.preventDefault();
      nextSlide();
      updateNavColor();
  });

  nav.querySelector(".prev").addEventListener("click", function(event) {
      event.preventDefault();
      prevSlide();
      updateNavColor();
  });

  //autoUpdate
  setInterval(function() {
    if (autoUpdate) {
      nextSlide();
      updateNavColor();
    };
    },timeTrans);

})();

// Búsqueda en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
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
});




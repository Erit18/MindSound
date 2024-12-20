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

// Mover la función realizarBusqueda fuera del evento DOMContentLoaded
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

class VoiceAssistant {
    constructor() {
        this.synthesis = window.speechSynthesis;
        this.recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        this.recognition.lang = 'es-ES';
        this.recognition.continuous = false;
        this.recognition.interimResults = false;
        this.isListening = false;
        this.setupVoiceAssistant();
        this.iniciarAsistenteAutomatico();
    }

    speak(text) {
        this.synthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'es-ES';
        utterance.rate = 1;
        this.synthesis.speak(utterance);
    }

    iniciarAsistenteAutomatico() {
        setTimeout(() => {
            const mensajeInicial = "Bienvenido a SoundMind. Puedes buscar un libro diciendo 'buscar' seguido del título, o reproducirlo directamente diciendo 'reproducir' seguido del título. Por ejemplo: 'buscar El principito' o 'reproducir El principito'. ¿Qué deseas hacer?";
            
            this.recognition.start();
            
            const utterance = new SpeechSynthesisUtterance(mensajeInicial);
            utterance.onstart = () => {
                document.querySelector('.voice-search-btn').classList.add('listening');
                this.isListening = true;
            };
            this.synthesis.speak(utterance);
        }, 1000); // Pequeño retraso para asegurar que la página esté cargada
    }

    cleanText(text) {
        return text.replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, "").trim();
    }

    setupVoiceAssistant() {
        const searchInput = document.getElementById('searchInput');
        const voiceButton = document.createElement('button');
        voiceButton.className = 'voice-search-btn';
        voiceButton.innerHTML = '<i class="fas fa-microphone"></i>';
        voiceButton.setAttribute('aria-label', 'Búsqueda por voz');
        
        searchInput.parentElement.appendChild(voiceButton);

        voiceButton.addEventListener('click', () => this.toggleListening());

        this.recognition.onresult = (event) => {
            this.synthesis.cancel();
            
            const command = this.cleanText(event.results[0][0].transcript.toLowerCase());
            this.commandDetected = true;
            
            if (command.includes('ayuda')) {
                this.speak('Puedes buscar un libro diciendo "buscar" seguido del título, o reproducir directamente diciendo "reproducir" seguido del título.');
            } else if (command.includes('reproducir')) {
                const searchTerm = this.cleanText(command.replace('reproducir', ''));
                searchInput.value = searchTerm;
                this.speak(`Buscando ${searchTerm} para reproducir`);
                this.buscarParaReproducir(searchTerm);
            } else if (command.includes('buscar')) {
                const searchTerm = this.cleanText(command.replace('buscar', ''));
                searchInput.value = searchTerm;
                this.speak(`Buscando ${searchTerm}`);
                realizarBusqueda(searchTerm);
            } else {
                searchInput.value = command;
                this.speak(`Buscando ${command}`);
                realizarBusqueda(command);
            }
        };

        this.recognition.onend = () => {
            if (!this.commandDetected) {
                setTimeout(() => {
                    this.recognition.start();
                }, 100);
            }
            this.isListening = false;
            document.querySelector('.voice-search-btn').classList.remove('listening');
        };
    }

    async buscarParaReproducir(termino) {
        try {
            const formData = new FormData();
            formData.append('accion', 'buscar');
            formData.append('termino', termino);

            const response = await fetch('Controlador/CLibros.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success' && data.data.length > 0) {
                this.speak(`Reproduciendo ${data.data[0].Titulo}`);
                setTimeout(() => {
                    window.location.href = `audio.php?id=${data.data[0].IDLibro}&autoplay=true`;
                }, 1500); // Pequeña pausa para que se escuche el mensaje
            } else {
                this.speak('No se encontró el libro especificado');
            }
        } catch (error) {
            console.error('Error en la búsqueda:', error);
            this.speak('Hubo un error al buscar el libro');
        }
    }

    toggleListening() {
        if (this.isListening) {
            this.recognition.stop();
        } else {
            this.recognition.start();
        }
    }

    listenForReproduction(libroId) {
        this.recognition.onresult = (event) => {
            const response = this.cleanText(event.results[0][0].transcript.toLowerCase());
            this.commandDetected = true;
            
            if (response.includes('si') || response.includes('sí')) {
                this.speak('De acuerdo, iniciando reproducción');
                window.location.href = `audio.php?id=${libroId}&autoplay=true`;
            } else if (response.includes('no')) {
                this.speak('De acuerdo, puedes seguir buscando otros libros');
            } else {
                this.speak('No he entendido tu respuesta. Por favor, di sí o no');
                setTimeout(() => this.listenForReproduction(libroId), 2000);
            }
        };

        this.recognition.onend = () => {
            this.isListening = false;
            document.querySelector('.voice-search-btn').classList.remove('listening');
        };

        this.isListening = true;
        document.querySelector('.voice-search-btn').classList.add('listening');
        this.recognition.start();
    }
}

// Inicializar el asistente de voz y configurar la búsqueda cuando se carga el documento
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const voiceAssistant = new VoiceAssistant();
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

    // Función global para mostrar resultados
    window.mostrarResultados = function(resultados) {
        if (resultados.length === 0) {
            searchResults.innerHTML = '<div class="search-result-item"><div class="search-result-info"><div class="search-result-title">No se encontraron resultados</div></div></div>';
            const utterance = new SpeechSynthesisUtterance('No se encontraron resultados. ¿Qué otro libro deseas buscar?');
            utterance.onend = () => {
                voiceAssistant.recognition.start();
            };
            voiceAssistant.synthesis.speak(utterance);
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
            
            const utterance = new SpeechSynthesisUtterance(`Se encontró ${resultados[0].Titulo} por ${resultados[0].Autor}. ¿Deseas reproducirlo?`);
            utterance.onend = () => {
                voiceAssistant.listenForReproduction(resultados[0].IDLibro);
            };
            voiceAssistant.synthesis.speak(utterance);
        }
        searchResults.classList.add('active');
    };

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.remove('active');
        }
    });
});




var animationContainer = document.getElementById('animationContainer');
var overlay = document.getElementById('overlay');
var focusedAnimationContainer = document.getElementById('focusedAnimationContainer');
var uploadForm = document.getElementById('uploadForm');


// Inicializar primera animación
var animation = lottie.loadAnimation({
    container: animationContainer,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: baseUrl + 'assets/animations/Animation_from.json'
});

// Función para mostrar la segunda animación con difuminado
function mostrarSegundaAnimacion() {
    overlay.style.display = 'flex';

    // Inicializar segunda animación
    var animation2 = lottie.loadAnimation({
        container: focusedAnimationContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path:  baseUrl + 'assets/animations/carga.json'
    });

    // Retornar una promesa que se resuelve cuando la animación termina
    return new Promise((resolve) => {
        animation2.addEventListener('complete', function () {
           overlay.style.display = 'none';
           resolve();
        });
    });
}

// Función para mostrar la tercera animación
function mostrarTerceraAnimacion() {
    // Limpiar contenedor de la segunda animación
    focusedAnimationContainer.innerHTML = '';

    // Inicializar tercera animación
    var animation3 = lottie.loadAnimation({
        container: focusedAnimationContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path:  baseUrl + 'assets/animations/animacion2.json'
    });

    // Retornar una promesa que se resuelve cuando la animación termina
    return new Promise((resolve) => {
        animation3.addEventListener('complete', function () {
            overlay.style.display = 'none';
            resolve();
        });
    });
}

// Función para realizar la acción predefinida
function ejecutarAccionPredefinida() {
    return new Promise((resolve) => {
        // Aquí puedes ejecutar tu acción predefinida
        // Simulación de tiempo de ejecución con setTimeout
        setTimeout(() => {
            resolve();
        }, 4000); // Cambia el tiempo según sea necesario
    });
}

// Función asincrónica para manejar el envío del formulario
async function handleFormSubmit(event) {
    await mostrarSegundaAnimacion(); // Mostrar las animaciones
    await mostrarTerceraAnimacion(); // Mostrar la tercera animación
    await ejecutarAccionPredefinida(); // Esperar a que la acción predefinida termine
    uploadForm.submit(); // Enviar el formulario manualmente
}

// Agregar un evento al formulario para manejar el envío
uploadForm.addEventListener('submit', handleFormSubmit);
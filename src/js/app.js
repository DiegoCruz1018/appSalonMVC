let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    mostrarSeccion(); //Muestra y oculta las secciones
    tabs(); //Cambia la sección cuando se presionen los tabs
    botonesPaginador()//Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //Consulta la API en el backend de PHP
    
    idCliente();
    nombreCliente();//Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //Añade la fecha en el objeto
    seleccionarHora(); //Añade la hora en el objeto
    mostrarResumen(); //muestra el resumen de la cita
}

function mostrarSeccion(){
    //console.log('Mostrando sección');

    //Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    
    //Seleccionar la sección con el paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    //console.log(botones); //ver si selecciona los botones

    //Iterar en cada boton
    botones.forEach(boton => {
        boton.addEventListener('click', function(e){
            //console.log('Diste click');
            //console.log(e); evento para ver a que le damos click

            //Target es a lo que le dimos click
            // console.log(e.target.dataset.paso);
            // console.log(typeof e.target.dataset.paso);
            //console.log(parseInt(e.target.dataset.paso));

            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
        });
    });
}

//La debemos de mandar a llamar cuando inicia la aplicación y en la función tab() para que funcione en la paginación
function botonesPaginador(){
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar')
    }else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        
        if(paso <= pasoInicial) return;
        paso--;

        //console.log(paso);

        botonesPaginador();
    });
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){

        if(paso >= pasoFinal) return;
        paso++;

        //console.log(paso);
        botonesPaginador();
    });
}


//async nos garantiza un buen performance
async function consultarAPI(){
    try{
        const url = '/api/servicios';//Funciona cuando el backend y el js esta alojado en el mismo dominio
        //const url = `${location.origin}/api/servicios`;
        //const url = `http://localhost:3000/api/servicios`;

        const resultado = await fetch(url); //await Espera a que descargue todo
        const servicios = await resultado.json();

        //console.log(servicios);
        mostrarServicios(servicios);
        
    }catch(error){
        console.log(error);
    }
}

function mostrarServicios(servicios){
    //console.log(servicios);

    //Iteramos los servicios
    servicios.forEach(servicio =>{
        //Aplicamos ddestructuring
        const { id, nombre, precio } = servicio;

        //console.log(nombre);

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id; //Nos sirve para seleccionar el servicio con el id
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //console.log(servicioDiv);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio){
    //console.log(servicio);

    const {id} = servicio;

    //Extraer el arreglo de servicios
    const { servicios } = cita;

    //console.log(servicio);

    //Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado
    //some retornara true o false en caso de que un elemento exista en el arreglo
    //some nos sirve para revisar si en un arreglo ya esta en un elemento
    //agregado.id es lo que ya esta en memoria y id es al que esta dando click en la interfaz
    if(servicios.some(agregado => agregado.id === id)){
        //console.log('Ya esta agregado');
        
        //cita.servicios = [...servicios, servicio];
        //Eliminarlo
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else{
        //console.log('Articulo nuevo');

        //Agregarlo
        //Toma una copia de los servicios y toma el nuevo servicio
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

    //console.log(cita);
}

function idCliente(){
    //console.log(cita);

    //Obtener el nombre del formulario
    cita.id = document.querySelector('#id').value;

    //console.log(id);
    //cita.id = id;
}

function nombreCliente(){
    //console.log(cita);

    //Obtener el nombre del formulario
    cita.nombre = document.querySelector('#nombre').value;

    //console.log(nombre);
    //cita.nombre = nombre;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){
        //console.log(inputFecha.value);
        //console.log(e.target.value);

        //Obtenemos el día dependiendo la fecha
        //getUTCDay() obtenemos el numero del dia
        const dia = new Date(e.target.value).getUTCDay();

        //console.log(dia);

        //Los numeros son los días de la semana 0 es domingo, 1 es lunes, etc.
        if([6, 0].includes(dia)){
            //console.log('Sabados y Domingos no abrimos');
            e.target.value = '';
            mostrarAlerta('error', 'Fines de semana no permitidos', '.formulario');
        }else{
            //console.log('Correcto');
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        //console.log(e.target.value);

        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0]; //Split nos ayuda a separar una cadena de texto
        
        //console.log(hora);
        if(hora < 10 || hora > 18){
            //console.log('Horas no validas');
            e.target.value = '';
            mostrarAlerta('error', 'Hora No Valida', '.formulario');
        }else{
            //console.log('Horas validas');
            cita.hora = e.target.value;
        }
    });
}

function mostrarAlerta(tipo, mensaje, elemento, desaparece = true){

    //Si ya hay una alerta ya no agregamos otra para que la interfaz no se llene de una misma alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    //Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //console.log(alerta);
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece){
        //Después de 2 segundos quitamos la alerta
        setTimeout(() => {
            alerta.remove();

        }, 2000);
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //console.log(cita);
    //console.log(Object.values(cita));

    //console.log(cita.servicios.length);

    //Limpiar el contenido del resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    //iteramos sobre el objeto de cita con el Object.values
    //Con Object.values accedemos a los valores de un objeto
    //Y verificamos con .includes('') si incluye un string vacio
    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        //console.log('Hacen falta datos o servicios');
        mostrarAlerta('error', 'Faltan datos de servicios, fecha u hora', '.contenido-resumen', false);

        return;
    }
    
    //console.log("Todo Bien");

    //Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    //console.log(nombreCliente);

    //Heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de servicios';

    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio => {

        const { id, nombre, precio } = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //Heading de cita en resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';

    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span>${nombre}`;

    //Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDay() + 23;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date(Date.UTC(year, mes, dia));
    //console.log(fechaUTC);

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
    //console.log(fechaFormateada);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

    //Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = "Reservar Cita";

    //Cuando hacemos esto debemos poner el () a la función para no mandarla a llamar, en caso de que queramos pasarle un parametro 
    //debemos hacer un callback aquí para mandar a llamar la función y pasarle el argumento
    botonReservar.onclick = reservarCita; 

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    resumen.appendChild(botonReservar);
}

async function reservarCita(){

    const { nombre, fecha, hora, servicios, id } = cita;

    //Se crea solo un objeto de FormData()
    const datos = new FormData(); //ES como el submit pero en javascript

    //El map coloca las coincidencias las coloca en la variable idServicios
    const idServicios = servicios.map( servicio => servicio.id );

    // console.log(idServicios);
    // return;

    //append es la forma en que podemos agregar datos a este fromdata
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    //Podemos ver que estamos colocando en el fromdata antes de enviarlo
    //console.log([...datos]);
    //return; //Lo ponemos para que no envie la petición


    try {

        //Petición hacia la API
        const url = `/api/citas`; //Funciona cuando el backend y el js esta alojado en el mismo dominio
        //const url = `${location.origin}/api/citas`;
        //const url = `http://localhost:3000/api/citas`;

        const respuesta = await fetch(url, {
            method: 'POST', 
            body: datos
        })

        //console.log(respuesta);

        const resultado = await respuesta.json();
        //console.log(resultado.resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Tu cita fue creada correctamente",
                // footer: '<a href="#">Why do I have this issue?</a>'
                button: 'OK'
            }).then( () => {

                setTimeout( () => {
                    window.location.reload();
                }, 1000);

            });
        }
        
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar la cita",
            // footer: '<a href="#">Why do I have this issue?</a>'
            //button: 'OK'
          })
    }
}
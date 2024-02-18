
let paso = 1
const pasoInicial = 1
const pasoFinla = 3

const cita = {
   id: '',
   nombre: '',
   fecha: '',
   hora: '',
   servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
   iniciarApp()
})

function iniciarApp() {
   mostrarSecion()
   tabs()
   botonesPaginador()
   paginaSiguiente()
   paginaAnterior()

   consultarAPI()

   idCliente()
   nombreCliente()
   seleccionarFecha()
   seleccionarHora()
   mostrarResumen()
}

function mostrarSecion() {

   // Ocultar la seccion anterior
   const seccionAnterior = document.querySelector('.mostrar')
   if(seccionAnterior) {
      seccionAnterior.classList.remove('mostrar')
   }
   
   // Seleccionar la seccion co el paso
   const pasoSelector = `#paso-${paso}`
   const seccion = document.querySelector(pasoSelector)
   seccion.classList.add('mostrar')

   // Quitar la clase Anterior
   const tabAnterior = document.querySelector('.actual')
   if(tabAnterior) {
      tabAnterior.classList.remove('actual')
   }

   // Resalta el Tab actual
   const tab = document.querySelector(`[data-paso="${paso}"]`)
   tab.classList.add('actual')

}

function tabs() {

   const botones = document.querySelectorAll('.tabs button')

   botones.forEach( boton => {
      boton.addEventListener('click', function(event) {
         paso = parseInt( event.target.dataset.paso )
         mostrarSecion()
         botonesPaginador()
      })
   })

}

function botonesPaginador() {

   const paginaAnterior = document.querySelector('#anterior')
   const paginaSiguiente = document.querySelector('#siguiente')

   if(paso === 1) {
      paginaAnterior.classList.add('ocultar')
      paginaSiguiente.classList.remove('ocultar')
   } else if(paso === 3) {
      paginaAnterior.classList.remove('ocultar')
      paginaSiguiente.classList.add('ocultar')
      mostrarResumen()
   } else {
      paginaAnterior.classList.remove('ocultar')
      paginaSiguiente.classList.remove('ocultar')
   }

   mostrarSecion()
   
}

function paginaAnterior() {

   const paginaAnterior = document.querySelector('#anterior')
   paginaAnterior.addEventListener('click', function() {
      if(paso <= pasoInicial) return
      paso--
      botonesPaginador();
   })

}

function paginaSiguiente() {

   const paginaSiguiente = document.querySelector('#siguiente')
   paginaSiguiente.addEventListener('click', function() {
      if(paso >= pasoFinla) return
      paso++
      botonesPaginador();
   })

}

async function consultarAPI() {

   try {

      const url = `${location.origin}/api/servicios`
      const resultado = await fetch(url)
      const servicios = await resultado.json()
      mostrarServicios(servicios)
      
   } catch (error) {
      console.log(error)      
   }

}

function mostrarServicios(servicios) {

   servicios.forEach( servicio => {
      const {id, nombre, precio } = servicio

      const nombreServicio = document.createElement('P')
      nombreServicio.classList.add('nombre-servicio')
      nombreServicio.textContent = nombre

      const precioServicio = document.createElement('P')
      precioServicio.classList.add('precio-servicio')
      precioServicio.textContent = `$${precio}`

      const servicioDIV = document.createElement('DIV')
      servicioDIV.classList.add('servicio')
      servicioDIV.dataset.idServicio = id
      servicioDIV.onclick = function() {
         seleccionarServicio(servicio)
      }

      servicioDIV.appendChild(nombreServicio)
      servicioDIV.appendChild(precioServicio)

      document.querySelector('#servicios').appendChild(servicioDIV)

   })

}

function seleccionarServicio(servicio) {

   const {id} = servicio
   const {servicios} = cita

   const divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

   if( servicios.some( agregado => agregado.id === id )) {
      cita.servicios = servicios.filter( agregado => agregado.id !== id)
      divServicio.classList.remove('seleccionado')
   } else {
      cita.servicios = [...servicios, servicio]
      divServicio.classList.add('seleccionado')
   }

}

function idCliente() {

   cita.id = document.querySelector('#id').value

}

function nombreCliente() {

   cita.nombre = document.querySelector('#nombre').value

}

function seleccionarFecha() {

   const inputFecha = document.querySelector('#fecha')
   inputFecha.addEventListener('input', function(event) {
      
      const dia = new Date(event.target.value).getUTCDay()
      if( [6, 0].includes(dia)) {
         event.target.value = ''
         mostrarAlerta('Fines de semana no permitido', 'error','.formulario')
      } else {
         cita.fecha = event.target.value
      }

   })
   
}

function seleccionarHora() {

   const inputHora = document.querySelector('#hora')
   inputHora.addEventListener('input', function(event) {

      const horaCita = event.target.value
      const hora = horaCita.split(':')[0]
      if(hora < 10 || hora > 18) {
         event.target.value = ''
         mostrarAlerta('Hora no válida','error','.formulario')
      } else {
         cita.hora = event.target.value
      }

   })

}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

   // Prevenir generar muchas alertas
   const alertaPrevia = document.querySelector('.alerta')
   if(alertaPrevia) {
      alertaPrevia.remove()
   }

   // Scripting para crear Alertas
   const alerta = document.createElement('DIV')
   alerta.textContent = mensaje
   alerta.classList.add('alerta')
   alerta.classList.add(tipo)

   const referencia = document.querySelector(elemento)
   referencia.appendChild(alerta)

   if(desaparece) {
      // Eliminar alerta
      setTimeout(() => {
         alerta.remove()
      }, 3000);
   }

}

function mostrarResumen() {

   const resumen = document.querySelector('.contenido-resumen')

   // Limpiar el contenido de Resumen
   while(resumen.firstChild) {
      resumen.removeChild(resumen.firstChild)
   }

   if( Object.values(cita).includes('') || cita.servicios.length === 0 ) {
      mostrarAlerta('Faltan Datos de Servicios, Fecha u Hora', 'error','.contenido-resumen', false)
      return
   }

   const {nombre, fecha, hora, servicios} = cita

   const headerServicio = document.createElement('H3')
   headerServicio.textContent = 'Resumen de Servicios'
   resumen.appendChild(headerServicio)

   servicios.forEach(servicio => {

      const {id, nombre, precio} = servicio

      const contenedorServicio = document.createElement('DIV')
      contenedorServicio.classList.add('contenedor-servicio')

      const textoServicio = document.createElement('P')
      textoServicio.textContent = nombre

      const precioServicio = document.createElement('P')
      precioServicio.innerHTML = `<span>Precio: $</span> ${precio}`

      contenedorServicio.appendChild(textoServicio)
      contenedorServicio.appendChild(precioServicio)

      resumen.appendChild(contenedorServicio)

   })

   const headerCita = document.createElement('H3')
   headerCita.textContent = 'Resumen de Cita'
   resumen.appendChild(headerCita)

   const nombreCliente = document.createElement('P')
   nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`

   // Formatear Fecha
   const fechaObj = new Date(fecha)
   const mes = fechaObj.getMonth()
   const dia = fechaObj.getDate() + 2
   const year = fechaObj.getFullYear()

   const fechaUTC = new Date( Date.UTC(year, mes, dia) )

   const opciones = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
   const fechaFormateada = fechaUTC.toLocaleDateString('es-ES', opciones)

   const fechaCliente = document.createElement('P')
   fechaCliente.innerHTML = `<span>Fecha: </span>${fechaFormateada}`

   const horaCliente = document.createElement('P')
   horaCliente.innerHTML = `<span>Hora: </span>${hora}`

   // Boton para Crear un Cita
   const botonReservar = document.createElement('BUTTON')
   botonReservar.classList.add('boton')
   botonReservar.textContent = 'Reservar Cita'
   botonReservar.onclick = reservarCita

   resumen.appendChild(nombreCliente)
   resumen.appendChild(fechaCliente)
   resumen.appendChild(horaCliente)

   resumen.appendChild(botonReservar)

}

async function reservarCita() {

   const {nombre, fecha, hora, servicios, id} = cita

   const idServicio = servicios.map(servicio => servicio.id)

   const datos = new FormData()
   
   datos.append('hora', hora)
   datos.append('fecha', fecha)
   datos.append('usuarioID', id)
   datos.append('servicios', idServicio)

   try {
      const url = `${location.origin}/api/citas`
      const respuesta = await fetch(url, {
         method: 'POST',
         body: datos
      })

      const resultado = await respuesta.json()

      if(resultado.resultado) {
         Swal.fire({
            icon: "success",
            title: "Cita Creada",
            text: "Tu cita fue creada correctamente"
         }).then( () => {
            setTimeout(() => {
               window.location.reload()
            }, 3000)
         })
      }
   } catch (error) {
      Swal.fire({
         icon: "error",
         title: "Error",
         text: "Hubo un error al guardar la cita"
      });
   }

   

}
let step = 1; // This variable shows the step you want to display first 
const firtStep = 1;
const lastStep = 3;

const appointment = {
    id: '',
    name: '',
    date: '',
    time: '',
    services: []
}

document.addEventListener('DOMContentLoaded', () => {
    startApp();
})

function startApp () {
    displaySection(); // Show and hide the sections
    tabs(); // Change the section when the user press the tabs
    pagerButtons(); // Add or remove buttons from the pager
    nextPage();
    previousPage();

    consultAPI(); // Consult the API in the Back-end

    idClient();
    nameClient(); // Add the client name to the appointment object
    selectDate(); // Add the date to the appointment in the object
    selectTime(); // Add the time to the appointment in the object

    showSummary(); // Show the appointment summary
}
    
function displaySection () {

    // Hide the section that has the display section
    const previousSelect = document.querySelector('.display');
    if (previousSelect) {
        previousSelect.classList.remove('display');
    }

    // Select the section with the step
    const selectorStep = `#step-${step}`
    const section = document.querySelector(selectorStep);
    section.classList.add('display');

    // Removes the current class from the previous tab
    const previousTab = document.querySelector('.current');
    if(previousTab) {
        previousTab.classList.remove('current');
    }

    // Highlight the curren tab
    const tab = document.querySelector(`[data-step="${step}"]`);
    tab.classList.add('current');
}

function tabs () {

    // Add and change the step variable according the selected tab
    const buttons = document.querySelectorAll('.tabs button');
    buttons.forEach(button => {
        button.addEventListener('click', (e) => {
            step = parseInt( e.target.dataset.step);
            displaySection();

            pagerButtons();
        });
    })
}

function pagerButtons() {
    const previousPage = document.querySelector('#previous');
    const nextPage = document.querySelector('#next');

    if(step === 1) {
        previousPage.classList.add('hide');
        nextPage.classList.remove('hide');
    } else if (step === 3) {
        previousPage.classList.remove('hide');
        nextPage.classList.add('hide');

        showSummary();
    } else {
        previousPage.classList.remove('hide');
        nextPage.classList.remove('hide');
    }

    displaySection();
}

function previousPage() {
    const previousPage = document.querySelector('#previous');
    previousPage.addEventListener('click', () => {
        if (step <= firtStep) return;
        step--;

        pagerButtons();
    });
}

function nextPage () {
    const nextPage = document.querySelector('#next');
    nextPage.addEventListener('click', () => {
        if (step >= lastStep) return;
        step++;

        pagerButtons();
    });

}

async function consultAPI () {
    try {
        const url = `${location.origin}/api/services`;
        const result = await fetch(url);
        const services = await result.json();
        showServices(services);

    } catch (err) {
        console.log(err);
    }
}

function showServices (services) {

    services.map(service => {
        const {id, servicename, price} = service;

        const nameService = document.createElement('P');
        nameService.classList.add('name-service');
        nameService.textContent = servicename;

        const priceService = document.createElement('P');
        priceService.classList.add('price-service');
        priceService.textContent = `$ ${price}`;

        const divService = document.createElement('DIV');
        divService.classList.add('service');
        divService.dataset.idService = id; // Custom attribute
        divService.onclick = function() {
            selectService(service);
        }

        divService.appendChild(nameService);
        divService.appendChild(priceService);

        document.querySelector('#services').appendChild(divService);
    });
}

function selectService (service) {
    const { id } = service;
    const { services } = appointment;

    // Identify the element that is clicked
    const divService = document.querySelector(`[data-id-service="${id}"]`);

    // Check if a service is already selected
    if ( services.some( added => added.id === id ) ) {
        // Deleted item
        appointment.services = services.filter( added => added.id !== id );
        divService.classList.remove('selected');
    } else {
        // Add item
        appointment.services = [...services, service];
        divService.classList.add('selected');
    }
}

function idClient () {
    appointment.id = document.querySelector('#id').value;
}

function nameClient() {
    appointment.name = document.querySelector('#name').value;
}

function selectDate () {
    const inputDate =  document.querySelector('#date');
    inputDate.addEventListener('input', function(e) {

        const day = new Date(e.target.value).getUTCDay();

        // Check if the day is available
        if ( [6, 0].includes(day) ) {
            e.target.value = '';
            showAlert('Weekends are not available', 'error', '.form');
        } else {
            appointment.date = e.target.value;
        }

    })
}

function selectTime() {
    const inputTime = document.querySelector('#time');
    inputTime.addEventListener('input', function (e) {
        const appointmentTime = e.target.value;
        const time = appointmentTime.split(":")[0]; // Split separte a string
        if (time < 10 || time >= 18) {
            e.target.value = ''; // For not saving the time
            showAlert('Invalid Time', 'error', '.form');
        } else {
            appointment.time = e.target.value;
        }
    })
}

function showAlert(message, type, element, disappears = true) {

    // Avoid generating more than 1 alert 
    const earlyAlert = document.querySelector('.alert');
    if (earlyAlert) {
        earlyAlert.remove();
    }

    // Scripting to create the alert
    const alert = document.createElement('DIV');
    alert.textContent = message;
    alert.classList.add('alert');
    alert.classList.add(type);

    const reference = document.querySelector(element);
    reference.appendChild(alert);

    // Delete alert
    if (disappears) {
        setTimeout(() => {
            alert.remove();
        }, 4000);
    }
}

function showSummary() {
    const summary = document.querySelector('.content-summary');

    // Clean summary content
    while(summary.firstChild) {
        summary.removeChild(summary.firstChild);
    }

    // Object.values = Validates and access to the values of an object (Verify an object)
    if (Object.values(appointment).includes('') || appointment.length === 0 ) {
        showAlert('Service details, date, or time are missing', 'error', '.content-summary', false);

        return;
    }

    // Format summary div
    const {name, date, time, services} = appointment;

    // Heading for summary services
    const headingServices =  document.createElement('H3');
    headingServices.textContent = "Services summary";
    summary.appendChild(headingServices);

    // Iterate and showing the services
    services.forEach(service => {

        const {id, price, servicename} = service;

        const containerService =  document.createElement('DIV');
        containerService.classList.add('service-container');

        const serviceText = document.createElement('P');
        serviceText.textContent = servicename;

        const servicePrice = document.createElement('P');
        servicePrice.innerHTML = `<span>Price:</span> $${price}`;

        containerService.appendChild(serviceText);
        containerService.appendChild(servicePrice);

        summary.appendChild(containerService);
    });


    // Heading for appointment services
    const appointmentHeading =  document.createElement('H3');
    appointmentHeading.textContent = "Appointment summary";
    summary.appendChild(appointmentHeading);

    const clientName = document.createElement('P');
    clientName.innerHTML = `<span>Name:</span> ${name} `;

    // Format the date
    const objDate = new Date(date);
    const month = objDate.getMonth();
    const day = objDate.getDate() + 2;
    const year = objDate.getFullYear();

    const dateUTC = new Date(Date.UTC(year, month, day));

    const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const formatDate = dateUTC.toLocaleDateString('en-US', options);
    console.log(formatDate);

    const appointmentDate = document.createElement('P');
    appointmentDate.innerHTML = `<span>Date:</span> ${date} `;

    const appointmentTime = document.createElement('P');
    appointmentTime.innerHTML = `<span>Time:</span> ${time} `;


    // Button to create an appointment
    const reservButton = document.createElement('BUTTON');
    reservButton.classList.add('button');
    reservButton.textContent = 'Book An Appointment';
    reservButton.onclick = bookAppointment;

    summary.appendChild(clientName);
    summary.appendChild(appointmentDate);
    summary.appendChild(appointmentTime);

    summary.appendChild(reservButton);
}

async function bookAppointment() {

    const {name, date, time, services, id} = appointment;
    const idServices = services.map( service => service.id);

    const data = new FormData();
    data.append('date', date);
    data.append('time', time);
    data.append('userId', id);
    data.append('services', idServices);

    try {
        // API Request
        const url = `${location.origin}/api/appointment`;

        // The body identifies the existence of the formdata
        const response = await fetch(url, {
            method: 'POST',
            body: data
        });

        const result = await response.json();
        console.log(result.result);

        if (result.result) {
            Swal.fire({
                icon: "success",
                title: "Appointment Created",
                text: "Your Appointment has been successfully created",
                button: "OK"
            }).then( () => {
                setTimeout(() => {
                     window.location.reload();
                }, 3000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "There was an error saving the appointment."
        });
    }

    // console.log([...data]);
}

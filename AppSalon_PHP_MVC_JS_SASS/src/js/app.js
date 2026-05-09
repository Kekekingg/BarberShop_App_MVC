let step = 1; // This variable shows the step you want to display first 
const firtStep = 1;
const lastStep = 3;

const appointment = {
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
        const url = 'http://localhost:3000/api/services';
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
    console.log(appointment);
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
            showAlert('Invalid Time', 'error');
        } else {
            appointment.time = e.target.value;
        }
    })
}

function showAlert(message, type) {

    // Avoid generating more than 1 alert 
    const earlyAlert = document.querySelector('.alert');

    if (earlyAlert) return;

    // Scripting to create the alert
    const alert = document.createElement('DIV');
    alert.textContent = message;
    alert.classList.add('alert');
    alert.classList.add(type);

    const form = document.querySelector('#step-2 p');
    form.appendChild(alert);

    // Delete alert
    setTimeout(() => {
        alert.remove();
    }, 3000);
}

function showSummary() {
    const summary = document.querySelector('content-summary');

    // Object.values = Validates and access to the values of an object
    if (Object.values(appointment).includes('') ) {
        console.log('Data is needed');
    } else {
        console.log("Everything's fine");
    }
}

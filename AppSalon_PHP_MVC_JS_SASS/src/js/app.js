let step = 1; // This variable shows the step you want to display first 
const firtStep = 1;
const lastStep = 3;

document.addEventListener('DOMContentLoaded', () => {
    startApp();
})

function startApp () {
    displaySection(); // Show and hide the sections
    tabs(); // Change the section when the user press the tabs
    pagerButtons(); // Add or remove buttons from the pager
    nextPage();
    previousPage();
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
        })
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

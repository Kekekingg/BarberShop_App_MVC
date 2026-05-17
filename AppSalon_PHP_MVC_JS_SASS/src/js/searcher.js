document.addEventListener('DOMContentLoaded', function () {
    starApp();
});

function starApp () {
    searchByDate();
}

function searchByDate () {
    const inputDate = document.querySelector('#date');
    inputDate.addEventListener('input', (e) => {
        const selectedDate = e.target.value;

        window.location = `?date=${selectedDate}`;
    })
}

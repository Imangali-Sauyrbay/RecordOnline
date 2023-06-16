const isNumber = (value) => typeof value === 'number' && isFinite(value);
const makeRecordCounter = (selector, msg, initialRecords) => {
    const target = document.querySelector(selector);

    if(!target) return () => null;

    let addedCount = 0

    return (count) => {
        addedCount = count - initialRecords;

        if(addedCount <= 0) return;

        if(target.classList.contains('d-none')) {
            target.classList.remove('d-none')
        }

        target.textContent = msg.replace('#', addedCount.toString())
    }
}

const newRecordsCounter = makeCounter(
    '#info',
    window.lang.addedMsg || '#',
    window.records || 0
);


const pollingSeconds = import.meta.VITE_POLLING_SECONDS * 1000;
const interval = isNumber(pollingSeconds) && !isNaN(pollingSeconds) ? pollingSeconds : 30000;

(function fetchRecordsCount() {
    if(!window.route) return;

    fetch(window.route)
    .then(res => res.json())
    .then(({ count }) => newRecordsCounter(count))
    .finally(() => setTimeout(fetchRecordsCount, interval))
})()

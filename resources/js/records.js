function isNumber(value)
{
   return typeof value === 'number' && isFinite(value);
}

let addedCount = 0
const info = document.getElementById('info')

const newRecordAdded = () => {
    if(!info) return

    if(info.classList.contains('d-none')) {
        info.classList.remove('d-none')
    }

    const msg = window.lang.addedMsg
    info.textContent = msg.replace('#', addedCount.toString())
}

const pollingSeconds = import.meta.VITE_POLLING_SECONDS * 1000;

const interval = isNumber(pollingSeconds) && !isNaN(pollingSeconds) ? pollingSeconds : 5000;

(function fetchRecordsCount() {
    if(!window.route) return;

    fetch(window.route)
    .then(res => res.json())
    .then(data => {
        addedCount = data.count - window.records;
        if(addedCount > 0) {
            newRecordAdded();
        }
    })
    .finally(() => setTimeout(fetchRecordsCount, interval))
})()

/**
 * 
 * @param {HTMLInputElement} elem 
 */
async function selectProductClicked(elem) {
    // let elem = document.getElementById('l')
    setOnBlur(elem)
    setOnKeyDown(elem)

    let json = await requestAllItems()
    let dropdownbox = ""
    if (json[0] === "axiosError") {
        dropdownbox = `<div id="dropdownbox" class="absolute w-full top-[60px] max-h-60 overflow-auto">Could not fetch items from server😢</div>`
    } else {
        let slot = jsonToDropdownList(json)
        dropdownbox = `<div id="dropdownbox" class="absolute w-full top-[60px] max-h-60 overflow-auto">${slot}</div>`
    }

    elem.parentElement.insertAdjacentHTML('beforeend', dropdownbox)
}

/**
 * @returns {Array}
 */
async function requestAllItems() {
    try {
        // TODO: reduce result, maybe show most recent only, if data too big may cause performance problem
        let response = await window.axios.get('/api/item')
        console.log(response.data)
        return response.data
    } catch (error) {
        console.log(error)
        return ["axiosError"]
    }
}

/**
 * 
 * @param {HTMLInputElement} elem 
 */
function setOnKeyDown(elem) {
    elem.addEventListener('input', async (e) => {
        let input = e.target.value
        let response
        try {
            response = await window.axios.get(`/api/item/search?q=${input}`)
        } catch (error) {
            console.log(error)
            // terminate this anon function
            return
        }
        document.getElementById('dropdownbox').innerHTML = ''
        let dropdownList = jsonToDropdownList(response.data)
        document.getElementById('dropdownbox').insertAdjacentHTML('beforeend', dropdownList)
    })
}

/**
 * 
 * @param {Array} json 
 */
function jsonToDropdownList(json) {
    let dropdownList = ""
        json.forEach(object => {
            dropdownList +=
                `
            <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
            <div class="flex flex-col p-2 pl-4">
            <p class="text-sm">${object.name}</p>
            <p class="text-sm">${object.sku}</p>
            </div>
            <p class="pt-2 pr-4">${object.id} pcs</p>
            </div>
            `
        });
    return dropdownList
}

function setOnBlur(elem) {
    elem.addEventListener('blur', (event) => {
        document.getElementById('dropdownbox').remove()
        removeListeners(elem)
    });
}

function removeListeners(elem) {
    elem.removeEventListener('blur', console.log('blur listener removed'))
    elem.removeEventListener('keydown', console.log('keydown listener removed'))
}

window.selectProductClicked = selectProductClicked

let dropdown = `<div id="dropdownbox" class="absolute w-full top-[60px] max-h-60 overflow-auto">
<div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
    <div class="flex flex-col p-2 pl-4">
        <p class="text-sm">MX Keys Logitech keyboard</p>
        <p class="text-sm">LOGI-MXKEYS</p>
    </div>
    <p class="pt-2 pr-4">5 pcs</p>
</div>
<div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
    <div class="flex flex-col p-2 pl-4">
        <p class="text-sm">MX Keys Logitech keyboard</p>
        <p class="text-sm">LOGI-MXKEYS</p>
    </div>
    <p class="pt-2 pr-4">5 pcs</p>
</div>
<div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
    <div class="flex flex-col p-2 pl-4">
        <p class="text-sm">MX Keys Logitech keyboard</p>
        <p class="text-sm">LOGI-MXKEYS</p>
    </div>
    <p class="pt-2 pr-4">5 pcs</p>
</div>
<div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
    <div class="flex flex-col p-2 pl-4">
        <p class="text-sm">MX Keys Logitech keyboard</p>
        <p class="text-sm">LOGI-MXKEYS</p>
    </div>
    <p class="pt-2 pr-4">5 pcs</p>
</div>
<div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200">
    <div class="flex flex-col p-2 pl-4">
        <p class="text-sm">MX Keys Logitech keyboard</p>
        <p class="text-sm">LOGI-MXKEYS</p>
    </div>
    <p class="pt-2 pr-4">5 pcs</p>
</div>
</div>`
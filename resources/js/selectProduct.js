/**
 * 
 * @param {HTMLInputElement} elem 
 */
async function chooseProduct(elem) {
    if (elem.dataset.clicked === 'true') {
        return
    } else {
        elem.dataset.clicked = true
    }

    removeListeners(elem)
    elem.addEventListener('blur', whenBlur);
    elem.addEventListener('input', whenInput)


    let json = await requestAllItems()
    let dropdownbox = ""
    if (json[0] === "axiosError") {
        dropdownbox = `<div id="dropdownbox" class="dropdownbox z-10 absolute w-full top-[60px] max-h-60 overflow-auto bg-white">Could not fetch items from server😢</div>`
    } else {
        let slot = jsonToDropdownList(json)
        dropdownbox = `<div id="dropdownbox" class="dropdownbox z-10 absolute w-full top-[60px] max-h-60 overflow-auto bg-white">${slot}</div>`
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

async function whenInput(e) {
    // throttle (less endpoint hit)
    if (e.target.value.length > 0 && e.target.value.length < 3) {
        populateDataDropdownBox([])
        return
    }
    let inputValue = e.target.value
    let response
    try {
        response = await window.axios.get(`/api/item/search?q=${inputValue}`)
    } catch (error) {
        console.log(error)
        // terminate this function
        return
    }
    populateDataDropdownBox(response.data)
}

function populateDataDropdownBox(json) {
    document.getElementById('dropdownbox').innerHTML = ''
    if (json.length > 0) {
        let dropdownList = jsonToDropdownList(json)
        document.getElementById('dropdownbox').insertAdjacentHTML('beforeend', dropdownList)
    }
}


function whenBlur(e) {
    let inputElement = e.target
    inputElement.dataset.clicked = false
    removeListeners(inputElement)
    const dropdownbox = inputElement.parentElement.getElementsByClassName('dropdownbox')[0];
    // delay so that dropdown item can be clicked before removed from dom
    dropdownbox.classList.add("opacity-0")
    setTimeout(() => {
        // document.getElementById('dropdownbox') ?? document.getElementById('dropdownbox').remove()
        dropdownbox.remove()
    }, 500)
}

function removeListeners(elem) {
    elem.removeEventListener('blur', whenBlur)
    elem.removeEventListener('keydown', whenInput)
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
            <div class="flex justify-between mb-2 bg-slate-100 hover:bg-slate-200 cursor-pointer" onclick="chooseProductSave(this, ${object.id}, '${object.name}')">
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

window.chooseProduct = chooseProduct

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

/////////////////////////////////////////////////////////////////////////////////////////////

function chooseProductSave(elem, productId, productName) {
    // validate self
    console.log(elem.parentElement.parentElement)
    // validate current list same product
    let selectProductDiv = elem.parentElement.parentElement.parentElement
    for (let i = 1; i < selectProductDiv.children.length - 1; i++) {
        let row = selectProductDiv.children[i]
        let parentInputElement = row.children[1] // second input (hidden one)
        console.log(parentInputElement)
        if (productId == parentInputElement.value) {
            elem.parentElement.remove()
            alert('Cannot select same product')
            return
        }
    }

    // insert to dom
    elem.parentElement.parentElement.children[0].value = productName
    elem.parentElement.parentElement.children[1].value = productId
    selectProductDiv.insertAdjacentHTML('beforeend', 
    `
    <div data-role="row" class="relative flex items-center h-14">
        <input class="h-12 pl-6 mr-4 basis-1/2" type="text" value="" placeholder="Select a product"
            onclick="chooseProduct(this)">
        <input type="hidden" name="item_ids[]" type="number">
        <input class="basis-1/2 max-w-[200px] h-8" type="number" min="1" name="item_quantities[]"
            required />
        <div class="mx-4 cursor-pointer" onclick="chooseProductRowDelete(this)">
            <?xml version="1.0" encoding="UTF-8"?><svg width="24" height="24" viewBox="0 0 48 48"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 11L40 11" stroke="#333" stroke-width="4" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M18 5L30 5" stroke="#333" stroke-width="4" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M12 17H36V40C36 41.6569 34.6569 43 33 43H15C13.3431 43 12 41.6569 12 40V17Z"
                    fill="none" stroke="#333" stroke-width="4" stroke-linejoin="round" />
                <path d="M20 25L28 33" stroke="#333" stroke-width="4" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M28 25L20 33" stroke="#333" stroke-width="4" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    `
    )
    elem.parentElement.remove()
}

window.chooseProductSave = chooseProductSave

/**
 * 
 * @param {HTMLDivElement} elem 
 */
function chooseProductRowDelete(elem) {
    let selectProductDiv = elem.parentElement.parentElement
    if (selectProductDiv.childElementCount > 2) {
        elem.parentElement.remove()
    } else {
        alert("Cannot remove last row")
    }
}

window.chooseProductRowDelete = chooseProductRowDelete

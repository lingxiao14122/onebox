import { drop } from "lodash";

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
    elem.addEventListener('blur', whenElementBlur);
    elem.addEventListener('input', whenKeydown)


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

async function whenKeydown(e) {
    // throttle (less endpoint hit)
    if (e.target.value.length > 0 && e.target.value.length < 3) {
        populateDataDropdownBox([])
        return
    }
    let input = e.target.value
    let response
    try {
        response = await window.axios.get(`/api/item/search?q=${input}`)
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


function whenElementBlur(e) {
    let input = e.target
    input.dataset.clicked = false
    removeListeners(input)
    // delay so that dropdown item can be clicked before removed from dom
    setTimeout(() => {
        document.getElementById('dropdownbox').remove()
    }, 200);
}

function removeListeners(elem) {
    elem.removeEventListener('blur', whenElementBlur)
    elem.removeEventListener('keydown', whenKeydown)
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
    // validate
    let dropdownbox = elem.parentElement.parentElement.parentElement
    for (let i = 1; i < dropdownbox.children.length; i++) {
        let listitem = dropdownbox.children[i].children[1].value
        console.log(i)
        if (productId == listitem) {
            alert('Cannot select same product')
            return
        }
    }

    // insert to dom
    elem.parentElement.parentElement.children[0].value = productName
    elem.parentElement.parentElement.children[1].value = productId
    dropdownbox.insertAdjacentHTML('beforeend', 
    `
    <div class="relative flex items-center h-14">
        <input class="h-12 pl-6 mr-4 basis-1/2" type="text" value="" placeholder="Select a product" onclick="chooseProduct(this)">
        <input type="hidden" name="items[]" type="number">
        <input class="basis-1/2 max-w-[200px] h-8" type="number" value="" name="itemsCount[] required">
    </div>
    `
    )
}

window.chooseProductSave = chooseProductSave
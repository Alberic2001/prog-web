// YOUR NAME HERE

// === constants ===
const MAX_QTY = 9;
const productIdKey = "product";
const orderIdKey = "order";
const inputIdKey = "qte";

// === elements  ===
const elements = {
    shop:   document.getElementById("boutique"),
    filter: document.getElementById("filter"),
    total:  document.getElementById("montant"),
    orders: document.getElementsByClassName("achats")[0],
};

// Map of id-to-quantity
const orders = new Map();

function loadCatalog(curCatalog) {
    let index = 0;
    return curCatalog.map(item => ({ ...item, id: index++ }));
}

const itemCatalog = loadCatalog(catalog);

window.onload = () => {
    renderMainCatalog(undefined);

    elements.filter.onkeyup = () => {
        const searchString = elements.filter.value;
        if (searchString.length > 0) {
            renderMainCatalog(searchString);
        } else {
            renderMainCatalog(undefined);
        }
    }
}

function addItemToOrder(item, quantity) {
    if (orders.has(item.id)) {
        orders.set(item.id, clamp(orders.get(item.id) + quantity, 1, MAX_QTY));
    } else {
        orders.set(item.id, clamp(quantity, 1, MAX_QTY));
    }

    renderOrderEntries();
}

function deleteItemFromOrder(item) {
    orders.delete(item.id);
    renderOrderEntries();
}

function calculateTotal() {
    return Array.from(orders.entries())
        .map(([id, quantity]) => [itemCatalog.find(item => item.id === id), quantity])
        .filter(([item, _quantity]) => item !== undefined)
        .map(([item, quantity]) => [Number(item.price), quantity])
        .reduce((acc, [price, quantity]) => acc + price * quantity, 0);
}

function renderTotal() {
    const total = calculateTotal();
    elements.total.innerText = total;
}


function renderMainCatalog(filterString) {
    const curCatalog = filterString === undefined
        ? itemCatalog
        : itemCatalog.filter(item => item.name.toLowerCase().search(filterString.toLowerCase()) !== -1 );
    // create all elements before re-rendering
    const renderedElements = curCatalog.map(item => createCatalogItem(item));
    // fast and hacky way to clear all HTML
    elements.shop.innerHTML = "";
    // append them all at once, should run in one update frame
    for (const element of renderedElements) {
        elements.shop.appendChild(element);
    }
}

function createCatalogItem(item) {
	const elContainer = document.createElement("div");
	elContainer.className = "produit";

    elTitle = document.createElement("h4");
    elTitle.innerText = item.name;
    elContainer.appendChild(elTitle);

	const elFigure = createCatalogItemFigure(item);
    elContainer.appendChild(elFigure);

    const elDescriptionDiv = document.createElement("div");
    elDescriptionDiv.className = "description";
    elDescriptionDiv.innerText = item.description;
    elContainer.appendChild(elDescriptionDiv);

    const elPriceDiv = document.createElement("div");
    elPriceDiv.className = "prix";
    elPriceDiv.innerText = item.price;
    elContainer.appendChild(elPriceDiv);

	elOrderControl = createOrderControlBlock(item);
	elContainer.appendChild(elOrderControl);

	return elContainer;
}

function createOrderControlBlock(item) {
	const elDiv = document.createElement("div");
	elDiv.className = "controle";

	const elInput = document.createElement("input");
	elInput.type = "number";
	elInput.step = "1";
	elInput.value = "0";
	elInput.min = "0";
	elInput.max = MAX_QTY.toString();
	elDiv.appendChild(elInput);

	const elButton = document.createElement("button");
	elButton.className = "commander";
	elDiv.appendChild(elButton);

    function updateOpacity() {
        const quantity = Number(elInput.value);
        elButton.style.opacity = quantity > 0 ? 1 : 0.25;
    }

    elInput.onchange = () => {
        const quantity = clamp(Number(elInput.value), 0, MAX_QTY);
        elInput.value = quantity.toString();
        updateOpacity();
    }

    elButton.onclick = () => {
        const quantity = Number(elInput.value);
        if (quantity > 0) {
            addItemToOrder(item, quantity);
            elInput.value = "0";
        }
        updateOpacity();
    }

	return elDiv;
}

function createCatalogItemFigure(item) {
    const elFigure = document.createElement("figure");
    const elImg = document.createElement("img");
    elImg.src = item.image;
    elImg.alt = item.name;
    elFigure.appendChild(elImg);
    return elFigure;
}

function renderOrderEntries() {
    // same principles as renderMainCatalog
    const renderedElements = Array.from(orders.entries())
        .map(([id, quantity]) => [itemCatalog.find(item => item.id === id), quantity])
        .filter(([item, _quantity]) => item !== undefined)
        .map(([item, quantity]) => createOrderEntry(item, quantity));

    elements.orders.innerHTML = "";
    for (const element of renderedElements) {
        elements.orders.appendChild(element);
    }

    renderTotal();
}

function createOrderEntry(item, quantity) {
    const elContainer = document.createElement("div");
    elContainer.className = "achat";

    const elFigure = createCatalogItemFigure(item);
    elContainer.appendChild(elFigure);

    const elTitle = document.createElement("h4");
    elTitle.innerText = item.name;
    elContainer.appendChild(elTitle);

    const elQuantity = document.createElement("div");
    elQuantity.className = "quantite";
    elQuantity.innerText = quantity.toString();
    elContainer.appendChild(elQuantity);

    const elPrice = document.createElement("div");
    elPrice.className = "prix";
    elPrice.innerText = item.price;
    elContainer.appendChild(elPrice);

    const elControl = document.createElement("div");
    elControl.className = "controle";

    const elDeleteButton = document.createElement("button");
    elDeleteButton.className = "retirer";
    elControl.appendChild(elDeleteButton);

    elDeleteButton.onclick = () => {
        deleteItemFromOrder(item);
    }

    elContainer.appendChild(elControl);

    return elContainer;
}

function clamp(number, min, max) {
    return Math.max(min, Math.min(number, max));
}

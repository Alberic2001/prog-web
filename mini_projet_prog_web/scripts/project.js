// Gabriel Levshin - Phuong-Nam Vu - Albéric Ziangbe


// === helper classes ===
class Catalog {

    // constructor in which data can be passed to initialize the db
    constructor(data) {
        let index = 0;
        // map of id to item
        this.db = new Map(data.map(item => ({ ...item, id: index++ })).map(item => [item.id, item]));
    }

    // returns an item via it's id, can be undefined
    getItemById(id) {
        return this.db.get(id);
    }

    // returns items by their ids, undefined items are filtered out
    getItemsByIds(ids) {
        return ids.map(id => this.getItemById(id)).filter(item => item !== undefined);
    }

    // returns all items in db
    getAllItems() {
        return Array.from(this.db.values());
    }

    // returns ids of all items
    getAllIds() {
        return Array.from(this.db.keys());
    }

}

class Cart {

    // constructor of cart helper class
    constructor(data, clampFunc, onUpdateFunc) {
        console.log("constructing cart with data", data);
        // clampFunc can be undefined, in which case it does nothing
        this.clampFunc = clampFunc || (item => item);
        this.onUpdateFunc = onUpdateFunc;
        this.data = data !== undefined
            ? new Map(data)
            : new Map();
    }

    // function to load cart data from the localstorage API
    static loadFromStorage(storageName, clampFunc, onUpdateFunc) {
        //serialized as JSON string
        const serialized = window.localStorage.getItem(storageName);
        let deserialized = undefined;
        try {
            if (serialized !== undefined) {
                //deserializing data into an object
                deserialized = JSON.parse(serialized);
            }
        } catch (error) {
            console.log("error deserializing cart!");
            console.log(error);
        } finally {
            
            return new Cart(deserialized, clampFunc, onUpdateFunc);
        }
    }

    // save to localstorage function
    saveToStorage(storageName) {
        const serialized = JSON.stringify(Array.from(this.data.entries()));
        window.localStorage.setItem(storageName, serialized);
    }

    // function to add item to storage
    addItem(id, quantity) {
        if (this.data.has(id)) {
            this.data.set(id, this.clampFunc(this.data.get(id) + quantity));
        } else {
            this.setItemQuantity(id, quantity);
        }
        // delete unnecesary items
        this.clearGarbage();
        // invoke the update function, used for redrawing the order
        this.forceUpdate();
    }

    // function to set item quantity in cart, clamped
    setItemQuantity(id, quantity) {
        this.data.set(id, this.clampFunc(quantity));
        this.clearGarbage();
        this.forceUpdate();
        return this.data.get(id) || 0;
    }

    // function for deleting item from cart
    removeItem(id) {
        //deleting
        this.data.delete(id);
        this.forceUpdate();
    }

    // returns [id, quantity] of each element of cart
    getQuantities() {
        return Array.from(this.data.entries());
    }

    // clears products from cart with quantity of 0
    clearGarbage() {
        const idsToDelete = this.getQuantities()
            .filter(([_id, quantity]) => quantity <= 0)
            // only ids are needed
            .map(([id, _quantity]) => id);

        // removing items after iterating
        // since mutating a collection while iterating through it can lead to unexpected behaviour
        for (const id of idsToDelete) {
            this.removeItem(id);
        }
    }

    // function to invoke the optional user-passed onUpdateFunc
    forceUpdate(){
        // calls onUpdateFunc if it isn't undefined
        if (this.onUpdateFunc !== undefined) {
            this.onUpdateFunc();
          }
    }

}


// === constants ===
const MAX_QTY = 9;
const productIdKey = "product";
const orderIdKey = "order";
const inputIdKey = "qte";

const CART_STORAGE_NAME = "cart";

// === elements  ===
const elements = {
    shop:   document.getElementById("boutique"),
    filter: document.getElementById("filter"),
    total:  document.getElementById("montant"),
    orders: document.getElementsByClassName("achats")[0],
    save:   document.getElementById("save-button"),
};

// Map of id-to-quantity
const orders = new Map();


const store = {
    // adding actual ids to items, and storing their price as numbers instead of the provided strings
    items: new Catalog(catalog.map(item => ({ ...item, price: Number(item.price) }))),
    // loading cart from local storage
    cart: Cart.loadFromStorage(CART_STORAGE_NAME, clampItemQuantity, renderOrderEntries),
};

window.onload = () => {
    // rendering of main catalogue
    renderMainCatalog(undefined);
    // forcing cart to update, re-renders orders list
    store.cart.forceUpdate();

    // search function by partial string match
    elements.filter.onkeyup = () => {
        const searchString = elements.filter.value;
        if (searchString.length > 0) {
            // use a filtered search if the search string isn't empty
            renderMainCatalog(searchString);
        } else {
            // don't filter if search string is empty
            renderMainCatalog(undefined);
        }
    }

    // save cart to localstorage when button pressed
    elements.save.onclick = () => {
        store.cart.saveToStorage(CART_STORAGE_NAME);
    }
}

// returns a number corresponding to the total of the order
function calculateTotal() {
    // getting quantities of products with ids
    return store.cart.getQuantities()
        // mapping id-quantity to item-quantity
        .map(([id, quantity]) => [store.items.getItemById(id), quantity])
        // filtering out unfound items
        .filter(([item, _quantity]) => item !== undefined)
        // calculating total amount of all items in cart
        .reduce((acc, [item, quantity]) => acc + item.price * clampItemQuantity(quantity), 0);
}

// function used to render/update total amount of the shoping cart
function renderTotal() {
    const total = calculateTotal();
    elements.total.innerText = total;
}

// rendering the front page products of the shop
function renderMainCatalog(filterString) {
    const itemsToRender = filterString === undefined
        ? store.items.getAllItems()
        : store.items.getAllItems().filter(item => item.name.toLowerCase().search(filterString.toLowerCase()) !== -1);
    // create all elements before re-rendering
    const renderedElements = itemsToRender.map(item => createCatalogItem(item));
    // quick and hacky way to delete all children from an element
    elements.shop.innerHTML = "";
    // append them all at once, should run in one update frame
    for (const element of renderedElements) {
        elements.shop.appendChild(element);
    }
}

// returns a catalogue item element, to be appended to the main list
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

// returns an order element with controls
function createOrderControlBlock(item) {
	const elContainer = document.createElement("div");
	elContainer.className = "controle";

	const elInput = document.createElement("input");
	elInput.type = "number";
	elInput.step = "1";
	elInput.value = "0";
	elInput.min = "0";
	elInput.max = MAX_QTY.toString();
	elContainer.appendChild(elInput);

	const elButton = document.createElement("button");
	elButton.className = "commander";
	elContainer.appendChild(elButton);

    function updateOpacity() {
        const quantity = Number(elInput.value);
        // set the opacity to 0.25 when quantity is zero, otherwise set to 1
        elButton.style.opacity = quantity > 0 ? 1 : 0.25;
    }

    // clamp the quantity and update the opacity on input value change
    elInput.onchange = () => {
        const quantity = clampItemQuantity(Number(elInput.value));
        elInput.value = quantity.toString();
        updateOpacity();
    }
    // adds elements to cart on click
    elButton.onclick = () => {
        const quantity = Number(elInput.value);
        if (quantity > 0) {
            store.cart.addItem(item.id, quantity);
            elInput.value = "0";
        }
        updateOpacity();
    }

	return elContainer;
}

// returns an element containing a figure for an item
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
    const renderedElements = store.cart.getQuantities()
        .map(([id, quantity]) => [store.items.getItemById(id), quantity])
        .filter(([item, _quantity]) => item !== undefined)
        .map(([item, quantity]) => createOrderEntry(item, quantity));

    elements.orders.innerHTML = "";

    // appending rendered elements to page
    for (const element of renderedElements) {
        elements.orders.appendChild(element);
    }

    // rendering order total
    renderTotal();
}

// returns an element for the orders page
function createOrderEntry(item, quantity) {
    const elContainer = document.createElement("div");
    elContainer.className = "achat";

    const elFigure = createCatalogItemFigure(item);
    elContainer.appendChild(elFigure);

    const elTitle = document.createElement("h4");
    elTitle.innerText = item.name;
    elContainer.appendChild(elTitle);

    const elQuantity = document.createElement("input");
    elQuantity.type = "number";
	elQuantity.step = "1";
	elQuantity.value = "0";
	elQuantity.min = "0";
    elQuantity.className = "quantite";
    elQuantity.value = quantity.toString();
    elContainer.appendChild(elQuantity);
    
    // updates cart item quantity on change, re-renders it using the update function
    elQuantity.onchange = () => {
        store.cart.setItemQuantity(item.id, Number(elQuantity.value));
    }

    const elSpan = document.createElement("span");
    elSpan.innerText = "x";
    elSpan.setAttribute( 'class', 'span-x' );
    elContainer.appendChild(elSpan);

    const elPrice = document.createElement("div");
    elPrice.className = "prix";
    elPrice.innerText = item.price;
    elContainer.appendChild(elPrice);

    const elControl = document.createElement("div");
    elControl.className = "controle";

    const elDeleteButton = document.createElement("button");
    elDeleteButton.className = "retirer";
    elControl.appendChild(elDeleteButton);

    // removes an item from the cart
    elDeleteButton.onclick = () => {
        store.cart.removeItem(item.id);
    }

    elContainer.appendChild(elControl);

    return elContainer;
}

// function to verify if given number is in [min, max]
// there exists a proposal for a Math.clamp() function, but it's not implemented yet
function clamp(number, min, max) {
    return Math.max(min, Math.min(number, max));
}

// clamps an item quantity to the domain's specification, being [0, 9]
function clampItemQuantity(quantity) {
    return clamp(Math.trunc(quantity), 0, MAX_QTY);
}

// Gabriel Levshin - Phuong-Nam Vu - Albéric Ziangbe


// === helper classes ===
class Catalog {

    constructor(data) {
        let index = 0;
        this.db = new Map(data.map(item => ({ ...item, id: index++ })).map(item => [item.id, item]));
    }

    getItemById(id) {
        return this.db.get(id);
    }

    getItemsByIds(ids) {
        return ids.map(id => this.getItemById(id)).filter(item => item !== undefined);
    }

    getAllItems() {
        return Array.from(this.db.values());
    }

    getAllIds() {
        return Array.from(this.db.keys());
    }

}

class Cart {

    constructor(data, clampFunc, onUpdateFunc) {
        console.log("constructing cart with data", data);
        // clampFunc can be undefined, in which case it does nothing
        this.clampFunc = clampFunc ?? (item => item);
        this.onUpdateFunc = onUpdateFunc;
        this.data = data !== undefined
            ? new Map(data)
            : new Map();
    }

    static loadFromStorage(storageName, clampFunc, onUpdateFunc) {
        const serialized = window.localStorage.getItem(storageName);
        let deserialized = undefined;
        try {
            if (serialized !== undefined) {
                deserialized = JSON.parse(serialized);
            }
        } catch (error) {
            console.log("error deserializing cart!");
            console.log(error);
        } finally {
            return new Cart(deserialized, clampFunc, onUpdateFunc);
        }
    }

    saveToStorage(storageName) {
        const serialized = JSON.stringify(Array.from(this.data.entries()));
        window.localStorage.setItem(storageName, serialized);
    }

    addItem(id, quantity) {
        if (this.data.has(id)) {
            this.data.set(id, this.clampFunc(this.data.get(id) + quantity));
        } else {
            this.setItemQuantity(id, quantity);
        }
        this.clearGarbage();
        this.forceUpdate();
    }

    setItemQuantity(id, quantity) {
        this.data.set(id, this.clampFunc(quantity));
        this.clearGarbage();
        this.forceUpdate();
        return this.data.get(id) ?? 0;
    }

    removeItem(id) {
        this.data.delete(id);
        this.forceUpdate();
    }

    getQuantities() {
        return Array.from(this.data.entries());
    }

    clearGarbage() {
        const idsToDelete = this.getQuantities()
            .filter(([_id, quantity]) => quantity <= 0)
            .map(([id, _quantity]) => id);
        for (const id of idsToDelete) {
            this.removeItem(id);
        }
    }

    forceUpdate() {
        this.onUpdateFunc?.();
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
    items: new Catalog(catalog.map(item => ({ ...item, price: Number(item.price) }))),
    cart: Cart.loadFromStorage(CART_STORAGE_NAME, clampItemQuantity, renderOrderEntries),
};

window.onload = () => {
    //catalogue par défaut (ici tous les produits du catalogue)
    renderMainCatalog(undefined);
    store.cart.forceUpdate();

    //fonction de recherche 
    elements.filter.onkeyup = () => {
        const searchString = elements.filter.value;
        if (searchString.length > 0) {
            //affichage des produits recherchés (si trouvés)
            renderMainCatalog(searchString);
        } else {
            //affichage de tous les produits par défaut
            renderMainCatalog(undefined);
        }
    }

    elements.save.onclick = () => {
        store.cart.saveToStorage(CART_STORAGE_NAME);
    }
}

//calcul du total de la commande du panier
function calculateTotal() {
    return store.cart.getQuantities()
        .map(([id, quantity]) => [store.items.getItemById(id), quantity])
        .filter(([item, _quantity]) => item !== undefined)
        .reduce((acc, [item, quantity]) => acc + item.price * clampItemQuantity(quantity), 0);
}

//function used to render/update total amount of the shoping cart
function renderTotal() {
    const total = calculateTotal();
    elements.total.innerText = total;
}

//rendering products of the front page of the shop
function renderMainCatalog(filterString) {
    const itemsToRender = filterString === undefined
        ? store.items.getAllItems()
        : store.items.getAllItems().filter(item => item.name.toLowerCase().search(filterString.toLowerCase()) !== -1);
    // create all elements before re-rendering
    //création de tous les élements avant le re-rendering
    const renderedElements = itemsToRender.map(item => createCatalogItem(item));
    // moyen rapide de réinitialiser le code HTML
    elements.shop.innerHTML = "";
    // append them all at once, should run in one update frame
    //on append tous les elements au shop
    for (const element of renderedElements) {
        elements.shop.appendChild(element);
    }
}

//creation d'un produit de la boutique
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
        //opacite egale a 1 si qte positive sinon 0.25
        elButton.style.opacity = quantity > 0 ? 1 : 0.25;
    }

    //controle de l'input qte d'un produit
    elInput.onchange = () => {
        const quantity = clampItemQuantity(Number(elInput.value));
        elInput.value = quantity.toString();
        updateOpacity();
    }
    //update du bouton ajout au panier
    elButton.onclick = () => {
        const quantity = Number(elInput.value);
        if (quantity > 0) {
            store.cart.addItem(item.id, quantity);
            elInput.value = "0";
        }
        updateOpacity();
    }

	return elDiv;
}

//ajout des images pour un produit
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

    //quick html initialization
    elements.orders.innerHTML = "";

    //appending rendered elements to page
    for (const element of renderedElements) {
        elements.orders.appendChild(element);
    }


    renderTotal();
}

//creation element dans le panier
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
    
    elQuantity.onchange = () => {
        const updatedQuantity = store.cart.setItemQuantity(item.id, Number(elQuantity.value));
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

    //suppresion d'un element du panier
    elDeleteButton.onclick = () => {
        store.cart.removeItem(item.id);
    }

    elContainer.appendChild(elControl);

    return elContainer;
}


//fonction qui permet de vérifier que la quantité saisie est comprise dans [min, max]
function clamp(number, min, max) {
    return Math.max(min, Math.min(number, max));
}

function clampItemQuantity(quantity) {
    return clamp(Math.trunc(quantity), 0, MAX_QTY);
}

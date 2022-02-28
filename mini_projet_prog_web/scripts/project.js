// YOUR NAME HERE

// === constants ===
const MAX_QTY = 9;
const productIdKey = "product";
const orderIdKey = "order";
const inputIdKey = "qte";
const purchaseIdKey = "achat";
const filter = document.getElementById('filter');
const shop = document.getElementById('boutique');
const purchases = document.querySelector('.achats');
// === global variables  ===
// the total cost of selected products 
let montant = document.getElementById('montant');
var total = 0;



// function called when page is loaded, it performs initializations 
var init = function() {
    createShop();

    // TODO : add other initializations to achieve if you think it is required
}
window.addEventListener("load", init);



// usefull functions

/*
 * create and add all the div.produit elements to the div#boutique element
 * according to the product objects that exist in 'catalog' variable
 */
var createShop = function() {
    var shop = document.getElementById("boutique");
    for (var i = 0; i < catalog.length; i++) {
        shop.appendChild(createProduct(catalog[i], i));
    }
}

/*
 * create the div.produit elment corresponding to the given product
 * The created element receives the id "index-product" where index is replaced by param's value
 * @param product (product object) = the product for which the element is created
 * @param index (int) = the index of the product in catalog, used to set the id of the created element
 */
var createProduct = function(product, index) {
    // build the div element for product
    var block = document.createElement("div");
    block.className = "produit";
    // set the id for this product
    block.id = index + "-" + productIdKey;
    // build the h4 part of 'block'
    block.appendChild(createBlock("h4", product.name));

    // /!\ should add the figure of the product... does not work yet... /!\ 
    block.appendChild(createFigureBlock(product));

    // build and add the div.description part of 'block' 
    block.appendChild(createBlock("div", product.description, "description"));
    // build and add the div.price part of 'block'
    block.appendChild(createBlock("div", product.price, "prix"));
    // build and add control div block to product element
    block.appendChild(createOrderControlBlock(index));
    return block;
}


/* return a new element of tag 'tag' with content 'content' and class 'cssClass'
 * @param tag (string) = the type of the created element (example : "p")
 * @param content (string) = the html wontent of the created element (example : "bla bla")
 * @param cssClass (string) (optional) = the value of the 'class' attribute for the created element
 */
var createBlock = function(tag, content, cssClass) {
    var element = document.createElement(tag);
    if (cssClass != undefined) {
        element.className = cssClass;
    }
    element.innerHTML = content;
    return element;
}

/*
 * builds the control element (div.controle) for a product
 * @param index = the index of the considered product
 *
 * TODO : add the event handling, 
 *   /!\  in this version button and input do nothing  /!\  
 */
var createOrderControlBlock = function(index) {
    var control = document.createElement("div");
    control.className = "controle";

    // create input quantity element
    var input = document.createElement("input");
    input.id = index + '-' + inputIdKey;
    input.type = "number";
    input.step = "1";
    input.value = "0";
    input.min = "0";
    input.max = MAX_QTY.toString();
    // add input to control as its child
    control.appendChild(input);

    // create order button
    var button = document.createElement("button");
    button.className = 'commander';
    button.id = index + "-" + orderIdKey;
    // add control to control as its child
    control.appendChild(button);

    // the built control div node is returned
    return control;
}


// Function that allows to create an element in the purchases box
let createPurchaseBlock = function(index, quantity) {
    let purchase = document.createElement('div');
    purchase.className = 'achat';
    purchase.id = purchaseIdKey + "-" + index;

    purchase.appendChild(createFigureBlock(catalog[index]));

    let h4 = document.createElement('h4');
    h4.textContent = catalog[index].name;
    purchase.appendChild(h4);

    let qty = document.createElement('div');
    qty.className = 'quantite';
    qty.textContent = quantity;
    purchase.appendChild(qty);

    let price = document.createElement('div');
    price.className = 'prix';
    price.textContent = catalog[index].price;
    purchase.appendChild(price);


    let control = document.createElement('div');
    control.className = 'controle';

    let btn = document.createElement('button');
    btn.className = 'retirer';
    btn.id = index + '-remove';

    control.appendChild(btn);
    purchase.appendChild(control);

    // purchases.appendChild(purchase);
    return purchase;
};

//  Event handler that add a product in the box when the purchase icoon is clicked
document.addEventListener('click', (e) => {
    if (e.target.className === 'commander') {
        if (document.querySelector('#achat-' + parseInt(e.target.previousSibling.id))) {
            let quantite = document.querySelector('#achat-' + parseInt(e.target.previousSibling.id) + ' .quantite');
            quantite.textContent = (parseInt(quantite.textContent) + parseInt(e.target.previousSibling.value)).toString();
            if (parseInt(quantite.textContent) > 9) {
                quantite.textContent = "9";
            }
            total += parseInt(quantite.textContent) * parseInt(catalog[parseInt(e.target.id)].price);
        } else {
            purchases.appendChild(createPurchaseBlock(parseInt(e.target.previousSibling.id), e.target.previousSibling.value));
        }
        e.target.previousSibling.value = 0;
        montant.textContent = total;
    }
});

// Delete an element in the box 
document.addEventListener('click', (e) => {
    if (e.target.id.indexOf('remove') !== -1) {
        let purchase = document.querySelector('#achat-' + parseInt(e.target.id));
        total -= parseInt(purchase.textContent) * parseInt(catalog[parseInt(e.target.id)].price);
        montant.textContent = total.toString();
        purchases.removeChild(purchase);
    }
});

/*
 * create and return the figure block for this product
 * see the static version of the project to know what the <figure> should be
 * @param product (product object) = the product for which the figure block is created
 *
 * TODO : write the correct code
 */
var createFigureBlock = function(product) {
    // this is absolutely not the correct answer !
    // TODO 

    return createBlock("figure", `<img src=${product.image} alt=${product.name}>`);
}

// Event listener filtre de recherche
let find = function() {

    shop.innerHTML = "";
    console.log(shop);
    for (var i = 0; i < catalog.length; i++) {
        if (catalog[i].name.indexOf(filter.value) >= 0) {
            shop.appendChild(createProduct(catalog[i], i));
        }
    }
};

filter.addEventListener('keyup', find);

// Event listener remlacement de la quantité saisie par 9 si elle est supérieur à 9
// Changement de l'opacite du bouton d'ajout dans le panier en fonction du nombre de produit selectionné

let replaceQtynput = (input) => {
    if (input.value > 9 || input.value < 0) {
        input.value = MAX_QTY.toString();
    }
};


document.addEventListener('keyup', (e) => {
    if (e.target.id.indexOf('qte') !== 1) {
        replaceQtynput(e.target);
    }
    if (e.target.value <= 9 && e.target.value > 0) {
        e.target.nextSibling.style.opacity = 1;
    } else {
        e.target.nextSibling.style.opacity = 0.2;
    }

});

document.addEventListener('click', (e) => {
    if (e.target.id.indexOf('qte') !== 1) {
        if (e.target.value <= 9 && e.target.value > 0) {
            e.target.nextSibling.style.opacity = 1;
        } else {
            e.target.nextSibling.style.opacity = 0.2;
        }
    }

})
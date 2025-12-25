/* --- Mock Data --- */
const products = [
    {
        id: 1,
        name: "Snake Plant (स्नेक प्लांट)",
        price: 450,
        category: "indoor",
        image: "https://images.unsplash.com/photo-1512428813838-6591185a31b1?auto=format&fit=crop&w=500&q=60",
        description: "Best air purifying indoor plant. Low maintenance."
    },
    {
        id: 2,
        name: "Aloe Vera (कोरफड)",
        price: 250,
        category: "succulents",
        image: "https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=500&q=60",
        description: "Medicinal plant for skin and health."
    },
    {
        id: 3,
        name: "Peace Lily (पीस लिली)",
        price: 550,
        category: "indoor",
        image: "https://images.unsplash.com/photo-1593691509543-c55ce15e0131?auto=format&fit=crop&w=500&q=60",
        description: "Beautiful white flowers and air purification."
    },
    {
        id: 4,
        name: "Monstera (मॉन्स्टेरा)",
        price: 850,
        category: "indoor",
        image: "https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=500&q=60",
        description: "Stylish large leaves for modern homes."
    },
    {
        id: 5,
        name: "Golden Cactus (कॅक्टस)",
        price: 300,
        category: "succulents",
        image: "https://images.unsplash.com/photo-1463936575829-25148e1db1b8?auto=format&fit=crop&w=500&q=60",
        description: "Beautiful golden spines, needs very less water."
    },
    {
        id: 6,
        name: "Rose Plant (गुलाब)",
        price: 150,
        category: "outdoor",
        image: "https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?auto=format&fit=crop&w=500&q=60",
        description: "Classic red roses for your garden."
    },
    {
        id: 7,
        name: "Tulsi (तुळस)",
        price: 100,
        category: "outdoor",
        image: "https://images.unsplash.com/photo-1596078736417-063a558509c2?auto=format&fit=crop&w=500&q=60",
        description: "Holy basil, essential for every home."
    },
    {
        id: 8,
        name: "Rubber Plant",
        price: 600,
        category: "indoor",
        image: "https://images.unsplash.com/photo-1470058869958-2a77ade41c02?auto=format&fit=crop&w=500&q=60",
        description: "Glossy leaves, gives a premium look."
    }
];

/* --- State --- */
let cart = JSON.parse(localStorage.getItem('cart')) || [];

/* --- DOM Elements --- */
const productContainer = document.getElementById('product-container');
const cartBtn = document.getElementById('cart-btn');
const cartModalOverlay = document.getElementById('cart-modal-overlay');
const closeCartBtn = document.getElementById('close-cart');
const cartItemsContainer = document.getElementById('cart-items');
const cartTotalEl = document.getElementById('cart-total');
const cartCountEl = document.getElementById('cart-count');
const cartCountHeader = document.getElementById('cart-count-header');
const toast = document.getElementById('toast');
const filterBtns = document.querySelectorAll('.filter-btn');

/* --- Initialization --- */
document.addEventListener('DOMContentLoaded', () => {
    renderProducts('all');
    updateCartUI();
    setupEventListeners();
});

/* --- Functions --- */

function setupEventListeners() {
    // Filter click
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add to clicked
            btn.classList.add('active');
            // Filter
            const category = btn.getAttribute('data-filter');
            renderProducts(category);
        });
    });

    // Cart Toggle
    cartBtn.addEventListener('click', () => {
        cartModalOverlay.style.display = 'flex';
        renderCartItems();
    });

    closeCartBtn.addEventListener('click', () => {
        cartModalOverlay.style.display = 'none';
    });

    cartModalOverlay.addEventListener('click', (e) => {
        if (e.target === cartModalOverlay) {
            cartModalOverlay.style.display = 'none';
        }
    });

    // Mobile Menu
    const mobileMenu = document.getElementById('mobile-menu');
    const navMenu = document.querySelector('.nav-menu');
    mobileMenu.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
}

function renderProducts(category) {
    productContainer.innerHTML = '';
    
    const filtered = category === 'all' 
        ? products 
        : products.filter(p => p.category === category);

    filtered.forEach(product => {
        const card = document.createElement('div');
        card.classList.add('card');
        card.innerHTML = `
            <div class="illustration-box">
                <img src="${product.image}" alt="${product.name}" loading="lazy">
            </div>
            <div class="card-content">
                <h3 class="card-title">${product.name}</h3>
                <p class="card-desc">${product.description}</p>
                <div class="card-footer">
                    <span class="price">₹${product.price}</span>
                    <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id})">
                        Add to Cart <i class="fas fa-shopping-bag"></i>
                    </button>
                </div>
            </div>
        `;
        productContainer.appendChild(card);
    });
}

function addToCart(id) {
    const product = products.find(p => p.id === id);
    const existingItem = cart.find(item => item.id === id);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }

    saveCart();
    updateCartUI();
    showToast(`Added ${product.name} to cart!`);
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    updateCartUI();
    renderCartItems(); // Re-render modal list
}

function updateCartUI() {
    const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    cartCountEl.textContent = totalCount;
    cartCountHeader.textContent = totalCount;
    cartTotalEl.textContent = totalPrice;
}

function renderCartItems() {
    cartItemsContainer.innerHTML = '';
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p style="text-align:center; color:#888;">Your cart is empty.</p>';
        return;
    }

    cart.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.classList.add('cart-item');
        itemEl.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <p>₹${item.price} x ${item.quantity}</p>
            </div>
            <i class="fas fa-trash remove-item" onclick="removeFromCart(${item.id})"></i>
        `;
        cartItemsContainer.appendChild(itemEl);
    });
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function showToast(message) {
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Global expose for onclick events
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;

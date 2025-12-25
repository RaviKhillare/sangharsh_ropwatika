/* --- Configuration --- */
const IS_GITHUB = window.location.hostname.includes('github.io');
const API_BASE = IS_GITHUB ? null : 'api/';

/* --- Mock Data (Fallback for GitHub Pages) --- */
const MOCK_PRODUCTS = [
    { id: 1, name: "Snake Plant (स्नेक प्लांट)", price: 450, category: "indoor", image_url: "https://images.unsplash.com/photo-1512428813838-6591185a31b1?auto=format&fit=crop&w=500&q=60", description: "Best air purifying indoor plant." },
    { id: 2, name: "Aloe Vera (कोरफड)", price: 250, category: "succulents", image_url: "https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=500&q=60", description: "Medicinal plant for skin and health." },
    { id: 3, name: "Peace Lily (पीस लिली)", price: 550, category: "indoor", image_url: "https://images.unsplash.com/photo-1593691509543-c55ce15e0131?auto=format&fit=crop&w=500&q=60", description: "Beautiful white flowers." },
    { id: 4, name: "Monstera (मॉन्स्टेरा)", price: 850, category: "indoor", image_url: "https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=500&q=60", description: "Stylish large leaves." },
    { id: 5, name: "Golden Cactus", price: 300, category: "succulents", image_url: "https://images.unsplash.com/photo-1463936575829-25148e1db1b8?auto=format&fit=crop&w=500&q=60", description: "Low maintenance." },
    { id: 6, name: "Rose Plant", price: 150, category: "outdoor", image_url: "https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?auto=format&fit=crop&w=500&q=60", description: "Classic red roses." }
];

/* --- Data Source --- */
let products = []; // Populated via API or Mock

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
    // Hide Admin Link on GitHub
    if (IS_GITHUB) {
        const adminLinks = document.querySelectorAll('a[href="login.php"]');
        adminLinks.forEach(link => link.style.display = 'none');
        console.log("Running in Static Mode (GitHub Pages)");
    }

    fetchProducts();
    if (!IS_GITHUB) {
        fetchSettings();
        fetchNotifications();
    }
    updateCartUI();
    setupEventListeners();
});

/* --- Functions --- */

async function fetchSettings() {
    try {
        const response = await fetch('api/settings.php');
        const settings = await response.json();

        if (settings.site_title) {
            document.title = settings.site_title;
            document.getElementById('nav-logo-text').textContent = settings.site_title;
            document.getElementById('footer-title').textContent = settings.site_title;
        }
        if (settings.hero_title) document.getElementById('hero-title').textContent = settings.hero_title;
        if (settings.hero_subtitle) document.getElementById('hero-subtitle').textContent = settings.hero_subtitle;

        if (settings.contact_email) document.getElementById('footer-email').textContent = settings.contact_email;
        if (settings.contact_phone) document.getElementById('footer-phone').textContent = settings.contact_phone;
        if (settings.contact_address) document.getElementById('footer-address').textContent = settings.contact_address;

    } catch (e) { console.error("Error loading settings", e); }
}

async function fetchNotifications() {
    try {
        const response = await fetch('api/notifications.php');
        const notifs = await response.json();

        const ticker = document.getElementById('notification-ticker');
        if (notifs.length > 0) {
            // Join with spacing
            ticker.textContent = notifs.join("  |  ");
        } else {
            document.querySelector('.notification-bar').style.display = 'none';
        }
    } catch (e) { console.error("Error loading notifications", e); }
}

async function submitContact(e) {
    e.preventDefault();

    const name = document.getElementById('contact-name').value;
    const email = document.getElementById('contact-email').value;
    const message = document.getElementById('contact-message').value;

    try {
        const response = await fetch('api/submit_message.php', {
            method: 'POST',
            body: JSON.stringify({ name, email, message })
        });
        const result = await response.json();

        if (result.success) {
            showToast("Message Sent Successfully!");
            document.getElementById('contact-form').reset();
        } else {
            showToast("Error: " + result.message);
        }
    } catch (err) {
        showToast("Network Error");
    }
}

async function checkout() {
    if (cart.length === 0) return showToast("Cart is empty!");

    const name = prompt("Enter your Name:");
    const phone = prompt("Enter your Phone:");
    const address = prompt("Enter Address:");

    if (!name || !phone || !address) return;

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    try {
        const response = await fetch('api/submit_order.php', {
            method: 'POST',
            body: JSON.stringify({
                name, phone, address, total, cart
            })
        });
        const res = await response.json();
        if (res.success) {
            showToast("Order Placed! ID: " + res.order_id);
            cart = [];
            saveCart();
            updateCartUI();
            document.getElementById('cart-modal-overlay').style.display = 'none';
        } else {
            showToast("Order Failed: " + res.message);
        }
    } catch (e) { showToast("Network Error"); }
}

async function fetchProducts(category = 'all') {
    productContainer.innerHTML = '<p style="text-align:center; width:100%;">Loading plants...</p>';

    // Hybrid Logic
    if (IS_GITHUB) {
        // Use Mock Data
        products = MOCK_PRODUCTS;
        if (category !== 'all') {
            products = products.filter(p => p.category === category);
        }
        renderProducts(products);
        return; // Stop here
    }

    // Localhost Logic (API)
    try {
        const response = await fetch(`api/products.php?category=${category}`);
        if (!response.ok) throw new Error('Failed to fetch');

        products = await response.json();
        renderProducts(products);
    } catch (error) {
        console.error("API Error:", error);
        productContainer.innerHTML = '<p style="text-align:center; color:red;">Connection Error. Ensure XAMPP is running.</p>';
    }
}

function renderProducts(productList) {
    productContainer.innerHTML = '';

    if (productList.length === 0) {
        productContainer.innerHTML = '<p style="text-align:center;">No products found.</p>';
        return;
    }

    productList.forEach(product => {
        const card = document.createElement('div');
        card.classList.add('card');
        // Ensure numbers are parsable
        const price = parseFloat(product.price);

        card.innerHTML = `
            <div class="illustration-box">
                <img src="${product.image_url}" alt="${product.name}" loading="lazy" onerror="this.src='https://via.placeholder.com/300?text=No+Image'">
            </div>
            <div class="card-content">
                <h3 class="card-title">${product.name}</h3>
                <p class="card-desc">${product.description || ''}</p>
                <div class="card-footer">
                    <span class="price">₹${price}</span>
                    <button class="btn btn-primary btn-sm" onclick="addToCart(${product.id})">
                        Add to Cart <i class="fas fa-shopping-bag"></i>
                    </button>
                </div>
            </div>
        `;
        productContainer.appendChild(card);
    });
}

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
            fetchProducts(category); // Call API instead of local filtering
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

    // Contact Form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', submitContact);
    }
}

function addToCart(id) {
    // API returns IDs as strings sometimes, so use loose comparison
    const product = products.find(p => p.id == id);
    if (!product) return;

    const existingItem = cart.find(item => item.id == id);

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
    cart = cart.filter(item => item.id != id);
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
            <img src="${item.image_url}" alt="${item.name}">
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

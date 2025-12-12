Cart and Orders API Implementation Plan
Overview
This plan implements a complete e-commerce API system allowing clients to browse products, manage shopping carts, place orders, track order status, and manage their profiles. Orders automatically convert to Sale records when status reaches "Delivered".

Part 1: Database Structure
1.1 Create Cart Model and Migration
File: app/Models/Cart.php
File: database/migrations/YYYY_MM_DD_HHMMSS_create_carts_table.php
Fields:
id (primary key)
client_id (foreign key to clients, nullable for guest carts initially)
created_at, updated_at
Relationship: belongsTo(Client), hasMany(CartItem)
1.2 Create CartItem Model and Migration
File: app/Models/CartItem.php
File: database/migrations/YYYY_MM_DD_HHMMSS_create_cart_items_table.php
Fields:
id (primary key)
cart_id (foreign key to carts)
item_id (foreign key to items)
quantity (decimal, respects advanced settings)
unit_price (decimal, snapshot of price at time of adding)
total_price (decimal, calculated)
created_at, updated_at
Relationships: belongsTo(Cart), belongsTo(Item)
1.3 Update Order Model
File: app/Models/Order.php
Add missing relationships and fields:
belongsTo(Client) relationship
belongsTo(Sale, nullable) relationship
Add shipping_address, shipping_phone, shipping_name fields if not in migration
Add order_number field for unique order identification
Update fillable array
1.4 Create OrderStatusEnum
File: app/Enums/OrderStatusEnum.php
Statuses: confirmed = 1, processing = 2, shipped = 3, delivered = 4
Include label() and style() methods following existing enum pattern
1.5 Update Orders Migration (if needed)
File: database/migrations/YYYY_MM_DD_HHMMSS_add_fields_to_orders_table.php
Add fields if missing:
order_number (string, unique)
shipping_name (string)
shipping_address (text)
shipping_phone (string)
notes (text, nullable)
Part 2: Product Display API
2.1 Update ItemController
File: app/Http/Controllers/Api/V1/ItemController.php
Enhance existing methods:
index(): Add filtering by category, search functionality, pagination
show(): Return detailed product info with images, category, unit
Add new methods:
byCategory($categoryId): Get products by category
search(Request $request): Search products by name/description
2.2 Create ItemResource
File: app/Http/Resources/V1/ItemResource.php (may already exist)
Include: id, name, item_code, price, description, category, unit, images, status
Format images array properly
2.3 Add API Routes
File: routes/api.php
Add routes:
GET /v1/items (already exists, enhance)
GET /v1/items/{id} (already exists, enhance)
GET /v1/items/category/{categoryId}
GET /v1/items/search?q={query}
Part 3: Shopping Cart API
3.1 Create CartController
File: app/Http/Controllers/Api/V1/CartController.php
Methods:
index(): Get current user's cart with items
addItem(AddCartItemRequest $request): Add item to cart
updateItem(UpdateCartItemRequest $request, $itemId): Update quantity
removeItem($itemId): Remove item from cart
clear(): Clear entire cart
getTotal(): Get cart total amount
Apply AdvancedSettings for decimal quantity validation
Use database transactions for cart operations
3.2 Create Cart Request Classes
File: app/Http/Requests/Api/V1/AddCartItemRequest.php
Validation: item_id (required, exists:items), quantity (required, numeric, min:1, integer if decimals not allowed)
File: app/Http/Requests/Api/V1/UpdateCartItemRequest.php
Validation: quantity (required, numeric, min:1, integer if decimals not allowed)
3.3 Create CartResource and CartItemResource
File: app/Http/Resources/V1/CartResource.php
Include: id, client_id, items (CartItemResource collection), total_amount, item_count
File: app/Http/Resources/V1/CartItemResource.php
Include: id, item (ItemResource), quantity, unit_price, total_price
3.4 Add Cart API Routes
File: routes/api.php
Protected routes (auth:api middleware):
GET /v1/cart → index()
POST /v1/cart/items → addItem()
PUT /v1/cart/items/{itemId} → updateItem()
DELETE /v1/cart/items/{itemId} → removeItem()
DELETE /v1/cart → clear()
GET /v1/cart/total → getTotal()
Part 4: Checkout and Orders API
4.1 Create OrderController
File: app/Http/Controllers/Api/V1/OrderController.php
Methods:
checkout(CheckoutRequest $request): Create order from cart
index(): Get user's orders list
show($id): Get order details
cancel($id): Cancel order (if status allows)
Apply AdvancedSettings for payment methods
Auto-create Sale when order is placed (based on previous answer)
4.2 Create CheckoutRequest
File: app/Http/Requests/Api/V1/CheckoutRequest.php
Validation:
shipping_name (required, string)
shipping_address (required, string)
shipping_phone (required, string)
payment_method (required, in:payment_methods from AdvancedSettings)
notes (nullable, string)
4.3 Create OrderService
File: app/Services/OrderService.php
Methods:
createOrderFromCart(Client $client, CheckoutRequest $request): Create order from cart
convertOrderToSale(Order $order): Convert delivered order to Sale
updateOrderStatus(Order $order, OrderStatusEnum $status): Update order status
Handle:
Stock validation before order creation
Stock management when order is confirmed
Automatic Sale creation when order is delivered
Client account balance updates
Safe transactions
4.4 Create OrderResource
File: app/Http/Resources/V1/OrderResource.php
Include: id, order_number, status, payment_method, price, shipping_cost, total_price, shipping_name, shipping_address, shipping_phone, items (collection), created_at, updated_at
4.5 Add Order API Routes
File: routes/api.php
Protected routes (auth:api middleware):
POST /v1/orders/checkout → checkout()
GET /v1/orders → index()
GET /v1/orders/{id} → show()
POST /v1/orders/{id}/cancel → cancel()
Part 5: Customer Profile API
5.1 Enhance AuthController
File: app/Http/Controllers/Api/V1/AuthController.php
Update existing methods:
updateProfile(): Already exists, ensure it works properly
getProfile(): Already exists, enhance to include order count
5.2 Create ProfileRequest
File: app/Http/Requests/Api/V1/ProfileRequest.php (may already exist)
Validation: name, email, phone, address (all required/optional as needed)
5.3 Enhance ClientResource
File: app/Http/Resources/V1/ClientResource.php (may already exist)
Include: id, name, email, phone, address, balance, status, orders_count
Part 6: Order Status Management (Admin Side)
6.1 Create Admin OrderController
File: app/Http/Controllers/Admin/OrderController.php
Methods:
index(): List all orders with filters
show($id): View order details
updateStatus(UpdateOrderStatusRequest $request, $id): Update order status
convertToSale($id): Manually convert order to sale (if needed)
When status changes to "Delivered", automatically convert to Sale
6.2 Create UpdateOrderStatusRequest
File: app/Http/Requests/Admin/UpdateOrderStatusRequest.php
Validation: status (required, Rule::enum(OrderStatusEnum::class))
6.3 Add Admin Routes
File: routes/web.php
Protected routes (auth middleware):
GET /admin/orders → index()
GET /admin/orders/{id} → show()
PUT /admin/orders/{id}/status → updateStatus()
Part 7: Integration with Existing Systems
7.1 Apply AdvancedSettings
Use allow_decimal_quantities in cart and order validation
Use default_discount_method for order discounts (if applicable)
Use payment_methods array to validate payment method selection
7.2 Stock Management Integration
Validate stock availability before adding to cart
Decrease stock when order status changes to "Confirmed"
Handle stock validation in OrderService
7.3 Sale Creation Logic
When order status = "Delivered":
Create Sale record with type = SaleTypeEnum::sale
Link Sale to Order via sale_id field
Create invoice_number for Sale
Attach all order items to Sale
Calculate totals (price, discount, shipping_cost, net_amount, paid_amount, remaining_amount)
Update client account balance
Create safe transaction (if payment received)
Create warehouse stock transactions
Implementation Order
Part 1: Database structure (models, migrations, enums)
Part 2: Product Display API (enhance existing ItemController)
Part 3: Shopping Cart API (CartController, resources, requests)
Part 4: Checkout and Orders API (OrderController, OrderService, resources)
Part 5: Customer Profile API (enhance existing AuthController)
Part 6: Admin Order Management (Admin OrderController)
Part 7: Integration and testing
Files to Create
Models:
app/Models/Cart.php
app/Models/CartItem.php
Migrations:
database/migrations/YYYY_MM_DD_HHMMSS_create_carts_table.php
database/migrations/YYYY_MM_DD_HHMMSS_create_cart_items_table.php
database/migrations/YYYY_MM_DD_HHMMSS_add_fields_to_orders_table.php (if needed)
Enums:
app/Enums/OrderStatusEnum.php
Controllers:
app/Http/Controllers/Api/V1/CartController.php
app/Http/Controllers/Api/V1/OrderController.php
app/Http/Controllers/Admin/OrderController.php
Requests:
app/Http/Requests/Api/V1/AddCartItemRequest.php
app/Http/Requests/Api/V1/UpdateCartItemRequest.php
app/Http/Requests/Api/V1/CheckoutRequest.php
app/Http/Requests/Admin/UpdateOrderStatusRequest.php
Resources:
app/Http/Resources/V1/CartResource.php
app/Http/Resources/V1/CartItemResource.php
app/Http/Resources/V1/OrderResource.php
Services:
app/Services/OrderService.php
Files to Modify
app/Models/Order.php - Add relationships and fields
app/Http/Controllers/Api/V1/ItemController.php - Enhance product display
app/Http/Controllers/Api/V1/AuthController.php - Enhance profile management
app/Http/Resources/V1/ItemResource.php - Enhance product resource
app/Http/Resources/V1/ClientResource.php - Add order count
routes/api.php - Add cart and order routes
routes/web.php - Add admin order routes
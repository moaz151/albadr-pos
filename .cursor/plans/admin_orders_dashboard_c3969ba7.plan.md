---
name: Admin Orders Dashboard
overview: Add an admin dashboard widget to list orders, view details, and update status (confirm/cancel or full flow).
todos:
  - id: fetch-dashboard-data
    content: Load recent orders for dashboard widget
    status: completed
  - id: render-widget
    content: Add orders table widget to admin dashboard
    status: completed
    dependencies:
      - fetch-dashboard-data
  - id: status-update
    content: Wire dropdown + route to update status
    status: completed
    dependencies:
      - render-widget
  - id: detail-view
    content: Link to order detail (modal or show page)
    status: completed
    dependencies:
      - render-widget
---

# Admin Orders Dashboard plan

## What we’ll build

- Add an orders widget to the existing admin dashboard page to list recent/all orders with key info.
- Allow clicking an order to view details (modal or dedicated detail view) from the dashboard list.
- Add a status dropdown per order (full flow: Confirm, Processing, Shipped, Delivered, Cancel) with an action to update.

## Files to touch

- Controller: [`app/Http/Controllers/Admin/OrderController.php`](app/Http/Controllers/Admin/OrderController.php) – fetch orders for dashboard, handle status updates.
- Routes: [`routes/web.php`](routes/web.php) – ensure dashboard fetch uses existing admin auth middleware.
- View(s): existing dashboard view (e.g., [`resources/views/admin/dashboard.blade.php`](resources/views/admin/dashboard.blade.php) or current home) – add orders table widget + status dropdown + detail link (modal or link to show route).
- Optional partials: create a small blade partial for order rows/details if needed.

## High-level steps

1) Fetch data for dashboard

- In the dashboard controller (or a new method), load recent orders with client, totals, status, and created_at; paginate or limit (e.g., last 20) for performance.

2) Render dashboard widget

- Add a card/table to the existing dashboard view with columns: Order #, Client, Total, Status, Created, Actions.
- Actions: link/button to view details (modal or existing show route), and a status dropdown (Confirm, Processing, Shipped, Delivered, Cancel) plus save button.

3) Status update handling

- Add a POST/PUT route for status changes (reusing `OrderController@updateStatus` if present).
- In the controller, validate using existing `UpdateOrderStatusRequest` and apply the enum logic you already use.
- On success, flash a toast/message; on failure, show validation/error.

4) Detail view

- Reuse existing admin order show view if present; otherwise, add a simple modal or a dedicated show route/view with items, totals, shipping info, status history (if available).

5) Polish

- Add basic filtering/sorting (by status) if feasible within the widget.
- Ensure CSRF, auth, and role checks are in place; keep queries eager-loaded to avoid N+1.

## Notes

- Status options will include the full flow (Confirm, Processing, Shipped, Delivered, Cancel) per your selection.
- If dashboard file differs, I’ll adapt to the current dashboard blade after a quick read.